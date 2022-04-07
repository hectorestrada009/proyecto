var tabla;

//Funcion que se ejecuta al inicio
	function init(){
		listar();
		//Cuando se da clickal boton submit se ejecuta la función guardaryeditar
		$("#usuario_form").on("submit", function(e){
			guardaryeditar(e);
		});
		//Cambia el titulo de la ventana modal cuando se da click al boton
		$("#add_button").click(function(){
			$(".modal-title").text("Agregar usuario");
		});
	}

//Funcion que limpia los campos del formulario
	function limpiar(){
		$("#cedula").val("");
		$("#nombre").val("");
		$("#apellido").val("");
		$("#cargo").val("");
		$("#usuario").val("");
		$("#password").val("");
		$("#password2").val("");
		$("#telefono").val("");
		$("#correo").val("");
		$("#direccion").val("");
		$("#estado").val("");
	}

//Funcion para listar
	function listar(){
		tabla = $('#usuario_data').dataTable({
			"aProcessing": true, //Activamos el procesamiento del datatable
			"aServerSide": true, //Paginacion y filtrado realizados por el servidor
			dom: 'Bfrtip', //Definimos los elementos de control de la tabla
			//Botones para exportar a documentos
			button: [
				'copyHtml5',
				'excelHtml5',
				'csvHtml5',
				'pdf'
			],
			"ajax": {
				url: '../ajax/usuario.php?op=listar',
				type: "get",
				dataType: "json",
				error: function(e){
					console.log(e.responseText);
				}
			},
			"bDestroy": true,
			"responsive": true,
			"bInfo": true,
			"iDisplayLength": 10,//Por cada 10 registros hace una paginacion
			"order": [[0, "desc"]],//Ordenar (Columna/Orden)

			"language": {
			"sProcessing": "Procesando...",
			"sLengthMenu": "Mostrar _MENU_ registros",
			"sZeroRecords": "No se encontraron resultados",
			"sEmptyTable": "Ningún dato disponible en esta tabla",
			"sInfo": "Mostrando un total de _TOTAL_ registros",
			"sInfoEmpty": "Montrando un total de 0 registros",
			"sInfoFiltered": "(Filtrado de un total de _MAX_ registros)",
			"sInfoPostFix": "",
			"sSearch": "Buscar:",
			"sUrl": "",
			"sInfoThousands": ",",
			"sLoadingRecords": "Cargando...",
			"oPaginate": {
				"sFirst": "Primero",
				"sLast": "Último",
				"sNext": "Siguiente",
				"sPrevious": "Anterior",
			},
			"oAria": {
				"sSortAscending": ": Activar para ordenar la columna de manera ascendiente",
				"sSortDescending": ": Activar para ordenar la columna de manera descendente",
			}
		}// Cierra languages
		}).DataTable();
	}

	//Mostrar datos en la ventana modal del formulario
	function mostrar($id_usuario){
		$post("../ajax/usuario.php?op=mostrar", {id_usuario : id_usuario}, function(data, status){
			data = JSON.parse(data);
				$("#usuarioModal").modal("show");
				$("#cedula").val(data.cedula);
				$("#nombre").val(data.nombre);
				$("#apellido").val(data.apellido);
				$("#cargo").val(data.cargo);
				$("#usuario").val(data.usuario);
				$("#password").val(data.password);
				$("#password2").val(data.password2);
				$("#telefono").val(data.telefono);
				$("#correo").val(data.correo);
				$("#direccion").val(data.direccion);
				$("#estado").val(data.estado);
				$('.modal-title').text("Editar usuario");
				$('#id_usuario').val(id_usuario);
				$('#action').val("Edit");
		});
	}

	//Guardar y editar (e) / Se llama cuando se da click al boton submit
	function guardaryeditar(e){
		e.preventDefault//Función que previene la accion predeterminada del evento
		var formData = new FormData($("#usuario_form")[0]);
		var password1 = $('password1').val();
		var password2 = $('password2').val();

			//Si el password coincide entonces se envía el formulario
			if(password1 == password2){
				$.ajax({
					url: "../ajax/usuario.php?op=guardaryeditar",
					type: "POST",
					data: formData,
					contentType: false,
					processData: false,
					success: function (datos){
						$('#usuarios_form')[0].reset();//Se limpian los campos
						$('#usuarioModal').modal('hide');//Se cierra la ventana modal
						$('#resultados_ajax').html(datos);//Se muestra el mensaje en el datatable
						$('#usuario_data').DataTable().ajax.reload();//Se recarga de forma asincrona el datable
						limpiar();
					}
				});
			//Cierre de la validación
			}else{
				bootbox.alert("No coinciden las contraseñas");
			}
	}

	//Editar estado del usuario
	//Importante: id_usuario y est, se envian por post via ajax
	function cambiarEstado(id_usuario, est){
		bootbox.confirm("¿Está seguro de cambiar el estado?", function(result){
			if(result){
				$.ajax({
					url: "../ajax/usuario.php?op=activarydesactivar",
					method: "POST",
					//Toma el valor del id y del estado
					data:{id_usuario:id_usuario, est:est},
					success: function(){
						$('#usuario_data').DataTable().ajax.reload();
					}
				})
			}

		});//Cierre de bootbox
	}

	init();