@extends('homepage.layout.home')
@section('content')
<style>
    header,
    footer {
        display: none !important;
    }
</style>
<section class="bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
        <a href="{{url('/')}}" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white uppercase">
            <img class="w-8 h-8 mr-2" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/logo.svg" alt="logo">
            ĐĂNG Ký tài khoản
        </a>
        <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
            <div class="p-6 space-y-4 md:space-y-2 sm:p-8">
                <form class="space-y-4 md:space-y-2" action="{{route('customer.register-store')}}" method="POST">
                    @csrf
                    @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700-700 px-4 py-3 rounded relative mt-2" role="alert">
                        <strong class="font-bold">Success!</strong>
                        <span class="block sm:inline">
                            {{session('success')}}
                        </span>
                    </div>
                    @endif
                    @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-2" role="alert">
                        <strong class="font-bold">ERROR!</strong>
                        <span class="block sm:inline">
                            @foreach ($errors->all() as $error)
                            {{ $error }}
                            @endforeach
                        </span>
                    </div>
                    @endif
                    <div>
                        <label class="font-bold text-f14">{{trans('index.Fullname')}}<span class="text-f13 text-red-600">*</span></label>
                        <input type="text" class="  border w-full h-11 px-3 focus:outline-none focus:ring focus:ring-red-300  hover:outline-none hover:ring hover:ring-red-300  rounded-lg" name="name" aria-describedby="emailHelp" placeholder="" value="{{old('name')}}">
                    </div>
                    <div>
                        <label class="font-bold text-f14">{{trans('index.Phone')}}<span class="text-f13 text-red-600">*</span></label>
                        <input type="text" class="  border w-full h-11 px-3 focus:outline-none focus:ring focus:ring-red-300  hover:outline-none hover:ring hover:ring-red-300  rounded-lg" name="phone" aria-describedby="emailHelp" placeholder="" value="{{old('phone')}}">
                    </div>
                    <div>
                        <label class="font-bold text-f14">Địa chỉ<span class="text-f13 text-red-600">*</span></label>
                        <input type="text" class="  border w-full h-11 px-3 focus:outline-none focus:ring focus:ring-red-300  hover:outline-none hover:ring hover:ring-red-300  rounded-lg" name="address" aria-describedby="emailHelp" placeholder="" value="{{old('address')}}">
                    </div>
                    <div>
                        <label class="font-bold text-f14">{{trans('index.Password')}}<span class="text-f13 text-red-600">*</span></label>
                        <input type="password" class="  border w-full h-11 px-3 focus:outline-none focus:ring focus:ring-red-300  hover:outline-none hover:ring hover:ring-red-300  rounded-lg" name="password" aria-describedby="emailHelp" placeholder="">
                    </div>
                    <div>
                        <label class="font-bold text-f14">{{trans('index.EnterPassword')}}<span class="text-f13 text-red-600">*</span></label>
                        <input type="password" class="  border w-full h-11 px-3 focus:outline-none focus:ring focus:ring-red-300  hover:outline-none hover:ring hover:ring-red-300  rounded-lg" name="confirm_password" aria-describedby="emailHelp" placeholder="">
                    </div>
                    <button type="submit" class="w-full text-white bg-primary-600 bg-global focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                        Đăng nhập
                    </button>
                    <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                        Bạn chưa đã có tài khoản? <a href="{{route('customer.login')}}" class="font-medium text-primary-600 hover:underline dark:text-primary-500">đăng nhập</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection