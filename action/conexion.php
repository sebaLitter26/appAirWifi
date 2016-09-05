<?php 
function conexion(){
    
	$mysqli = new mysqli("http://192.168.100.155:82", "root", "", "aire_acondicionado");
	//$mysqli = new mysqli("localhost", "dmax_stage", "dmax_stage", "aire_acondicionado");
    /* check connection */
    if ($mysqli->connect_error) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
    return $mysqli;
}
?>