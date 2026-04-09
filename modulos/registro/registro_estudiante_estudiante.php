<?php
include "../conexion/conndb.php";
include "../validacion.php";
include "insertar.php";
include "../rellenar/rellenar.php";
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';
// Iniciar sesión
// Verificar si el usuario ha iniciado sesión y si su rol es de "usuario"
include('../verificar_session.php');
    
if (isset($_POST['padre_madre'])) {
    
    $padre_madre = $_POST['padre_madre'];
   
    foreach ($_POST['padre_madre'] as $key => $value) {
        //echo $key.'-'.$value.'<br>';
    }   
}    
if(isset($_POST['padre'])){
    
    if ($_POST['padre'] != '1') {
            
        validarPadreMadre($padre_madre);
        if (isset($representante) && is_array($representante) && isset($representante['cedula_r'])) {
            entradaDuplicada('cedula_r', $representante['cedula_r'], 'representante', $conexion);
        } else {
            // Manejar el caso en que $representante no está definido, no es un array o no contiene 'cedula_r'
        }
        //insertar representante

        $_SESSION['padre_madre_estudiante'] = insertarPadreMadre($padre_madre,$conexion);

        //echo $_SESSION['representante_estudiante'];
    }   
    else{
        $_SESSION['padre_madre_estudiante'] = $_POST['id_padre_madre'];
    }    
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
    
    <center><h1 class="h1">Registrar estudiante</h1></center>

    <form id='form' action="guardar/estudiante_estudiante.php" method="POST" enctype="multipart/form-data">
        <div class="contenedor-formularios">
            <div class="formulario">
                <h1 class="animate__animated animate__backInLeft">Datos del Alumno</h1>
                <?php //echo $_SESSION['padre_madre_estudiante'] ?><br>
                <?php //echo $_SESSION['representante_estudiante'] ?><br>
                <label for="cedula_e" id="cedula_label">Cédula:<span style="color: red;">*</span></label>
                <input type="number" id="cedula_e" maxlength="10" pattern="[0-9]+" placeholder="Ej: 34654342" name="estudiante[cedula_e]" value="<?php echo rellenar('i',8);?>" required >
                <label for="apellidos_e">Apellidos:<span style="color: red;">*</span></label>
                <input type="text" id="apellidos_e" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+" name="estudiante[apellidos_e]" placeholder="Ej: Fernandez Cazorla" maxlength="50" value="<?php echo rellenar();?>"required>

                <label for="nombres_e">Nombres:<span style="color: red;">*</span></label>
                <input type="text" id="nombres_e" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+" name="estudiante[nombres_e]" placeholder="Ej: Julio Jesus" maxlength="50" value="<?php echo rellenar();?>"required>

                <label for="fecha_nac">Fecha de Nacimiento:<span style="color: red;">*</span></label>
                <input type="date" id="fecha_nac" name="estudiante[fecha_nac]" value="<?php echo rellenar('d');?>"required>
                
                <label for="edad">Edad:</label>
<input type="number" id="edad" name="estudiante[edad_e]" readonly>

                <label for="sexo">Sexo:<span style="color: red;">*</span></label>
                <select id="sexo" name="estudiante[sexo]" required>
                    <option value="">--Seleccione--</option>
                    <option value="Masculino" selected>Masculino</option>
                    <option value="Femenino">Femenino</option>
                </select><br>

                <label for="estado">Estado:<span style="color: red;">*</span></label>
                <select name="estudiante[estado]" id="estado"required>
                    
                </select>

                <label for="municipio">Selecciona un municipio:<span style="color: red;">*</span></label>
                <select name="estudiante[municipio]" id="municipio"required>
                    <option value="">Selecciona un municipio</option>
                </select>

                <label for="parroquia">Selecciona una parroquia:<span style="color: red;">*</span></label>
                <select name="estudiante[parroquia]" id="parroquia"required>
                    <option value="">Selecciona una parroquia</option>
                </select>

                <label for="lugar_nac">Lugar de nacimiento:<span style="color: red;">*</span></label>
                <input type="text" name="estudiante[lugar_nac]" id="lugar_nac" placeholder="Ej. Yaguaraparo" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+" maxlength="50" value="<?php echo rellenar();?>" required><br>

                <label for="direccion_e">Dirección:<span style="color: red;">*</span></label>
                <input type="text" id="direccion_e" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+" placeholder="Ej. Calle Bolivar" name="estudiante[direccion_e]" maxlength="100" value="<?php echo rellenar();?>" required><br>
            </div>

            <div class="formulario">
                <h1 class="animate__animated animate__backInLeft">Información Adicional</h1>
                    
                <label for="tel_e">Teléfono:<span style="color: red;">*</span></label>
                <input type="text" id="tel_e" name="estudiante[tel_e]" maxlength="11" minlength="11" pattern="[0-9]*" placeholder="Ej: 04124427654" value="<?php echo rellenar('i',11);?>" required><br>

                <label for="peso">Peso (kg):<span style="color: red;">*</span></label>
                <input type="number" id="peso" min="1" step="0.1" max="200" name="estudiante[peso]" placeholder="Ej. 30" value="<?php echo rellenar('i',2);?>" required>

                <label for="talla">Talla:<span style="color: red;">*</span></label>
                <select name="estudiante[talla]" id="talla"required>
                    <option value="">--Seleccione--</option>
                    <option value="SS" selected>SS</option>
                    <option value="S">S</option>
                    <option value="M">M</option>
                    <option value="L">L</option>
                </select>

                <label for="posee_can">Posee Canaima:</label>
                <select id="posee_can" name="estudiante[posee_can]" >
                    <option value="">--Seleccione--</option>
                    <option value="Si" selected>Si</option>
                    <option value="No">No</option>
                </select>

                <label for="serial_can">Serial de la Canaima:</label>
                <input type="text" name="estudiante[serial_can]" id="serial_can" placeholder="Ej. ZJW20039ELK21" pattern="[A-Za-z0-9]+" maxlength="18" value="<?php echo rellenar('i',18);?>" >

                <span id="no_canaima_text" style="display: none;">No posee Canaima</span>

                <label for="becado">Es becado:<span style="color: red;">*</span></label>
                <select id="becado" name="estudiante[becado]" required>
                    <option value="">--Seleccione--</option>
                    <option value="Si" selected>Si</option>
                    <option value="No">No</option>
                </select><br>
                <label for="foto" class="custom-label">
        <img src="../../img/foto.png" alt="Foto" class="foto-icon" style="width: 40px; height: 50px;">
        Subir foto
    </label>
                <input type="file" name="foto" id="foto" accept="image/*" onchange="previewImage(event)">
                <style>
                    .foto{
                        background-image: url('../../img/foto.png');
                    }
                </style>
                <img id="imagePreview" src="../../img/previsualizar.png" alt="Previsualización" height="80" width="80">


            </div>
            <div class="formulario">
                <h1 class="animate__animated animate__backInLeft">Documentos Consignados</h1>
                <label for="fotos">Fotos tipo Carnet:<span style="color: red;">*</span></label>
                <select id="fotos" name="estudiante[fotos]" required>
                    <option value="">--Seleccione--</option>
                    <option value="Si" selected>Si</option>
                    <option value="No">No</option>
                </select>
                <label for="copias_ci">Copias de Cedula:<span style="color: red;">*</span></label>
                <select id="copias_ci" name="estudiante[copias_ci]"required>
                    <option value="">--Seleccione--</option>
                    <option value="Si" selected>Si</option>
                    <option value="No">No</option>
                </select>

                <label for="partida_nac_co">Partida de Nacimiento Copia:<span style="color: red;">*</span></label>
                <select id="partida_nac_co" name="estudiante[partida_nac_co]"required>
                    <option value="">--Seleccione--</option>
                    <option value="Si"selected>Si</option>
                    <option value="No">No</option>
                </select><br>

                <label for="historia_esc">Historia Escolar:<span style="color: red;">*</span></label>
                <select id="historia_esc" name="estudiante[historia_esc]"required>
                    <option value="">--Seleccione--</option>
                    <option value="Si" selected>Si</option>
                    <option value="No">No</option>
                </select><br>

                <label for="cert_prom">Certificado de Promoción:<span style="color: red;">*</span></label>
                <select id="cert_prom" name="estudiante[cert_prom]"required>
                    <option value="">--Seleccione--</option>
                    <option value="Si" selected>Si</option>
                    <option value="No">No</option>
                </select><br>

                <label for="tarj_vac">Tarjeta de vacunas:<span style="color: red;">*</span></label>
                <select id="tarj_vac" name="estudiante[tarj_vac]" required>
                    <option value="">--Seleccione--</option>
                    <option value="Si" selected>Si</option>
                    <option value="No">No</option>
                </select><br>

                <label for="cons_conduc">Constancia de Buena Conducta:<span style="color: red;">*</span></label>
                <select id="cons_conduc" name="estudiante[cons_conduc]"required>
                    <option value="">--Seleccione--</option>
                    <option value="Si" selected>Si</option>
                    <option value="No">No</option>
                </select><br>

                <label for="cons_retiro">Constancia de Retiro:<span style="color: red;">*</span></label>
                <select id="cons_retiro" name="estudiante[cons_retiro]" required>
                    <option value="">--Seleccione--</option>
                    <option value="Si" selected>Si</option>
                    <option value="No">No</option>
                </select><br>

                <label for="cert_notas">Constancia Notas:<span style="color: red;">*</span></label>
                <select id="cert_notas" name="estudiante[cert_notas]" required>
                    <option value="">--Seleccione--</option>
                    <option value="Si" selected>Si</option>
                    <option value="No">No</option>
                </select>
                
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
    function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('imagePreview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }

    document.getElementById('tel_e').addEventListener('input', function (e) {
  e.target.value = e.target.value.replace(/[^\d]/g, '');
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

    //mostrar o no los campos del representante    
    
    $(document).ready(function(){   
        
        llenarSelectorEstado(document.getElementById("estado"),venezuela);    
    }); 
         
    var poseeCanSelect = document.getElementById("posee_can");
    var serialCanInput = document.getElementById("serial_can");
    var noCanaimaText = document.getElementById("no_canaima_text");

    poseeCanSelect.addEventListener("change", function() {
        if (poseeCanSelect.value === "No") {
            serialCanInput.style.display = "none";
            serialCanInput.removeAttribute("required");
            noCanaimaText.style.display = "inline";
        } else {
            serialCanInput.style.display = "block";
            serialCanInput.setAttribute("required", "");
            noCanaimaText.style.display = "none";
        }
    var  gradoE = document.getElementById('grado_id');   
    });

    // Obtener los selectores desplegables
    var estadoSelector = document.getElementById("estado");
    var municipioSelector = document.getElementById("municipio");
    var parroquiaSelector = document.getElementById("parroquia");
    var estadoSeleccionado = 0;
    var municipioSeleccionado = 0;


    // Agregar un evento para actualizar los municipios cuando se selecciona un estado

    estadoSelector.addEventListener("change", function() {

        llenarSelector(municipioSelector,buscarEstado(estadoSelector.value)); 
    }); 

    // Agregar un evento para actualizar las parroquias cuando se selecciona un municipio
    municipioSelector.addEventListener("change", function() {
        llenarSelectorMunicipio(parroquiaSelector,buscarMunicipio(estadoSelector.value,municipioSelector.value)); 
           
    });     

    // Función para llenar un selector desplegable con opciones
    function llenarSelector(selector, opciones) {
        
        selector.innerHTML = "";
        
        for (var i = 0; i < opciones.length; i++) {
            var opcion = document.createElement("option");
            opcion.value = opciones[i].municipio;
            opcion.text = opciones[i].municipio;
            selector.add(opcion);
        }
    }
    function llenarSelectorMunicipio(selector, opciones) {
        
        selector.innerHTML = "";
        
        for (var i = 0; i < opciones.length; i++) {
            var opcion = document.createElement("option");
            opcion.value = opciones[i];
            opcion.text = opciones[i];
            selector.add(opcion);
        }
    }
    function llenarSelectorEstado(selector, opciones) {
        selector.innerHTML = "";
        for (var i = 0; i < opciones.length; i++) {
            var opcion = document.createElement("option");
            opcion.value = opciones[i].estado;
            opcion.text = opciones[i].estado;
            selector.add(opcion);
        }
    }
    function buscarEstado(estado){  

        for (var i = 0; i < venezuela.length; i++) {
                
            if(venezuela[i].estado == estado){
                return venezuela[i].municipios;
            }   
        }   
    }       
    function buscarMunicipio(estado,municipio){

        var arreglo = buscarEstado(estado);

        for (var i = 0; i < arreglo.length; i++) {
            
            if (arreglo[i].municipio == municipio) {
                
                return arreglo[i].parroquias;
            }   
        }   
    }           

</script>