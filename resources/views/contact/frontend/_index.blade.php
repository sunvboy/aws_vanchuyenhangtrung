  @extends('homepage.layout.home')
  @section('content')
  {!!htmlBreadcrumb($page->title)!!}

  <main class="pt-3">
      <div class="container px-4">
          <h1 class="text-2xl my-8 font-bold">{{$page->title}}</h1>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
              <div class="wow fadeInUp delay01">
                  <img src="{{asset('frontend/images/contact_icon_1.svg')}}" alt="{{$page->title}}" class="lazy mx-auto w-10 mb-4">
                  <div class="font-medium text-f15 text-center">
                      {{$fcSystem['contact_address']}}
                  </div>
              </div>
              <div class="wow fadeInUp delay02">
                  <img src="{{asset('frontend/images/contact_icon_2.svg')}}" alt="{{$page->title}}" class="lazy mx-auto w-10 mb-4">
                  <div class="font-medium text-f15 text-center" style="word-break: break-all;">
                      <a href="tel:{{$fcSystem['contact_hotline']}}">{{$fcSystem['contact_hotline']}}</a>
                  </div>
              </div>
              <div class="wow fadeInUp delay03">
                  <img src="{{asset('frontend/images/contact_icon_3.svg')}}" alt="{{$page->title}}" class="lazy mx-auto w-10 mb-4">
                  <div class="font-medium text-f15 text-center" style="word-break: break-all;">
                      <a href="mailto:{{$fcSystem['contact_email']}}">{{$fcSystem['contact_email']}}</a>
                  </div>
              </div>
              <div class="wow fadeInUp delay04">
                  <img src="{{asset('frontend/images/contact_icon_4.svg')}}" alt="{{$page->title}}" class="lazy mx-auto w-10 mb-4">
                  <div class="font-medium text-f15 text-center" style="word-break: break-all;">
                      <?php echo $fcSystem['contact_time'] ?>
                  </div>
              </div>

          </div>
          <div class="my-8">
              <div class="grid md:grid-cols-2 gap-8">
                  <div class="bg-[#f4f6f8] rounded-2xl p-6 wow fadeInLeft">
                      <h3 class="font-bold text-lg">
                          {{trans('index.Questions')}}
                      </h3>
                      <p class="text-f14 mb-[10px]">
                          {{trans('index.information')}}
                      </p>
                      <form id="form-submit-contact">
                          @csrf
                          @include('homepage.common.alert')
                          <div class="mt-2">
                              <label class="font-bold text-f14">{{trans('index.Fullname')}}<span class="text-f13 text-red-600">*</span></label>
                              <input type="text" class="  border w-full h-11 px-3 focus:outline-none focus:ring focus:ring-red-300  hover:outline-none hover:ring hover:ring-red-300  rounded-lg" name="fullname" aria-describedby="emailHelp" placeholder="{{trans('index.Fullname')}}">
                          </div>
                          <div class="mt-2">
                              <label class="font-bold text-f14">Email<span class="text-f13 text-red-600">*</span></label>
                              <input type="email" class="border w-full h-11 px-3 focus:outline-none focus:ring focus:ring-red-300  hover:outline-none hover:ring hover:ring-red-300  rounded-lg" name="email" placeholder="Email">
                          </div>
                          <div class="mt-2">

                              <label class="font-bold text-f14">{{trans('index.Phone')}}<span class="text-f13 text-red-600">*</span></label>
                              <input type="text" class="border w-full h-11 px-3 focus:outline-none focus:ring focus:ring-red-300  hover:outline-none hover:ring hover:ring-red-300  rounded-lg" name="phone" placeholder="{{trans('index.Phone')}}">
                          </div>
                          <div class="mt-2">
                              <label class="font-bold text-f14">{{trans('index.Address')}}</label>
                              <input type="text" class="border w-full h-11 px-3 focus:outline-none focus:ring focus:ring-red-300  hover:outline-none hover:ring hover:ring-red-300  rounded-lg" name="address" placeholder="{{trans('index.Address')}}">
                          </div>
                          <div class="mt-2">
                              <label class="font-bold text-f14">{{trans('index.Message')}}<span class="text-f13 text-red-600">*</span></label>
                              <textarea rows="6" class="border w-full px-3 focus:outline-none focus:ring focus:ring-red-300  hover:outline-none hover:ring hover:ring-red-300  rounded-lg" name="message" placeholder="{{trans('index.Message')}}"></textarea>
                          </div>
                          <button type="submit" class="btn-submit-contact py-4 px-1 md:px-8 rounded-lg block bg-global  text-white transition-all leading-none text-f15 font-bold"> {{trans('index.SendContactInformation')}}</button>
                      </form>
                  </div>
                  <div class="wow fadeInRight">
                      <?php echo $fcSystem['contact_map'] ?>
                  </div>
              </div>
          </div>
      </div>
  </main>
  <style>
      iframe {
          height: 100%
      }
  </style>
  @endsection
  @push('javascript')
  <script type="text/javascript">
      $(document).ready(function() {
          $(".btn-submit-contact").click(function(e) {
              e.preventDefault();
              var _token = $("#form-submit-contact input[name='_token']").val();
              var fullname = $("#form-submit-contact input[name='fullname']").val();
              var phone = $("#form-submit-contact input[name='phone']").val();
              var email = $("#form-submit-contact input[name='email']").val();
              var address = $("#form-submit-contact input[name='address']").val();
              var message = $("#form-submit-contact textarea[name='message']").val();
              $.ajax({
                  url: "<?php echo route('contactFrontend.store') ?>",
                  type: 'POST',
                  data: {
                      _token: _token,
                      fullname: fullname,
                      phone: phone,
                      email: email,
                      address: address,
                      message: message
                  },
                  success: function(data) {
                      if (data.status == 200) {
                          $("#form-submit-contact .print-error-msg").css('display', 'none');
                          $("#form-submit-contact .print-success-msg").css('display', 'block');
                          $("#form-submit-contact .print-success-msg span").html("<?php echo $fcSystem['message_2'] ?>");
                          setTimeout(function() {
                              location.reload();
                          }, 3000);
                      } else {
                          $("#form-submit-contact .print-error-msg").css('display', 'block');
                          $("#form-submit-contact .print-success-msg").css('display', 'none');
                          $("#form-submit-contact .print-error-msg span").html(data.error);
                      }
                  }
              });
          });
      });
  </script>
  @endpush