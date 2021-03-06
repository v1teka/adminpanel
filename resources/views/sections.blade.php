<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Список разделов</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <!-- Styles -->
    <link href="{{ URL::asset('css/style.css') }}" rel="stylesheet">
</head>
<body>
    <div class="flex-center position-ref full-height">
        <div class="top-left links">
            <a href="{{ url('/') }}">На главную</a>
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
            <ul class="links articles">
            @foreach ($sections as $section)
                <li><a href="{{ url('/section/').$section->id }}">{{ $section->title }}</a></li>
            @endforeach
            </ul>
        </div>
    </div>
</body>
</html>