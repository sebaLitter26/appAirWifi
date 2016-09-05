<?php 
include_once 'conexion.php';
error_reporting(0); 

function getVelocidad(){

$con=conexion();
$sql="select * from velocidad order by valor asc";
$result=  mysqli_query($con,$sql);
$combo = '<option value="0">Seleccione velocidad</option>';
 while($row= mysqli_fetch_object($result)){
	$combo .= '<option value="'.$row->valor.'">'.utf8_encode($row->nombre).'</option>';
 }

	echo $combo;
}

getVelocidad();

?>