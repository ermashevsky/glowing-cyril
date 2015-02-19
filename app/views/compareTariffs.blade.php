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
        <h3><i class="glyphicon glyphicon-dashboard"></i> Сравнение тарифов</h3>
    </div>

    <ul class="nav navbar-nav">

        <li style="margin:10px;">
            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#myFindWindow">
                <i class="glyphicon glyphicon-plus-sign"> </i> Новый тариф
            </button>
        </li>
        <li style="margin:10px;">
            <button type="button" class="btn btn-warning" data-toggle="modal" id="compareButton" onclick="compareTariff(); return false;">
                <i class="glyphicon glyphicon-tasks"> </i> Сравнить
            </button>
        </li>
        <!--        <li style="margin:10px;">
                    <input type="checkbox" name="my-checkbox" id="my-checkbox" checked />
                </li>-->

    </ul>
    <?php $i = 1 ?>
    <div id="tariffsTable">

        <table class="table table-striped table-bordered table-condensed">
            <thead>
                <tr>
                    <th>№</th>
                    <th></th>
                    <th>Наименование тарифа</th>
                    <th>IP-адрес</th>
                    <th>Дата создания</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tariffsList as $tariff)
                <tr>
                    <td><?php echo $i ++ ?></td>
                    <td style="text-align:center; vertical-align:middle; min-width: 5px;">
                        <span class="button-checkbox">
                            <button type="button" class="btn" data-color="success">Сравнение</button>
                            <input type="checkbox" name="locationthemes" class="hidden" value="{{$tariff->id}}" />
                        </span>
                    </td>
                    <td>{{ $tariff->description }}</td>
                    <td>{{ $tariff->ip_address }}         </td>
                    <td>{{ date("d.m.Y H:i",strtotime($tariff->timestamp)) }}          </td>

                    <td>
                        {{ link_to_route('getPrices', '', $tariff->id, array('class' => 'btn btn-warning glyphicon glyphicon glyphicon-eye-open')) }}

                        {{ link_to_route('editPrices', '', $tariff->id, array('class' => 'btn btn-warning glyphicon glyphicon-pencil')) }}

                        <button type="button" class="btn btn-warning glyphicon glyphicon-trash" onclick="deleteTariff({{ $tariff->id }}); return false;"></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="myFindWindow" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">Новый тариф</h4>
                </div>
                <div class="modal-body">
                    <form id="operatorRulesform">
                        <input type="hidden" id="id" />
                        <div class="form-group">
                            <label for="description" class="control-label">Наименование</label>
                            <input type="text" class="form-control typeahead" id="description" size="50" placeholder="">
                        </div>
                        <div class="form-group">
                            <label for="ip_address" class="control-label">IP адрес:</label>
                            <input type="text" class="form-control" id="ip_address" placeholder="">
                        </div>
                        <div class="form-group">
                            <label for="csv_file">CSV файл</label>
                            <input type="file" class="form-control" id="csv_file" accept=".csv" />
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="saveButtonTariff" >Добавить</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    @stop