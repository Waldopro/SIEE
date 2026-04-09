<?php
include "../conexion/conndb.php";
// Iniciar sesión
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';
// Verificar si el usuario ha iniciado sesión y si su rol es de "usuario"
include('../verificar_session.php');
// Verificar si hay datos del representante almacenados en la sesión
if (isset($_SESSION['representante'])) {
    // Si hay datos de representante en la sesión, extraerlos para mostrar en el formulario
    $representante = $_SESSION['representante'];
    $cedula_r = $representante['cedula_r'];
    $apellidos = $representante['apellidos'];
    $nombres = $representante['nombres'];
}       
        
$sql_representantes = 'select id_representante id ,cedula_r ced,nombres nom ,apellidos ape from representante';

$sql_pad = 'select id_padre_madre id ,cedula_p ced,nombres_p nom ,apellidos_p ape from padre_madre';
        
$result_repre = $conexion->query($sql_representantes);

$result_pad = $conexion->query($sql_pad);

?>      
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../../css/fontawesome.css">
    <link rel="stylesheet" type="text/css" href="../../css/procesare.css">
    <link rel="stylesheet" type="text/css" href="../../css/inicio.css">
    <link rel="stylesheet" type="text/css" href="../../css/registro_e.css">
    <link rel="stylesheet" href="../../css/footer.css">
    <title>Registro estudiante</title>
</head>

<body oncontextmenu="return false;">

    <?php include('../menu.php') ?>
    
    <center><h1 class="h1">Registrar representante</h1></center>

    <form id='form' action="registro_padre_madre_estudiante.php" method="POST">
        <div class="contenedor-formularios">
            <div class="formulario">
                <h1 class="animate__animated animate__backInLeft">Representante</h1>
                <div class="flex">
                    <label class="flex">Registrado
                        <input type="radio" name="repre" class="representante"  value="1" id="representante_registrado_radio">
                    </label>
                    <label class="flex">No Registrado
                        <input type="radio" name="repre" class="representante" id="representante_no_registrado_radio" value="0">
                    </label>
                </div>
                <div id="representante_registrado">
                    <label> Cedula<span style="color: red;">*</span>
                        <input type="number" min="0" step='1' max="999999999" name="representante[cedula_r]" placeholder='Ej: 25996633'  >
                    </label>
                    <label> Apellidos<span style="color: red;">*</span>
                        <input type="text" minlength="3" maxlength="50" name="representante[apellidos]" placeholder="Ej. Rodriguez Marcano">
                    </label>
                    <label> Nombres<span style="color: red;">*</span>
                        <input type="text" minlength="3" maxlength="50" name="representante[nombres]" placeholder="Ej. Angel Manuel" >
                    </label>
                    <label> Fecha de nacimiento<span style="color: red;">*</span>
                        <input type="date" id="fecha_nac" name="representante[fecha_nacimiento]">
                    </label>
                    <label for="edad">Edad:</label>
<input type="number" id="edad" name="representante[edad]" readonly>
                    <label> Ocupacion<span style="color: red;">*</span>
                        <select name="representante[ocupacion]">
                            <option value="" selected>--Selecione--</option>
                            <option value="Estudiante" >Estudiante</option>
                            <option value="Obrero">Obrero</option>
                            <option value="Empleado">Empleado</option>
                            <option value="Desempleado">Desempleado</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </label>
                    <label> Telefono
                    <input type="tel" id="tel_r" name="representante[tel_r]" pattern="[0-9]*" max="999999999999" min="0" step="1" title="Solo se permiten números." placeholder="Ej. 04148160712">
                    </label>
                    <label> Direccion<span style="color: red;">*</span>
                        <input type="text" maxlength="100" name="representante[direccion_r]" minlength="3" placeholder="Ej. calle saman">
                    </label>
                    <label> Parentesco<span style="color: red;">*</span>
                        <select name="representante[parentesco]">
                            <option value="" selected>--Selecione--</option>
                            <option value="Madre">Madre</option>
                            <option value="Padre">Padre</option>
                            <option value="Tio" >Tio</option>
                            <option value="Hermano">Hermano</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </label>
                    <label> Ingreso Mensual<span style="color: red;">*</span>
                        <input type="number" name="representante[ingreso_mes]" minlength="3" maxlength="20" placeholder="Ej. 500bs">
                    </label>
                    <p>Tiene Carnet de la Patria?</p>
                    <div class="flex">
                        <label class="flex"> Si
                            <input type="radio" value="Si" name="representante[carnet_patria]" id="tiene_carnet" class="input_carnet" >
                        </label>
                        <label class="flex"> No
                            <input type="radio" value="No" name="representante[carnet_patria]" id='no_tiene_carnet' class="input_carnet">
                        </label> 
                    </div>
                    <div id="div_patria">
                        <label for="ser_car"> Serial del Carnet</label>
                            <input type="number" name="representante[ser_car]" maxlength="24" id="ser_car">
                        <label> Codigo del Carnet
                            <input type="number" name="representante[codigo_car]" maxlength="24">
                        </label>    
                    </div>
                    
                    <p>Tiene cuenta de Banco?</p>
                    <div class="flex">
                        <label class="flex"> Si
                            <input type="radio" value="1" name="cuenta_banco" class='input_cuenta' id="tiene_cuenta" >
                        </label>
                        <label class="flex"> No
                            <input type="radio" value="0" name="cuenta_banco" class='input_cuenta'id="no_tiene_cuenta">
                        </label>
                    </div>   
                    <div id="div_cuenta">
                        <label> Entidad Bancaria
                            <input type="text" name="representante[entidad_ban]" maxlength="50">
                        </label>
                        <label>Tipo de cuenta
                            <select name="representante[tipo_cta]" >
                                <option value='' selected>--Seleccione</option>
                                <option value='Corriente'>Corriente</option>
                                <option value='Ahorro'>Ahorro</option>
                            </select>
                        </label>
                        <label> Numero de cuenta
                            <input type="text" name="representante[num_cuenta]">
                        </label>    
                    </div> 
                </div>
                <div id="representante_no_registrado">
                    <label> Representante
                        <select name="id_representante">
                            <option value="">--Seleccione--</option>
                            
                            <?php 
                            while($r = $result_repre->fetch_assoc()) {
                                    echo "<option value='".$r['id']."'>";
                                    echo $r['ced'].'-'.$r['nom'].' '.$r['ape'];
                                    echo "</option>";
                            }

                            ?> 
                        </select>
                    </label>
                </div>
                <button type="submit" class="btn-primary" value="Guardar"><img src="../../img/guardar.png" alt="Guardar" style="width: 40px; height: 50px;"> Guardar</button>
            </div>            
        </div>
    </form>
    <div class="footer">
    <p>Proyecto <?php echo date('Y'); ?> UPTP CAJIGAL &copy; Todos los derechos reservados</p>
</div>
</body>
        
</html>
        
<script src="../../node_modules/jquery/dist/jquery.min.js"></script>
<script src="../../lib/venezuela.js"></script>

<script>
document.getElementById('tel_r').addEventListener('input', function (e) {
  e.target.value = e.target.value.replace(/[^\d]/g, '');
});
    //mostrar o no los campos del representante    
    
    $(document).ready(function(){   
        $('#representante_registrado_radio').click();
        $('#div_cuenta').hide();
        $('#div_patria').hide();
        $('#representante_registrado').hide();

    }); 

    $('.representante').on('change',function(){

        if($('.representante:checked').val()=='1'){
            
            $('#representante_registrado').hide(500);

            $('#representante_no_registrado').show(500);
        }
        else{
            
            $('#representante_registrado').show(500);
            $('#representante_no_registrado').hide(500);
        }   
    });
    document.getElementById('fecha_nac').addEventListener('change', function() {
    var fechaNac = new Date(this.value);
    var hoy = new Date();
    var edad = hoy.getFullYear() - fechaNac.getFullYear();
    var m = hoy.getMonth() - fechaNac.getMonth();
    if (m < 0 || (m === 0 && hoy.getDate() < fechaNac.getDate())) {
        edad--;
    }
    document.getElementById('edad').value = edad;
}); 
    //mostrar o no los campos de las cuentas de banco 
    $('.input_cuenta').on('change',function(){
        if($('.input_cuenta:checked').val()=='1'){
            $('#div_cuenta').show(1000);
        }
        else{
            $('#div_cuenta').hide(1000);
        }
    });     
    //mostrar o no los campos de carnet de la patria
    $('.input_carnet').on('change',function(){
        if ($('.input_carnet:checked').val()=='Si') {
            $('#div_patria').show(1000);
        }
        else{
            $('#div_patria').hide(1000);
        }
    });     
   
</script>