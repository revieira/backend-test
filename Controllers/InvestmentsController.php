<?php

require_once 'Services/InvestmentsService.php';

class InvestmentsController extends InvestmentsService{

	public function get($id = null){
		
		$resp=array("status"=>404,"message"=>"Não encontrado");
		if(!empty($id)){
			if(is_numeric($id[0])){
				$resp = self::setSelect($id[0]);
			}
			else if(count($id) > 0 && count($id) < 3){
				if($id[0] == "owner"){
					$resp = self::setSelectByOwner($id);
				}
				else if(empty($id[0])){
					$resp = self::setSelectAll();
				}
			}
			else if((count($id) > 2 && count($id) < 5) && (!empty($id[2]) && !empty($id[3])) && (is_numeric($id[2]) && is_numeric($id[3]))){
				$resp = self::setSelectByOwnerByPages($id);
			}
			else if(empty($id[0])){
				$resp = self::setSelectAll();
			}
		}else{
			$resp = self::setSelectAll();
		}
		if($resp === false) return json_encode(array("status"=>404,"message"=>"A APLICAÇÃO NÃO FOI ENCONTRADA EM NOSSA BASE DE DADOS"));
		else return json_encode($resp);
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
		$inputs = file_get_contents('php://input');
		$data = json_decode($inputs,true);
		
		if(isset($data) && !empty($data)){
			$resp = self::setUpdate($data);
			return json_encode($resp);
		}
		else{
			return json_encode(array("status"=>400,"message"=>"Operação inválida"));
		}
	}

	public function delete(){
		$inputs = file_get_contents('php://input');
		$data = json_decode($inputs,true);
		
		if(isset($data) && !empty($data)){
			$resp = self::setDelete($data);
			return json_encode($resp);
		}
		else{
			return json_encode(array("status"=>404,"message"=>"Não encontrado"));
		}
	}
}

?>