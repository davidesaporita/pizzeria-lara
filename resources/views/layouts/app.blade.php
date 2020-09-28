<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm fixed-top">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown d-flex align-items-center">
                                <span class="bg-success rounded-pill text-white nav-link font-weight-bold px-3">
                                    <span class="font-weight-normal">Your credit |</span>
                                     € {{ number_format(Auth::user()->credit, 2) }}</span>
                                <a id="navbarDropdown" class="nav-link dropdown-toggle font-weight-bold" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    
                                    {{-- Merchant menu --}}
                                    @isMerchant
                                    <a class="dropdown-item" href="{{ route('merchant.index') }}">
                                       Dashboard
                                    </a>
                                    @endisMerchant

                                    @isMerchant
                                    <a class="dropdown-item" href="{{ route('merchant.products.create') }}">
                                       Add new product
                                    </a>
                                    @endisMerchant

                                    @isMerchant
                                    <a class="dropdown-item" href="{{ route('merchant.orders.index') }}">
                                       Order list
                                    </a>
                                    @endisMerchant
                                    
                                    {{-- Customer menu --}}
                                    @isCustomer
                                    <a class="dropdown-item" href="{{ route('customer.index') }}">
                                       Dashboard
                                    </a>
                                    @endisCustomer
                                    
                                    @isCustomer
                                    <a class="dropdown-item" href="{{ route('customer.cart.pay') }}">
                                       Checkout
                                    </a>
                                    @endisCustomer

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="pb-4" style="padding-top:80px">
            @yield('content')
        </main>
    </div>
</body>

<footer>
   <div class="container">
       <div class="row mb-4">
           <div class="col">
                <span>Pizzeria da Lara | Davide Saporita | 2020</span>
           </div>
       </div>
   </div>
</footer>

</html>
