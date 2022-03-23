<?php

require_once 'Database.php';

class Users extends Database{

	private static $table = "owners";

	private static function select($id){

		$db = self::dbConnection();

		$query = "SELECT * FROM ".self::$table." WHERE id = :id";

		$stmt = $db->prepare($query);
		$stmt->bindParam(":id", $id, PDO::PARAM_STR);
		$stmt->execute();

		if($stmt->rowCount() > 0){
			return $stmt->fetch(PDO::FETCH_ASSOC);	
		}else{
			return false;
		}

	}
	protected function showSelect($id){
		return self::select($id);
	}

	private static function selectAll(){

		$db = self::dbConnection();

		$query = "SELECT * FROM ".self::$table;

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

		if(self::checkDuplicate($data['email']) == true) return array("status"=>400,"msg"=>"Dados duplicados. Esse email já está sendo usado!");

		$query = "INSERT INTO ".self::$table." (owner_name, email) VALUES(UPPER(:owner_name), :email)";

		$stmt = $db->prepare($query);
		$stmt->bindParam(":owner_name", $data['owner'], PDO::PARAM_STR);
		$stmt->bindParam(":email", $data['email'], PDO::PARAM_STR);
		$stmt->execute();
		
		if($stmt->rowCount() > 0){
			return array("status"=>200,"message"=>"Usuário ".$data['owner']." inserido com sucesso!");	 	
		}else{
			//throw new \Exception("USUÁRIO NÃO PÔDE SER INSERIDO EM NOSSA BASE DE DADOS!");
			return false;
		}
	}
	protected function insertOwner($data){
		return self::insert($data);
	}

	// verifica se já existe cadastro com o mesmo email informado
	private static function checkDuplicate($email){

		$db = self::dbConnection();

		$query = "SELECT owner_name, email FROM ".self::$table." WHERE email = :email";

		$stmt = $db->prepare($query);
		$stmt->bindParam(":email", $email, PDO::PARAM_STR);
		$stmt->execute();

		if($stmt->rowCount() > 0){
			return true;	
		}else{
			return false;
		}
	}
}

?>