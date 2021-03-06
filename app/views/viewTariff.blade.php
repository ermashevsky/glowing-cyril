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
                <img src=<?php public_path();?>"/img/logo-foot.png"/>
                <img src=<?php public_path(); ?>"/img/MysqlLogo_32.png" />
                <img src=<?php public_path(); ?>"/img/PythonPowered.png" />
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
    <div id="saveButton"></div>
    <div class="page-header">
        <h3><i class="glyphicon glyphicon-dashboard"></i> Сравнение тарифов - Стоимость
            
                @foreach($prices as $tariff)
                @endforeach
                
                [Тариф: {{$tariff->description}}
                {{$tariff->ip_address}}
                {{date("d.m.Y H:i",strtotime($tariff->timestamp))}}]
            </h3>
    </div>
{{  HTML::link(URL::previous(),' Назад', array('class' => 'btn btn-warning glyphicon glyphicon-arrow-left')) }}
    <a href></a>
    <?php $i = 1 ?>
    <div id="tariffsTable">

        <table class="table table-striped table-bordered table-condensed" id="priceTable">
            <thead>
                <tr>
                    <th>№</th>
                    <th>Код</th>
                    <th>Цена</th>
                    <th>Направление (справочно)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prices as $price)
                <tr>
                    <td><?php echo $i ++ ?></td>
                    <td>{{ $price->code }}</td>
                    <td>{{ $price->price }}</td>
                    <td>{{ $price->operator }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>


    @stop