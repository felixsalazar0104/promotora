<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Personal
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($idcontratista,$cedula,$nombre,$celular,$eps,$arl,$cargo,$actividad,$correo,$imagen)
	{
		$sql="INSERT INTO personal (idcontratista,cedula,nombre,celular,eps,arl,cargo,actividad,correo,imagen,condicion)
		VALUES ('$idcontratista','$cedula','$nombre','$celular','$eps','$arl','$cargo','$actividad','$correo','$imagen','1')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idpersonal,$idcontratista,$cedula,$nombre,$celular,$eps,$arl,$cargo,$actividad,$correo,$imagen)
	{
		$sql="UPDATE personal SET idcontratista='$idcontratista',cedula='$cedula',nombre='$nombre',celular='$celular',eps='$eps', arl='$arl',cargo='$cargo', actividad='$actividad', correo='$correo', imagen='$imagen' WHERE idpersonal='$idpersonal'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar registros
	public function desactivar($idpersonal)
	{
		$sql="UPDATE personal SET condicion='0' WHERE idpersonal='$idpersonal'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar registros
	public function activar($idpersonal)
	{
		$sql="UPDATE personal SET condicion='1' WHERE idpersonal='$idpersonal'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idpersonal)
	{
		$sql="SELECT * FROM personal WHERE idpersonal='$idpersonal'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT a.idpersonal,a.idcontratista,c.nombre as contratista,a.cedula,a.nombre,a.celular,a.eps,a.imagen,a.condicion FROM personal a INNER JOIN contratista c ON a.idcontratista=c.idcontratista";
		return ejecutarConsulta($sql);		
	}

	//Implementar un método para listar los registros activos
	public function listarActivos()
	{
		$sql="SELECT a.idpersonal,a.idcontratista,c.nombre as contratista,a.cedula,a.nombre,a.celular,a.eps,a.imagen,a.condicion FROM personal a INNER JOIN contratista c ON a.idcontratista=c.idcontratista WHERE a.condicion='1'";
		return ejecutarConsulta($sql);		
	}

	//Implementar un método para listar los registros activos, su último precio y el celular (vamos a unir con el último registro de la tabla detalle_ingreso)
	public function listarActivosVenta()
	{
		$sql="SELECT a.idpersonal,a.idcontratista,c.nombre as contratista,a.cedula,a.nombre,a.celular,(SELECT precio_venta FROM detalle_ingreso WHERE idpersonal=a.idpersonal order by iddetalle_ingreso desc limit 0,1) as precio_venta,a.eps,a.imagen,a.condicion FROM personal a INNER JOIN contratista c ON a.idcontratista=c.idcontratista WHERE a.condicion='1'";
		return ejecutarConsulta($sql);		
	}
}

?>