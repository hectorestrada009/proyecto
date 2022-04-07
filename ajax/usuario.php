<?php 

	//Llamar a la base de datos
	require_once("../config/conexion.php");
	//Llamar al modelo usuarios
	require_once("../modelos/Usuarios.php");

	//Objeto para llamar a distintos metodo de la clase usuarios en Usuarios.php
	$usuarios = new Usuarios();

	//Declarar las variables de los valores que se enviar por el formulario que recibimos por ajax y decimos que si existe el parametro que estamos recibiendo
	$id_usuario = isset($_POST["id_usuario"]);
	$nombre = isset($_POST["nombre"]);
	$apellido = isset($_POST["apellido"]);
	$cedula = isset($_POST["cedula"]);
	$telefono = isset($_POST["telefono"]);
	$email = isset($_POST["email"]);
	$direccion = isset($_POST["direccion"]);
	$cargo = isset($_POST["cargo"]);
	$usuario = isset($_POST["usuario"]);
	$password = isset($_POST["password"]);
	$password2 = isset($_POST["password2"]);
	$estado = isset($_POST["estado"]);
	//Este es el que se envia del formulario

	switch ($_GET['op']) {
	/*Verificamos si la cedula y el correo existe en 
	la base de datos de ser el caso, no se registra*/
		case 'guardaryeditar':
			//Llamando al metodo get_cedula_correo_del_usuario de Usuario.php
			$datos = $usuarios->get_cedula_correo_del_usuario($_POST["cedula"], $_POST["email"]);
			//Validacion de contrase;as
			if ($password == $password2) {
				//Si el id no existe, lo registra
				if (empty($_POST["id_usuario"])){
					/*Si las contrase;as coinciden, verificamos si existe el correo y la cedula;
					si existen en la base de datos, no se registra el usuario*/
					if (is_array($datos) == true and count($datos) == 0) {
						//No existe el usuario, por lo tanto se hace el registro
						$usuarios->registrar_usuario($nombre, $apellido, $cedula, $telefono, $email, $direccion, $cargo, $usuario, $password, $password2, $estado);
						$messages[] = "El usuario se registró correctamente";
						//Si ya existe el correo o la cedula, entonces aparece el siguiente mensaje
					}else{
						$messages[] = "La cedula o el correo ya existe";
					}
				//Cierre de la validacion empty
				}else{
					//Si ya existe, editamos el usuario
					$usuarios->editar_usuario($id_usuario, $nombre, $apellido, $cedula, $telefono, $email, $direccion, $cargo, $usuario, $password, $password2, $estado);
					$messages[] = "El usuario de edito correctamente";
				}
			}else{
			//Si las contrase;as no coinciden se da un mensaje de error
				$errors[] = "Las contrase;as no coinciden";
			}

			if (isset($messages)){
				?>
				<div class="alert alert-succes" role="alert">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Bien Hecho!</strong>
					<?php 
						foreach($messages as $message){
							echo $message;
						}
					?>
				</div>
				<?php 
			}//Fin success

			if (isset($errors)){
				?>
				<div class="alert alert-danger" role="alert">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error!</strong>
					<?php 
						foreach($errors as $error){
							echo $error;
						}
					?>
				</div>
				<?php 
			}//Fin Error

			break;
			//
		case 'mostrar':
			//Seleccionamos el id
			//El parametro id_usuario se envia por ajax cuando se edita el usuario
			$datos = $usuarios->get_usuario_por_id($_POST["id_usuario"]);
			//Validacion del id del usuario
			if (is_array($datos) == true and count($datos)>0){
			 	foreach($datos as $row){
			 		$output["cedula"] = $row["cedula"];
			 		$output["nombre"] = $row["nombres"];
			 		$output["apellido"] = $row["apellidos"];
			 		$output["cargo"] = $row["cargo"];
			 		$output["usuario"] = $row["usuario"];
			 		$output["password"] = $row["password"];
			 		$output["password2"] = $row["password2"];
			 		$output["telefono"] = $row["telefono"];
			 		$output["correo"] = $row["correo"];
			 		$output["direccion"] = $row["direccion"];
			 		$output["estado"] = $row["estado"];
			 	}
			 	echo json_encode($output);
			 }else{
			 	//Si no existe en la base de datos
			 	$errors[] = "El usuario no existe";
			 }
			 if (isset($errors)){
				?>
				<div class="alert alert-danger" role="alert">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error!</strong>
					<?php 
						foreach($errors as $error){
							echo $error;
						}
					?>
				</div>
				<?php 
			}//Fin Error
			break;
			//
		case 'activarydesactivar':
			//Los parametros id_usuario y est viene por ajax
			$datos = $usuarios->get_usuario_por_id($_POST["id_usuario"]);
			//Valida el id del usuario
			if (is_array($datos) == true and count($datos)>0) {
				//Edita el estado del usuario
				$usuarios->editar_estado($_POST["id_usuario"], $_POST["est"]);
			}
			break;
			//
		case 'listar':
			$datos = $usuarios->get_usuarios();
			//declaramos el array
			$data = array();
			foreach ($datos as $row) {
				$sub_array = array();
				//Estado
				$est = '';
				$atrib = "btn btn-success btn-md estado";
				if ($row["estado"] == 0) {
					$est = 'Inactivo';
					$atrib = "btn btn-warning btn-md estado";
				}else{
					if ($row["estado"] == 1) {
						$est = 'Activo';
					}
				}
				//Cargo
				if ($row["cargo"] == 1) {
					$cargo = "administrador";
				}else{
					if ($row["cargo"] == 0) {
						$cargo = "empleado";
					}
				}
				$sub_array[] = $row["cedula"];
				$sub_array[] = $row["nombres"];
				$sub_array[] = $row["apellidos"];
				$sub_array[] = $row["usuario"];
				$sub_array[] = $cargo;
				$sub_array[] = $row["telefono"];
				$sub_array[] = $row["correo"];
				$sub_array[] = $row["direccion"];
				$sub_array[] = date("d-m-y", strtotime($row["fecha_ingreso"]));
				//Boton cambiar estado
				$sub_array[] = '<button type="button" onClick="cambiarEstado('.$row["id_usuario"].','.$row["estado"].');"name="estado" id="'.$row["id_usuario"].'" class="'.$atrib.'">'.$est.'</button>';
				//Boton editar
				$sub_array[] = '<button type="button" onClick="mostrar('.$row["id_usuario"].');" id="'.$row["id_usuario"].'"class="btn btn-warning btn-md update"><i class="glyphicon glyphicon-edit"></i> Editar </button>';
				//Boton eliminar
				$sub_array[] = '<button type="button" onClick="eliminar('.$row["id_usuario"].');" id="'.$row["id_usuario"].'"class="btn btn-danger btn-md"><i class="glyphicon glyphicon-edit"></i> Eliminar </button>';
				//Array que almacena los registros obtenidos por el foreach
				$data[] = $sub_array;
			}
			$results = array(
				//Indices del array
				"sEcho" => 1, //Informacion para el datatable
				"iTotalRecords" => count($data),//Enviamos el total de los registros al datatable
				"iTotalDisplayRecords" => count($data),//Enviamos el total de registros a visualizar
				"aaData" => $data);
			echo json_encode($results);
			break;
		
	}

?>