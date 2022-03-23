<?php

require_once 'Services/AuthService.php';

class AuthController extends AuthService{

	public function post(){
		return self::gerarToken();
	}

	public function get(){
		$inputs = file_get_contents('php://input');
		$data = json_decode($inputs,true);
		
		if(isset($data) && !empty($data)){
			return self::checkPermission($data);
		}
		else{
			return json_encode(array("status"=>400,"message"=>"Autenticação inválida!"));
		}
	}
}

?>