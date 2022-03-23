<?php

require_once 'Services/UsersService.php';

class UsersController extends UsersService{

	public function get($id = null){
		
		$resp=array("status"=>400,"message"=>"Não encontrado");
		if(!empty($id)){
			if(is_numeric($id[0])){
				$resp = self::setSelect($id[0]);
			}
			else if(empty($id[0])){
				$resp = self::setSelectAll();
			}
		}
		return json_encode($resp);
	}

	public function post(){
		$inputs = file_get_contents('php://input');
		$data = json_decode($inputs,true);
		
		if(isset($data) && !empty($data)){
			$resp = self::setInsert($data);
			
			return json_encode($resp);
		}
		else{
			return json_encode(array("status"=>400,"message"=>"Operação inválida"));
		}
	}

	public function put(){
		
	}

	public function delete(){
		
	}
}

?>