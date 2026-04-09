<?php 

$errores = [];

function nombre($string,$campo){
	$validos = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ"; 
	$validez = 1; 
	for ($i = 0 ; $i <= mb_strlen($string)-1 ; $i++ ) { 
		if (strpos($validos,substr($string , $i , 1))===false) {
			$validez=0;
			die('error de formato para el campo '.$campo);
		} 	
	} 		
	return $validez; 
}
function nombres($string,$campo){
	$error = 'error de formato para el campo '.$campo;
	$validos = " abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ"; 
	$validez=1; 
	for ($i = 0 ; $i <= mb_strlen($string) - 1 ; $i++) { 
		if ( strpos( $validos , substr( $string , $i , 1 ))===false) {
			$validez=0;
			die($error);
		} 	
	} 
	//que no tenga 2 espacios seguidos
	if(preg_match("/[  ]{2}/i",$string)){	
		die($error);
	}		
	return $validez;
}	
function fecha($string,$campo){
	
	//$string = date($string,'d-m-Y');

	$error = 'error de formato de fecha en el campo '.$campo;
	$dia = substr($string,8,2);
	$mes = substr($string,5,2);
	$ano = substr($string,0,4);
	
	if (!is_numeric($dia)){
		die($error);
	}
	else if(!is_numeric($mes)){
		die($error);
	}
	else if (!is_numeric($ano)) {
		die($error);		
	}
}

function sexo($string,$campo){
	if($string!='Masculino' && $string!='Femenino'){
		die('error de sexo en el campo '.$campo);
		//return 0;
	}
	//return 1;
}
function entero($string,$campo){
	$validos = "0987654321"; 
	$validez = 1; 
	for ($i = 0 ; $i <= mb_strlen($string)-1 ; $i++ ) { 
		if (strpos($validos,substr($string , $i , 1))===false) {
			$validez=0;
			die('error el campo '.$campo.' no es entero');
		} 	
	} 		
	return $validez; 
}
function numerico($string,$campo){
	if (!is_numeric($string)) {
		die('error el campo '.$campo.' no es un numero');
	}	
}		
function ano($string,$campo){
	if( $string < 1000 ){
		die('año incorrecto en al campo '.$campo);
	}
}
function enum($arr,$string,$arreglo){
	
	$campo = dameCampo($arr,$string);

	$validez = 0;
	
	foreach ($arreglo as $key => $value) {
		if($string == $value){
			$validez = 1;
		}
	}
	if ($validez!=1) {
		die('error el campo '.$campo.' no esta en la lista');		
	}
	return $validez;
}
function maximo($string,$maximo,$campo){
	if (!empty($string)) {
		if( mb_strlen($string) > $maximo ){
			die('error el campo '.$campo.' es de maximo '.$maximo.' caracteres');
			return 0;
		}
		return 1;
	}
}
function minimo($string,$minimo,$campo){
	if (!empty($string)) {
		if( mb_strlen($string) < $minimo ){
			die('error el campo '.$campo.' es de minimo '.$minimo.' caracteres');
			return 0;
		}
		return 1;	
	}
}	
function telefono($string,$campo){
	$error = 'error de formato de telefono en el campo '.$campo;
	$validez = 1;
	
	if( substr($string,0,1) != '0' ){
		$validez = 0;
		die($error);
	}
	if(!is_numeric($string)){
		$validez = 0;
		die($error);

	}
	if (mb_strlen($string)!=11) {
		$validez = 0;
		die($error);
	}

	return $validez;
}
function requerido($string,$campo){
	$validez = 1;
	if (empty($string)) {
		$validez = 0;
		die('el campo '.$campo.' es requerido');
	}
	return $validez;
}
function serial($string,$campo){
	$validos = "ABCDEFGHIJKLMNÑOPQRSTUVWXYZ0987654321"; 
	$validez = 1; 
	for ($i = 0 ; $i <= mb_strlen($string)-1 ; $i++ ) { 
		if (strpos($validos,substr($string , $i , 1))===false) {
			$validez=0;
			die('error serial no valido en el campo '.$campo);
		} 		
	}		
}			
			
function validar($arr,$n,$opciones){
	
	$campo = dameCampo($arr,$n);
	
	foreach ($opciones as $key => $value) {
		convinar($n,$value,$campo);
	}
}			
			
function convinar($n,$value,$campo){
		
	$p = damePosicion($value);
		
	if( !empty($p) ){ 	
		
		$n1 = substr($value,0,$p);
		$n2 = substr($value,$p+1,strlen($value)-$p);

		//die($n);

		$n1($n,$n2,$campo);
	}	
	else{
		$value($n,$campo);
	}	
}

function damePosicion($string){
	$validos = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ";
	for ($i = 0 ; $i <= mb_strlen($string)-1 ; $i++ ) { 
		if (strpos($validos,substr($string , $i , 1))===false) {
			return $i;
		} 	
	} 		
}
function dameCampo($arreglo,$campo){

	foreach ($arreglo as $key => $value) {
		if( $campo == $value ){
			return $key;
		}
	}
}
//+++++++++++++++validaciones DB+++++++++++++++++//
	
function entradaDuplicada($campo,$valor,$tabla,$con){
	$sql = 'select '.$campo.' from '.$tabla.'';
	$r = $con->query($sql);
	while($row = $r->fetch_assoc()){
		if($row[$campo]==$valor){
			die('entrada duplicada para el campo '.$campo);
		}
	}
}		
	
//$est = [ 'estudiante' => ['cedula'=>'25996622','nombre'=>'angel'] ];

//$estu = $est['estudiante'];

//echo var_dump($estu['cedula']);

//enum($est,$estu['nombre'],['!angel']);
//echo array_keys([$estu['cedula']])[0];

//dameCampo($est['estudiante'],$estu['nombre']);
//validar('holap mijo',['maximo:2']);
	
//++++++++++++++++++Validacion de entidades++++++++++++++


function validarEstudiante($est){

	validar($est,$est['cedula_e'],['entero','requerido','minimo:8','maximo:9']);	
		
	validar($est,$est['apellidos_e'],['requerido','nombres','maximo:50']);	
	validar($est,$est['nombres_e'],['requerido','nombres','maximo:50']);	
	validar($est,$est['fecha_nac'],['requerido','fecha']);	
	validar($est,$est['sexo'],['sexo']);	

	validar($est,$est['peso'],['numerico','maximo:3']);	
	enum($est,$est['talla'],['SS','S','M','L']);	
	validar($est,$est['lugar_nac'],['requerido','nombres','maximo:50']);	
	validar($est,$est['estado'],['requerido','nombres','maximo:50']);	
	validar($est,$est['municipio'],['requerido','nombres','maximo:50']);	
	validar($est,$est['parroquia'],['requerido','nombres','maximo:50']);	
	validar($est,$est['direccion_e'],['requerido','nombres','maximo:100']);

	validar($est,$est['tel_e'],['requerido','telefono']);	
	enum($est,$est['posee_can'],['Si','No']);
	validar($est,$est['serial_can'],['serial','maximo:50','minimo:13']);
	enum($est,$est['becado'],['Si','No']);
	enum($est,$est['copias_ci'],['Si','No']);
	enum($est,$est['partida_nac_co'],['Si','No']);
	enum($est,$est['historia_esc'],['Si','No']);
	enum($est,$est['cert_prom'],['Si','No']);
	enum($est,$est['tarj_vac'],['Si','No']);
	enum($est,$est['cons_conduc'],['Si','No']);
	enum($est,$est['cons_retiro'],['Si','No']);
	enum($est,$est['cert_notas'],['Si','No']);
}
function validarRepresentante($rep){
	validar($rep,$rep['cedula_r'],['requerido','entero','maximo:10']);
	validar($rep,$rep['apellidos'],['requerido','nombres','maximo:50']);
	validar($rep,$rep['nombres'],['requerido','nombres','maximo:50']);
	validar($rep,$rep['fecha_nacimiento'],['requerido','fecha']);
	validar($rep,$rep['edad'],['entero','maximo:2']);
	enum($rep,$rep['ocupacion'],['Estudiante','Empleado','Obrero','Desempleado','Otro']);
	validar($rep,$rep['tel_r'],['requerido','telefono']);
	validar($rep,$rep['direccion_r'],['requerido','nombres','maximo:100']);
	enum($rep,$rep['parentesco'],['Padre','Madre','Tio','Hermano','Otro']);
	validar($rep,$rep['ingreso_mes'],['requerido','entero','maximo:20']);
	enum($rep,$rep['carnet_patria'],['Si','No','']);
	validar($rep,$rep['ser_car'],['entero','maximo:10','minimo:10']);
	validar($rep,$rep['codigo_car'],['entero','maximo:10','minimo:10']);
	validar($rep,$rep['entidad_ban'],['nombres','maximo:50']);
	enum($rep,$rep['tipo_cta'],['Corriente','Ahorro','No posee','']);
	validar($rep,$rep['num_cuenta'],['entero','maximo:20','minimo:18']);
}

function validarPadreMadre($pad){
	validar($pad,$pad['cedula_p'],['requerido','entero','maximo:10']);
	validar($pad,$pad['apellidos_p'],['requerido','nombres','maximo:50']);
	validar($pad,$pad['nombres_p'],['requerido','nombres','maximo:50']);
	validar($pad,$pad['fecha_nac_p'],['requerido','fecha']);
	validar($pad,$pad['edad'],['entero','maximo:2']);
	enum($pad,$pad['ocupacion_p'],['Estudiante','Empleado','Obrero','Desempleado','Otro']);
	validar($pad,$pad['tel_p'],['requerido','telefono']);
	validar($pad,$pad['direccion_p'],['requerido','nombres','maximo:100']);
	enum($pad,$pad['parentesco'],['Padre','Madre']);
}


?>	