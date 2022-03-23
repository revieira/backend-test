<?php

require_once 'Models/Users.php';

class UsersService extends Users{

	public function setSelect($id){
		$resp = self::showSelect($id);

		if($resp === false){
			return array("status"=>404,"data"=>"USUÁRIO NÃO FOI ENCONTRADO EM NOSSA BASE DE DADOS");
		}
		else{
			return $resp;
		}
	}
    
    public function setSelectAll(){
		$resp = self::showSelectAll();
		
		if($resp === false){
			return array("status"=>404,"message"=>"NENHUM DADO ENCONTRADO");
		}
		else{
			return $resp;
		}
	}

	public function setInsert($arr){
		if((isset($arr["owner"]) && !empty($arr["owner"])) && (isset($arr["email"]) && !empty($arr["email"])))
		{
			return self::insertOwner($arr);
		}
		else{
			return array("status"=>400,"message"=>"Dados inválidos");
		}
	}

	public function setUpdate($id, $new_amount, $next_birthday_date){
		return self::showUpdate($id, $new_amount, $next_birthday_date);
	}

	public function setDelete($id, $new_amount, $status){
		return self::showDelete($id, $new_amount, $status);
	}
}

?>