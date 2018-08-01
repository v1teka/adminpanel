<?php
    use App\Material;
    $materials = Material::where('isInMenu', true)->get();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Главное меню</title>
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
                @foreach ($materials as $material)
                    <?php
                        if($material->isArticle){
                            $link = url('/article/');
                            $className = 'another';
                        }
                        else{
                            $link = url('/section/');
                            $className = '';
                        } 
                    ?>
                    <li class="{{ $className }}"><a href="{{ $link.$material->id }}">{{ $material->title }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
</body>
</html>