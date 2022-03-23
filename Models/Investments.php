<?php

require_once 'Database.php';

class Investments extends Database{

	private static $table = "investments";

	private static function select($id){

		$db = self::dbConnection();

		$query = "SELECT * FROM ".self::$table." WHERE id = :id AND status = 1";

		$stmt = $db->prepare($query);
		$stmt->bindParam(":id", $id, PDO::PARAM_STR);
		$stmt->execute();

		if($stmt->rowCount() > 0){
			return $stmt->fetch(PDO::FETCH_ASSOC);	
		}else{
			//throw new Exception("A APLICAÇÃO NÃO FOI ENCONTRADA EM NOSSA BASE DE DADOS");
			return false;
		}
	}
	protected function showSelect($id){
		return self::select($id);
	}

	// lista investimentos/aplicações por nome de proprietário
	private static function selectByOwner($owner){
		//var_dump($owner);
		$db = self::dbConnection();

		$query = "SELECT * FROM ".self::$table." WHERE owner = UPPER(:owner) AND status = 1";

		$stmt = $db->prepare($query);
		$stmt->bindParam(":owner", $owner[1], PDO::PARAM_STR);
		$stmt->execute();

		if($stmt->rowCount() > 0){
			return $stmt->fetchAll(PDO::FETCH_ASSOC);	
		}else{
			//throw new Exception("A APLICAÇÃO NÃO FOI ENCONTRADA EM NOSSA BASE DE DADOS");
			return false;
		}
	}
	protected function showSelectByOwner($owner){
		return self::selectByOwner($owner);
	}

	private static function selectAll(){

		$db = self::dbConnection();

		$query = "SELECT * FROM ".self::$table." WHERE status = 1";

		$stmt = $db->prepare($query);
		$stmt->execute();
		
		if($stmt->rowCount() > 0){
			return $stmt->fetchAll(PDO::FETCH_ASSOC);	
		}else{
			//throw new \Exception("NENHUM DADO ENCONTRADO");
			return false;
		}
	}
	protected function showSelectAll(){
		return self::selectAll();
	}

	private static function insert($data){

		$db = self::dbConnection();

		$query = "INSERT INTO ".self::$table." (owner, create_date, initial_amount, actual_amount, next_birthday_date) VALUES(UPPER(:owner), :create_date, :initial_amount, :actual_amount, :next_birthday_date)";

		$stmt = $db->prepare($query);
		$stmt->bindParam(":owner", $data['owner'], PDO::PARAM_STR);
		$stmt->bindParam(":create_date", $data['create_date'], PDO::PARAM_STR);
		$stmt->bindParam(":initial_amount", $data['initial_amount'], PDO::PARAM_STR);
		$stmt->bindParam(":actual_amount", $data['actual_amount'], PDO::PARAM_STR);
		$stmt->bindParam(":next_birthday_date", $data['next_birthday_date'], PDO::PARAM_STR);
		$stmt->execute();

		if($stmt->rowCount() > 0){
			return array("status"=>200,"msg"=>"Investimento criado em ".$data['create_date']." com sucesso!");
			 	
		}else{
			//throw new \Exception("INVESTIMENTO NÃO PÔDE SER CRIADO EM NOSSA BASE DE DADOS!");
			return false;
		}
	}
	protected function insertApplication($data){
		return self::insert($data);
	}

	private static function update($arr){
		
		$db = self::dbConnection();
		
		if(isset($arr["next_birthday_date"])){
			$query = "UPDATE ".self::$table." SET actual_amount = :actual_amount, next_birthday_date = :next_birthday_date WHERE id = :id AND status = 1";

			$stmt = $db->prepare($query);
			$stmt->bindParam(":id", $arr["id"], PDO::PARAM_STR);
			$stmt->bindParam(":actual_amount", $arr["new_amount"], PDO::PARAM_STR);
			$stmt->bindParam(":next_birthday_date", $arr["next_birthday_date"], PDO::PARAM_STR);
		}
		else{
			$query = "UPDATE ".self::$table." SET actual_amount = :actual_amount WHERE id = :id AND status = 1";

			$stmt = $db->prepare($query);
			$stmt->bindParam(":id", $arr["id"], PDO::PARAM_STR);
			$stmt->bindParam(":actual_amount", $arr["new_amount"], PDO::PARAM_STR);
		}
		
		$stmt->execute();
		
		if($stmt->rowCount() > 0){
			return self::showSelect($arr["id"]);	
		}else{
			return false;
			//throw new Exception("USUÁRIO NÃO FOI ENCONTRADO EM NOSSA BASE DE DADOS");
		}
	}
	protected function showUpdate($arr){
		return self::update($arr);
	}

	private static function delet($arr){
		
		$db = self::dbConnection();
		
		$query = "UPDATE ".self::$table." SET actual_amount = 0, withdrawal_amount = :withdrawal_amount, withdrawal_date = :withdrawal_date, status = 0 WHERE id = :id AND status = 1";

		$stmt = $db->prepare($query);
		$stmt->bindParam(":id", $arr["id"], PDO::PARAM_STR);
		$stmt->bindParam(":withdrawal_amount", $arr["withdrawal_amount"], PDO::PARAM_STR);
		$stmt->bindParam(":withdrawal_date", $arr["withdrawal_date"], PDO::PARAM_STR);
		$stmt->execute();
		
		if($stmt->rowCount() > 0){
			return array("status"=>200,"msg"=>"Retirada de ".$arr["withdrawal_amount"]." em ".$arr["withdrawal_date"]." realizada com sucesso!");
		}else{
			//throw new Exception("USUÁRIO NÃO FOI ENCONTRADO EM NOSSA BASE DE DADOS");
			return false;
		}
	}
	protected function showDelete($data){
		
		return self::delet($data);
	}
}

?>