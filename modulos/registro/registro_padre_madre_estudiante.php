<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';
include "../conexion/conndb.php";
include "../validacion.php";
include "insertar.php";
// Iniciar sesión

// Verificar si el usuario ha iniciado sesión y si su rol es de "usuario"
include('../verificar_session.php');
        
$sql_pad = 'select id_padre_madre id ,cedula_p ced,nombres_p nom ,apellidos_p ape from padre_madre';
            
    
$result_pad = $conexion->query($sql_pad);
    
$representante = $_POST['representante'];
    
if(!isset($representante['carnet_patria'])){
    $representante['carnet_patria'] = '';
}       

foreach ($_POST as $key => $value) {
    //echo $key.'-'.$value.'<br>';
}

if ($_POST['repre'] != '1') {

    validarRepresentante($representante);
    entradaDuplicada('cedula_r',$representante['cedula_r'],'representante',$conexion);

    //insertar representante

    $_SESSION['representante_estudiante'] = insertarRepresentante($representante,$conexion);

    $parentesco = $representante['parentesco'];
        
    if ($parentesco == 'Padre' or $parentesco == 'Madre') {
        
        $_SESSION['padre_madre_estudiante'] = insertarPadreMadreConDatosRepresentante($representante,$conexion);

        //echo $_SESSION['padre_madre_estudiante'].'-'.$_SESSION['representante_estudiante'];

        header('location:registro_estudiante_estudiante.php');   
    }

    //echo $_SESSION['representante_estudiante'];
}   
else{
    $_SESSION['representante_estudiante'] = $_POST['id_representante'];
}    
    
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
    
    <center><h1 class="h1">Registrar Padre o Madre del estudiante</h1></center>
    <form action="registro_estudiante_estudiante.php" method="post">
        
        <input type="hidden" name="representante_estudiante" value="<?php  ?>" >

        <div class="contenedor-formularios">
            <div class="formulario">
                <h1>Padre o Madre</h1>
                <div class="flex">
                    <label class="flex">Registrado
                        <input type="radio" name="padre" class="padre_madre"  value="1" id="padre_madre_registrado_radio">
                    </label>
                    <label class="flex">No Registrado
                        <input type="radio" name="padre" class="padre_madre" id="padre_madre_no_registrado_radio" value="0">
                    </label>
                </div>
                <div id="padre_madre_registrado">
                    <label> Padre o madre
                        <select name="id_padre_madre">
                            <option value="">--Seleccione--</option>
                            
                            <?php 
                            while($r = $result_pad->fetch_assoc()) {
                                    echo "<option value='".$r['id']."'>";
                                    echo $r['ced'].'-'.$r['nom'].' '.$r['ape'];
                                    echo "</option>";
                            }
                            
                            ?> 
                        </select>
                    </label>
                </div>
                <div id="padre_madre_no_registrado">
                    <label> Cedula<span style="color: red;">*</span>
                        <input type="number" min="0" step='1' max="999999999" name="padre_madre[cedula_p]" placeholder="ej. 25996622" >
                    </label>
                    <label> Apellidos<span style="color: red;">*</span>
                        <input type="text" minlength="3" maxlength="50" name="padre_madre[apellidos_p]" placeholder="ej. rodriguez">
                    </label>
                    <label> Nombres<span style="color: red;">*</span>
                        <input type="text" minlength="3" maxlength="50" name="padre_madre[nombres_p]" placeholder="ej. angel">
                    </label>
                    <label> Fecha de nacimiento<span style="color: red;">*</span>
                        <input type="date" id="fecha_nac" name="padre_madre[fecha_nac_p]"  >
                    </label>
                    <label for="edad">Edad:</label>
<input type="number" id="edad" name="padre_madre[edad]" readonly>
                    <label> Ocupacion<span style="color: red;">*</span>
                        <select name="padre_madre[ocupacion_p]">
                            <option value="" selected>--Selecione--</option>
                            <option value="Estudiante">Estudiante</option>
                            <option value="Obrero">Obrero</option>
                            <option value="Empleado">Empleado</option>
                            <option value="Desempleado">Desempleado</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </label>
                    <label> Telefono<span style="color: red;">*</span>
                        <input type="tel" id="tel_p" name="padre_madre[tel_p]" max="999999999999" min="0" step="1" placeholder="ej. 04148160712">
                    </label>
                    <label> Direccion<span style="color: red;">*</span>
                        <input type="text" maxlength="100" name="padre_madre[direccion_p]" minlength="3" placeholder="ej. calle saman">
                    </label>
                    <label> Parentesco<span style="color: red;">*</span>
                        <select name="padre_madre[parentesco]" >
                            <option value="" selected>--Selecione--</option>
                            <option value="Madre">Madre</option>
                            <option value="Padre">Padre</option>
                            
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
        
<script src="../../node_modules/jquery/dist/jquery.min.js"></script>
<script src="../../lib/venezuela.js"></script>

<script>
    document.getElementById('tel_p').addEventListener('input', function (e) {
  e.target.value = e.target.value.replace(/[^\d]/g, '');
});
 
    //mostrar o no los campos del representante    
    
    $(document).ready(function(){   
        $('#padre_madre_registrado_radio').click();
        $('#padre_madre_no_registrado').hide();

    }); 

    $('.padre_madre').on('change',function(){

        if($('.padre_madre:checked').val()=='1'){
            
            $('#padre_madre_registrado').show(500);

            $('#padre_madre_no_registrado').hide(500);
        }
        else{
            
            $('#padre_madre_registrado').hide(500);
            $('#padre_madre_no_registrado').show(500);
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
    </script>
</html>
        