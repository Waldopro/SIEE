<?php 	

	function rellenar($tipo = 's',$cantidad = 5){
		
		$active = 0;

		if($active!=1){
			return "";
		}

		if($tipo == 's'){
			$permitted_chars = 'abcdefghijklmnopqrstuvwxyz';
		}
		else if($tipo == 'i'){
			$permitted_chars = '0123456789';
		}
		else if($tipo == 'd'){
			$dias = rand(-10000, -50000);
			$fecha = strtotime("-$dias days");
			return date('Y-m-d', $fecha);
		}	

		return substr(str_shuffle($permitted_chars),0,$cantidad);
	}		

	//echo rellenar('d');
?>		