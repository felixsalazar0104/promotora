var tabla;

//Función que se ejecuta al inicio
function init(){
	mostrarform(false);
	listar();

	$("#formulario").on("submit",function(e)
	{
		guardaryeditar(e);	
	});
    $('#mAlmacen').addClass("treeview active");
    $('#lcontratistas').addClass("active");
	$("#imagenmuestra").hide();
}

//Función limpiar
function limpiar()
{
	$("#idcontratista").val("");
	$("#nombre").val("");
	$("#nit").val("");
	$("#regional").val("");
	$("#departamento1").val("");
	$("#departamento2").val("");
	$("#departamento3").val("");
	$("#imagenmuestra").attr("src","");
	$("#imagenactual").val("");
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
					url: '../ajax/contratista.php?op=listar',
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
		url: "../ajax/contratista.php?op=guardaryeditar",
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

function mostrar(idcontratista)
{
	$.post("../ajax/contratista.php?op=mostrar",{idcontratista : idcontratista}, function(data, status)
	{
		data = JSON.parse(data);		
		mostrarform(true);

		$("#nombre").val(data.nombre);
		$("#nit").val(data.nit);
 		$("#idcontratista").val(data.idcontratista);
 		$("#regional").val(data.regional);
 		$("#departamento1").val(data.departamento1);
 		$("#departamento2").val(data.departamento2);
 		$("#departamento3").val(data.departamento3);
		$("#imagenmuestra").show();
		$("#imagenmuestra").attr("src","../files/contratistas/"+data.imagen);
		$("#imagenactual").val(data.imagen);

 	})
}

//Función para desactivar registros
function desactivar(idcontratista)
{
	bootbox.confirm("¿Está Seguro de desactivar el contratista?", function(result){
		if(result)
        {
        	$.post("../ajax/contratista.php?op=desactivar", {idcontratista : idcontratista}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

//Función para activar registros
function activar(idcontratista)
{
	bootbox.confirm("¿Está Seguro de activar el contratista ?", function(result){
		if(result)
        {
        	$.post("../ajax/contratista.php?op=activar", {idcontratista : idcontratista}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}


init();