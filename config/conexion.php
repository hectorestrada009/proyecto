<?php 

	class Conectar{

	protected $dbh;

		public function conexion(){
			try{
				$conectar = $this->dbh = new PDO("mysql:local=localhost;dbname=dbproyecto","root","");
				return $conectar;
			}catch (Exception $e){
				print "Error!:" . $e->getMessage() . "<br/>";
				die();
			}
		}// Cierre de la funcion conexion
		public function set_names(){
			return $this->dbh->query("SET NAMES 'utf8'");
		}
		public function ruta(){
			return "http://localhost/proyecto/";
		}
	}// Cierre de la clase conectar

?>