<?php

namespace App\Http\Controllers\notification\backend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Device;
use App\Models\Notification;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Carbon\Carbon;

class NotificationController extends Controller
{
    protected $table = 'notifications';

    public function __construct()
    {
    }

    public function index(Request $request)
    {


        $module = $this->table;
        $data = Notification::where('deleted_at', null)->orderBy('id', 'desc');
        if (is($request->keyword)) {
            $keyword = $request->keyword;
            $data = $data->where(function ($query) use ($keyword) {
                $query->where('title', 'like', '%' . $keyword . '%')
                    ->orWhere('body', 'like', '%' . $keyword . '%');
            });
        }
        if (is($request->customer_id)) {
            $data = $data->where('customer_id', $request->customer_id);
        }
        if (is($request->type)) {
            $data = $data->where('type', $request->type);
        }
        $date_start = $request->date_start;
        $date_end = $request->date_end;
        if (isset($date_start) && !empty($date_start) && empty($date_end)) {
            $data = $data->where('created_at', '>=', $date_start . ' 00:00:00')->where('created_at', '<=', $date_start . ' 23:59:59');
        }
        if (isset($date_end) && !empty($date_end) && empty($date_start)) {
            $data = $data->where('created_at', '>=', $date_end . ' 00:00:00')->where('created_at', '<=', $date_end . ' 23:59:59');
        }
        if (isset($date_end) && !empty($date_end) && isset($date_start) && !empty($date_start)) {
            if ($date_end == $date_start) {
                $data = $data->where('created_at', '>=', $date_start . ' 00:00:00');
            } else {
                $data = $data->where('created_at', '>=', $date_start . ' 00:00:00')->where('created_at', '<=', $date_end . ' 23:59:59');
            }
        }
        $data = $data->paginate(env('APP_paginate'));
        if (is($request->customer_id)) {
            $data->appends(['customer_id' => $request->customer_id]);
        }
        if (is($request->type)) {
            $data->appends(['type' => $request->packaging_v_n_s]);
        }
        if (is($request->date_start)) {
            $data->appends(['date_start' => $request->date_start]);
        }
        if (is($request->date_end)) {
            $data->appends(['date_end' => $request->date_end]);
        }
        if (is($request->keyword)) {
            $data->appends(['keyword' => $request->keyword]);
        }
        $customers = dropdown(Customer::select('id', 'name', 'code')->orderBy('name', 'asc')->get(), 'Chọn thành viên', 'id', 'name', 'code');

        return view('notification.index', compact('module', 'data', 'customers'));
    }

    public function create()
    {
        $customers = dropdown(Customer::select('id', 'name', 'code')->orderBy('name', 'asc')->get(), 'Chọn thành viên', 'id', 'name', 'code');
        $module = $this->table;
        return view('notification.create', compact('module', 'customers'));
    }
    public function pushIOS($CURLOPT_POSTFIELDS,$body){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://exp.host/--/api/v2/push/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($CURLOPT_POSTFIELDS),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
    }
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'body' => 'required',
        ], [
            'title.required' => 'Tiêu đề là trường bắt buộc.',
            'body.required' => 'Nội dung là trường bắt buộc.',
        ]);
        $validator->validate();
        $customer_system_id = $request->customer_system_id;

        if (!empty($customer_system_id) && count($customer_system_id) > 0) {
            $devices = Device::select('device_token', 'customer_id')->whereIn('customer_id', $customer_system_id)->groupBy('customer_id')->get();
            $deviceToken = $devices->pluck('device_token');
            if (!empty($devices)) {
                foreach ($devices as $d) {
                    Notification::insertGetId([
                        'title' => $request->title,
                        'type' => 'system',
                        'body' => $request->body,
                        'customer_id' => $d->customer_id,
                        "content" => $request->content,
                        'user_id' => Auth::user()->id,
                        'created_at' => Carbon::now(),
                    ]);

                }
            }
            //ios
            $deviceTokenIOS = Device::select('device_token', 'customer_id')->where('device_type','ios')->whereIn('customer_id', $customer_system_id)->get()->pluck('device_token');
            $CURLOPT_POSTFIELDS = [];
            if(!empty($deviceTokenIOS)){
                foreach ($deviceTokenIOS as $item){
                    $CURLOPT_POSTFIELDS[] = [
                        "to"=> $item,
                        "sound"=> "default",
                        "body"=> $request->body
                    ];
                }
            }
            $this->pushIOS($CURLOPT_POSTFIELDS,$request->body);
        } else {
            Notification::insertGetId([
                'title' => $request->title,
                'type' => 'system',
                'body' => $request->body,
                "content" => $request->content,
                'user_id' => Auth::user()->id,
                'created_at' => Carbon::now(),
            ]);
            $deviceToken = Device::select('device_token')->get()->groupBy('customer_id')->pluck('device_token');
            $deviceTokenIOS = Device::select('device_token')->where('device_type','ios')->get()->pluck('device_token');
            $CURLOPT_POSTFIELDS = [];
            if(!empty($deviceTokenIOS)){
                foreach ($deviceTokenIOS as $item){
                    $CURLOPT_POSTFIELDS[] = [
                        "to"=> $item,
                        "sound"=> "default",
                        "body"=> $request->body
                    ];
                }

            }
            $this->pushIOS($CURLOPT_POSTFIELDS,$request->body);

        }
        if (!empty($deviceToken) && count($deviceToken) > 0) {
            $SERVER_API_KEY = env('FIREBASE_SERVER_KEY');
            $data = [
                "registration_ids" => $deviceToken,
                "notification" => [
                    "title" => $request->title,
                    "body" => $request->body,
                    "sound" => "system",
                    "tag" => 299999
                ],
            ];
            $dataString = json_encode($data);
            $headers = [
                'Authorization: key=' . $SERVER_API_KEY,
                'Content-Type: application/json',
            ];
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://fcm.googleapis.com/fcm/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $dataString,
                CURLOPT_HTTPHEADER => $headers,
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $response = json_decode($response, TRUE);
            if (!empty($response)) {
                if (!empty($response['success'] == 1)) {
                    return redirect()->route('notifications.index')->with('success', "Tạo thông báo thành công");
                } else {
                    return redirect()->route('notifications.index')->with('success', "Tạo thông báo không thành công");
                }
            }
        }
        return redirect()->route('notifications.index')->with('success', "Tạo thông báo thành công");
    }

    public function show($id)
    {
        $module = $this->table;
        $detail = Notification::where('deleted_at', null)->with('customer')->find($id);
        if (!empty($detail->customer_system_id)) {
            $customers = Customer::select('code', 'name')->whereIn('id', json_decode($detail->customer_system_id))->get();
        } else {
            $customers = [];
        }
        if (!isset($detail)) {
            return redirect()->route('notifications.index')->with('error', "Thông báo không tồn tại");
        }
        return view('notification.show', compact('module', 'detail', 'customers'));
    }
}
