@extends('template.template')
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <a href="/" class="navbar-brand"><i class="glyphicon glyphicon-leaf"> </i> MeraLogAnalyzer 2.0.1 (MySQL Version)</a>
        </div>
        <div id="navbarCollapse" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li style="margin:10px;"><button type="button" class="btn btn-warning" data-toggle="modal" data-target="#myModal"><i class="glyphicon glyphicon-send"> </i> Составить запрос</button></li>
            </ul>
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
                <img src=<?php public_path();?>"img/logo-foot.png" />
                <img src=<?php public_path();?>"img/MysqlLogo_32.png" />
                <img src=<?php public_path();?>"img/PythonPowered.png" />
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
    <div type="hidden" id="saveButton"></div>

    <ul class="nav nav-pills" id="statisticBar" style="display: none;">
        <li class="active"><a href="#">Количество звонков: <span class="badge" id="all_call_stat"></span></a></li>
        <li class="active"><a href="#">Q931: 16, 17, 19 <span class="badge" id="q931_16_17_19_stat"></span></a></li>
        <li class="active"><a href="#">Q931: 34 <span class="badge" id="q931_34_stat"></span></a></li>
        <li class="active"><a href="#">Q931: 03 <span class="badge" id="q931_03_stat"></span></a></li>
        <li class="active"><a href="#">Elapsed Time > 0 <span class="badge" id="elapsed_time_counter_calls_stat"></span></a></li>
        <li class="active"><a href="#">ASR (%) <span class="badge" id="asr_stat"></span></a></li>
        <li class="active"><a href="#">ASR Full (%) <span class="badge" id="asr_full_stat"></span></a></li>
        <li class="active"><a href="#">ACD <span class="badge" id="asd_stat"></span></a></li>
    </ul>


    @stop