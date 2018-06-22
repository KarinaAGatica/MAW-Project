<?php
	
	$filePath = '';
	$results["msg"] = '';
	$results["success"] = true;
	function handleFile() {
		global $results;
		global $filePath;
		try {
		
			// Undefined | Multiple Files | $_FILES Corruption Attack
			// If this request falls under any of them, treat it invalid.
			if (
				!isset($_FILES['file']['error']) ||
				is_array($_FILES['file']['error'])
			) {
				throw new RuntimeException('Error en la subida de archivo.');
			}

			// Check $_FILES['file']['error'] value.
			switch ($_FILES['file']['error']) {
				case UPLOAD_ERR_OK:
					break;
				case UPLOAD_ERR_NO_FILE:
					return;
				case UPLOAD_ERR_INI_SIZE:
				case UPLOAD_ERR_FORM_SIZE:
					throw new RuntimeException('Tamaño máximo de archivo excedido.');
				default:
					throw new RuntimeException('Error al subir archivo.');
			}

			// You should also check filesize here. 
			if ($_FILES['file']['size'] > 10000000) {
				throw new RuntimeException('Tamaño máximo de archivo excedido.');
			}

			// DO NOT TRUST $_FILES['file']['mime'] VALUE !!
			// Check MIME Type by yourself.
			$finfo = new finfo(FILEINFO_MIME_TYPE);
			if (false === $ext = array_search(
				$finfo->file($_FILES['file']['tmp_name']),
				array(
					'pdf' => 'application/pdf',
					'doc' => 'application/msword',
					'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
				),
				true
			)) {
				throw new RuntimeException('Formato de archivo inválido.');
			}

			// You should name it uniquely.
			// DO NOT USE $_FILES['file']['name'] WITHOUT ANY VALIDATION !!
			// On this example, obtain safe unique name from its binary data.
			$filePath = sprintf('../tmp/%s-%s',
					//sha1_file($_FILES['file']['tmp_name']),
					$_POST['name'],
					$_FILES['file']['name']
				);
				
			if (!move_uploaded_file(
				$_FILES['file']['tmp_name'],
				$filePath
			)) {
				throw new RuntimeException('Error en la subida de archivo.');
			}
			return;

		} catch (RuntimeException $e) {

			$results['success'] = false;
			$results['msg'] = "Error al enviar el mensaje: " . $e->getMessage();
			echo json_encode($results);
			return '';
		}

	}
	error_reporting(E_ALL | E_STRICT);

	
	require 'PHPMailer/PHPMailerAutoload.php';
	$isIE = isset($_POST['isIE']);
	if (!$isIE)
		handleFile();

	if (!$results['success']) return;
	$contactName = $_POST['name'];
	$contactEmail = $_POST['email'];
	$enterprise = $_POST['enterprise'];
	$message = $_POST['message'];
	
	$mailBody = "Ha recibido un nuevo contacto v&iacute;a web!<br/>";
	
	$mailBody = $mailBody . "<br/><b>Nombre: </b>" . $contactName;
	$mailBody = $mailBody . "<br/><b>Email: </b>". $contactEmail;
	$mailBody = $mailBody . "<br/><b>Empresa: </b>". $enterprise;
	$mailBody = $mailBody . "<br/><b>Comentarios:</b><br/>". $message;
	//Create a new PHPMailer instance
	$mail = new PHPMailer;
	$isRRHH = false;
	if ($filePath != '') {
		$isRRHH = true;
		//$mailBody = $mailBody . "<br/><b>Path de archivo:</b><br/>". $filePath;
		$mailBody = $mailBody . "<br/><b>Nombre de archivo:</b><br/>". $_FILES['file']['name'];		
		/*$mail->AddAttachment($filePath,
							 $_FILES['file']['name']); */
	}

	//Tell PHPMailer to use SMTP
	$mail->isSMTP();

	//Enable SMTP debugging
	// 0 = off (for production use)
	// 1 = client messages
	// 2 = client and server messages
	$mail->SMTPDebug = 0;

	//Ask for HTML-friendly debug output
	$mail->Debugoutput = 'html';

	//Set the hostname of the mail server
	$mail->Host = 'mail.evolve-sdf.com';
	

	//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
	$mail->Port = 9025;
	//$mail->Port = 25;

	//Set the encryption system to use - ssl (deprecated) or tls
	$mail->SMTPSecure = '';

	//Whether to use SMTP authentication
	$mail->SMTPAuth = true;

	//Username to use for SMTP authentication - use full email address for gmail
	$mail->Username = "info@evolve-sdf.com";

	//Password to use for SMTP authentication
	$mail->Password = "";

	//Set who the message is to be sent from
	$mail->setFrom('info@evolve-sdf.com', 'Info Evolve');

	//Set an alternative reply-to address
	$mail->addReplyTo($contactEmail, $contactName);

	//Set who the message is to be sent to
	if (!$isRRHH) {
		$mail->addAddress('info@evolve-sdf.com', 'Info Evolve');
		
		$mail->addAddress('ezequiel.barnes@evolve-sdf.com', 'Ezequiel L. Barnes');
	}
	else
		$mail->addAddress('rrhh@evolve-sdf.com', 'RRHH Evolve');
	//Set the subject line
	$mail->Subject = '[EVOLVE] - Notificacion de contacto via Web';

	
	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	$mail->msgHTML("<html><head/><body>".$mailBody."</body></html>");

	//Replace the plain text body with one created manually
	//$mail->AltBody = 'This is a plain-text message body';




	//send the message, check for errors
	
	if (!$mail->send()) {
		$results['success'] = false;
		$results['msg'] = "Error al enviar el mensaje: " . $mail->ErrorInfo;
	} else {
		$results['success'] = true;
		$results['msg'] = "Mensaje enviado exitosamente";
	}
	if ($isIE) {
		
		echo '<script type="text/javascript">';
		echo "alert('" . $results['msg'] . ". Redireccionando a Evolve . ');";
		echo 'window.location.href = \'../\';';
		echo '</script>';
		
		return;
	}
	echo json_encode($results);
?>