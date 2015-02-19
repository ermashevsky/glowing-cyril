</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class="glyphicon glyphicon-send"> </i> Конструктор запроса</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label for="start_date" class="col-sm-3 control-label">Дата/Время:</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="start_date" placeholder="">
                        </div>
                        
                    </div>
                    <div class="form-group">
                        <label for="end_date" class="col-sm-3 control-label">Дата/Время:</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="end_date" placeholder="">
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="host" class="col-sm-3 control-label">Host:</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="host" placeholder="">
                        </div>
                        <div class="col-sm-4">
                            <select class="form-control" id="select_host">
                                <option value="anywhere">в любом месте</option>
                                <option value="beginning">в начале строки</option>
                                <option value="ending">в конце строки</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="destination_ip" class="col-sm-3 control-label">Destionation IP:</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="destination_ip" placeholder="">
                        </div>
                        <div class="col-sm-4">
                            <select class="form-control" id="select_destination_ip">
                                <option value="anywhere">в любом месте</option>
                                <option value="beginning">в начале строки</option>
                                <option value="ending">в конце строки</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="source_ip" class="col-sm-3 control-label">Source IP:</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="source_ip" placeholder="">
                        </div>
                        <div class="col-sm-4">
                            <select class="form-control" id="select_source_ip">
                                <option value="anywhere">в любом месте</option>
                                <option value="beginning">в начале строки</option>
                                <option value="ending">в конце строки</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="q931" class="col-sm-3 control-label">Q931:</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="q931" placeholder="">
                        </div>
                        <div class="col-sm-4">
                            <select class="form-control" id="select_q931">
                                <option value="anywhere">в любом месте</option>
                                <option value="beginning">в начале строки</option>
                                <option value="ending">в конце строки</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="elapsed_time" class="col-sm-3 control-label">Elapsed Time:</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="elapsed_time" placeholder="">
                        </div>
                        <div class="col-sm-4">
                            <select class="form-control" id="select_elapsed_time">
                                <option value="anywhere">в любом месте</option>
                                <option value="beginning">в начале строки</option>
                                <option value="ending">в конце строки</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="src_number_bill" class="col-sm-3 control-label">Source Number:</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="src_number_bill" placeholder="">
                        </div>
                        <div class="col-sm-4">
                            <select class="form-control" id="select_src_number_bill">
                                <option value="anywhere">в любом месте</option>
                                <option value="beginning">в начале строки</option>
                                <option value="ending">в конце строки</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="dst_number_bill" class="col-sm-3 control-label">Dest. Number:</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="dst_number_bill" placeholder="">
                        </div>
                        <div class="col-sm-4">
                            <select class="form-control" id="select_dst_number_bill">
                                <option value="anywhere">в любом месте</option>
                                <option value="beginning">в начале строки</option>
                                <option value="ending">в конце строки</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="dst_number_out" class="col-sm-3 control-label">Dest. Number Out:</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="dst_number_out" placeholder="">
                        </div>
                        <div class="col-sm-4">
                            <select class="form-control" id="select_dst_number_out">
                                <option value="anywhere">в любом месте</option>
                                <option value="beginning">в начале строки</option>
                                <option value="ending">в конце строки</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="dst_codec" class="col-sm-3 control-label">Dest. Codec:</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="dst_codec" placeholder="">
                        </div>
                        <div class="col-sm-4">
                            <select class="form-control" id="select_dst_codec">
                                <option value="anywhere">в любом месте</option>
                                <option value="beginning">в начале строки</option>
                                <option value="ending">в конце строки</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="region" class="col-sm-3 control-label">Region:</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="region" placeholder="">
                        </div>
                        <div class="col-sm-4">
                            <select class="form-control" id="select_region">
                                <option value="anywhere">в любом месте</option>
                                <option value="beginning">в начале строки</option>
                                <option value="ending">в конце строки</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                
                <button type="button" class="btn btn-primary" onclick="sendQueryData(); return false;"><i class="glyphicon glyphicon-search"> </i> Найти</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->

<div id="tableDataBlock" style="margin: 20px;">
    
</div>
<div id="footer" class="navbar-fixed-bottom row-fluid">
      <div class="container">
          <p class="muted credit">
          
          </p>
      </div>
    </div>
</body>
</html>
