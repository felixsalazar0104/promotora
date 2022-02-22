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
require_once "../modelos/personal.php";

$personal=new personal();

$idpersonal=isset($_POST["idpersonal"])? limpiarCadena($_POST["idpersonal"]):"";
$idcontratista=isset($_POST["idcontratista"])? limpiarCadena($_POST["idcontratista"]):"";
$cedula=isset($_POST["cedula"])? limpiarCadena($_POST["cedula"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$celular=isset($_POST["celular"])? limpiarCadena($_POST["celular"]):"";
$eps=isset($_POST["eps"])? limpiarCadena($_POST["eps"]):"";
$arl=isset($_POST["arl"])? limpiarCadena($_POST["arl"]):""; 
$cargo=isset($_POST["cargo"])? limpiarCadena($_POST["cargo"]):""; 
$actividad=isset($_POST["actividad"])? limpiarCadena($_POST["actividad"]):""; 
$correo=isset($_POST["correo"])? limpiarCadena($_POST["correo"]):""; 
$correo=isset($_POST["correo"])? limpiarCadena($_POST["correo"]):""; 
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
				move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/personals/" . $imagen);
			}
		}
		if (empty($idpersonal)){
			$rspta=$personal->insertar($idcontratista,$cedula,$nombre,$celular,$eps,$arl,$cargo,$actividad,$correo,$imagen);
			echo $rspta ? "Persona registrado" : "Persona no se pudo registrar";
		}
		else {
			$rspta=$personal->editar($idpersonal,$idcontratista,$cedula,$nombre,$celular,$eps,$arl,$cargo,$actividad,$correo,$imagen);
			echo $rspta ? "Persona actualizado" : "Persona no se pudo actualizar";
		}
	break;

	case 'desactivar':
		$rspta=$personal->desactivar($idpersonal);
 		echo $rspta ? "Persona Desactivado" : "Persona no se puede desactivar";
	break;

	case 'activar':
		$rspta=$personal->activar($idpersonal);
 		echo $rspta ? "Persona activado" : "Persona no se puede activar";
	break;

	case 'mostrar':
		$rspta=$personal->mostrar($idpersonal);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listar':
		$rspta=$personal->listar();
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				
				"0"=>$reg->contratista,
				"1"=>"<img src='../files/personals/".$reg->imagen."' height='70px' width='70px' >",
				"2"=>$reg->nombre,
 				"3"=>$reg->cedula,
 				"4"=>$reg->celular,
 				"5"=>($reg->condicion)?'<span class="label bg-green">Activo</span>':
 				'<span class="label bg-red">Inactivo</span>',
				"6"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idpersonal.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-danger" onclick="desactivar('.$reg->idpersonal.')"><i class="fa fa-close"></i></button>':
 					'<button class="btn btn-warning" onclick="mostrar('.$reg->idpersonal.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-primary" onclick="activar('.$reg->idpersonal.')"><i class="fa fa-check"></i></button>',
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
					echo '<option value=' . $reg->idcontratista . '>' . $reg->nombre . '</option>';
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