<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Contratista
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($nombre,$nit,$regional,$departamento1,$departamento2,$departamento3,$imagen)
	{
		$sql="INSERT INTO contratista(nombre,nit,regional,departamento1,departamento2,departamento3,imagen,condicion)
		VALUES ('$nombre','$nit','$regional','$departamento1','$departamento2','$departamento3','$imagen','1')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idcontratista,$nombre,$nit,$regional,$departamento1,$departamento2,$departamento3,$imagen)
	{
		$sql="UPDATE contratista SET nombre='$nombre',nit='$nit',regional='$regional',departamento1='$departamento1', departamento2='$departamento2', departamento3='$departamento3', imagen='$imagen' WHERE idcontratista='$idcontratista'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($idcontratista)
	{
		$sql="UPDATE contratista SET condicion='0' WHERE idcontratista='$idcontratista'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idcontratista)
	{
		$sql="UPDATE contratista SET condicion='1' WHERE idcontratista='$idcontratista'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idcontratista)
	{
		$sql="SELECT * FROM contratista WHERE idcontratista='$idcontratista'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM contratista";
		return ejecutarConsulta($sql);		
	}
	//Implementar un método para listar los registros y mostrar en el select
	public function select()
	{
		$sql="SELECT * FROM contratista where condicion=1";
		return ejecutarConsulta($sql);		
	}
}

?>