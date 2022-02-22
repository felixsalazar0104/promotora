<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class site
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($idcontratista,$cedula,$nombre_site,$id_umbrella,$centro_costo,$nombre_sy,$estado,$imagen)
	{
		$sql="INSERT INTO site (idcontratista,cedula,nombre_site,id_umbrella,centro_costo,nombre_sy,estado,imagen,condicion)
		VALUES ('$idcontratista','$cedula','$nombre_site','$id_umbrella','$centro_costo','$nombre_sy','$estado','$imagen','1')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idsite,$idcontratista,$cedula,$nombre_site,$id_umbrella,$centro_costo,$nombre_sy,$estado,$imagen)
	{
		$sql="UPDATE site SET idcontratista='$idcontratista',cedula='$cedula',nombre_site='$nombre_site',id_umbrella='$id_umbrella',centro_costo='$centro_costo', nombre_sy='$nombre_sy',estado='$estado',imagen='$imagen' WHERE idsite='$idsite'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar registros
	public function desactivar($idsite)
	{
		$sql="UPDATE site SET condicion='0' WHERE idsite='$idsite'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar registros
	public function activar($idsite)
	{
		$sql="UPDATE site SET condicion='1' WHERE idsite='$idsite'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idsite)
	{
		$sql="SELECT * FROM site WHERE idsite='$idsite'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT a.idsite,a.idcontratista,a.nombre_site,a.id_umbrella,a.centro_costo,a.imagen,a.condicion FROM site a INNER JOIN contratista c ON a.idcontratista=c.idcontratista";
		return ejecutarConsulta($sql);		
	}

	//Implementar un método para listar los registros activos
	public function listarActivos()
	{
		$sql="SELECT a.idsite,a.idcontratista,c.nombre_site as contratista,a.cedula,a.nombre_site,a.id_umbrella,a.centro_costo,a.imagen,a.condicion FROM site a INNER JOIN contratista c ON a.idcontratista=c.idcontratista WHERE a.condicion='1'";
		return ejecutarConsulta($sql);		
	}

	//Implementar un método para listar los registros activos, su último precio y el id_umbrella (vamos a unir con el último registro de la tabla detalle_ingreso)
	public function listarActivosVenta()
	{
		$sql="SELECT a.idsite,a.idcontratista,c.nombre_site as contratista,a.cedula,a.nombre_site,a.id_umbrella,(SELECT precio_venta FROM detalle_ingreso WHERE idsite=a.idsite order by iddetalle_ingreso desc limit 0,1) as precio_venta,a.centro_costo,a.imagen,a.condicion FROM site a INNER JOIN contratista c ON a.idcontratista=c.idcontratista WHERE a.condicion='1'";
		return ejecutarConsulta($sql);		
	}
}

?>