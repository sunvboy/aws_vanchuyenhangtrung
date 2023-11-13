@extends('dashboard.layout.dashboard')
@section('title')
<title>Bảng giá vận chuyển</title>
@endsection
@section('breadcrumb')
<?php
$array = array(
    [
        "title" => "Bảng giá vận chuyển",
        "src" => 'javascript:void(0)',
    ]
);
echo breadcrumb_backend($array);
?>
@endsection
@section('content')
<div class="content">
    <div class="flex flex-wrap sm:flex-nowrap items-center mt-10 justify-between">
        <h1 class=" text-lg font-medium">
            Bảng giá vận chuyển
        </h1>
        @can('shippings_create')
        <a href="{{route('shippings.create')}}" class="btn btn-primary shadow-md mr-2">Thêm mới</a>
        @endcan
    </div>
    <div class="grid grid-cols-12 gap-6 mt-5">

        <!-- BEGIN: Data List -->
        <div class=" col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th style="width:40px;">
                            <input type="checkbox" id="checkbox-all">
                        </th>
                        <th class="whitespace-nowrap">STT</th>
                        <th class="whitespace-nowrap">TIÊU ĐỀ</th>
                        <th class="whitespace-nowrap">Giá</th>
                        <th class="whitespace-nowrap">NGƯỜI TẠO</th>
                        <th class="whitespace-nowrap">NGÀY TẠO</th>
                        <th class="whitespace-nowrap">#</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $v)
                    <tr class="odd " id="post-<?php echo $v->id; ?>">
                        <td>
                            <input type="checkbox" name="checkbox[]" value="<?php echo $v->id; ?>" class="checkbox-item">
                        </td>
                        <td>
                            {{$data->firstItem()+$loop->index}}
                        </td>
                        <td>
                            <?php echo $v->title; ?>
                        </td>
                        <td>
                            <?php echo number_format($v->price, '0', ',', '.'); ?> VNĐ
                        </td>
                        <td>
                            {{!empty($v->user)?$v->user->name:''}}
                        </td>
                        <td>
                            @if($v->created_at)
                            {{Carbon\Carbon::parse($v->created_at)->diffForHumans()}}
                            @endif
                        </td>

                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                                @can('shippings_edit')
                                <a class="flex items-center mr-3" href="{{ route('shippings.edit',['id'=>$v->id]) }}">
                                    <i data-lucide="check-square" class="w-4 h-4 mr-1"></i>
                                    Edit
                                </a>
                                @endcan
                                @can('shippings_destroy')
                                <a class="flex items-center text-danger ajax-delete" href="javascript:;" data-id="<?php echo $v->id ?>" data-module="<?php echo $module ?>" data-child="0" data-title="Lưu ý: Khi bạn xóa, dữ liệu sẽ bị xóa vĩnh viễn. Hãy chắc chắn rằng bạn muốn thực hiện hành động này!">
                                    <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete
                                </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- END: Data List -->
        <!-- BEGIN: Pagination -->
        <div class=" col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center justify-center">
            {{$data->links()}}
        </div>
        <!-- END: Pagination -->
    </div>
</div>
@endsection