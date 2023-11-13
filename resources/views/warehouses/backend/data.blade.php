 <table class="table table-report">

     <thead class="table-dark">

         <tr>

             <td colspan="2" class="text-center font-bold" style="    border-radius: 0px;color: #000">Tổng dòng : {{!empty($count)?$count : 0}} </td>

             <td class=" text-white" style="background-color: red">Tổng cân </td>

             <td class=" text-white font-bold" style="background-color: red;border-radius: 0px;">{{$total}}</td>

             <td colspan="11" class="text-right">

                 <a href="javascript:void(0)" onclick="PrintElem()" class="btn btn-danger hidePrint ">In hàng loạt</a>

             </td>

         </tr>

         <tr>

             <th style="width:40px;">

                 <input type="checkbox" id="checkbox-all">

             </th>

             <th class="">NGÀY 日期</th>

             <th class="whitespace-nowrap">MÃ VẬN ĐƠN 运单号</th>

             <th class="whitespace-nowrap">MÃ VN</th>

             <th class="">CÂN NẶNG(KG) 产品重量 KG </th>

             <th class="">Số lượng kiện 数量件</th>

             <th class="">Số lượng sản phẩm 产品数量</th>

             <th class="">TÊN SẢN PHẨM 品名 </th>

             <th class="">TÊN SẢN PHẨM VN </th>

             <th class="">GIÁ</th>

             <th class="">MÃ KHÁCH 客户码 </th>

             <th class="">MÃ BAO 包号</th>

             <th class="">Trạng thái</th>

             <th class="">Người tạo</th>

             <th class=" text-center">#</th>

         </tr>

     </thead>

     <tbody>



         @foreach($data as $v)


         <tr class="odd " id="post-<?php echo $v->id; ?>">

             <td>

                 <input type="checkbox" name="checkbox[]" value="<?php echo $v->id; ?>" class="checkbox-item">

             </td>

             <td>

                 {{$v->date}}

             </td>

             <td>

                 <a href="{{route('warehouses.show',['id'=>$v->id])}}" class="text-danger font-bold" style="text-decoration: underline;">{{$v->code_cn}}</a>

             </td>

             <td>

                 {{$v->code_vn}}

             </td>

             <td>

                 {{$v->weight}}

             </td>

             <td>

                 {{$v->quantity_kien}}

             </td>

             <td>

                 {{$v->quantity}}

             </td>

             <td>

                 {{$v->name_cn}}

             </td>

             <td>

                 {{$v->name_vn}}

             </td>

             <td>

                 {{$v->price}}

             </td>

             <td>

                 {{!empty($v->customer->code)?$v->customer->code:''}}

             </td>



             <td>

                 @if(!empty($v->packaging_relationships) && !empty($v->packaging_relationships->packagings))

                 <a target="_blank" href="{{route('packagings.show',['id' =>  $v->packaging_relationships->packaging_id])}}" class="text-primary font-bold" style="text-decoration: underline;">

                     {{$v->packaging_relationships->packagings->code}}

                 </a>

                 @endif

             </td>

             <td>

                 @if(!empty($v->packaging_relationships) && !empty($v->packaging_relationships->packagings) && !empty($v->packaging_relationships->packagings->packaging_v_n_s))

                 <span class="btn btn-success text-white btn-sm">Đã về VN</span>
                 @else
                 @if(!empty($v->status_packaging_truck))
                 <span class="btn btn-warning btn-sm p-2">Xếp xe TQ</span>
                 @else
                 <span class="btn btn-primary btn-sm p-2">Nhập kho TQ</span>
                 @endif
                 @endif

             </td>

             <td>

                 {{!empty($v->user)?$v->user->name:''}}

             </td>

             <td class="table-report__action">

                 <div class="flex justify-center items-center">

                     <a class="flex items-center mr-3" href="javascript:void(0)" onclick="getinfo({{$v->id}},'{{!empty($v->customer->code)?$v->customer->code:''}}')">

                         <i data-lucide="printer" class="w-6 h-6 mr-1 text-primary"></i>

                     </a>

                     @can('warehouses_edit')

                     <a class="flex items-center mr-3" href="{{route('warehouses.edit',['id'=>$v->id]) }}">

                         <i data-lucide="check-square" class="w-6 h-6 mr-1"></i>

                         Edit

                     </a>

                     @endcan

                     @can('warehouses_destroy')

                     <a class="flex items-center text-danger ajax-delete" href="javascript:;" data-id="<?php echo $v->id ?>" data-module="<?php echo $module ?>" data-child="0" data-title="Lưu ý: Khi bạn xóa đơn giao hàng, đơn giao hàng sẽ bị xóa vĩnh viễn. Hãy chắc chắn rằng bạn muốn thực hiện hành động này!">

                         <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete

                     </a>

                     @endcan

                 </div>

             </td>

         </tr>

         @endforeach

     </tbody>

 </table>

 <div id="Print" style="float:left;margin-left:0px;display: none">
     <table style="width:320px;height:600px;border:1px solid #000;font-size:13px !important">
         <tr>
             <td style="border:1px solid #000" colspan="4">
                 <div style="margin-top:2px;font-weight:bold" id="inputdata">
                     <img src="" id="imgbarcode" style="width: 272px;height: 70px;margin: 0px auto;" />
                 </div>
                 <p style="text-align: center;font-weight: bold;font-size:20px;margin-top: 10px;"><span class="code_vn"></span></p>
             </td>
         </tr>
         <tr>
             <td style="border:1px solid #000" class="customer_code_print">

             </td>
             <td style="border:1px solid #000" colspan="1">
                 {{$str[0]}}
             </td>
             <td style="border:1px solid #000" colspan="2">
                 <p class="toPrint"></p>
                 <p>SĐT: <span class="phonePrint"></span></p>
             </td>

         </tr>
         <tr>
             <td style="border:1px solid #000;text-align: center;" colspan="2">Tên sản phẩm</td>
             <td style="border:1px solid #000;text-align: center;">Số lượng</td>
             <td style="border:1px solid #000;text-align: center;">Trọng lượng</td>
         </tr>
         <tr>
             <td class="namePrint" style="border:1px solid #000;text-align: center;" colspan="2"></td>
             <td class="quantityPrint" style="border:1px solid #000;text-align:center"></td>
             <td class="weightPrint" style="border:1px solid #000;text-align:center"></td>
         </tr>

         <tr>
             <td style="border:1px solid #000" colspan="4">
                 <div style="margin-top:2px;font-weight:bold" id="inputdataCN">
                     <img src="" id="imgbarcodeCN" style="width: 272px;height: 70px;margin: 0px auto;" />
                 </div>
                 <p style="text-align: center;font-weight: bold;font-size:20px;margin-top: 10px;"><span class="codeCNPrint"></span></p>
             </td>
         </tr>
         <tr>
             <td style="border:1px solid #000;text-align: center;" colspan="2">
                 Ngày
             </td>
             <td colspan="2" class="datePrint" style="border:1px solid #000;text-align: center;">
             </td>
         </tr>
         <tr>
             <td style="border:1px solid #000;text-align: center;" colspan="2">
                 Khu vực
             </td>
             <td style="border:1px solid #000;text-align: center;font-weight: bold;" colspan="2">
                 MBT
             </td>
         </tr>
         <tr>
             <td style="border:1px solid #000;height:100px" colspan="4" class="strPrint">
             </td>
         </tr>
     </table>
 </div>

 @push('javascript')

 <script>

     function PrintBarcode(divId) {

         $('.linePrint').remove();

         var printContents = document.getElementById(divId).innerHTML;

         var originalContents = document.body.innerHTML;

         document.body.innerHTML = printContents;

         window.print({

             numberOfCopies: 5

         });

         document.body.innerHTML = originalContents;

     }



     function getinfo(id, code) {

         $.ajax({

             headers: {

                 "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(

                     "content"

                 ),

             },

             type: 'GET',

             url: "<?php echo route('warehouses.printer') ?>",

             data: {

                 id: id

             },

             contentType: "application/json;charset=utf-8",

             dataType: "json",

             success: function(result) {

                 $('.codeCNPrint').html(result.detail.code_cn);

                 $('.code_vn').html(result.detail.code_vn);

                 $('.datePrint').html(result.detail.date);

                 $('.namePrint').html(result.detail.name_vn);

                 $('.quantityPrint').html(result.detail.quantity);

                 $('.weightPrint').html(result.detail.weight);

                 $('.strPrint').html(result.str);

                 $('.toPrint').html('ĐẾN: ' + result.detail.fullname + ' - ' + result.detail.address + '');

                 $('.phonePrint').html(result.detail.phone)

                 $('.customer_code_print').html(code)

                 var img = document.getElementById("imgbarcode");
                 var imgCN = document.getElementById("imgbarcodeCN");
                 img.src = result.code;
                 imgCN.src = result.codeCN;

                 img.onload = function() {

                     PrintBarcode("Print");

                 }

             }

         });

     }

 </script>

 <!-- in nhiều đơn hàng -->

 <style>

     @media print {

         @page {

             margin: 0;

         }



         .linePrint {

             page-break-after: always;

             display: block;

         }



         .main>div.flex>* {

             display: none;

         }

     }

 </style>

 <script>

     function PrintElem() {

         var ids = '';

         $(".checkbox-item").each(function() {

             if ($(this).is(":checked")) {

                 ids += $(this).val() + ','

             }

         });

         if (ids.length <= 0) {

             swal({

                     title: "Có vấn đề xảy ra",

                     text: "Bạn phải chọn ít nhất 1 bản ghi để thực hiện chức năng này",

                     type: "error",

                 },

                 function() {

                     location.reload();

                 }

             );

             return false;

         }

         $.ajax({

             headers: {

                 "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(

                     "content"

                 ),

             },

             type: 'POST',

             url: "<?php echo route('warehouses.printer_all') ?>",

             data: {

                 ids: ids

             },

             dataType: "JSON",

             success: function(result) {

                 $('.linePrint').remove();

                 $('body').append(result.html)

                 var img = document.getElementById("imgbarcodeall");

                 img.onload = function() {

                     window.print();

                 }

             }

         });

     }

 </script>

 @endpush
