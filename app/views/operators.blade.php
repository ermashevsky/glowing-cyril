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

    <div class="page-header">
        <h3><i class="glyphicon glyphicon-phone"></i> Статистика по операторам</h3>
    </div>
    
    <ul class="nav navbar-nav">
        
        <li style="margin:10px;">
            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#myModalRule">
                <i class="glyphicon glyphicon-plus-sign"> </i> Добавить правило
            </button>
        </li>
<!--        <li style="margin:10px;">
            <input type="checkbox" name="my-checkbox" id="my-checkbox" checked />
        </li>-->
        
    </ul>
    <?php $i=1 ?>
    <table class="table table-striped table-bordered table-condensed">
        <thead>
            <tr>
                <th>№</th>
                <th>Оператор</th>
                <th>IP-адрес</th>
                <th>Параметр</th>
                <th>Оператор сравнения</th>
                <th>Значение</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
        @foreach($rules as $rule)
        <tr>
            <td><?php echo $i ++?></td>
            <td>{{ $rule->operatorDescription }}</td>
            <td>{{ $rule->ip_address }}         </td>
            <td>{{ $rule->parameter }}          </td>
            <td>{{ $rule->comparisonOperator }} </td>
            <td>{{ $rule->value }}              </td>
            <td>
                
                <button type="button" class="btn btn-warning" title="Редактировать запись" onclick="editRule({{$rule->id}}); return false;">
                    <i class="glyphicon glyphicon-pencil"> </i>
                </button>
                
                <button type="button" class="btn btn-warning" title="Удалить запись" onclick="deleteRule({{$rule->id}}); return false;">
                    <i class="glyphicon glyphicon-trash"> </i>
                </button>
                
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>

    <!-- Modal -->
    <div class="modal fade" id="myModalRule" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">Новое правило</h4>
                </div>
                <div class="modal-body">
                    <form id="operatorRulesform">
                        <input type="hidden" id="id" />
                        <div class="form-group">
                            <label for="inputOperatorDescription">Оператор</label>
                            <input type="text" class="form-control" id="inputOperatorDescription" placeholder="Введите наименование оператора">
                        </div>
                        <div class="form-group">
                            <label for="inputIpAddress">Адрес</label>
                            <input type="text" class="form-control" id="inputIpAddress" placeholder="Введите IP-адрес оператора">
                        </div>
                        <div class="form-group">
                            <label for="inputParameter">Параметр</label>
                            <select class="form-control" id="inputParameter">
                                <option value="asr">ASR</option>
                                <option value="asrFull">ASR Full</option>
                                <option value="ACD">ACD</option>
                                <option value="callCounter">Количество звонков</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="inputComparisonOperator">Оператор сравнения</label>
                            <select class="form-control" id="inputComparisonOperator">
                                <option value=">"> > Больше</option>
                                <option value="<"> < Меньше</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="inputValue">Значение</label>
                            <input type="text" class="form-control" id="inputValue" placeholder="Введите значение">
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="saveButton" onclick="saveRule();
                            return false;">Сохранить</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
    
    @stop