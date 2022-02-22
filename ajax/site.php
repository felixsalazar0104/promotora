<?php 
ob_start();
if (strlen(session_id()) < 1){
	session_start();//Validamos si existe o no la sesión
}
if (!isset($_SESSION["nombre"]))
{
  header("Location: ../vistas/login.html");//Validamos el acceso solo a los usuarios logueados al sistema.
}
else
{
//Validamos el acceso solo al usuario logueado y autorizado.
if ($_SESSION['almacen']==1)
{	
require_once "../modelos/site.php";

$site=new site();

$idsite=isset($_POST["idsite"])? limpiarCadena($_POST["idsite"]):"";
$idcontratista=isset($_POST["idcontratista"])? limpiarCadena($_POST["idcontratista"]):"";
$nombre_site=isset($_POST["nombre_site"])? limpiarCadena($_POST["nombre_site"]):"";
$id_umbrella=isset($_POST["id_umbrella"])? limpiarCadena($_POST["id_umbrella"]):"";
$centro_costo=isset($_POST["centro_costo"])? limpiarCadena($_POST["centro_costo"]):"";
$nombre_sy=isset($_POST["nombre_sy"])? limpiarCadena($_POST["nombre_sy"]):"";
$estado=isset($_POST["estado"])? limpiarCadena($_POST["estado"]):""; 
$cargo=isset($_POST["cargo"])? limpiarCadena($_POST["cargo"]):""; 
$imagen=isset($_POST["imagen"])? limpiarCadena($_POST["imagen"]):"";

switch ($_GET["op"]){
	case 'guardaryeditar':

		if (!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name']))
		{
			$imagen=$_POST["imagenactual"];
		}
		else 
		{
			$ext = explode(".", $_FILES["imagen"]["name"]);
			if ($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png")
			{
				$imagen = round(microtime(true)) . '.' . end($ext);
				move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/sites/" . $imagen);
			}
		}
		if (empty($idsite)){
			$rspta=$site->insertar($idcontratista,$nombre_site,$id_umbrella,$centro_costo,$nombre_sy,$estado,$cargo,$actividad,$correo,$imagen);
			echo $rspta ? "Persona registrado" : "Persona no se pudo registrar";
		}
		else {
			$rspta=$site->editar($idsite,$idcontratista,$nombre_site,$id_umbrella,$centro_costo,$nombre_sy,$estado,$cargo,$actividad,$correo,$imagen);
			echo $rspta ? "Persona actualizado" : "Persona no se pudo actualizar";
		}
	break;

	case 'desactivar':
		$rspta=$site->desactivar($idsite);
 		echo $rspta ? "Persona Desactivado" : "Persona no se puede desactivar";
	break;

	case 'activar':
		$rspta=$site->activar($idsite);
 		echo $rspta ? "Persona activado" : "Persona no se puede activar";
	break;

	case 'mostrar':
		$rspta=$site->mostrar($idsite);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listar':
		$rspta=$site->listar();
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				
				"0"=>$reg->idsite,
				"1"=>$reg->id_umbrella,
 				"2"=>($reg->condicion)?'<span class="label bg-green">Activo</span>':
 				'<span class="label bg-red">Inactivo</span>',
				"3"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idsite.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-danger" onclick="desactivar('.$reg->idsite.')"><i class="fa fa-close"></i></button>':
 					'<button class="btn btn-warning" onclick="mostrar('.$reg->idsite.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-primary" onclick="activar('.$reg->idsite.')"><i class="fa fa-check"></i></button>',
 				);
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);

	break;

	case "selectcontratista":
		require_once "../modelos/contratista.php";
		$contratista = new contratista();

		$rspta = $contratista->select();

		while ($reg = $rspta->fetch_object())
				{
					echo '<option value=' . $reg->idcontratista . '>' . $reg->id_umbrella . '</option>';
				}
	break;
}
//Fin de las validaciones de acceso
}
else
{
  require 'noacceso.php';
}
}
ob_end_flush();
?>