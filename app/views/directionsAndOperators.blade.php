@extends('template.template')
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <a href="/" class="navbar-brand"><i class="glyphicon glyphicon-leaf"> </i> MeraLogAnalyzer 2.0.1 (MySQL Version)</a>
        </div>
        <div id="navbarCollapse" class="collapse navbar-collapse">
            <!--            <ul class="nav navbar-nav">
                            <li style="margin:10px;"><button type="button" class="btn btn-warning" data-toggle="modal" data-target="#myModal"><i class="glyphicon glyphicon-send"> </i> Составить запрос</button></li>
                        </ul>-->
            <ul class="nav navbar-nav">
                <li style="margin:10px;"><button type="button" class="btn btn-warning" onclick="getStatistic();
                        return false;"><i class="glyphicon glyphicon-stats"> </i> Статистика</button></li>
            </ul>
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="glyphicon glyphicon-th"> </i> Разделы <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="/"><i class="glyphicon glyphicon-home"></i> Главная</a></li>
                        <li><a href="/operators"><i class="glyphicon glyphicon-phone"></i> Статистика по операторам</a></li>
                        <li><a href="/directions"><i class="glyphicon glyphicon-random"></i> Направления и операторы</a></li>
                        <li><a href="/compareTariffs"><i class="glyphicon glyphicon-dashboard"></i> Сравнение тарифов</a></li>
                    </ul>
                </li>
            </ul>
            <div class="pull-right" style="margin-top:12px;">
                <img src=<?php public_path(); ?>"img/logo-foot.png" />
                <img src=<?php public_path(); ?>"img/MysqlLogo_32.png" />
                <img src=<?php public_path(); ?>"img/PythonPowered.png" />
            </div>
        </div>
    </div>
</nav>

<div class="container-fluid" style="margin-top: 66px;">
    @section('content')
    <div class="modal fade" id="myStatisticModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="list-group">
                    <a href="#" class="list-group-item active">
                        <i class="glyphicon glyphicon-stats"> </i> Статистика базы данных
                    </a>
                    <ul class="nav nav-pills nav-stacked">
                        <li>
                            <a>
                                <span class="badge alert-info pull-right" id="actual_db_date"></span>
                                Актуальность базы
                            </a>
                        </li>
                        <li>
                            <a>
                                <span class="badge alert-info pull-right" id="records_counter"></span>
                                Количество записей
                            </a>
                        </li>
                        <li>
                            <a>
                                <span class="badge alert-info pull-right" id="range_date"></span>
                                Данные за период
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div id="circle"></div>

    <div class="page-header">
        <h3><i class="glyphicon glyphicon-random"></i> Направления и операторы</h3>
    </div>

    <ul class="nav navbar-nav">

        <li style="margin:10px;">
            <button type="button" class="btn btn-warning" data-toggle="modal" id="findButton" data-target="#myFindWindow">
                <i class="glyphicon glyphicon-search"> </i> Поиск
            </button>
        </li>
        <!--        <li style="margin:10px;">
                    <input type="checkbox" name="my-checkbox" id="my-checkbox" checked />
                </li>-->

    </ul>
    <?php $i = 1 ?>
    <div id="directionBlockTable"></div>

    <!-- Modal -->
    <div class="modal fade" id="myFindWindow" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">Поиск</h4>
                </div>
                <div class="modal-body">
                    <form id="operatorRulesform">
                        <input type="hidden" id="id" />
                        <div class="form-group">
                            <label for="start_date" class="control-label">Дата/Время:</label>
                            <input type="text" class="form-control" id="start_date" placeholder="">
                        </div>
                        <div class="form-group">
                            <label for="end_date" class="control-label">Дата/Время:</label>
                            <input type="text" class="form-control" id="end_date" placeholder="">
                        </div>
                        <div class="form-group">
                            <label for="inputDirection">Направление</label>
                            <input type="text" class="form-control" id="inputDirection" placeholder="Введите наименование направления">
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="strongParam"> Строгое соответствие
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="inputOperatorDescription">Оператор</label>
                            <input type="text" class="form-control" id="inputOperatorDescription" placeholder="Введите наименование оператора">
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="chkBoxQ931"> Отображать коды завершений
                            </label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="saveButton" onclick="findDireactionsData();
                            return false;">Найти</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    @stop