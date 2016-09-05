<?php 
include_once 'conexion.php';
error_reporting(0); 

function getTemperatura(){

$con=conexion();
$sql="select * from temperatura order by valor asc";
$result=  mysqli_query($con,$sql);
$combo = '<option value="0">Seleccione temperatura</option>';
 while($row= mysqli_fetch_object($result)){
	$combo .= '<option value="'.$row->valor.'">'.utf8_encode($row->valor).' °C</option>';
 }

	echo $combo;
}

getTemperatura();

?>