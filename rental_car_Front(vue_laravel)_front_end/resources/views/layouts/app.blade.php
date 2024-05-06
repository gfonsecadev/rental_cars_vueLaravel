<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>RentalCars</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    {{-- Animate css --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    {{-- Bootstrap icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body style="background: linear-gradient(to right bottom, gray,white) no-repeat ; min-height: 100vh" >
    <div id="app">
        <header>
            <nav class="navbar navbar-expand-lg navbar-dark"
                style="background: linear-gradient(to left , rgb(48, 48, 48), #565656)">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#"><img src="{{ asset('images/brand.png') }}" width="60"
                            height="40" alt="Brand"></a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">

                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav me-auto">
                            @auth
                                <li class="nav-item"><a class="nav-link disabled"  href="#">Clientes</a></li>
                                <li class="nav-item"><a href="{{ route('car')}}" class="nav-link">Carros</a>  </li>

                                <li class="nav-item dropdown">
                                    <a href="#" data-bs-toggle="dropdown"
                                        class="nav-link dropdown-toggle">Cadastro e pesquisa</a>
                                    <div class="dropdown-menu">
                                        <a href="{{ route('brand') }}" class="dropdown-item">Marcas</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="{{ route('model') }}" class="dropdown-item">Modelos</a>
                                    </div>
                                </li>
                                <li class="nav-item"><a href="{{ route('about')}}" class="nav-link">Informações</a>  </li>
                            @endauth
                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ms-auto">
                            <!-- Authentication Links -->
                            @guest
                                @if (Route::has('login'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                                    </li>
                                @endif

                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">Faça um cadastro</a>
                                    </li>
                                @endif
                            @else
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ Auth::user()->name }}
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Sair
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

            @auth
                <nav class="breadcrumb" style="background-color: rgb(194, 188, 188)" aria-label="breadcrumb">
                    <ol class="breadcrumb m-1">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ Route::currentRouteName() }}</li>
                    </ol>
                </nav>
            @endauth
        </header>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>

</html>
