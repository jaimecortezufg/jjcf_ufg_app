<?php
require("classes/conn.class.php");
require("classes/validaciones.inc.php");
class Persona{
	public $idpersona;
	public $nombres;
	public $apellidos;
	public $fnac;
	public $telefono;
	public $email;
	public $conexion;
	public $validacion;

	public function __construct(){
		$this->conexion = new DB();
		$this->validacion = new Validaciones();
	}

	public function setIdPersona($idpersona){
		$this->idpersona = intval($idpersona);
	}

	public function getIdPersona(){
		return intval($this->idpersona);
	}


	public function setNombres($nombres){
		$this->nombres = $nombres;
	}
	public function getNombres(){
		return $this->nombres;
	}


	public function setApellidos($apellidos){
		$this->apellidos = $apellidos;
	}

	public function getApellidos(){
		return $this->apellidos;
	}


	public function setFNac($fnac){
		$this->fnac = $fnac;
		
	}
	public function getFNac(){
		return $this->fnac;
	}


	public function setTelefono($telefono){
		$this->telefono = $telefono;
	}
	public function getTelefono(){
		return $this->telefono;
	}


	public function setEmail($email){
		$this->email = $email;
	}
	public function getEmail(){
		return $this->email;
	}


	public function obtenerPersona(int $idpersona){
		if($idpersona > 0){
			$resultado = $this->conexion->run('SELECT * FROM persona WHERE id_persona='.$idpersona);
			$array = array("mensaje"=>"Registros encontrados","valores"=>$resultado->fetch());
			return $array;
		}else{
			$array = array("mensaje"=>"No se puede ejecutar la consulta, el parámetro ID es incorrecto","valores"=>"");
			return $array;
		}
	}

	public function obtenerPersonas(){
			$resultado = $this->conexion->run('SELECT id_persona,nombres,apellidos,DATE_FORMAT(fnac,"%d/%m/%Y") as fnac,telefono,email FROM persona;');
			$array = array("mensaje"=>"Registros encontrados","data"=>$resultado->fetchAll());
			return $array;
	}

	public function nuevaPersona($nombres,$apellidos,$fnac,$telefono,$email){
		$bandera_validacion = 0;
		if($this->validacion::verificar_solo_letras(trim($nombres),true)){
			$this->setNombres(trim($nombres));
		}else{
			$bandera_validacion++;
		}
		if($this->validacion::verificar_solo_letras(trim($apellidos),true)){
			$this->setApellidos(trim($apellidos));
		}else{
			$bandera_validacion++;
		}
		if($this->validacion::verificar_fecha($fnac,"Y-m-d")){
			$this->setFNac($fnac);
		}else{
			$bandera_validacion++;
		}
		if($this->validacion::validar_telefono($telefono)){
			$this->setTelefono($telefono);
		}else{
			$bandera_validacion++;
		}
		if($this->validacion::validar_email($email)){
			$this->setEmail($email);
		}else{
			$bandera_validacion++;
		}
		
		if($bandera_validacion === 0){
			$parametros = array(
				"nom"=>$this->getNombres(),
				"ape"=>$this->getApellidos(),
				"fnac"=>$this->getFNac(),
				"tel"=>$this->getTelefono(),
				"email"=>$this->getEmail()
			);
	
			$resultado = $this->conexion->run("INSERT INTO persona (nombres,apellidos,fnac,telefono,email)VALUES(:nom,:ape,:fnac,:tel,:email);",$parametros);
			if($this->conexion->n > 0 and $this->conexion->id > 0){
				//Si se hizo un insert
				$resultado = $this->obtenerPersona($this->conexion->id);
				$array = array("mensaje"=>"Se ha registrado la persona correctamente","valores"=>$resultado);
				return $array;
			}else{
				//no se hizo insert
				$array = array("mensaje"=>"Hubo un problema al registrar la persona","valores"=>"");
				return $array;
			}
		}else{
			//Al menos una validación no fue superada
			$array = array("mensaje"=>"Un campo obligatorio no fue enviado, o el formato de los valores en los campos digitados no es correcto","valores"=>"");
			return $array;
		}
	}
}
?>