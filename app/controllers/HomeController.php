<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

use Elasticsearch\Client as ES;

class HomeController extends BaseController {

    public function index() {

        return View::make('home');
    }

    public function compareTariffs() {
        $tariffs = DB::table('tariffs')->get();

        return View::make('compareTariffs')->with(array(
                    'tariffsList' => $tariffs
        ));
    }

    function comparePrice() {

        $ids = Input::all();

        $comma_separated = implode(",", $ids['ids']);
        $count_elements = count($ids['ids']);

        $rows = "";
        

        $query2 = DB::select(DB::raw("SELECT * from tariffs where id IN (" . $comma_separated . ")"));


        $rows .= "<table id='compareTable' class='table table-striped table-bordered table-condensed'>";
        $rows .= "<thead><tr>";
        $rows .= "<th>#</th><th>Код</th><th>Регион</th>";
        
        $n = 1;
        $k=1;
        
        foreach ($query2 as $column) {
            $rows .= "<th id=" . "column_" . $column->id . ">" . $column->description . "</th>";
        }
        $rows .= "</tr></thead>";

        $rows .= "<tbody>";


        
        foreach (DB::table('PhoneCode')->get() as $row) {
            $rows .= "<tr>";
            $rows .= "<td>" . $n++ . "</td>";
            $rows .= "<td>" . $row->PhoneCode . "</td>";
            $rows .= "<td>" . $row->Region . "</td>";
            
            foreach ($query2 as $value) {
                $rows .= "<td id=" . $value->id . "_" . $row->PhoneCode . "></td>";
            }

            $query = DB::select(DB::raw("SELECT tariffs.id as id, prices.tariffs_id as tariffs_id, price FROM prices"
                                    . " join tariffs on tariffs.id = prices.tariffs_id"
                                    . " WHERE " . $row->PhoneCode . " LIKE CONCAT(  `code` ,  '%' ) AND  `tariffs_id` IN (" . $comma_separated . ") GROUP BY prices.tariffs_id ORDER BY code DESC LIMIT " . $count_elements));
            
            
            foreach ($query as $res) {
                $dataContainer = array();
                $dataContainer['id'] = $res->id;
                $dataContainer['PhoneCode'] = $row->PhoneCode;
                $dataContainer['price'] = $res->price;
                $dataContainer['selector'] = $res->id."_".$row->PhoneCode;
                $array_container[$k++] = $dataContainer;
            }
        }
        $rows .= "</tr>";
        $rows .= "</tbody></table>";
        $glob_array['dataset'] = $array_container;
        $glob_array['table'] = $rows;
        echo json_encode($glob_array);
    }

    public function getPricesList($id) {

        $prices = DB::table('prices')
                        ->join('tariffs', 'tariffs.id', '=', 'prices.tariffs_id')
                        ->where('tariffs_id', array($id))->get();

        return View::make('viewTariff')->with(array(
                    'prices' => $prices
        ));
    }

    public function viewComparedPrices() {

        return View::make('viewComparedPrices');
    }

    public function getOperatorsList() {
        $operators = DB::table('operators')->select('operator as value', 'ip_address as data')->get();
        return $operators;
    }

    function addNewTariffs($description, $ip_address) {

        $theid = DB::table('tariffs')->insertGetId(
                array('description' => $description, 'ip_address' => $ip_address)
        );

        return $theid;
    }

    public function fileUpload() {

        $inputs = Input::all();
        $description = $inputs['description'];
        $ip_address = $inputs['ip_address'];

        //properties of the uploaded file
        $name = $_FILES["csv_file"]["name"];
        //$type = $_FILES["csv_file"]["type"];
        //$size = $_FILES["csv_file"]["size"];
        $temp = $_FILES["csv_file"]["tmp_name"];
        $error = $_FILES["csv_file"]["error"];

        if ($error > 0) {
            die("Error uploading file! code $error.");
        } else {
            move_uploaded_file($temp, public_path() . "/upload/csv/" . $name);

            $id_tariff = $this->addNewTariffs($description, $ip_address);
            $output = shell_exec('cat ' . public_path() . '/upload/csv/' . $name . '|iconv -f WINDOWS-1251 -t UTF-8');

            $pieces = explode("\n", $output);

            foreach ($pieces as $row) {
                try {
                    $parts = explode(";", $row);

                    DB::table('prices')->insert(
                            array(
                                'code' => $parts[0],
                                'price' => str_replace(",", ".", $parts[2]),
                                'operator' => $parts[1],
                                'tariffs_id' => $id_tariff,
                    ));
                } catch (ErrorException $e) {
                    
                }
            }
        }
        echo json_encode("ok");
    }

    public function statisticsOnOperators() {
        //$logs = Logs::getAll();
        $rules = DB::table('operator_rules')->get();


        return View::make('operators')->with(array(
                    'rules' => $rules
        ));
    }

    public function directions() {
        //$logs = Logs::getAll();
        $rules = DB::table('operator_rules')->get();


        return View::make('directionsAndOperators')->with(array(
                    'rules' => $rules
        ));
    }

    public function saveRule() {
        $data = Input::all();
        print_r($data);

        DB::table('operator_rules')->insert(
                array('operatorDescription' => $data['inputOperatorDescription'],
                    'ip_address' => $data['inputIpAddress'],
                    'parameter' => $data['inputParameter'],
                    'comparisonOperator' => $data['inputComparisonOperator'],
                    'value' => $data['inputValue'],
                )
        );
    }

    public function sendQueryData() {

        $data = Input::all();


        if ($data["select_dst_number_out"] === 'anywhere') {
            $dst_number_out = '%' . $data["dst_number_out"] . '%';
        } elseif ($data["select_dst_number_out"] === 'beginning') {
            $dst_number_out = $data["dst_number_out"] . '%';
        } elseif ($data["select_dst_number_out"] === 'ending') {
            $dst_number_out = '%' . $data["dst_number_out"];
        }

        if ($data["select_host"] === 'anywhere') {
            $host = '%' . $data["host"] . '%';
        } elseif ($data["select_host"] === 'beginning') {
            $host = $data["host"] . '%';
        } elseif ($data["select_host"] === 'ending') {
            $host = '%' . $data["host"];
        }

        if ($data["select_destination_ip"] === 'anywhere') {
            $destination_ip = '%' . $data["destination_ip"] . '%';
        } elseif ($data["select_destination_ip"] === 'beginning') {
            $destination_ip = $data["destination_ip"] . '%';
        } elseif ($data["select_destination_ip"] === 'ending') {
            $destination_ip = '%' . $data["destination_ip"];
        }

        if ($data["select_source_ip"] === 'anywhere') {
            $source_ip = '%' . $data["source_ip"] . '%';
        } elseif ($data["select_source_ip"] === 'beginning') {
            $source_ip = $data["source_ip"] . '%';
        } elseif ($data["select_source_ip"] === 'ending') {
            $source_ip = '%' . $data["source_ip"];
        }

        if ($data["select_q931"] === 'anywhere') {
            $q931 = '%' . $data["q931"] . '%';
        } elseif ($data["select_q931"] === 'beginning') {
            $q931 = $data["source_ip"] . '%';
        } elseif ($data["select_q931"] === 'ending') {
            $q931 = '%' . $data["select_q931"];
        }

        if ($data["select_elapsed_time"] === 'anywhere') {
            $elapsed_time = '%' . $data["elapsed_time"] . '%';
        } elseif ($data["select_elapsed_time"] === 'beginning') {
            $elapsed_time = $data["elapsed_time"] . '%';
        } elseif ($data["select_elapsed_time"] === 'ending') {
            $elapsed_time = '%' . $data["elapsed_time"];
        }


        if ($data["select_src_number_bill"] === 'anywhere') {
            $src_number_bill = '%' . $data["src_number_bill"] . '%';
        } elseif ($data["select_src_number_bill"] === 'beginning') {
            $src_number_bill = $data["src_number_bill"] . '%';
        } elseif ($data["select_src_number_bill"] === 'ending') {
            $src_number_bill = '%' . $data["src_number_bill"];
        }

        if ($data["select_dst_number_bill"] === 'anywhere') {
            $dst_number_bill = '%' . $data["dst_number_bill"] . '%';
        } elseif ($data["select_dst_number_bill"] === 'beginning') {
            $dst_number_bill = $data["dst_number_bill"] . '%';
        } elseif ($data["select_dst_number_bill"] === 'ending') {
            $dst_number_bill = '%' . $data["dst_number_bill"];
        }

        if ($data["select_dst_codec"] === 'anywhere') {
            $dst_codec = '%' . $data["dst_codec"] . '%';
        } elseif ($data["select_dst_codec"] === 'beginning') {
            $dst_codec = $data["dst_codec"] . '%';
        } elseif ($data["select_dst_codec"] === 'ending') {
            $dst_codec = '%' . $data["dst_codec"];
        }

        if ($data["select_region"] === 'anywhere') {
            $region = '%' . $data["region"] . '%';
        } elseif ($data["select_region"] === 'beginning') {
            $region = $data["region"] . '%';
        } elseif ($data["select_region"] === 'ending') {
            $region = '%' . $data["region"];
        }

        $response = DB::table('Logs');
        $response->select('id as id', 'HOST as host', 'SRC-IP as src_ip', 'DST-IP as dst_ip', 'DISCONNECT-CODE-Q931 as disconnect_code_q931', 'DISCONNECT-CODE-LOCAL as disconnect_code_local', 'ELAPSED-TIME as elapsed_time', 'SRC-NUMBER-BILL as src_number_bill', 'DST-NUMBER-OUT as dst_number_out', 'DST-CODEC as dst_codec', 'SETUP_TIME_TIME as setup_time_time', 'SETUP_TIME_DATE as setup_time_date', 'PhoneCode as PhoneCode', 'Region as Region', 'DST-NUMBER-BILL as dst_number_bill');
        if (strlen($dst_number_out) > 2 || strlen($data['dst_number_out']) > 0) {
            $response->where('DST-NUMBER-OUT', 'LIKE', $dst_number_out);
        }
        if (strlen($host) > 2 || strlen($data['host']) > 0) {
            $response->where('HOST', 'LIKE', $host);
        }
        if (strlen($destination_ip) > 2 || strlen($data['destination_ip']) > 0) {
            $response->where('DST-IP', 'LIKE', $destination_ip);
        }
        if (strlen($source_ip) > 2 || strlen($data['source_ip']) > 0) {
            $response->where('SRC-IP', 'LIKE', $source_ip);
        }
        if (strlen($q931) > 2 || strlen($data['q931']) > 0) {
            $response->where('DISCONNECT-CODE-Q931', 'LIKE', $q931);
        }
        if (strlen($elapsed_time) > 2 || strlen($data['elapsed_time']) > 0) {
            $response->where('ELAPSED-TIME', 'LIKE', $elapsed_time);
        }
        if (strlen($src_number_bill) > 2 || strlen($data['src_number_bill']) > 0) {
            $response->where('SRC-NUMBER-BILL', 'LIKE', $src_number_bill);
        }
        if (strlen($dst_number_bill) > 2 || strlen($data['dst_number_bill']) > 0) {
            $response->where('DST-NUMBER-BILL', 'LIKE', $dst_number_bill);
        }
        if (strlen($dst_codec) > 2 || strlen($data['dst_codec']) > 0) {
            $response->where('DST-CODEC', 'LIKE', $dst_codec);
        }
        if (strlen($region) > 2 || strlen($data['region']) > 0) {
            $response->where('Region', 'LIKE', $region);
        }
        $response->whereBetween('TIMESTAMP', array(date("Y-m-d H:i:s", strtotime($data["start_date"])), date("Y-m-d H:i:s", strtotime($data["end_date"]))));
        $rows = $response->get();

        $resultData = array();

        foreach ($rows as $value) {

            $logsData = new HomeController();
            $logsData->host = $value->host;
            $logsData->src_ip = $value->src_ip;
            $logsData->dst_ip = $value->dst_ip;
            $logsData->disconnect_code_q931 = $value->disconnect_code_q931;
            $logsData->disconnect_code_local = $value->disconnect_code_local;
            $logsData->elapsed_time = $value->elapsed_time;
            $logsData->src_number_bill = $value->src_number_bill;
            $logsData->dst_number_out = $value->dst_number_out;
            $logsData->dst_codec = $value->dst_codec;
            $logsData->setup_time_time = $value->setup_time_time;
            $logsData->setup_time_date = $value->setup_time_date;
            $logsData->phone_code = $value->PhoneCode;
            $logsData->region = $value->Region;
            $logsData->dst_number_bill = $value->dst_number_bill;


            $resultData[$value->id] = $logsData;
        }
        return $resultData;
    }

    function getStatisticInfo() {
        $total = DB::table('Logs')->count('*');
        $epoch_max_date = DB::table('Logs')->max('timestamp');
        $epoch_min_date = DB::table('Logs')->min('timestamp');


        $packetData['records_counter'] = $total;
        $packetData['actual_db_date'] = date('d.m.Y H:i:s', strtotime($epoch_max_date));
        $packetData['range_date'] = date('d.m.Y H:i:s', strtotime($epoch_min_date)) . " - " . date('d.m.Y H:i:s', strtotime($epoch_max_date));

        echo json_encode($packetData);
    }

    function findDireactionsData() {
        $data = Input::all();

        $response = DB::table('Logs');
        $response->select('Logs.id', 'Region', 'operator', DB::raw('COUNT( * ) AS call_count'), DB::raw('ROUND( ( SUM(  `ELAPSED-TIME` ) /60 ) , 2 ) AS minutes'), DB::raw('ROUND( SUM(  `ELAPSED-TIME` ) , 2 ) AS seconds'), DB::raw('SUM( CASE WHEN  `ELAPSED-TIME` >0 THEN 1  ELSE 0 END )  AS eltime_counter'));
        $response->join('operators', 'operators.ip_address', '=', DB::raw('SUBSTRING_INDEX(  `Logs`.`DST-IP` ,  ":" , 1 )'));
        $response->whereBetween('TIMESTAMP', array(date("Y-m-d H:i:s", strtotime($data["start_date"])), date("Y-m-d H:i:s", strtotime($data["end_date"]))));
        if (strlen($data["operator"]) > 2) {
            $response->where('operators.operator', 'LIKE', '%' . $data["operator"] . '%');
        }
        if (strlen($data["direction"]) > 2) {
            $response->where('Region', 'LIKE', '%' . $data["direction"] . '%');
        }
        $response->groupBy('Region');
        $response->groupBy('operator');
        $response->having('minutes', '>', 0);
        $rows = $response->get();

        #print_r($response);

        $resultData = array();
        $n = 1;
        $eltime_counter = 1;
        foreach ($rows as $value) {

            $logsData = new HomeController();


            $logsData->region = $value->Region;
            $logsData->operator = $value->operator;
            $logsData->call_count = $value->call_count;
            $logsData->minutes = ceil($value->minutes);
            $logsData->seconds = $value->seconds;
            $logsData->eltime_counter = $value->eltime_counter;
            $logsData->asr = round((($value->eltime_counter / $value->call_count) * 100), 2);

            if ($value->eltime_counter > 0) {

                $logsData->acd = round(($value->seconds / $value->eltime_counter), 2);
            } else {
                $logsData->acd = 0;
            }



            $resultData[$n++] = $logsData;
        }
        return $resultData;
    }

    function deleteRule() {
        $data = Input::all();

        $id = $data['id'];
        DB::table('operator_rules')->delete($id);
    }

    function deleteTariff() {
        $data = Input::all();

        $id = $data['id'];
        DB::table('prices')->where('tariffs_id', $id)->delete();
        DB::table('tariffs')->delete($id);
    }

    function getRuleParameter() {
        $data = Input::all();

        $id = $data['id'];

        $ruleParameter = DB::select('select * from operator_rules where id = ?', array($id));

        echo json_encode($ruleParameter);
    }

    function updateRule() {
        $data = Input::all();

        $id = $data['id'];
        $inputComparisonOperator = $data['inputComparisonOperator'];
        $inputIpAddress = $data['inputIpAddress'];
        $inputOperatorDescription = $data['inputOperatorDescription'];
        $inputParameter = $data['inputParameter'];
        $inputValue = $data['inputValue'];

        DB::table('operator_rules')
                ->where('id', '=', $id)
                ->update(array(
                    'operatorDescription' => $inputOperatorDescription,
                    'ip_address' => $inputIpAddress,
                    'parameter' => $inputParameter,
                    'comparisonOperator' => $inputComparisonOperator,
                    'value' => $inputValue
        )); // Update column ‘price’ with a new value
    }

}
