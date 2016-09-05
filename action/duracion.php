<?php 
include_once 'conexion.php';
error_reporting(0); 

function getDuracion(){

$con=conexion();
$sql="select * from duracion order by valor asc";
$result=  mysqli_query($con,$sql);
$combo = '<option value="0">Seleccione Duracion</option>';
 while($row= mysqli_fetch_object($result)){
	$combo .= '<option value="'.$row->valor.'">'.utf8_encode($row->valor).' Minutos</option>';
 }

	echo $combo;
}

getDuracion();

?>