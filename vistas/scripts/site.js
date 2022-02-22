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
	$.post("../ajax/site.php?op=selectcontratista", function(r){
	            $("#idcontratista").html(r);
	            $('#idcontratista').selectpicker('refresh');

	});
	$("#imagenmuestra").hide();
	$('#mAlmacen').addClass("treeview active");
    $('#lsites').addClass("active");
}

//Función limpiar
function limpiar()
{
	$("#nombre_site").val("");
	$("#id_umbrella").val("");
	$("#centro_costo").val("");
    $("#nombre_sy").val("");
    $("estado").val("");
	$("#imagenmuestra").attr("src","");
	$("#imagenactual").val("");
	$("#print").hide();
	$("#idsite").val("");
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
					url: '../ajax/site.php?op=listar',
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
		url: "../ajax/site.php?op=guardaryeditar",
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

function mostrar(idsite)
{
	$.post("../ajax/site.php?op=mostrar",{idsite : idsite}, function(data, status)
	{
		data = JSON.parse(data);		
		mostrarform(true);

		$("#idcontratista").val(data.idcontratista);
		$('#idcontratista').selectpicker('refresh');
		$("#nombre_site").val(data.nombre_site);
		$("#id_umbrella").val(data.id_umbrella);
		$("#centro_costo").val(data.centro_costo);
		$("#nombre_sy").val(data.nombre_sy);
		$("#estado").val(data.estado);
		$("#imagenmuestra").show();
		$("#imagenmuestra").attr("src","../files/sites/"+data.imagen);
		$("#imagenactual").val(data.imagen);
 		$("#idsite").val(data.idsite);
 		generarbarcode();

 	})
}

//Función para desactivar registros
function desactivar(idsite)
{
	bootbox.confirm("¿Está Seguro de Inactivar la Persona?", function(result){
		if(result)
        {
        	$.post("../ajax/site.php?op=desactivar", {idsite : idsite}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

//Función para activar registros
function activar(idsite)
{
	bootbox.confirm("¿Está Seguro de activar la Persona?", function(result){
		if(result)
        {
        	$.post("../ajax/site.php?op=activar", {idsite : idsite}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

//función para generar el código de barras
function generarbarcode()
{
	nombre_site=$("#nombre_site").val();
	JsBarcode("#barcode", nombre_site);
	$("#print").show();
}

//Función para imprimir el Código de barras
function imprimir()
{
	$("#print").printArea();
}

init();