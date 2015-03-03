<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>MeraLogAnalyzer v2.0.1</title>
        {{ HTML::script('js/jquery-2.1.1.min.js') }}
        {{ HTML::script('js/bootstrap.js') }}
        {{ HTML::script('js/jquery.datetimepicker.js') }}
        {{ HTML::script('js/date.format.js') }}
        {{ HTML::script('js/jquery.dataTables.js') }}
        {{ HTML::script('js/dataTables.fixedHeader.js') }}
        {{ HTML::script('js/waitMe.js') }}
        {{ HTML::script('js/bootbox.js') }}
        {{ HTML::script('js/bootstrap-switch.js') }}
        {{ HTML::script('js/jquery.autocomplete.min.js') }}
        {{ HTML::script('js/jquery.csv-0.71.js') }}
        {{ HTML::script('js/pnotify.core.js') }}
        
        {{ HTML::style('css/bootstrap.css') }}
        {{ HTML::style('css/bootstrap-theme.css') }}
        {{ HTML::style('css/jquery.datetimepicker.css') }}
        {{ HTML::style('css/jquery.dataTables.css') }}
        {{ HTML::style('css/waitMe.css') }}
        {{ HTML::style('css/bootstrap-switch.css') }}
        {{ HTML::style('css/pnotify.core.css') }}

        {{ HTML::style('http://fonts.googleapis.com/css?family=Armata') }}
        {{ HTML::style('http://fonts.googleapis.com/css?family=Roboto:300,400') }}



        <script type="text/javascript">
            $(function () {

                var table = localStorage.getItem('table');
                var obj_data = JSON.parse(localStorage.getItem('dataset'));


                $('#comparePriceTable').append(table);
                //console.info(obj_data);

                if (obj_data) {
                    $.each(obj_data, function (i, val) {
                        // console.info(obj_data[i].selector);
                        // console.info(obj_data[i].price);
                        if($.trim( $('td#' + obj_data[i].selector).text())){
                            $('td#' + obj_data[i].selector).css('background-color', 'red');
                        }
                         

                        $('td#' + obj_data[i].selector).append(obj_data[i].price);
                    });
                }

                $('#compareButton').prop('disabled', true);

                $('input[name="locationthemes"]').change(function () {
                    var checked = $('input[name="locationthemes"]:checked').length;

                    if (checked >= 2) {
                        console.info("Condition OK");
                        $('#compareButton').prop('disabled', false);
                    } else {
                        $('#compareButton').prop('disabled', true);
                    }
                });

                $('.button-checkbox').each(function () {

                    // Settings
                    var $widget = $(this),
                            $button = $widget.find('button'),
                            $checkbox = $widget.find('input:checkbox'),
                            color = $button.data('color'),
                            settings = {
                                on: {
                                    icon: 'glyphicon glyphicon-check'
                                },
                                off: {
                                    icon: 'glyphicon glyphicon-unchecked'
                                }
                            };

                    // Event Handlers
                    $button.on('click', function () {
                        $checkbox.prop('checked', !$checkbox.is(':checked'));
                        $checkbox.triggerHandler('change');
                        updateDisplay();
                    });
                    $checkbox.on('change', function () {
                        updateDisplay();
                    });

                    // Actions
                    function updateDisplay() {
                        var isChecked = $checkbox.is(':checked');

                        // Set the button's state
                        $button.data('state', (isChecked) ? "on" : "off");

                        // Set the button's icon
                        $button.find('.state-icon')
                                .removeClass()
                                .addClass('state-icon ' + settings[$button.data('state')].icon);

                        // Update the button's color
                        if (isChecked) {
                            $button
                                    .removeClass('btn-default')
                                    .addClass('btn-' + color + ' active');
                        }
                        else {
                            $button
                                    .removeClass('btn-' + color + ' active')
                                    .addClass('btn-default');
                        }
                    }

                    // Initialization
                    function init() {

                        updateDisplay();

                        // Inject the icon if applicable
                        if ($button.find('.state-icon').length === 0) {
                            $button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i> ');
                        }
                    }
                    init();
                });


                $('#priceTable').dataTable({
                    "bProcessing": false,
                    "sDom": "<'row'<'span8'l><'span8'f>r>t<'row'<'span8'i><'span8'p>>",
                    "bDestroy": true,
                    "bJQueryUI": false, //Without Theme
                    "bPaginate": false,
                    "bLengthChange": false,
                    "bFilter": true,
                    "bSort": true,
                    "bInfo": true,
                    "bAutoWidth": true,
                    "sPaginationType": "bootstrap",
                    "iDisplayLength": -1,
                    "oLanguage": {
                        "sUrl": "/js/russian-language-DataTables.txt"
                    }
                });


                var table = $('#compareTable').dataTable({
                    "bProcessing": false,
                    "sDom": "<'row'<'span8'l><'span8'f>r>t<'row'<'span8'i><'span8'p>>",
                    "bDestroy": true,
                    "bJQueryUI": false, //Without Theme
                    "bPaginate": false,
                    "bLengthChange": true,
                    "bFilter": true,
                    "sPaginationType": "bootstrap",
                    "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Все"]],
                    "oLanguage": {
                        "sUrl": "js/russian-language-DataTables.txt"
                    }
                });

                //document.getElementById('saveButtonTariff').addEventListener('click', upload, false);
                $("#saveButtonTariff").click(function () {
                    var fileName = $("#csv_file").val();
                    var description = $("#description").val();
                    var ip_address = $("#ip_address").val();

                    if (fileName.lastIndexOf("csv") === fileName.length - 3) {
                        
                        $("#saveButtonTariff").prop('disabled', true);
                        upload();
                        

                    }
                    else
                    {
                        new PNotify({
                            title: 'Файл импорта',
                            text: 'Не указан файл импорта в csv формате',
                            styling: "bootstrap3",
                            type: "error",
                            delay: 3000
                        });
                    }


                });

                $("#ip_address").val();
                $.ajax({
                    type: "POST",
                    url: "/getOperatorsList",
                    success: function (data) {
                        console.info(data);
                        $('#description').autocomplete({
                            lookup: data,
                            onSelect: function (suggestion) {
                                $("#ip_address").val(suggestion.data);
                            }
                        });
                    }
                });

                var substringMatcher = function (strs) {
                    return function findMatches(q, cb) {
                        var matches, substrRegex;

                        // an array that will be populated with substring matches
                        matches = [];

                        // regex used to determine if a string contains the substring `q`
                        substrRegex = new RegExp(q, 'i');

                        // iterate through the pool of strings and for any string that
                        // contains the substring `q`, add it to the `matches` array
                        $('#ip_address').empty();
                        $.each(strs, function (i, str) {

                            if (substrRegex.test(strs[i].operator)) {
                                // the typeahead jQuery plugin expects suggestions to a
                                // JavaScript object, refer to typeahead docs for more info

                                matches.push({value: strs[i].operator});
                                $('#ip_address').val(strs[i].ip_address);

                            }
                        });

                        cb(matches);
                    };
                };

                $("[name='my-checkbox']").bootstrapSwitch({
                    size: 'normal',
                    onColor: 'warning',
                    offColor: 'danger'
                });

                var lastIdx = null;
                var table = $('#tableData').DataTable();

                $('#tableData tbody')
                        .on('mouseover', 'td', function () {
                            var colIdx = table.cell(this).index().column;

                            if (colIdx !== lastIdx) {
                                $(table.cells().nodes()).removeClass('highlight');
                                $(table.column(colIdx).nodes()).addClass('highlight');
                            }
                        })
                        .on('mouseleave', function () {
                            $(table.cells().nodes()).removeClass('highlight');
                        });

                $('#start_date').datetimepicker({
                    format: 'M d Y H:i:s',
                    value: new Date().format('mmm d yyyy 00:00:00'),
                    lang: 'ru',
                    step: 5,
                    closeOnDateSelect: true,
                    todayButton: true,
                    dayOfWeekStart: 1
                });

                $('#end_date').datetimepicker({
                    format: 'M d Y H:i:s',
                    value: new Date().format('mmm d yyyy HH:MM:ss'),
                    lang: 'ru',
                    step: 5,
                    closeOnDateSelect: true,
                    todayButton: true,
                    dayOfWeekStart: 1
                });
                /* Set the defaults for DataTables initialisation */
                $.extend(true, $.fn.dataTable.defaults, {
                    "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
                    "sPaginationType": "bootstrap",
                    "oLanguage": {
                        "sLengthMenu": "_MENU_ records per page"
                    }
                });




                /* Default class modification */
                $.extend($.fn.dataTableExt.oStdClasses, {
                    "sWrapper": "dataTables_wrapper form-inline",
                    "sFilterInput": "form-control input-sm",
                    "sLengthSelect": "form-control input-sm"
                });


                /* API method to get paging information */
                $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings)
                {
                    return {
                        "iStart": oSettings._iDisplayStart,
                        "iEnd": oSettings.fnDisplayEnd(),
                        "iLength": oSettings._iDisplayLength,
                        "iTotal": oSettings.fnRecordsTotal(),
                        "iFilteredTotal": oSettings.fnRecordsDisplay(),
                        "iPage": oSettings._iDisplayLength === -1 ?
                                0 : Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                        "iTotalPages": oSettings._iDisplayLength === -1 ?
                                0 : Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
                    };
                };

                /* Bootstrap style pagination control */
                $.extend($.fn.dataTableExt.oPagination, {
                    "bootstrap": {
                        "fnInit": function (oSettings, nPaging, fnDraw) {
                            var oLang = oSettings.oLanguage.oPaginate;
                            var fnClickHandler = function (e) {
                                e.preventDefault();
                                if (oSettings.oApi._fnPageChange(oSettings, e.data.action)) {
                                    fnDraw(oSettings);
                                }
                            };

                            $(nPaging).append(
                                    '<ul class="pagination">' +
                                    '<li class="prev disabled"><a href="#">&larr; ' + oLang.sPrevious + '</a></li>' +
                                    '<li class="next disabled"><a href="#">' + oLang.sNext + ' &rarr; </a></li>' +
                                    '</ul>'
                                    );
                            var els = $('a', nPaging);
                            $(els[0]).bind('click.DT', {action: "previous"}, fnClickHandler);
                            $(els[1]).bind('click.DT', {action: "next"}, fnClickHandler);
                        },
                        "fnUpdate": function (oSettings, fnDraw) {
                            var iListLength = 5;
                            var oPaging = oSettings.oInstance.fnPagingInfo();
                            var an = oSettings.aanFeatures.p;
                            var i, ien, j, sClass, iStart, iEnd, iHalf = Math.floor(iListLength / 2);

                            if (oPaging.iTotalPages < iListLength) {
                                iStart = 1;
                                iEnd = oPaging.iTotalPages;
                            }
                            else if (oPaging.iPage <= iHalf) {
                                iStart = 1;
                                iEnd = iListLength;
                            } else if (oPaging.iPage >= (oPaging.iTotalPages - iHalf)) {
                                iStart = oPaging.iTotalPages - iListLength + 1;
                                iEnd = oPaging.iTotalPages;
                            } else {
                                iStart = oPaging.iPage - iHalf + 1;
                                iEnd = iStart + iListLength - 1;
                            }

                            for (i = 0, ien = an.length; i < ien; i++) {
                                // Remove the middle elements
                                $('li:gt(0)', an[i]).filter(':not(:last)').remove();

                                // Add the new list items and their event handlers
                                for (j = iStart; j <= iEnd; j++) {
                                    sClass = (j == oPaging.iPage + 1) ? 'class="active"' : '';
                                    $('<li ' + sClass + '><a href="#">' + j + '</a></li>')
                                            .insertBefore($('li:last', an[i])[0])
                                            .bind('click', function (e) {
                                                e.preventDefault();
                                                oSettings._iDisplayStart = (parseInt($('a', this).text(), 10) - 1) * oPaging.iLength;
                                                fnDraw(oSettings);
                                            });
                                }

                                // Add / remove disabled classes from the static elements
                                if (oPaging.iPage === 0) {
                                    $('li:first', an[i]).addClass('disabled');
                                } else {
                                    $('li:first', an[i]).removeClass('disabled');
                                }

                                if (oPaging.iPage === oPaging.iTotalPages - 1 || oPaging.iTotalPages === 0) {
                                    $('li:last', an[i]).addClass('disabled');
                                } else {
                                    $('li:last', an[i]).removeClass('disabled');
                                }
                            }
                        }
                    }
                });

            });

            function getColumnId() {
                $('#compareTable tr').each(function () {
                    var row_id = $(this).closest('tr').attr('id');

                    $('#compareTable th').each(function () {

                        var col_id = $(this).attr('id');

                        return col_id;
                    });
                });
            }


            // Method that reads and processes the selected file
            function upload() {

                var formdata = new FormData();
                jQuery.each($('#csv_file')[0].files, function (i, file) {
                    formdata.append('csv_file', file);
                    formdata.append('description', $("#description").val());
                    formdata.append('ip_address', $("#ip_address").val());
                });


                $.ajax({
                    url: "fileUpload",
                    data: formdata,
                    dataType: "json",
                    type: "post",
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {

                        new PNotify({
                            title: 'Файл импорта',
                            text: 'Файл импорта данных загружен. Ожидайте его обработки',
                            styling: "bootstrap3",
                            type: "success",
                            delay: 3000
                        });
                        setTimeout(function () {
                            location.reload();
                        }, 4500);

                    },
                    failure: function () {

                    }
                });
                return false;
            }

            function compareTariff() {
                new PNotify({
                    title: 'Сравнение тарифов',
                    text: 'Готовим сводную таблицу. Ожидайте',
                    styling: "bootstrap3",
                    type: "success",
                    delay: 3000
                });

                //Количество чекбоксов
                //var checked = $('input[name="locationthemes"]:checked').length;
                //console.info(checked);

                var matches = [];

                $('input[name="locationthemes"]:checked').each(function () {
                    matches.push(this.value);

                });

                $('#comparePriceTable').empty();

                $.post("comparePrice", {ids: matches}, function (data) {

                    localStorage.setItem('table', data.table);
                    localStorage.setItem('dataset', JSON.stringify(data.dataset));

                    //console.info(data.table);
                    window.location.replace("http://mla3.lcl/viewComparedPrices");
                }, 'json');
            }

            Number.prototype.between = function (first, last) {
                return (first < last ? this >= first && this <= last : this >= last && this <= first);
            };

            function findDireactionsData() {
                var startTime = new Date();
                $('#directionBlockTable').empty();

                var start_date = $("#start_date").val();
                var end_date = $("#end_date").val();
                var operator = $("#inputOperatorDescription").val();
                var direction = $("#inputDirection").val();

                
                
                $('#myFindWindow .modal-dialog').waitMe({
                    effect: 'win8',
                    text: 'Данные готовятся. Пожалуйста, подождите.',
                    bg: 'rgba(255,255,255,0.7)',
                    color: '#428bca'
                });
                
                
                $.ajax({
                    url: '/findDireactionsData',
                    dataType: 'json',
                    type: 'post',
                    contentType: 'application/json',
                    data: JSON.stringify(
                            {start_date: start_date, end_date: end_date, operator: operator, direction: direction}),
                    processData: false,
                    success: function (data, textStatus, jQxhr) {
                        console.info(data);

                        console.time('test');
                        $("#directionBlockTable").append('<table class="table table-striped table-bordered table-condensed" id="directionTable">\n\
                <thead><tr><th>№</th><th>Направление</th><th>Оператор</th><th>Кол-во минут</th><th>Кол-во звонков</th><th>Кол-во звонков > 0</th><th>ACD</th><th>ASR (%)</th></tr></thead>\n\
                <tbody></tbody></table>');
                        $.each(data, function (i, val) {
                            var acd_bgcolor = data[i].acd_bgcolor;

                            $("#directionTable").append('<tr><td>' + i + '</td><td>' + data[i].region + '</td><td>' + data[i].operator + '</td><td align="right">' + data[i].minutes + '</td><td align="right">'
                                    + data[i].call_count + '</td><td align="right">' + data[i].eltime_counter + '</td><td align="right">' + data[i].acd + '</td><td align="right">' + data[i].asr + '</td></tr>');


                        });

                        $('#myFindWindow').modal('hide');
                        console.log('ending:  ', (new Date() - startTime) / 1000);

                    },
                    error: function (jqXhr, textStatus, errorThrown) {
                        console.log(errorThrown);
                    },
                    complete: function (jqXHR, textStatus) {

                        $('#directionTable').dataTable({
                            "bProcessing": true,
                            "sDom": "<'row'<'span8'l><'span8'f>r>t<'row'<'span8'i><'span8'p>>",
                            "bDestroy": true,
                            "bJQueryUI": false, //Without Theme
                            "bPaginate": true,
                            "bLengthChange": false,
                            "bFilter": true,
                            "bSort": true,
                            "bInfo": true,
                            "bAutoWidth": true,
                            "sPaginationType": "bootstrap",
                            "iDisplayLength": -1,
                            "oLanguage": {
                                "sUrl": "js/russian-language-DataTables.txt"
                            },
                            "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                                if (aData[6] >= 70.00 && aData[6] <= 100.00)
                                {
                                    $('td:eq(6)', nRow).css('background-color', '#FF9E1F');
                                }
                                if (aData[6] < 70.00)
                                {
                                    $('td:eq(6)', nRow).css('background-color', '#CC2D2D');
                                }

                                if (aData[7] >= 40 && aData[7] <= 50)
                                {
                                    $('td:eq(7)', nRow).css('background-color', '#FF9E1F');
                                }
                                if (aData[7] < 40)
                                {
                                    $('td:eq(7)', nRow).css('background-color', '#CC2D2D');
                                }
//                                    if (aData[8] < "70")
//                                    {
//                                        $('td', nRow).css('background-color', 'Red');
//                                        }
                            }
                        });
                        //$('#myModal .modal-dialog').waitMe("hide");
                        console.timeEnd('test');
                    }
                });



                $('#tableDataBlock').css('display', 'block');
            }

            function sendQueryData() {
                var startTime = new Date();
                $('#tableDataBlock').empty();

                var start_date = $("#start_date").val();
                var end_date = $("#end_date").val();
                var host = $("#host").val();
                var destination_ip = $("#destination_ip").val();
                var source_ip = $("#source_ip").val();
                var q931 = $("#q931").val();
                var elapsed_time = $("#elapsed_time").val();
                var src_number_bill = $("#src_number_bill").val();
                var dst_number_bill = $("#dst_number_bill").val();
                var dst_number_out = $("#dst_number_out").val();
                var dst_codec = $("#dst_codec").val();
                var region = $("#region").val();
                var select_host = $("#select_host").val();
                var select_destination_ip = $("#select_destination_ip").val();
                var select_source_ip = $("#select_source_ip").val();
                var select_q931 = $("#select_q931").val();
                var select_elapsed_time = $("#select_elapsed_time").val();
                var select_src_number_bill = $("#select_src_number_bill").val();
                var select_dst_number_bill = $("#select_dst_number_bill").val();
                var select_dst_number_out = $("#select_dst_number_out").val();
                var select_dst_codec = $("#select_dst_codec").val();
                var select_region = $("#select_region").val();

                spinner();
                $.ajax({
                    url: '/sendQueryData',
                    dataType: 'json',
                    type: 'post',
                    contentType: 'application/json',
                    data: JSON.stringify(
                            {start_date: start_date, end_date: end_date,
                                host: host, destination_ip: destination_ip,
                                source_ip: source_ip, q931: q931, elapsed_time: elapsed_time,
                                src_number_bill: src_number_bill, dst_number_bill: dst_number_bill,
                                dst_number_out: dst_number_out, dst_codec: dst_codec, region: region, select_host: select_host,
                                select_destination_ip: select_destination_ip, select_source_ip: select_source_ip,
                                select_q931: select_q931, select_elapsed_time: select_elapsed_time, select_src_number_bill: select_src_number_bill,
                                select_dst_number_bill: select_dst_number_bill, select_dst_number_out: select_dst_number_out, select_dst_codec: select_dst_codec, select_region: select_region
                            }),
                    processData: false,
                    success: function (data, textStatus, jQxhr) {
                        console.info(data);

                        html = '<table id="tableData" class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">' +
                                '<thead><tr> <th>Host</th><th>SRC-IP</th>' +
                                '<th>DST-IP</th><th>Q931</th><th>DCL</th>' +
                                '<th>Elapsed Time</th><th>Source Number (Billing)</th>' +
                                '<th>Destination Number (Billing)</th>' +
                                '<th>Destination Number (Out)</th>' +
                                '<th>Destionation Codec</th>' +
                                '<th>Setup Date</th>' +
                                '<th>Setup Time</th>' +
                                '<th>Phone Code</th>' +
                                '<th>Region</th></tr></thead>' +
                                '<tbody></tbody></table>';

                        $('div#tableDataBlock').append(html);

                        var appendDataRows = "";

                        var count_34 = 0;
                        var count_16_17_19 = 0;
                        var count_03 = 0;
                        var elapsed_time_counter_calls = 0;
                        var elapsed_time_min_collector = 0;
                        var elapsed_time_counter_min = 0;
                        var counter_all_call = 0;
                        console.time('test');

                        $.each(data, function (i, val) {
                            appendDataRows += '<tr><td>' + data[i].host + '</td><td>' + data[i].src_ip + '</td><td>' + data[i].dst_ip + '</td><td>' + data[i].disconnect_code_q931
                                    + '</td><td>' + data[i].disconnect_code_local + '</td><td>' + data[i].elapsed_time + '</td><td>' + data[i].src_number_bill
                                    + '</td><td>' + data[i].dst_number_bill + '</td><td>' + data[i].dst_number_out + '</td><td>' + data[i].dst_codec
                                    + '</td><td>' + data[i].setup_time_date + '</td><td>' + data[i].setup_time_time + '</td><td>' + data[i].phone_code
                                    + '</td><td>' + data[i].region + '</td></tr>';

                            if (data[i].disconnect_code_q931 === "34") {
                                count_34++;
                            }
                            if (data[i].disconnect_code_q931 === "16" || data[i].disconnect_code_q931 === "17" || data[i].disconnect_code_q931 === "19") {
                                count_16_17_19++;
                            }

                            if (data[i].disconnect_code_q931 === "3") {
                                count_03++;
                            }

                            if (data[i].elapsed_time > 0) {
                                elapsed_time_counter_calls++;
                                elapsed_time_min_collector += +data[i].elapsed_time;
                            }

                            counter_all_call++;

                        });


                        var asr_stat = ((elapsed_time_counter_calls / (counter_all_call)) * 100).toFixed(2);
                        var asr_full_stat = ((count_16_17_19 / (counter_all_call)) * 100).toFixed(2);
                        var asd = elapsed_time_min_collector / elapsed_time_counter_calls;



                        $('#start_date_stat').empty();
                        $('#end_date_stat').empty();
                        $('#q931_16_17_19_stat').empty();
                        $('#q931_03_stat').empty();
                        $('#q931_34_stat').empty();
                        $('#all_call_stat').empty();
                        $('#elapsed_time_counter_calls_stat').empty();
                        $('#asr_stat').empty();
                        $('#asr_full_stat').empty();
                        $('#asd_stat').empty();


                        $('#start_date_stat').append($('form.myform #start_date').val());
                        $('#end_date_stat').append($('form.myform #end_date').val());
                        $('#q931_16_17_19_stat').append(count_16_17_19);
                        $('#q931_03_stat').append(count_03);
                        $('#q931_34_stat').append(count_34);
                        $('#all_call_stat').append(counter_all_call);
                        $('#asr_stat').append(asr_stat);
                        $('#elapsed_time_counter_calls_stat').append(elapsed_time_counter_calls);
                        $('#asr_full_stat').append(asr_full_stat);
                        $('#asd_stat').append(asd);

                        $('#statisticBar').css('display', 'block');

                        $('#tableData').append(appendDataRows);

                        $('#myModal').modal('hide');
                        console.log('ending:  ', (new Date() - startTime) / 1000);

                    },
                    error: function (jqXhr, textStatus, errorThrown) {
                        console.log(errorThrown);
                    },
                    complete: function (jqXHR, textStatus) {

                        $('#tableData').dataTable({
                            "bProcessing": true,
                            "sDom": "<'row'<'span8'l><'span8'f>r>t<'row'<'span8'i><'span8'p>>",
                            "bDestroy": true,
                            "bJQueryUI": false, //Without Theme
                            "bPaginate": true,
                            "bLengthChange": true,
                            "bFilter": true,
                            "bSort": true,
                            "bInfo": true,
                            "bAutoWidth": true,
                            "sPaginationType": "bootstrap",
                            "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Все"]],
                            "oLanguage": {
                                "sUrl": "js/russian-language-DataTables.txt"
                            }
                        });

                        $('#myModal .modal-dialog').waitMe("hide");
                        console.timeEnd('test');
                    }
                });



                $('#tableDataBlock').css('display', 'block');
            }

            function spinner() {
                $('#myModal .modal-dialog').waitMe({
                    effect: 'win8',
                    text: 'Данные готовятся. Пожалуйста, подождите.',
                    bg: 'rgba(255,255,255,0.7)',
                    color: '#428bca'
                });
            }
            function getStatistic() {

                $('#actual_db_date').empty();
                $('#records_counter').empty();
                $('#range_date').empty();

                $.post("/getStatisticInfo", function (data) {

                    $('#actual_db_date').append(data.actual_db_date);
                    $('#records_counter').append(data.records_counter);
                    $('#range_date').append(data.range_date);

                    $('#myStatisticModal').modal('show');
                }, 'json');
            }

            function saveRule() {

                var inputOperatorDescription = $("#inputOperatorDescription").val();
                var inputIpAddress = $("#inputIpAddress").val();
                var inputParameter = $("#inputParameter").val();
                var inputComparisonOperator = $("#inputComparisonOperator").val();
                var inputValue = $("#inputValue").val();

                $.ajax({
                    type: "POST",
                    url: "/saveRule",
                    data: {
                        inputOperatorDescription: inputOperatorDescription,
                        inputIpAddress: inputIpAddress,
                        inputParameter: inputParameter,
                        inputComparisonOperator: inputComparisonOperator,
                        inputValue: inputValue
                    },
                    success: function (data) {

                        $('#myModalRule').modal('hide');
                        location.reload();
                    }
                });
            }

            function deleteRule(id) {
                bootbox.dialog({
                    message: "Вы действительно хотите удалить правило?",
                    title: "Удаление правила",
                    buttons: {
                        main: {
                            label: "Да",
                            className: "btn btn-primary",
                            callback: function () {
                                $.post("/deleteRule", {id: id}, function (data) {
                                    console.info(data);
                                    location.reload();
                                });
                            }
                        },
                        danger: {
                            label: "Нет",
                            className: "btn btn-default",
                            callback: function () {
                                location.reload();
                            }
                        }
                    }
                });
            }

            function deleteTariff(id) {
                bootbox.dialog({
                    message: "Вы действительно хотите удалить тариф?",
                    title: "Удаление тарифа",
                    buttons: {
                        main: {
                            label: "Да",
                            className: "btn btn-primary",
                            callback: function () {
                                $.post("/deleteTariff", {id: id}, function (data) {
                                    console.info(data);
                                    location.reload();
                                });
                            }
                        },
                        danger: {
                            label: "Нет",
                            className: "btn btn-default",
                            callback: function () {
                                location.reload();
                            }
                        }
                    }
                });
            }

            function editRule(id) {
                $.post("/getRuleParameter", {id: id}, function (data) {
                    $.each(data, function (i, val) {
                        console.info(data[i].comparisonOperator);
                        $('#inputComparisonOperator').val(data[i].comparisonOperator);
                        $('#inputIpAddress').val(data[i].ip_address);
                        $('#inputOperatorDescription').val(data[i].operatorDescription);
                        $('#inputParameter').val(data[i].parameter);
                        $('#inputValue').val(data[i].value);
                        $('#id').val(data[i].id);

                    });
                    $("#saveButton").attr("onclick", "saveEditedRule(); return false;");
                    $('#myModalRule').modal('show');

                }, 'json');
            }

            function saveEditedRule() {
                var inputComparisonOperator = $('#inputComparisonOperator').val();
                var inputIpAddress = $('#inputIpAddress').val();
                var inputOperatorDescription = $('#inputOperatorDescription').val();
                var inputParameter = $('#inputParameter').val();
                var inputValue = $('#inputValue').val();
                var id = $('#id').val();
                $.post("/updateRule", {id: id, inputComparisonOperator: inputComparisonOperator, inputIpAddress: inputIpAddress,
                    inputOperatorDescription: inputOperatorDescription, inputParameter: inputParameter, inputValue: inputValue
                }, function (data) {
                    location.reload();
                });
            }


        </script>

        <style>
            td.highlight {
                font-weight: bold;
                color: #FF9E1F;
            }

            #priceTable_filter{
                margin-right: 30px;
            }
            #compareTable_filter{
                margin-right: 30px;
            }

            .autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; min-width: 270px;}
            .autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
            .autocomplete-selected { background: #F0F0F0; }
            .autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
            .autocomplete-group { padding: 2px 5px; }
            .autocomplete-group strong { display: block; border-bottom: 1px solid #000; }

            .tt-dropdown-menu {
                max-height: 250px;
                width: 268px;
                overflow-y: auto;
                background-color: white;
            }

            .navbar-brand{
                font-family: 'Armata', sans-serif;
            }
            #myStatisticModal .modal-dialog
            {
                width: 350px;/* your width */
            }

            .table-condensed{
                font-family: "Roboto";
            }


            div.dataTables_length label {
                float: left;
                text-align: left;
                margin-left: 30px;
            }

            div.dataTables_length select {
                width: 75px;
            }

            div.dataTables_filter label {
                float: right;
            }

            div.dataTables_info {
                padding-top: 8px;
                margin-left: 30px;
            }

            div.dataTables_paginate {
                float: right;
            }

            table.table {
                clear: both;
                margin-bottom: 6px !important;
                max-width: none !important;
            }

            table.table thead .sorting,
            table.table thead .sorting_asc,
            table.table thead .sorting_desc,
            table.table thead .sorting_asc_disabled,
            table.table thead .sorting_desc_disabled {
                cursor: pointer;
                *cursor: hand;
            }

            table.table thead .sorting { background: url('/img/sort_both.png') no-repeat center right; }
            table.table thead .sorting_asc { background: url('/img/sort_asc.png') no-repeat center right; }
            table.table thead .sorting_desc { background: url('/img/sort_desc.png') no-repeat center right; }

            table.table thead .sorting_asc_disabled { background: url('/img/sort_asc_disabled.png') no-repeat center right; }
            table.table thead .sorting_desc_disabled { background: url('/img/sort_desc_disabled.png') no-repeat center right; }

            table.dataTable th:active {
                outline: none;
            }

            /* Scrolling */
            div.dataTables_scrollHead table {
                margin-bottom: 0 !important;
                border-bottom-left-radius: 0;
                border-bottom-right-radius: 0;
            }

            div.dataTables_scrollHead table thead tr:last-child th:first-child,
            div.dataTables_scrollHead table thead tr:last-child td:first-child {
                border-bottom-left-radius: 0 !important;
                border-bottom-right-radius: 0 !important;
            }

            div.dataTables_scrollBody table {
                border-top: none;
                margin-bottom: 0 !important;
            }

            div.dataTables_scrollBody tbody tr:first-child th,
            div.dataTables_scrollBody tbody tr:first-child td {
                border-top: none;
            }

            div.dataTables_scrollFoot table {
                border-top: none;
            }
            #tableData{
                font-size: 12px;
                font-family: 'Roboto', sans-serif;
            }

            #tableData_list_info, #tableData_list_paginate {
                margin-top: 10px;
                font-size: 12px;
            }
            .dataTables_length_wrapper select {
                width: auto !important;
                font-size: 12px;
                margin-top:10px;
            }
            .dataTables_length label{
                font-size: 12px;
            }
            .dataTables_filter input{
                width: 120px;
                font-size: 12px;
            }
            .dataTables_filter label{
                font-size: 12px;
            }
            .dataTables_paginate{
                margin-top: 10px;
                font-size: 12px;
            }
            .dataTables_info{
                margin-top: 10px;
                font-size: 12px;
            }

            /*
             * TableTools styles
             */
            .table tbody tr.active td,
            .table tbody tr.active th {
                background-color: #08C;
                color: white;
            }

            .table tbody tr.active:hover td,
            .table tbody tr.active:hover th {
                background-color: #0075b0 !important;
            }

            .table-striped tbody tr.active:nth-child(odd) td,
            .table-striped tbody tr.active:nth-child(odd) th {
                background-color: #017ebc;
            }

            table.DTTT_selectable tbody tr {
                cursor: pointer;
                *cursor: hand;
            }

            div.DTTT .btn {
                color: #333 !important;
                font-size: 12px;
            }

            div.DTTT .btn:hover {
                text-decoration: none !important;
            }


            ul.DTTT_dropdown.dropdown-menu a {
                color: #333 !important; /* needed only when demo_page.css is included */
            }

            ul.DTTT_dropdown.dropdown-menu li:hover a {
                background-color: #0088cc;
                color: white !important;
            }

            /* TableTools information display */
            div.DTTT_print_info.modal {
                height: 150px;
                margin-top: -75px;
                text-align: center;
            }

            div.DTTT_print_info h6 {
                font-weight: normal;
                font-size: 28px;
                line-height: 28px;
                margin: 1em;
            }

            div.DTTT_print_info p {
                font-size: 14px;
                line-height: 20px;
            }



            /*
             * FixedColumns styles
             */
            div.DTFC_LeftHeadWrapper table,
            div.DTFC_LeftFootWrapper table,
            table.DTFC_Cloned tr.even {
                background-color: white;
            }

            div.DTFC_LeftHeadWrapper table {
                margin-bottom: 0 !important;
                border-top-right-radius: 0 !important;
                border-bottom-left-radius: 0 !important;
                border-bottom-right-radius: 0 !important;
            }

            div.DTFC_LeftHeadWrapper table thead tr:last-child th:first-child,
            div.DTFC_LeftHeadWrapper table thead tr:last-child td:first-child {
                border-bottom-left-radius: 0 !important;
                border-bottom-right-radius: 0 !important;
            }

            div.DTFC_LeftBodyWrapper table {
                border-top: none;
                margin-bottom: 0 !important;
            }

            div.DTFC_LeftBodyWrapper tbody tr:first-child th,
            div.DTFC_LeftBodyWrapper tbody tr:first-child td {
                border-top: none;
            }

            div.DTFC_LeftFootWrapper table {
                border-top: none;
            }
            table.display tr.even.row_selected td {
                background-color: #B0BED9;
            }

            table.display tr.odd.row_selected td {
                background-color: #9FAFD1;
            }
            .nav-pills > li > a{
                font-size: 11px;
            }

            .nav-pills > li > a span{
                font-size: 11px;
            }
            /* Set the fixed height of the footer here */

            #footer {
                height: 44px;
                padding-top: 2px;
            }

            #myModalRule .modal-dialog  {
                width: 300px;
            }

            #myFindWindow .modal-dialog{
                width: 300px;
            }

            .bootbox .modal-dialog  {
                width: 350px;
            }

            .bootstrap-switch-container{
                height: 34px;
            }
        </style>
    </head>
    <body>

