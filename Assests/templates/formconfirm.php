
<?php
/*
Template Name: Booking-confirm
*/
    $Time = $_GET['time'];
    $Email = $_GET["email"];
    $Timestamp = $_GET['timestamp'];
    $Treatment = $_GET["treatment"];///can make a treatment object and have set time for treatments to have different times
    $eventType = 1;
    $Treatmenttime = 60*60;//currently hard coded to make times one hour but can make differnt for different treatments
    $newDateString = date('Y-m-d H:i:s',strtotime($Time));
    $newDateString2 = date("Y-m-d H:i:s", strtotime($Time)+ $Treatmenttime);
    $start_ts = strtotime($newDateString);
    $end_ts = strtotime($newDateString2);
    
    
    echo '<p>'. $Email.
    '<br>&treatment='. $Treatment. 
    '<br>&time='. $Time.
    '<br>but format needed ' . $newDateString.
    '<br> till '. $newDateString2.' </p><br><br>';
    
    
     $connect = new PDO('mysql:host=localhost;dbname=portfolio', 'root', '');
    

       $query = "INSERT INTO bookings ( startevent, endevent, start_ts, end_ts, eventType, treatmentType, email)
        VALUES ( :startevent, :endevent, :start_ts, :end_ts, :eventType, :treatmentType, :email)";
        $statement = $connect->prepare($query);
        $statement->execute(
            array(
                ':startevent' => $newDateString,
                ':endevent' => $newDateString2,
                ':start_ts' => $start_ts,
                ':end_ts' => $end_ts,
                ':eventType' => $eventType,
				':treatmentType' => $Treatment,
				':email' => $Email,
            )
            );

       // echo $query;
        $to = $Email;
        
        $subject = 'Booking Confirmation';
        
        $headers = "From:  no-reply@aneurinj.com \r\n";
        
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $message = '<p>'. $Time.'</br> and treatment ' . $Treatment.'</p>';
        mail($to, $subject, $message, $headers);
?>