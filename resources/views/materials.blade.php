<?php
    use App\Material;
    if(isset($_GET['status'])){
        if($_GET['status']=='ok'){
            $messageClass = 'good';
            $messageText = 'Изменения внесены';
        }else{
            $messageClass = 'error';
            $messageText = 'Произошла ошибка';
        }
    }
    if(!isset($section_id)) $section_id=0;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
        <?php
            if($section_id==0)
                print('Корневой каталог');
            else print(Material::find($section_id)->title);
        ?>
    </title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <!-- Styles -->
    <link href="{{ URL::asset('css/style.css') }}" rel="stylesheet">
    <script>
        function showForm(){
            document.getElementById('new_section').style.display = 'block';
        }
    </script>
</head>
<body>
    <div class="flex-center position-ref full-height">
        @if (isset($messageClass))
            <div class="message {{ $messageClass }}">{{ $messageText }}</div>
        @endif
        <div class="top-left links">
            <a href="{{ url('/') }}">На главную</a>
            @if ($section_id!=0)
                <?php
                    $p_id = Material::find($section_id)->parent_id;
                    if($p_id==0) $p_title = 'В корень'; else $p_title = Material::find($p_id)->title;
                ?>
                <a href="{{ url('/section'.$p_id) }}">▲{{ $p_title }}</a>
            @endif
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
                @auth
                    @if (Auth::user()->level > 0)
                        <li><a href="#" onClick="showForm()" class="dashed-border">Добавить раздел</a></li>
                    @endif
                    <li><a href="{{ url('/new?parent_id='.$section_id) }}"  class="dashed-border">Добавить статью</a></li>
                @endauth
            </ul>
            @auth
            @if (Auth::user()->level > 0)
            <form id="new_section" style="display:none" method="post" action="{{ url('/newsection') }}">
                @csrf
                <input name="section_name" type="text" placeholder="Название нового раздела" />
                <input name="parent_id" type="hidden" value="{{ $section_id }}" />
                <input type="submit" value="Добавить" />
            </form>
            @endif
            @endauth
        </div>
    </div>
</body>
</html>