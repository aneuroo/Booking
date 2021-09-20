
<?php
/*
Template Name: Booking-rest
*/
$connect = new PDO('mysql:host=localhost;dbname=portfolio', 'root', '');
$data = array();
$query = "SELECT * FROM bookings WHERE eventType=1 ORDER BY id";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();

foreach($result as $row)
{
 $data[] = array(
  'id'   => $row["id"],
  'start'   => $row["startevent"],
  'end'   => $row["endevent"]
 );
}

echo json_encode($data);