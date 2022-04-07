<?php 

	//Conexion a la base de datos
	require_once("../config/conexion.php");

	class Usuarios extends Conectar{

		//Listar los usuarios
		public function get_usuarios(){
			$conectar = parent::conexion();
			parent::set_names();
			$sql = "SELECT * FROM usuarios";
			$sql = $conectar->prepare($sql);
			$sql->execute();
			return $resultado = $sql->fetchAll();
		}

		//Metodo para registrar usuario
		public function registrar_usuario($nombre, $apellido, $cedula, $telefono, $email, $direccion, $cargo, $usuario, $password, $password2, $estado){
			$conectar = parent::conexion();
			parent::set_names();
			$sql = "INSERT INTO usuarios values(null, ?,?,?,?,?,?,?,?,?,?,now(),?);";
			$sql = $conectar->prepare($sql);
			$sql->bindValue(1, $_POST["nombre"]);
			$sql->bindValue(2, $_POST["apellido"]);
			$sql->bindValue(3, $_POST["cedula"]);
			$sql->bindValue(4, $_POST["telefono"]);
			$sql->bindValue(5, $_POST["email"]);
			$sql->bindValue(6, $_POST["direccion"]);
			$sql->bindValue(7, $_POST["cargo"]);
			$sql->bindValue(8, $_POST["usuario"]);
			$sql->bindValue(9, $_POST["password"]);
			$sql->bindValue(10, $_POST["password2"]);
			$sql->bindValue(11, $_POST["estado"]);
			$sql->execute();
		}

		//Metodo para editar usuario
		public function editar_usuario($id_usuario, $nombre, $apellido, $cedula, $telefono, $email, $direccion, $cargo, $usuario, $password, $password2, $estado){
			$conectar = parent::conexion();
			parent::set_names();
			$sql = "UPDATE usuarios SET
			nombres=?,
			apellidos=?,
			cedula=?,
			telefono=?,
			correo=?,
			direccion=?,
			cargo=?,
			usuario=?,
			password=?,
			password2=?,
			estado=?,
			WHERE
			id_usuario=?,
			";
			$sql = $conectar->prepare($sql);
			$sql->bindValue(1, $_POST["nombre"]);
			$sql->bindValue(2, $_POST["apellido"]);
			$sql->bindValue(3, $_POST["cedula"]);
			$sql->bindValue(4, $_POST["telefono"]);
			$sql->bindValue(5, $_POST["email"]);
			$sql->bindValue(6, $_POST["direccion"]);
			$sql->bindValue(7, $_POST["cargo"]);
			$sql->bindValue(8, $_POST["usuario"]);
			$sql->bindValue(9, $_POST["password"]);
			$sql->bindValue(10, $_POST["password2"]);
			$sql->bindValue(11, $_POST["estado"]);
			$sql->bindValue(12, $_POST["id_usuario"]);
			$sql->execute();
		}

		//Mostrar los datos del usuario por id
		public function get_usuario_por_id($id_usuario){
			$conectar = parent::conexion();
			parent::set_names();
			$sql = "SELECT * FROM usuarios WHERE id_usuario=?";
			$sql = $conectar->prepare($sql);
			$sql->bindValue(1, $id_usuario);
			$sql->execute();
			return $resultado = $sql->fetchAll();
		}

		//Editar estado del usuario (Activo/Inactivo)
		public function editar_estado($id_usuario, $estado){
			$conectar = parent::conexion();
			parent::set_names();
			//El parametro se envia por via ajax
			//Activo = 1 / Inactivo = 0
			if ($_POST["est"] == "0") {
				$estado = 1;
			}else{
				$estado = 0;
			}
			$sql = "UPDATE usuarios SET estado=? WHERE id_usuario=?";
			$sql = $conectar->prepare($sql);
			$sql->bindValue(1, $id_usuario);
			$sql->bindValue(2, $estado);
			$sql->execute();
		}

		//Seleccion de correo y usuario (Validacion para registro de usuario)
		public function get_cedula_correo_del_usuario($cedula, $email){
			$coenctar = parent::conexion();
			parent::set_names();
			$sql = "SELECT * FROM usuarios WHERE cedula=? or correo=?";
			$sql = $conectar->prepare($sql);
			$sql->bindValue(1, $cedula);
			$sql->bindValue(2, $email);
			$sql->execute();
			return $resultado = $sql->fetchAll();
		}
	}

?>