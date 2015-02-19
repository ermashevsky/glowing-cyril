<?php

require '/home/agent/web/mla.lcl/vendor/autoload.php';
require '/home/agent/web/mla.lcl/vendor/PHPMailer/PHPMailerAutoload.php';


date_default_timezone_set('Europe/Kaliningrad');

$servername = "localhost";
$username = "root";
$password = "11235813";
$dbname = "mera_logs";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT operatorDescription, ip_address, parameter, comparisonOperator, value FROM operator_rules";
$result2 = $conn->query($sql);

if ($result2->num_rows > 0) {
    // output data of each row
    while ($row = $result2->fetch_assoc()) {


        $sql3 = "SELECT `DISCONNECT-CODE-Q931` as disconnect_code_q931, `ELAPSED-TIME` as elapsed_time FROM Logs"
                . " where `DST-IP` like '".$row['ip_address']."%'"
                . " AND `timestamp` between '".date("Y-m-d H:i:00", strtotime('-2 hour'))."'"
                . " and '".date("Y-m-d H:i:00", strtotime('-1 hour'))."'";
        $result3 = $conn->query($sql3);
        
        $count_34 = 0;
        $count_16_17_19 = 0;
        $count_03 = 0;
        $elapsed_time_counter_calls = 0;
        $elapsed_time_min_collector = 0;
        $elapsed_time_counter_min = 0;
        $counter_all_call = 0;
        $asr = 0;
        $asr_full = 0;
        $asd = 0;
        

        $row_cnt = $result3->num_rows;
        
        if ($row_cnt > 0) {
        while($followingdata = $result3->fetch_assoc()) {


                if ($followingdata['disconnect_code_q931'] === "34") {
                    $count_34++;
                }
                if ($followingdata['disconnect_code_q931'] === "16" || $followingdata['disconnect_code_q931'] === "17" || $followingdata['disconnect_code_q931'] === "19") {
                    $count_16_17_19++;
                }

                if ($followingdata['disconnect_code_q931'] === "3") {
                    $count_03++;
                }

                if ($followingdata['elapsed_time'] > 0) {
                    $elapsed_time_counter_calls++;
                    $elapsed_time_min_collector += +$followingdata['elapsed_time'];
                }else{
                    
                }

                $counter_all_call++;


            $asr = (($elapsed_time_counter_calls / ($counter_all_call)) * 100);
            $asr_full = (($count_16_17_19 / ($counter_all_call)) * 100);
            $asd = $elapsed_time_min_collector / $elapsed_time_counter_calls;

        }
        }
        echo $asr."\n";
        echo $asr_full."\n";
        echo $asd."\n";
        
        switch ($row['parameter']) {
            case 'asr':
                if ($row['comparisonOperator'] === ">") {
                    if ($asr > $row['value']) {
                        $table = '<table border=1>'
                                . '<tr>'
                                . '<td>Оператор</td><td>' . $row['operatorDescription'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>IP-адрес</td><td>' . $row['ip_address'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>Параметр</td><td>' . $row['parameter'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>Значение</td><td>' . $row['value'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>Оператор сравнения</td><td>' . $row['comparisonOperator'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>Расчетное значение</td><td>' . $asr . '</td>'
                                . '</tr>'
                                . '</table>';
                        sendMailTo($row['operatorDescription'],$table);
                    }
                } else {
                    if ($asr < $row['value']) {
                        $table = '<table border=1>'
                                . '<tr>'
                                . '<td>Оператор</td><td>' . $row['operatorDescription'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>IP-адрес</td><td>' . $row['ip_address'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>Параметр</td><td>' . $row['parameter'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>Значение</td><td>' . $row['value'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>Оператор сравнения</td><td>' . $row['comparisonOperator'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>Расчетное значение</td><td>' . $asr . '</td>'
                                . '</tr>'
                                . '</table>';
                        sendMailTo($row['operatorDescription'],$table);
                    }
                }
                break;
            case 'asd':
                if ($row['comparisonOperator'] === ">") {
                    if ($asd > $row['value']) {
                        $table = '<table border=1>'
                                . '<tr>'
                                . '<td>Оператор</td><td>' . $row['operatorDescription'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>IP-адрес</td><td>' . $row['ip_address'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>Параметр</td><td>' . $row['parameter'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>Значение</td><td>' . $row['value'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>Оператор сравнения</td><td>' . $row['comparisonOperator'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>Расчетное значение</td><td>' . $asd . '</td>'
                                . '</tr>'
                                . '</table>';
                        sendMailTo($row['operatorDescription'],$table);
                    }
                } else {
                    if ($asd < $row['value']) {
                        $table = '<table border=1>'
                                . '<tr>'
                                . '<td>Оператор</td><td>' . $row['operatorDescription'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>IP-адрес</td><td>' . $row['ip_address'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>Параметр</td><td>' . $row['parameter'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>Значение</td><td>' . $row['value'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>Оператор сравнения</td><td>' . $row['comparisonOperator'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>Расчетное значение</td><td>' . $asd . '</td>'
                                . '</tr>'
                                . '</table>';
                        sendMailTo($row['operatorDescription'],$table);
                    }
                }
                break;
            case 'asrFull':
                if ($row['comparisonOperator'] === ">") {
                    if ($asr_full > $row['value']) {
                        $table = '<table border=1>'
                                . '<tr>'
                                . '<td>Оператор</td><td>' . $row['operatorDescription'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>IP-адрес</td><td>' . $row['ip_address'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>Параметр</td><td>' . $row['parameter'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>Значение</td><td>' . $row['value'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>Оператор сравнения</td><td>' . $row['comparisonOperator'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>Расчетное значение</td><td>' . $asr_full . '</td>'
                                . '</tr>'
                                . '</table>';
                        sendMailTo($row['operatorDescription'],$table);
                    }
                } else {
                    if ($asr_full < $row['value']) {
                        $table = '<table border=1>'
                                . '<tr>'
                                . '<td>Оператор</td><td>' . $row['operatorDescription'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>IP-адрес</td><td>' . $row['ip_address'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>Параметр</td><td>' . $row['parameter'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>Значение</td><td>' . $row['value'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>Оператор сравнения</td><td>' . $row['comparisonOperator'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>Расчетное значение</td><td>' . $asr_full . '</td>'
                                . '</tr>'
                                . '</table>';
                        sendMailTo($row['operatorDescription'],$table);
                    }
                }
                break;
            case 'callCounter':
                if ($row['comparisonOperator'] === ">") {
                    if ($counter_all_call > $row['value']) {
                        $table = '<table border=1>'
                                . '<tr>'
                                . '<td>Оператор</td><td>' . $row['operatorDescription'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>IP-адрес</td><td>' . $row['ip_address'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>Параметр</td><td>' . $row['parameter'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>Значение</td><td>' . $row['value'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>Оператор сравнения</td><td>' . $row['comparisonOperator'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>Расчетное значение</td><td>' . $counter_all_call . '</td>'
                                . '</tr>'
                                . '</table>';
                        sendMailTo($row['operatorDescription'],$table);
                    }
                } else {
                    if ($counter_all_call < $row['value']) {
                        $table = '<table border=1>'
                                . '<tr>'
                                . '<td>Оператор</td><td>' . $row['operatorDescription'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>IP-адрес</td><td>' . $row['ip_address'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>Параметр</td><td>' . $row['parameter'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>Значение</td><td>' . $row['value'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>Оператор сравнения</td><td>' . $row['comparisonOperator'] . '</td>'
                                . '</tr>'
                                . '<tr>'
                                . '<td>Расчетное значение</td><td>' . $counter_all_call . '</td>'
                                . '</tr>'
                                . '</table>';
                        sendMailTo($row['operatorDescription'],$table);
                    }
                }
                break;
        }
    }
} else {
    echo "0 results";
}
$conn->close();

function sendMailTo($providers, $table) {
    $mail = new PHPMailer;

    //$mail->SMTPDebug = 3;                               // Enable verbose debug output

    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.dialog64.ru';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'ermashevsky@dialog64.ru';                 // SMTP username
    $mail->Password = 'kk6k29';                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 25;                                    // TCP port to connect to

    $mail->From = 'ermashevsky@dialog64.ru';
    $mail->FromName = 'Ермашевский Денис Сергеевич';
    $mail->addAddress('tech.staff@dialog64.ru', 'Тех.Отдел');     // Add a recipient
    $mail->addCC('ermashevsky@dialog64.ru');

    $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
    // Optional name
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->CharSet = 'UTF-8';
    $mail->Subject = '(MeraLogAnalyzer) Уведомление о состоянии провайдеров';
    $mail->Body = 'Добрый день, Кирилл Александрович!<br/><br/>Вы получили данное уведомление, т.к. в период с ' . date("Y-m-d H:i:00", strtotime('-2 hour')) . " по " . date("Y-m-d H:i:00",strtotime('-1 hour')) . '<br/>
        проблема с '.$providers.' шлюзом.<br/><br/>'
        . $table;


    if (!$mail->send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo 'Message has been sent'."\n";
    }
}
