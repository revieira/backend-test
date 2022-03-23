<?php
	date_default_timezone_set('America/Sao_Paulo');
	
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Headers: Authorization,authorization,Content-Type,content-type");
	header("Content-Type: application/json; charset=UTF-8");


	require_once("Services/RouterService.php");

	if (isset($_GET['route']) && !empty($_GET['route'])) {
		$rest = new RouterService($_GET['route']); 
	}
	else{
		//mandar para uma página de index ou de erro
		echo json_encode(array("status"=>404,"message"=>"NENHUM DADO ENCONTRADO"));
	}

?>