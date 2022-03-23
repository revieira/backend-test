<?php

require_once 'Models/Investments.php';
require_once 'Services/SendEmailService.php';

class InvestmentsService extends Investments{

	public function setSelect($id){
		
		$resp = self::showSelect($id);
		
		if($resp === false){
			return $resp;
		}
		else{
			//faz uma verificação de datas
			$arr_date = self::calcDateTime($resp["create_date"]);

			//calcula a próxima data de aniversário do investimento
			$next_birthday_date = self::getBirthdayDate($arr_date["dtCreated"],$arr_date["meses"]);
			
			$dtnext=strtotime($next_birthday_date);
			$dtt=strtotime("2022-02-14");
			
			$arrmeses=$arr_date["meses"]-1;
			$birthday_str=strtotime('+'.$arr_date["meses"].' month',$dtnext);
			
			//se na data da visualização for a data de aniversário do investimento...
			if($birthday_str >= strtotime($arr_date["today"])){
				//calcula o saldo atual (com ou sem ganhos)
				$new_amount = self::getSaldo($resp,$arr_date["meses"]);
				
				$arr_set = array("id"=>$resp["id"],"new_amount"=>$new_amount,"next_birthday_date"=>$next_birthday_date);
				$ret = self::setUpdate($arr_set);
				
				if($ret != false){
					return self::setSelect($ret["id"]);
				}
				else{
					return $resp;
				}
			}
		}
	}
    
    public function setSelectAll(){
		$resp = self::showSelectAll();
		
		if($resp === false){
			return array("status"=>404,"message"=>"NENHUM DADO ENCONTRADO");
		}
		else{
			$x=0;
			foreach($resp as $arr){
				foreach($arr as $k => $v){
					$arr1[$k] = $v;
				}

				$ret[$x] = self::setSelect($arr1["id"]);
				
				$x++;
			}

			return $ret;
		}
	}

	// lista investimentos/aplicações por nome de proprietário
	public function setSelectByOwner($steps){
		
		$resp = self::showSelectByOwner($steps);
		
		if($resp === false){
			return array("status"=>404,"message"=>"NENHUM DADO ENCONTRADO");
		}
		else{
			$x=1;
			foreach($resp as $arr){
				foreach($arr as $k => $v){
					$arr1[$k] = $v;
				}
				
				$aux[$x] = self::setSelect($arr1["id"]);
				
				$x++;
			}
			return $aux;
		}
	}

	// lista(com paginação) investimentos/aplicações por nome de proprietário
	public function setSelectByOwnerByPages($steps){
		
		$resp = self::showSelectByOwner($steps);
		
		if($resp === false){
			return array("status"=>404,"message"=>"NENHUM DADO ENCONTRADO");
		}
		else{
			$step = $steps[2];
			$page = $steps[3]*1;
			$tot = count($resp);
			$rst = $tot % $step;
			$totpage = intdiv($tot,$step);
			if($rst>0) $totpage += 1;

			$res=array("status"=>404,"message"=>"Não exite mais dados para exibir.");

			$x=$step*($page-1);
			$limit=$page * $step;
			if($rst>0){
				if($page == $totpage) $limit=$tot;
			}

			if((($limit <= $tot))){
				for($x;$x<$limit;$x++){
					$ret[$x+1] = self::setSelect($resp[$x]["id"]);
				}
				return $ret;
			}
			else{
				return $res;
			}
		}
	}

	public function setInsert($arr){
		
		if(count($arr) == 3 
			&& (isset($arr["owner"]) && !empty($arr["owner"])) 
			&& (isset($arr["create_date"]) && !empty($arr["create_date"])) 
			&& (isset($arr["initial_amount"]) && !empty($arr["initial_amount"])))
		{
			//faz uma verificação de datas
			$arr_date = self::calcDateTime($arr["create_date"]);
			
			//verifica a data de criação
			if($arr_date["dtcObj"] > $arr_date["nowObj"]){
				$resp="Data de criação é maior que a data atual.";
				$ret=false;
			}
			else{
				//verifica se valor inicial investido é positivo ou maior que zero
				if($arr["initial_amount"] > 0){
					//calcula a próxima data de aniversário do investimento
					$birthday = self::getBirthdayDate($arr_date["dtCreated"],$arr_date["meses"]);
					//calcula o saldo atual (com ou sem ganhos)
					$actual_amount = self::getSaldo($arr,$arr_date["meses"]);
					$ret=array("owner"=>$arr["owner"],"create_date"=>$arr_date["dtCreated"],"initial_amount"=>$arr["initial_amount"],"actual_amount"=>$actual_amount,"next_birthday_date"=>$birthday);
				}
				else{
					$resp="O valor informado deve ser sempre positivo (maior que zero).";
					$ret=false;
				}
			}
			if($ret === false){
				return array("status"=>400,"message"=>$resp);
			}
			else{
				return self::insertApplication($ret);
			}
		}
		else{
			return array("status"=>400,"message"=>"Dados inválidos");
		}
	}

	public function setUpdate($arr){
		
		if((isset($arr["id"]) && !empty($arr["id"])) && (isset($arr["new_amount"]) && !empty($arr["new_amount"])))
		{
			return self::showUpdate($arr);
		}
		else{
			return array("status"=>400,"message"=>"Dados inválidos");
		}
	}

	public function setDelete($arr){

		$arrwdl = self::setSelect($arr["id"]);
		
		if($arrwdl === false){

			return array("status"=>404,"message"=>"Nenhum registro foi encontrado.");
		}
		else {
			if((isset($arr["id"]) && !empty($arr["id"])) && (isset($arr["withdrawal_date"]) && !empty($arr["withdrawal_date"])))
			{
				//$arrwdl = self::setSelect($arr["id"]);
				
				// se status é igual a 0(false)...
				if(isset($arrwdl["status"]) == false) return $arrwdl;

				$arr_date = self::calcDateTime($arrwdl["create_date"]);

				$dtc=strtotime($arr_date["dtCreated"]);
				$tdy=strtotime($arr_date["today"]);
				$withdrawal_date=strtotime($arr["withdrawal_date"]);
				
				$dtdwl_date=date("Y-m-d",$withdrawal_date);
				$dtdwl=strtotime($dtdwl_date);
				
				//verificando a data da retirada do valor
				if(($dtdwl > $tdy) || ($dtdwl < $dtc)){
					$resp="Data de retirada não pode ser maior que a data atual nem menor que a data de criação do investimento.";
					$ret = false;
				}
				else{
					$withdrawal = self::getTaxes($arr_date,$arrwdl);
					$send=array("id"=>$arr["id"],"withdrawal_date"=>$dtdwl_date,"withdrawal_amount"=>$withdrawal);		
					$ret = self::showDelete($send);
				}
				if($ret === false){
					return array("status"=>400,"message"=>$resp);
				}
				else{
					return $ret;
				}
			}
			else{
				return array("status"=>400,"message"=>"Dados inválidos");
			}
		}
	}

	// calcula o saldo ganho
	private function getSaldo($arr,$meses){
		$actual_amount=$arr["initial_amount"];
		
		for($i=0;$i<$meses-1;$i++){
			$actual_amount*=1.0052;
		}
		return $actual_amount=number_format($actual_amount, 2, '.', '');
	}

	// pega a data de aniversário do investimento/aplicação
	private function getBirthdayDate($dtCreated,$meses){
		//inicia uma data aniversário
		$dtini=strtotime($dtCreated);
		
		$birthday_str=strtotime('+'.$meses.' month',$dtini);
		return $birthday=date("Y-m-d",$birthday_str);
	}

	// calcula algumas datas...
	private function calcDateTime($create_date){
		//data da criação
		$dtc=strtotime($create_date);
		$dtCreated=date("Y-m-d",$dtc);
		$dtcObj=new DateTime($dtCreated);
		$dtcObj->format("Y-m-d");

		//data de hoje
		$now=date("Y-m-d");
		$nowObj=new DateTime($now);

		//calcula quantos meses se passou desde a data de criação
		$int = $dtcObj->diff($nowObj);
		if($int->y>0) $meses=($int->y*12)+$int->m;
		else $meses=$int->m + 1;
		
		return array("dtcObj"=>$dtcObj, "nowObj"=>$nowObj, "meses"=>$meses, "dtCreated"=>$dtCreated, "today"=>$now);
	}

	private function getTaxes($arr_date,$arr){

		//calcula quantos anos se passou desde a data de criação
		$int = $arr_date["dtcObj"]->diff($arr_date["nowObj"]);
		$anos=$int->y;

		//calcula os impostos de retirada
		$amount = $arr["actual_amount"] - $arr["initial_amount"];
		$actual_amount = $arr["actual_amount"] + 0;
		
		switch ($anos) {
			case 0:
				$tax_amount  = $amount * 0.225;
				//$amount-=$tax_amount;
				$withdraw_amount = $actual_amount - $tax_amount;
				break;
			case 1:
				$tax_amount = $amount * 0.185;
				//$amount-=$tax_amount;
				$withdraw_amount = $actual_amount - $tax_amount;
				break;
			default:
				$tax_amount = $amount * 0.15;
				//$amount-=$tax_amount;
				$withdraw_amount = $actual_amount - $tax_amount;
				break;
		}

		$withdraw_amount=number_format($withdraw_amount, 2, '.', '');

		return $withdraw_amount;
	}
}

?>