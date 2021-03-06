<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <!-- Styles -->
    <link href="{{ URL::asset('css/style.css') }}" rel="stylesheet">
</head>
<body>
    <div class="flex-center position-ref full-height">
        <div class="top-left links">
            <a href="{{ url('/') }}">На главную</a>
            <a href="{{ url('/section'.$p_id) }}">▲{{$p_title}}</a>
        </div>
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
            <div class="article position-ref">
                <h1>{{ $title }}</h1>
                <div class="text">{{ $body }}</div>
                <div class="article-bottom">
                    <span class='author'> {{ $author }} </span>
                    <span class='date'> {{ $created_at }} </span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>