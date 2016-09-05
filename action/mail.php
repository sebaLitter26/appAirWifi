<?php

	$to = 'sebastian_litter@hotmail.com'.',';
	//$to .= 'litter.jorge@outlook.com';
	// subject
	$subject='Aviso de alarma en DEPOSITO MORENO';

	// message
	$message = '
		<br>
		<strong>ALARMA ACTIVADA</strong> : <br><br>
	';

	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	// Additional headers
	//$headers .= 'To: Pepe <mary@zoedev.com>, Pepe2 <kelly@zoedev.com>' . "\r\n";
	$headers .= 'From: PIC WIFI \r\n';                    
	
	if(mail($to, $subject, $message, $headers))
	{
		echo "Correo enviado correctamente";
	} else {
		echo "Error al enviar mensaje";
	}
	
	function isEmail($email)
	{
		return preg_match('/^[a-z0-9!#$%&\'*+\/=?^`{}|~_-]+[.a-z0-9!#$%&\'*+\/=?^`{}|~_-]*@[a-z0-9]+[._a-z0-9-]*\.[a-z0-9]+$/ui', $email);
	}


?>