<?php
class Validaciones{
	public static function verificar_solo_letras($str,Bool $mayusculas=false) {
		if($mayusculas){
			return preg_match('/^[\sA-Z]{1,50}$/', $str) > 0;
		}else{
			return preg_match('/^[\sa-zA-Z]{1,50}$/', $str) > 0;
		}
	}

	public static function verificar_formato_fecha($fecha,$formato){
		//$d = DateTime::createFromFormat($formato, $fecha);
		//return $d && $d->format($formato) === $fecha;
		$correcto = false;
		switch($formato){
			case "Y-m-d":
				$correcto = ((preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9][|1-2][0-9]|3[0-1])$/',$fecha)) === 1 ? true : false);
			break;
			case "d/m/Y":
				$correcto = ((preg_match('/^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/',$fecha)) === 1 ? true : false);
			break;
			default:
				$correcto = false;
			break;
		}
		return $correcto;
	}

	public static function verificar_fecha($fecha, $formato = 'Y-m-d'){
		$resultado = false;
		$formato_correcto = self::verificar_formato_fecha($fecha, $formato);
		if($formato_correcto){
			switch($formato){
				case "Y-m-d":
					$seccion = explode("-",$fecha);
					$resultado = checkdate ( $seccion[1], $seccion[2], $seccion[0] );
				break;
				case "d/m/Y":
					$seccion = explode("-",$fecha);
					$resultado = checkdate ( $seccion[1], $seccion[0], $seccion[2] );
				break;
				default:
					$resultado = false;
				break;
			}
			return $resultado;
		}else{
			return false;
		}
	}

	public static function validar_email($email){
		if(filter_var($email, FILTER_VALIDATE_EMAIL)){
			//formato ejemplo: usuario@dominio.com.sv
			if (preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', $email) === 1 ){  
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	public static function validar_telefono($telefono){
		if(preg_match('/^[\D]?(\d{4})[\D]?(\d{4})$/', $telefono) === 1){
			return true;
		}else{
			return false;
		}
	}
}

?>