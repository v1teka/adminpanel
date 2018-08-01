<?php
    use App\User;
    use App\Material;
    $users=User::all();
    $materials=Material::all();
    if(isset($_GET['status'])){
        if($_GET['status']=='ok'){
            $messageClass = 'good';
            $messageText = 'Изменения внесены';
        }else{
            $messageClass = 'error';
            $messageText = 'Ошибка доступа';
        }
    }
?>
<html>
    <head>
        <title>Панель управления</title>
        <meta charset='utf8' />
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <!-- Styles -->
        <link href="{{ URL::asset('css/style.css') }}" rel="stylesheet">
        <script>
            function hideForms(){
                document.getElementById('level').style.display = 'none';
                document.getElementById('article').style.display = 'none';
                document.getElementById('section').style.display = 'none';
            }

            function editUser(e){
                hideForms();
                document.querySelector("#level > input[name='newlevel']").value = e.getAttribute('level');
                document.querySelector("#level > input[name='username']").value = e.innerText;
                document.getElementById('level').style.display = 'block';
                document.getElementById('level').style.left = e.getBoundingClientRect().left+e.getBoundingClientRect().width+10;
                document.getElementById('level').style.top = e.getBoundingClientRect().top;
            }

            function editArticle(e){
                hideForms();
                document.getElementById('article').style.display = 'block';
                document.querySelector("#article > input[name='articleID']").value = e.getAttribute('id');
                if(e.getAttribute('inMenu') == 1) document.querySelector("#article > input[name='menu']").value = 'Убрать из меню';
                else document.querySelector("#article > input[name='menu']").value = 'Добавить в меню';
                document.getElementById('article').style.left = e.getBoundingClientRect().left+e.getBoundingClientRect().width+10;
                document.getElementById('article').style.top = e.getBoundingClientRect().top;
            }

            function editSection(e){
                hideForms();
                document.getElementById('section').style.display = 'block';
                document.querySelector("#section > input[name='id']").value = e.getAttribute('id');
                if(e.getAttribute('id')!=''){
                    document.querySelector("#section > input[name='menu']").style.display = 'inline';
                    if(e.getAttribute('inMenu') == 1) document.querySelector("#section > input[name='menu']").value = 'Убрать из меню';
                    else document.querySelector("#section > input[name='menu']").value = 'Добавить в меню';
                }
                else document.querySelector("#section > input[name='menu']").style.display = 'none';
                document.getElementById('section').style.left = e.getBoundingClientRect().left+e.getBoundingClientRect().width+10;
                document.getElementById('section').style.top = e.getBoundingClientRect().top;
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
                <table class='admin-table'>
                    <tr>
                        <td>
                            <p>Статьи:</p>
                            <ul class="admin-list links">
                            @foreach ($materials as $material)
                                @if ($material->isArticle)
                                    <li><a href="javascript:void(0)" inMenu="{{ $material->isInMenu }}" id="{{ $material->id }}" onclick="editArticle(this)">{{ $material->title }}</a></li>
                                @endif
                            @endforeach
                            </ul>
                            <form action="{{ url('admin/article')}}" method="post" id="article" style="display:none">
                                @csrf
                                <input type='hidden' name='articleID' />
                                <input type='submit' name='menu'/>
                                <input type='submit' name='edit' value='Редактировать' />
                                <input type='submit' name='delete' value='Удалить' />
                            </form>
                        </td>
                        <td>
                            <p>Пользователи:</p>
                            <ul class="admin-list links">
                            @foreach ($users as $user)
                                <li><a href="javascript:void(0)" level="{{ $user->level }}" onclick="editUser(this)">{{ $user->name }}</a></li>
                            @endforeach
                            </ul>
                            <form action="{{ url('admin/userlevel') }}" method="post" id="level" style="display:none">
                                @csrf
                                <input type="hidden" name="username" />
                                <input type="text" name="newlevel"/>
                            </form>
                        </td>
                        <td>
                            <p>Разделы:</p>
                            <ul class="admin-list links">
                            @foreach ($materials as $material)
                                @if (!$material->isArticle)
                                    <li><a href="javascript:void(0)" id="{{ $material->id }}"  inMenu="{{ $material->isInMenu }}" onclick="editSection(this)">{{ $material->title }}</a></li>
                                @endif
                            @endforeach
                                <li class="another"><a href="javascript:void(0)" id="" onclick="editSection(this)">Создать новый раздел</a></li>
                            </ul>
                            <form action="{{ url('admin/section') }}" method="post" id="section" style="display:none">
                                @csrf
                                <input type='submit' name='menu'/>
                                <input type="hidden" name="id" />
                                <input type="text" name="title" placeholder="Новое название"/>
                                <input type="submit" value="Сохранить" />
                                <input type='submit' name='delete' value="Удалить"/>
                            </form>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>