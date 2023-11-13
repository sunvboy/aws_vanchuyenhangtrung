@extends('dashboard.layout.dashboard')
@section('title')
<title>{{trans('admin.index_category_customer')}}</title>
@endsection
@section('breadcrumb')
<?php
$array = array(
    [
        "title" => trans('admin.index_category_customer'),
        "src" => 'javascript:void(0)',
    ]
);
echo breadcrumb_backend($array);
?>
@endsection
@section('content')
<div class="content">
    <h1 class=" text-lg font-medium mt-10">
        {{trans('admin.index_category_customer')}}
    </h1>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class=" col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2 justify-between">
            @include('components.search',['catalogue' => TRUE,'module'=>$module])
            @can('customers_create')
            <a href="{{route('customer_categories.create')}}" class="btn btn-primary shadow-md mr-2">{{trans('admin.create')}}</a>
            @endcan
        </div>
        <!-- BEGIN: Data List -->
        <div class=" col-span-12 lg:col-span-12">
            <table class="table table-report -mt-2">
                <thead class="table-dark">
                    <tr>

                        <th class="text-center">STT</th>
                        <th class="whitespace-nowrap">{{trans('admin.title')}}</th>
                        <th class="whitespace-nowrap">{{trans('admin.code_customer')}}</th>
                        <th class="whitespace-nowrap">{{trans('admin.created')}}</th>
                        <th class="whitespace-nowrap text-center">#</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $v)

                    <tr class="odd " id="post-<?php echo $v->id; ?>">
                        <td>
                            {{$data->firstItem()+$loop->index}}
                        </td>
                        <td style="text-align:left"><a href="{{route('customers.index',['catalogueid' => $v->id])}}">{{$v->title}}({{$v->customers->count()}})</a></td>
                        <td>
                            {{$v->slug}}
                        </td>
                        <td>
                            {{$v->created_at}}
                        </td>
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                                @can('customers_edit')
                                <a class="flex items-center mr-3" href="{{ route('customer_categories.edit',['id'=>$v->id]) }}">
                                    <i data-lucide="check-square" class="w-4 h-4 mr-1"></i>
                                    Edit
                                </a>
                                @endcan
                                @can('customers_destroy')
                                @if($v->id != 5 && $v->id != 8)

                                <a class="flex items-center text-danger <?php echo !empty($v->customers->count() == 0) ? 'ajax-delete' : '' ?> <?php echo !empty($v->customers->count() == 0) ? '' : 'disabled' ?>" href="javascript:;" data-id="<?php echo $v->id ?>" data-module="<?php echo $module ?>" data-child="0" data-title="Lưu ý: Khi bạn xóa thương hiệu, thương hiệu sẽ bị xóa vĩnh viễn. Hãy chắc chắn rằng bạn muốn thực hiện hành động này!">
                                    <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete
                                </a>
                                @endif
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