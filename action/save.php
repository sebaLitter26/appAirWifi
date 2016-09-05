<?php 
include_once 'conexion.php';
error_reporting(0); 
function saveData(){

	
	$temperatura = trim($_POST['temperatura']);
	$modo = trim($_POST['modo']);
	$velocidad = trim($_POST['velocidad']);
    $duracion = trim($_POST['duracion']);
	$programa = trim($_POST['programa_group']);

	/*
        $concesionario=trim($_POST['concesionario']);   
        $modelo=trim($_POST['modelo']);
		$accesorios=implode(', ',$_POST['accesorios_group']);
	
	
	if($lastname=='' || !isName($lastname)){
            $error['error'][]='lastname';
	}
	if($telefono=='' || !isTelefono($telefono)){
        $error['error'][]='telefono';
	}
	if($email=='' || !isEmail($email)){ // || verify($email,$con)   
        $error['error'][]='email';
	}
	
    if($duracion>0){
	    $sql="select valor from duracion where valor = ".$valor;
		$result=  mysqli_query($con,$sql);
		while($row= mysqli_fetch_object($result)){
			$duracion = $row->valor;
		}
    }else{
		$error['error'][]='duracion';
	}
        
	if($temperatura>0){
		$sql="select valor from temperatura where id = ".$temperatura;
		$result=  mysqli_query($con,$sql);
		while($row= mysqli_fetch_object($result)){
			$temperatura = $row->valor;
		}
	}else{
		$error['error'][]='temperatura';
	}
	*/
	$error=array();
	
	if($modo== '0' || !isName($modo)){
        $error['error'][]='modo';
	}
	if($velocidad== '0' || !is_numeric($velocidad)){
        $error['error'][]='velocidad';
	}
	if($duracion== '0' || !is_numeric($duracion)){
        $error['error'][]='duracion';
	}
	if($temperatura == '0' || !is_numeric($temperatura)){
        $error['error'][]='temperatura';
	}
    
	if(empty($programa)){
		$error['error'][]='programa';
	}
    
	
	if(count($error)==0){
		
		if(insertar($temperatura,$modo,$duracion,$velocidad,$programa)){
                    $error['ok'][]="save";
                    $error['error'][]="";
                    //send mail
                    // multiple recipients
                    //$to  = 'supervisorcallgm@road-track.com.ec' . ', '; // note the comma
                    //$to .= 'supervisorcallgm@road-track.com'.',';
                    $to .= 'sebastian_litter@hotmail.com';

                    // subject
                    $subject='El aire acondicionado se modifico';

                    // message
                    $message = '
                        Aire Acondicionado<br>
                        <strong>Temperatura</strong> : '.$temperatura.' <br>
                        <strong>Modo</strong> : '.$modo.' <br>
                        <strong>Velocidad</strong> : '.$velocidad.' <br>
                        <strong>Duración</strong> : '.$duracion.' <br>
                        <strong>Programa</strong> : '.$programa.' <br>
                        <strong>Concesionario</strong> : '.$concesionario.' <br><br>
                    ';

                    $headers  = 'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                    // Additional headers
                    //$headers .= 'To: Pepe <mary@zoedev.com>, Pepe2 <kelly@zoedev.com>' . "\r\n";
                    //$headers .= 'From: '.$name.' '.$lastname.' <'.$email.'>' . "\r\n";                    
                    // Mail it
                    mail($to, $subject, $message, $headers);

		}else{
                    $error['error'][]='Error al registrar';
                    $error['ok'][]='';
		}
	}else{
		$error['ok'][]='';
	}
	//sleep(5);
	echo json_encode($error);	
}

function verify($email,$con){
	$sql="select email from usuarios where email='".$email."' limit 1";
	if($res=mysqli_query($con,$sql)){
            $cant = mysqli_num_rows($res);
            if($cant==0)
                return false;
            return true;
	}
	return true;
}

function isEmail($email)
{
	return preg_match('/^[a-z0-9!#$%&\'*+\/=?^`{}|~_-]+[.a-z0-9!#$%&\'*+\/=?^`{}|~_-]*@[a-z0-9]+[._a-z0-9-]*\.[a-z0-9]+$/ui', $email);
}
function isName($name)
{
    return preg_match('/^[^0-9!<>,;?=+()@#"�{}_$%:]*$/ui', stripslashes($name));
}
function isTelefono($name)
{
    return preg_match('/^[0-9 -()]{9,10}$/', stripslashes($name));
}
function isNumber($name)
{
    return preg_match('/^[0-9]{10,10}$/', stripslashes($name));
}

function insertar($temperatura,$modo,$duracion,$velocidad,$programa)
{
	$con=conexion();
	//$consulta_exito = false;
	$sql="INSERT INTO registros (temperatura,modo,velocidad,duracion,programa,fecha_fin) 
            values('{$temperatura}','{$modo}','{$velocidad}','{$duracion}','{$programa}',DATE_ADD(NOW(),INTERVAL {$duracion} MINUTE))";
	
	return mysqli_query($con,$sql);
}

if($_POST){
	switch ($_POST['task']) {
            case 'save':
                    saveData();
            break;
			
        }	
}else{
	echo "USER not autorizado";
}


?>