<?php


	if(isset($_POST['submit'])){

		require 'PHPMailer/PHPMailerAutoload.php';

		$nombre = $_POST["name"];
		$correo = $_POST["email"];;
		$telefono = $_POST["phone"];
		$mensaje = $_POST["address"];
		$mensaje = $_POST["dpto"];
		$mensaje = $_POST["cp"];

	  $contenido = "Ha recibido una nueva compra v&iacute;a web!<br/>";
		$contenido = $contenido . "<br/><b>Nombre: </b>" . $nombre;
		$contenido = $contenido . "<br/><b>Email: </b>". $correo;
		$contenido = $contenido . "<br/><b>Telefono: </b>". $telefono;
		$contenido = $contenido . "<br/><b>Dirección: </b><br/>". $address;
		$contenido = $contenido . "<br/><b>Departamento/Piso: </b><br/>". $dpto;
		$contenido = $contenido . "<br/><b>Código Postal: </b><br/>". $cp;

		error_reporting(E_ALL);
		ini_set('display_errors','On');

		$mail = new PHPMailer;                             // Passing `true` enables exceptions

		try {
				//Server settings
				$mail->SMTPDebug = 0;                                 // Enable verbose debug output
				$mail->isSMTP();                                       // Set mailer to use SMTP
				$mail->Debugoutput = 'html';
				$mail->Host = 'mr.fibercorp.com.ar';  // Specify main and backup SMTP servers
				$mail->SMTPAuth = true;                               // Enable SMTP authentication
				$mail->Username = 'administrador@makeawish.org.ar';                 // SMTP username
				$mail->Password = 'm4k34w1sh';                           // SMTP password
				$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
				$mail->Port = 587;                                    // TCP port to connect to

				//Recipients
				$mail->setFrom('administrador@makeawish.org.ar', 'Mensaje Web MAW');
				$mail->addAddress('makeawish@makeawish.org.ar', 'Compra Web MAW');     // Add a recipient

				//Content
				$mail->isHTML(true);                                  // Set email format to HTML
				$mail->Subject = 'Compra de Evento';
				$mail->Body  = "<html><head/><body>".$contenido."</body></html>";

				$mail->send();

				header("Location: envioEmailCompra.html");

		} catch (Exception $e) {
				header("Location: envioEmailCompraError.html");
		}
	}

?>
