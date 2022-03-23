<?php

class Database{

	protected function dbConnection(){

		$conn = new \PDO("mysql:host=localhost;dbname=investmentapp","root","");
		return $conn;

	}
}

?>