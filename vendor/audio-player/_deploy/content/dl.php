<?php 
    header("Content-Type: application/octet-stream");
    if(isset($_GET['name'])){
		$name = $_GET['name'];
		header("Content-Disposition: attachment; filename=\"$name\"");
	}
    if(isset($_GET['path'])){
	   $path = str_replace(' ', '%20', $_GET['path']);
	   readfile($path);
    }
    exit;
?>