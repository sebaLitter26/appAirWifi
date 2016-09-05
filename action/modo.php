<?php 
include_once 'conexion.php';
error_reporting(0); 

function getModo(){

$con=conexion();
$sql="select * from modo order by id asc";
$result=  mysqli_query($con,$sql);
$combo = '<option value="0">Seleccione Modo</option>';
 while($row= mysqli_fetch_object($result)){
	$combo .= '<option value="'.$row->nombre.'">'.utf8_encode($row->nombre).'</option>';
 }

	echo $combo;
}

getModo();

?>