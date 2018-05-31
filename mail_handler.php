<?php
	if(isset($_POST['submit'])){

		$destino = "karii.gatica@gmail.com";
		$nombre = $_POST["name"];
		$correo = $_POST["email"];;
		$mensaje = $_POST["phone"];
		$mensaje = $_POST["message"];
		$contenido = "Nombre: ".$nombre."\nTeléfono: ".$phone."\nCorreo: ".$correo."\nMensaje: ".$mensaje;
		$envio = mail($destino,"Contacto", $contenido);
		if($envio){
			mailheader("Location: sendEmail.html");
		}else{
			echo "Hubo problemas con el envio";
			exit();
		}
	}else{
		echo "No hay datos que procesar";
		exit();
	}
?>
