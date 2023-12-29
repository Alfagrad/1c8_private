<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>API: {{$title}}</title>

    <!-- css stylesheets -->
    <link href="{{ asset('assets/vendors/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/api/style.css') }}" rel="stylesheet">
</head>

<body>

<!-- modal box -->
<div class="modal fade" id="my-modal-box" tabindex="-1" role="dialog" aria-labelledby="my-modal-box-l"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <div class="modal-title" id="my-modal-box-l">
                    <h3>Share it</h3>
                </div>
            </div>
            <!-- /.modal-header -->
            <div class="modal-body">
                <p>Share it box content</p>
                <!-- AddThis Button BEGIN -->
                <div class="addthis_toolbox addthis_default_style addthis_32x32_style">
                    <a class="addthis_button_preferred_1"></a>
                    <a class="addthis_button_preferred_2"></a>
                    <a class="addthis_button_preferred_3"></a>
                    <a class="addthis_button_preferred_4"></a>
                    <a class="addthis_button_compact"></a>
                    <a class="addthis_counter addthis_bubble_style"></a>
                </div>
                <script type="text/javascript"
                        src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4dbfb1915f17d240"></script>
            </div>
            <!-- /.modal-body -->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- fixed navigation bar -->
<div class="navbar navbar-fixed-top navbar-inverse" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#b-menu-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"> AlfaStock API: {{$title}}</a>
        </div>

    </div>
    <!-- /.container -->
</div>
<!-- /.navbar -->


<!-- main container -->
<div class="container">

    <!-- second menu bar -->
    <nav class="navbar navbar-default navbar-static">
        <div class="navbar-header">
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#b-menu-2">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="http://www.script-tutorials.com/responsive-website-using-bootstrap/">Bootstrap
                website</a>
        </div>

        <!-- submenu elements for #b-menu-2 -->
        <div class="collapse navbar-collapse" id="b-menu-2">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#"><span class="glyphicon glyphicon-home"></span> Home</a></li>
                <li><a href="#"><span class="glyphicon glyphicon-question-sign"></span> Help</a></li>
                <li><a href="#"><span class="glyphicon glyphicon-exclamation-sign"></span> About</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span
                                class="glyphicon glyphicon-list"></span> Other <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Submenu 1</a></li>
                        <li><a href="#">Submenu 2</a></li>
                        <li><a href="#">Submenu 3</a></li>
                        <li><a href="#">Submenu 4</a></li>
                        <li class="divider"></li>
                        <li><a href="#">Submenu 5</a></li>
                        <li><a href="#">Submenu 6</a></li>
                        <li class="divider"></li>
                        <li><a href="#">Submenu 7</a></li>
                    </ul>
                </li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li data-toggle="modal" data-target="#my-modal-box"><a href="#"><span
                                class="glyphicon glyphicon-share"></span> Share popup</a></li>
            </ul>
        </div>
        <!-- /.nav-collapse -->
    </nav>

    <!-- 2-column layout -->
    <div class="row row-offcanvas row-offcanvas-right">
        <div class="col-xs-12 col-sm-9">
            <div class="row">

                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{$title}}</h3>
                    </div>
                    <div class="panel-body">

                        <p><b>Глагол:</b> {{$verb}}</p>
                        <p><b>Путь:</b> {{$url_api}}</p>
                        @if(isset($url_api_alt))
                            <p><b>Альтернатива для GET:</b> {{$url_api_alt}}</p>
                        @endif
                        <p>{!! $desc !!}</p>

                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Отправка запроса</h3>
                    </div>
                    <div class="panel-body">
                        <form action="api/v1/mark/create" method="post" id="test-api">

                            <textarea name="demo_xml" id="demo_xml" class="form-control" rows="15">{{$test_xml}}</textarea>

                            <input type="submit" value="Отправить" id="submit" class="btn btn-primary"/>
                        </form>
                    </div>
                </div>

                <div class="panel panel-success "> <!-- panel-danger -->
                    <div class="panel-heading">
                        <h3 class="panel-title">Ответ от сервера</h3>
                    </div>
                    <div class="panel-body">
                        <textarea name="request" id="request" class="form-control" rows="15"></textarea>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-sm-3 sidebar-offcanvas" id="sidebar">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <h3 class="panel-title">Информация:</h3>
                </div>
                <div class="panel-body">
                    <p>
                        <b>Login:</b> AlfaStockApi <br>
                        <b>Password:</b> PC6wpCjZ
                    </p>
                </div>
            </div>
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">Категории</h3>
                </div>
                <ul class="nav nav-pills nav-stacked">
                    <li><a href="/api/v1/category/createOrUpdate">Добавить или обновить</a></li>
                    <li><a href="/api/v1/category/delete">Удалить</a></li>
                    <li><a href="/api/v1/category/truncate">Очистить таблицу</a></li>

                </ul>
            </div>

            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">Товары</h3>
                </div>
                <ul class="nav nav-pills nav-stacked">
                    <li><a href="/api/v1/items/createOrUpdate">Добавить или обновить</a></li>
                    <li><a href="/api/v1/items/updatePrice">Обновить цену</a></li>
                    <li><a href="/api/v1/items/delete">Удалить</a></li>
                    <li><a href="/api/v1/items/truncate">Очистить таблицу</a></li>

                </ul>
            </div>


            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">Компании</h3>
                </div>
                <ul class="nav nav-pills nav-stacked">
                    <li><a href="/api/v1/users/createOrUpdate">Обновить</a></li>
                    <li><a href="/api/v1/users/blocked">Заблокировать</a></li>
                    <li><a href="/api/v1/users/unblocked">Разблокировать</a></li>
                    <li><a href="/api/v1/users/show/new">Получить новых</a></li>

                </ul>
            </div>


            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">Ремонты</h3>
                </div>
                <ul class="nav nav-pills nav-stacked">
                    <li><a href="/api/v1/repairs/createOrUpdate">Добавить или обновить</a></li>
                    <li><a href="/api/v1/repairs/delete">Удалить</a></li>
                    <li><a href="/api/v1/repairs/truncate">Очистить таблицу</a></li>
                </ul>
            </div>

            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">Задолженность</h3>
                </div>
                <ul class="nav nav-pills nav-stacked">
                    <li><a href="/api/v1/depts/createOrUpdate">Добавить или обновить</a></li>
                    <li><a href="/api/v1/depts/delete">Удалить</a></li>
                    <li><a href="/api/v1/depts/truncate">Очистить таблицу</a></li>
                </ul>
            </div>



            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">Курсы валют</h3>
                </div>
                <ul class="nav nav-pills nav-stacked">
                    <li><a href="/api/v1/currency/createOrUpdate">Добавить или обновить</a></li>
                </ul>
            </div>

            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">Заявки</h3>
                </div>
                <ul class="nav nav-pills nav-stacked">
                    <li><a href="/api/v1/orders/show/new">Получить новые</a></li>
                </ul>
            </div>

            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">Бренды</h3>
                </div>
                <ul class="nav nav-pills nav-stacked">
                    <li><a href="/api/v1/brand/createOrUpdate">Добавить или обновить</a></li>
                    {{-- <li><a href="/api/v1/brand/delete">Удалить</a></li> --}}
                    {{-- <li><a href="/api/v1/brand/truncate">Очистить таблицу</a></li> --}}

                </ul>
            </div>

            <!--
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Модели</h3>
                            </div>
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="/api/v1/seria/createOrUpdate">Добавить или обновить</a></li>
                                <li><a href="/api/v1/seria/delete">Удалить</a></li>
                                <li><a href="/api/v1/seria/truncate">Очистить таблицу</a></li>

                            </ul>

                        </div>


                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Тип запчасти</h3>
                            </div>
                            <ul class="nav nav-pills nav-stacked">

                                <li><a href="/api/v1/spire/createOrUpdate">Добавить или обновить</a></li>
                                <li><a href="/api/v1/spire/delete">Удалить</a></li>
                                <li><a href="/api/v1/spire/truncate">Очистить таблицу</a></li>

                            </ul>

                        </div>


                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Тип характеристики запчасти</h3>
                            </div>
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="/api/v1/feature/createOrUpdate">Добавить или обновить</a></li>
                                <li><a href="/api/v1/feature/delete">Удалить</a></li>
                                <li><a href="/api/v1/feature/truncate">Очистить таблицу</a></li>

                            </ul>

                        </div>


                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Свойста характеристики запчасти</h3>
                            </div>
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="/api/v1/elfeature/createOrUpdate">Добавить или обновить</a></li>
                                <li><a href="/api/v1/elfeature/delete">Удалить</a></li>
                                <li><a href="/api/v1/elfeature/truncate">Очистить таблицу</a></li>
                            </ul>

                        </div>


                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Запчасть</h3>
                            </div>
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="/api/v1/part/createOrUpdate">Добавить или обновить</a></li>
                                <li><a href="/api/v1/part/delete">Удалить</a></li>
                                <li><a href="/api/v1/part/truncate">Очистить таблицу</a></li>
                            </ul>

                        </div>


                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Заявки</h3>
                            </div>
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="/api/v1/request/show">Получить заявку</a></li>
                            </ul>
                        </div>


                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Вопросы</h3>
                            </div>
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="/api/v1/question/show">Получить вопрос</a></li>
                                <li><a href="/api/v1/question/createOrUpdate">Обновить вопрос</a></li>
                            </ul>
                        </div>

                        --!>
                        <!-- box 6 -->
            <!--
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">Машинокомплект: Марка</h3>
                        </div>
                        <ul class="nav nav-pills nav-stacked">
                            <li><a href="/api/v1/complete/mark/createOrUpdate">Добавить или обновить</a></li>
                            <li><a href="/api/v1/complete/mark/delete">Удалить</a></li>
                            <li><a href="/api/v1/complete/mark/truncate">Очистить таблицу</a></li>
                        </ul>
                    </div>


                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">Машинокомплекты: Модель</h3>
                        </div>
                        <ul class="nav nav-pills nav-stacked">
                            <li><a href="/api/v1/complete/seria/createOrUpdate">Добавить или обновить</a></li>
                            <li><a href="/api/v1/complete/seria/delete">Удалить</a></li>
                            <li><a href="/api/v1/complete/seria/truncate">Очистить таблицу</a></li>
                        </ul>

                    </div>

                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">Машинокомплекты: Комплект</h3>
                        </div>
                        <ul class="nav nav-pills nav-stacked">
                            <li><a href="/api/v1/complete/mcomplete/createOrUpdate">Добавить или обновить</a></li>
                            <li><a href="/api/v1/complete/mcomplete/delete">Удалить</a></li>
                            <li><a href="/api/v1/complete/mcomplete/truncate">Очистить таблицу</a></li>
                        </ul>

                    </div>

                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">Шины</h3>
                        </div>
                        <ul class="nav nav-pills nav-stacked">
                            <li><a href="/api/v1/bus/createOrUpdate">Добавить или обновить</a></li>
                            <li><a href="/api/v1/bus/delete">Удалить</a></li>
                            <li><a href="/api/v1/bus/truncate">Очистить таблицу</a></li>
                        </ul>

                    </div>

                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">Шины: Свойства</h3>
                        </div>
                        <ul class="nav nav-pills nav-stacked">
                            <li><a href="/api/v1/bus/feature/createOrUpdate">Добавить или обновить</a></li>
                            <li><a href="/api/v1/bus/feature/delete">Удалить</a></li>
                            <li><a href="/api/v1/bus/feature/truncate">Очистить таблицу</a></li>
                        </ul>

                    </div>
            -->



        </div>
        <!-- /column 3 (sidebar) -->

    </div>
    <!--/row-->

    <footer>
        <nav class="navbar navbar-default navbar-static-bottom" role="navigation">
            <p class="navbar-text">&copy Copyright 2017</p>
        </nav>
    </footer>

</div>
<!--/.container-->


<!-- add javascripts -->
<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="{{ asset('assets/vendors/bootstrap/js/bootstrap.min.js') }}"></script>
<script>

    $(document).ready(function () {
        $("#test-api").submit(function (event) {
            event.preventDefault();

            var options = new Object();
            options['xml'] = $('#demo_xml').val();
            // console.log(options);

            $.post("{{$url_api}}", options, function (data) {
                // console.log("Data Loaded: " + data);
                $('#request').val(data);


                if (data.indexOf('<error>') + 1) {
                    $('.panel-success').removeClass('panel-success').addClass('panel-danger')
                } else {
                    $('.panel-danger').removeClass('panel-danger').addClass('panel-success')
                }

            });

            return false;

        })
    });


</script>

</body>
</html>