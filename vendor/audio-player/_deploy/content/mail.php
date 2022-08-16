<?php

	 if(!isset($_POST['mail']) || !isset($_POST['name']) || !isset($_POST['path']))exit();

	$to = $_POST['mail'];
	$subject = 'Message from HTML5 Audio Player';
	$name = $_POST['name'];
	$path = str_replace(' ', '%20', $_POST['path']);
	$message = 'Download link for the requested song named "'. $name .'" has been generated: '. $path;
	$header = "From: do-not-reply@example.com\r\n"; 
	$header.= "MIME-Version: 1.0\r\n"; 
	$header.= "Content-Type: text/plain; charset=utf-8\r\n"; 
	$header.= "X-Priority: 1\r\n"; 
	
	if(!mail($to, $subject, $message, $header)){
		die('Error sending email!');
	}else{
		die('Email sent!');
	}
?>