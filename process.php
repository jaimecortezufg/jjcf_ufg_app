<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header('Access-Control-Max-Age: 1000');
header("Access-Control-Allow-Credentials: true");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
require("classes/persona.class.php");
$Persona = new Persona();
if($_SERVER["REQUEST_METHOD"]==="GET"){
	$tipo_peticion = ((isset($_GET["t"])) ? (($_GET["t"]!="") ? : null) : null);
	switch($tipo_peticion){
		case "selectAll":
			$resultado = $Persona->obtenerPersonas();
		break;
		case "select":
			$id = ((isset($_GET["id"])) ? ( ( $_GET["id"]!="") ? intval($_GET["id"]) : 0) : 0);
			if($id > 0){
				$resultado = $Persona->obtenerPersona($id);
			}else{
				header('HTTP/1.1 412 Precondition Failed');
				$resultado = array("mensaje"=>"El parámetro de ID no es correcto", "valores"=>"");
			}
		break;
		case "insert":
			$resultado = $Persona->nuevaPersona("JAIME JEOVANNY","CORTEZ FLORES","1985-09-02","7777-7777","jaimecortez@ufg.edu.sv");
		break;
		default:
			header('HTTP/1.1 403 Forbidden');
			$resultado = array("mensaje"=>"Debe indicar el tipo de procesamiento de datos a realizar", "valores"=>"");
		break;
	}
}elseif($_SERVER["REQUEST_METHOD"]=="POST"){
	$resultado = $Persona->nuevaPersona($_POST["n"],$_POST["a"],$_POST["f"],$_POST["t"],$_POST["e"]);
}

header("Content-Type: Application/json");
echo(json_encode($resultado));
?>