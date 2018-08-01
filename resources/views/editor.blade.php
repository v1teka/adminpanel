<?php
    use App\Material;
    $materials=Material::all();
    if(isset($_GET['parent_id'])) $parent_id = $_GET['parent_id'];
?>
<html>
    <head>
        <title>Редактор текстовой записи</title>
        <meta charset='utf-8' />
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
                <form action = "{{ url('/insert') }}" method = "post">
                    @csrf
                    <?php if (isset($id)) print("<input type='hidden' name='id' value='".$id."' />");?>
                    <input type='text' name='title' placeholder="Название" value='<?php if(isset($id)) print($title);?>' /><br>
                    <select name='parent_id'>
                        @foreach ($materials as $material)
                            <?php if(!$material->isArticle){
                                $fullTitle = $material->title;
                                $p_id = $material->parent_id;
                                while($p_id!=0){
                                    $parent = Material::find($p_id);
                                    $fullTitle = $parent->title.' -> '.$fullTitle;
                                    $p_id = $parent->parent_id;
                                }
                                print '<option ';
                                if(isset($parent_id) && $parent_id==$material->id) print('selected ');
                                print 'value="'.$material->id.'">'.$fullTitle.'</option>';
                            }  ?>
                        @endforeach
                    </select><br>
                    <textarea placeholder="Текст"  name='body' cols='130' rows='10'><?php if(isset($id)) print($body);?></textarea><br>
                    <input type='submit' />
                </form>
            </div>
        </div>
    </body>
</html>