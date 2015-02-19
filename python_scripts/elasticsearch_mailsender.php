<?php
require '/home/agent/web/mla.lcl/vendor/autoload.php';
require '/home/agent/web/mla.lcl/vendor/PHPMailer/PHPMailerAutoload.php';

use Elasticsearch\Client as ES;
date_default_timezone_set('Europe/Kaliningrad');

$es = new ES();

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

$sql = "SELECT zone, zone_parameter FROM phones_zone";
$result2 = $conn->query($sql);

if ($result2->num_rows > 0) {
    // output data of each row
    while($row = $result2->fetch_assoc()) {
        
        $sql3 = "SELECT count(`DST-NUMBER-BILL`) as counter FROM Logs where `DST-NUMBER-BILL` like '".$row['zone']."%' AND `timestamp` between '".date("Y-m-d H:i:00", strtotime('-2 hour'))."' and '".date("Y-m-d H:i:00", strtotime('-1 hour'))."'";
        $result3 = $conn->query($sql3);
        print $sql3;
        while($followingdata = $result3->fetch_assoc()) {
            
    
 
    if($followingdata['counter']>$row['zone_parameter']):
        echo $followingdata['counter']."\n";
    
        echo "Период: ".date("Y-m-d H:i:00", strtotime('-2 hour'))." - ".date("Y-m-d H:i:00", strtotime('-1 hour'))." Зона: ".$row['zone'] ." Количество: ".$followingdata['counter']."\n";
        
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
        $mail->Subject = '(MeraLogAnalyzer) Уведомление о превышении показателей';
        $mail->Body    = 'Добрый день, Кирилл Александрович!<br/><br/>Вы получили данное уведомление, т.к. в период с '.date("Y-m-d H:i:00", strtotime('-2 hour'))." по ".date("Y-m-d H:i:00", strtotime('-1 hour')).' был превышен коэффициент '.$row['zone_parameter'].' по зоне '.$row['zone'].'.<br/>
        Количество звонков по направлению - '.$followingdata['counter'];
        

        if(!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message has been sent';
        }


    endif;
    }
    }
} else {
    echo "0 results";
}
$conn->close();