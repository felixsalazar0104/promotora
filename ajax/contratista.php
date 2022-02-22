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
require_once "../modelos/Contratista.php";

$contratista=new Contratista();

$idcontratista=isset($_POST["idcontratista"])? limpiarCadena($_POST["idcontratista"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$nit=isset($_POST["nit"])? limpiarCadena($_POST["nit"]):"";
$regional=isset($_POST["regional"])? limpiarCadena($_POST["regional"]):"";
$departamento1=isset($_POST["departamento1"])? limpiarCadena($_POST["departamento1"]):"";
$departamento2=isset($_POST["departamento2"])? limpiarCadena($_POST["departamento2"]):"";
$departamento3=isset($_POST["departamento3"])? limpiarCadena($_POST["departamento3"]):"";
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
				move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/contratistas/" . $imagen);
			}
		}

		if (empty($idcontratista)){
			$rspta=$contratista->insertar($nombre,$nit,$regional,$departamento1,$departamento2,$departamento3,$imagen);
			echo $rspta ? "Contratista registrado" : "Contratista no se pudo registrar";
		}
		else {
			$rspta=$contratista->editar($idcontratista,$nombre,$nit,$regional,$departamento1,$departamento2,$departamento3,$imagen);
			echo $rspta ? "Contratista actualizado" : "Contratista no se pudo actualizar";
		}
	break;

	case 'desactivar':
		$rspta=$contratista->desactivar($idcontratista);
 		echo $rspta ? "Contratista Desactivado" : "Contratista no se puede desactivar";
	break;

	case 'activar':
		$rspta=$contratista->activar($idcontratista);
 		echo $rspta ? "Contratista activado" : "Contratista no se puede activar";
	break;

	case 'mostrar':
		$rspta=$contratista->mostrar($idcontratista);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listar':
		$rspta=$contratista->listar();
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
				"0"=>"<img src='../files/contratistas/".$reg->imagen."' height='70px' width='70px' >",
 				"1"=>$reg->nombre,
				"2"=>$reg->nit,
 				"3"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
 				'<span class="label bg-red">Desactivado</span>',
				"4"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idcontratista.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-danger" onclick="desactivar('.$reg->idcontratista.')"><i class="fa fa-close"></i></button>':
 					'<button class="btn btn-warning" onclick="mostrar('.$reg->idcontratista.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-primary" onclick="activar('.$reg->idcontratista.')"><i class="fa fa-check"></i></button>',
 				);
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);

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