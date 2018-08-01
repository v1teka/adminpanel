<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Главная страница</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link href="{{ URL::asset('css/style.css') }}" rel="stylesheet">
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a>{{Auth::user()->name}}</a>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                             @csrf
                        </form>         
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    
                </div>

                <div class="links">
                    <a href="{{ url('/menu') }}">Главное меню</a>
                    @auth
                        @if (Auth::user()->level > 0)
                        <a href="{{ url('/admin') }}">Админ-панель</a>
                        @endif
                        <a href="{{ url('/new') }}">Добавить статью</a>
                    @endauth
                    <a href="{{ url('/sections') }}">Статьи по разделам</a>
                    <a href="{{ url('/articles') }}">Все статьи</a>
                </div>
            </div>
        </div>
    </body>
</html>
