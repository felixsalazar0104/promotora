var tabla;

//Función que se ejecuta al inicio
function init(){
	mostrarform(false);
	listar();

	$("#formulario").on("submit",function(e)
	{
		guardaryeditar(e);	
	})

	//Cargamos los items al select contratista
	$.post("../ajax/personal.php?op=selectcontratista", function(r){
	            $("#idcontratista").html(r);
	            $('#idcontratista').selectpicker('refresh');

	});
	$("#imagenmuestra").hide();
	$('#mAlmacen').addClass("treeview active");
    $('#lpersonals').addClass("active");
}

//Función limpiar
function limpiar()
{
	$("#cedula").val("");
	$("#nombre").val("");
	$("#celular").val("");
    $("#eps").val("");
    $("arl").val("");
    $("cargo").val("");
    $("actividad").val("");
    $("correo").val("");
	$("#imagenmuestra").attr("src","");
	$("#imagenactual").val("");
	$("#print").hide();
	$("#idpersonal").val("");
}

//Función mostrar formulario
function mostrarform(flag)
{
	limpiar();
	if (flag)
	{
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
	}
	else
	{
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
	}
}

//Función cancelarform
function cancelarform()
{
	limpiar();
	mostrarform(false);
}

//Función Listar
function listar()
{
	tabla=$('#tbllistado').dataTable(
	{
		"lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
	    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
	    buttons: [		          
		            'copyHtml5',
		            'excelHtml5',
		            'csvHtml5',
		            'pdf'
		        ],
		"ajax":
				{
					url: '../ajax/personal.php?op=listar',
					type : "get",
					dataType : "json",						
					error: function(e){
						console.log(e.responseText);	
					}
				},
		"language": {
            "lengthMenu": "Mostrar : _MENU_ registros",
            "buttons": {
            "copyTitle": "Tabla Copiada",
            "copySuccess": {
                    _: '%d líneas copiadas',
                    1: '1 línea copiada'
                }
            }
        },
		"bDestroy": true,
		"iDisplayLength": 5,//Paginación
	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();
}
//Función para guardar o editar

function guardaryeditar(e)
{
	e.preventDefault(); //No se activará la acción predeterminada del evento
	$("#btnGuardar").prop("disabled",true);
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/personal.php?op=guardaryeditar",
	    type: "POST",
	    data: formData,
	    contentType: false,
	    processData: false,

	    success: function(datos)
	    {                    
	          bootbox.alert(datos);	          
	          mostrarform(false);
	          tabla.ajax.reload();
	    }

	});
	limpiar();
}

function mostrar(idpersonal)
{
	$.post("../ajax/personal.php?op=mostrar",{idpersonal : idpersonal}, function(data, status)
	{
		data = JSON.parse(data);		
		mostrarform(true);

		$("#idcontratista").val(data.idcontratista);
		$('#idcontratista').selectpicker('refresh');
		$("#cedula").val(data.cedula);
		$("#nombre").val(data.nombre);
		$("#celular").val(data.celular);
		$("#eps").val(data.eps);
		$("#arl").val(data.arl);
		$("#cargo").val(data.cargo);
		$("#actividad").val(data.actividad);
		$("#correo").val(data.correo);
		$("#imagenmuestra").show();
		$("#imagenmuestra").attr("src","../files/personals/"+data.imagen);
		$("#imagenactual").val(data.imagen);
 		$("#idpersonal").val(data.idpersonal);
 		generarbarcode();

 	})
}

//Función para desactivar registros
function desactivar(idpersonal)
{
	bootbox.confirm("¿Está Seguro de Inactivar la Persona?", function(result){
		if(result)
        {
        	$.post("../ajax/personal.php?op=desactivar", {idpersonal : idpersonal}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

//Función para activar registros
function activar(idpersonal)
{
	bootbox.confirm("¿Está Seguro de activar la Persona?", function(result){
		if(result)
        {
        	$.post("../ajax/personal.php?op=activar", {idpersonal : idpersonal}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

//función para generar el código de barras
function generarbarcode()
{
	cedula=$("#cedula").val();
	JsBarcode("#barcode", cedula);
	$("#print").show();
}

//Función para imprimir el Código de barras
function imprimir()
{
	$("#print").printArea();
}

init();