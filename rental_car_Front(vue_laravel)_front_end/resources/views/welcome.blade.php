<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>RentalCars</title>
    <style>
        .measurement {
            height: 80vh;
            width: 100vw;
        }

        @media(min-width: 600px) {
            .carousel-item>img {
                height: 100vh;
                width: 80vw;
            }

            .carousel-item img {
                box-shadow: 0 0 5px 2px rgb(43, 41, 41);
            }

            .carousel-caption {
                text-shadow: black 0.1em 0.1em 0.2em
            }
        }

        @media(max-width:450px) {
            * {
                font-size: 12px;
            }


                .carousel-item>img {
                    height: 65vh;
                }
            }
    </style>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <!-- Bootstrap icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body>


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
                    <ul class="navbar-nav">

                        @if (Route::has('login'))

                            @auth
                                <li class="nav-item">
                                    <a href="{{ url('/home') }}" class="nav-link">
                                        Página Principal
                                    </a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a href="{{ route('login') }}" class="nav-link">
                                        Login
                                    </a>
                                </li>

                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a href="{{ route('register') }}" class="nav-link">
                                            Faça um cadastro
                                        </a>
                                    </li>
                                @endif
                            @endauth

                        @endif
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <div id="carouselExampleCaptions" class="carousel slide carousel-fade"
            style="background: linear-gradient(to left bottom, rgb(128, 37, 37),rgb(110, 109, 109))"
            data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
                    aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
                    aria-label="Slide 3"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="3"
                    aria-label="Slide 4"></button>
            </div>
            <div class="carousel-inner" style=" box-shadow: inset 0 0 5px 5px rgb(49, 48, 48)">
                <div class="carousel-item active d-flex justify-content-center" data-bs-interval="3000">
                    <img class="measurement" src="{{ asset('images/rental.jpeg') }}" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Aqui é você cliente quem manda</h5>
                        <p>Temos uma equipe especializada para lhe atender do jeito que você merece</p>
                    </div>
                </div>
                <div class="carousel-item d-flex justify-content-center " data-bs-interval="3000">
                    <img class="measurement" src="{{ asset('images/rental1.jpg') }}" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Sem enrolação</h5>
                        <p>Aqui não há enrolação, em menos de 10 minutos vc jã está com seu carro em mãos</p>
                    </div>
                </div>
                <div class="carousel-item d-flex justify-content-center " data-bs-interval="3000">
                    <img class="measurement" src="{{ asset('images/rental2.jpg') }}" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Todos marcas, todos modelos</h5>
                        <p>Temos convênio com várias marcas nacionais e internacionais</p>
                    </div>
                </div>
                <div class="carousel-item d-flex justify-content-center " data-bs-interval="3000">
                    <img class="measurement" src="{{ asset('images/rental3.jpg') }}" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Liberdade e segurança</h5>
                        <p>Com nossos serviços você não se preocupa mais com problemas comuns do dia a dia que um carro
                            proporciona</p>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <footer class="p-2 " style="background: linear-gradient(to top, rgb(14, 12, 12),rgb(81, 65, 65))">
        <div class="d-flex justify-content-around text-white">
            <div>
                <h4 class="fw-bolder">Para clientes</h4>
                <ul class="list-unstyled">
                    <li class="nav-item"><a class="nav-link" href="">Notícias</a></li>
                    <li class="nav-item"><a class="nav-link" href="">Preços</a></li>
                    <li class="nav-item"><a class="nav-link" href="">Filiais</a></li>
                    <li class="nav-item"><a class="nav-link" href="">Promoções</a></li>
                </ul>
            </div>

            <div>
                <h4 class="fw-bolder">Para investidores</h4>
                <ul class="list-unstyled">
                    <li class="nav-item"><a class="nav-link" href="">Balanços</a></li>
                    <li class="nav-item"><a class="nav-link" href="">Acionistas</a></li>
                    <li class="nav-item"><a class="nav-link" href="">Cotação</a></li>
                    <li class="nav-item"><a class="nav-link" href="">Mercado</a></li>
                </ul>

            </div>
            <div>
                <h4 class="fw-bolder list-unstyled ps-3">Nossas redes sociais</h4>
                <ul>
                    <li class="nav-item"><a class="nav-link" href=""><i class="bi bi-facebook"></i></a></li>
                    <li class="nav-item"><a class="nav-link" href=""><i class="bi bi-instagram"></i></a></li>
                    <li class="nav-item"><a class="nav-link" href=""><i class="bi bi-whatsapp"></i></a></li>
                    <li class="nav-item"><a class="nav-link" href=""><i class="bi bi-twitter-x"></i></a></li>
                </ul>
            </div>
        </div>
        <small class="d-block text-center text-secondary">Copyright © 2024 GILMAR ALBERTO FONSECA. Todos os direitos
            reservados. </small>

    </footer>

</body>

</html>
