<?php
require 'funcion_e.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';
include ('../modulos/verificar_session.php');
include ('../modulos/rol1.php');

// Asegúrate de que esta línea esté presente y correcta
$estadisticas = obtenerEstadisticas(); // Reemplazar por función de obtener estadísticas

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estadísticas de Inscripción de Estudiantes</title>
    <!-- Archivos CSS optimizados -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/fontawesome.css">
    <link rel="stylesheet" href="../css/procesare.css">
    <link rel="stylesheet" href="../css/estadistica.css">
    <link rel="stylesheet" href="../css/inicio.css">
    <link rel="stylesheet" href="../lib/datatable/datatables.min.css">
    <link rel="stylesheet" href="../css/footer.css">
    
    <!-- Archivos JS optimizados -->
    <script src="../js/chartjs-plugin-datalabels.js"></script>

    <script src="../node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../lib/datatable/datatables.min.js"></script>
    <script src="../js/chart.js"></script>
</head>
<body oncontextmenu="return false;">
    <?php include('../modulos/menu.php') ?>

    <div class="content">
        <h1 class="text-center">Estadísticas de Inscripción de Estudiantes</h1>
        
        <div class="button-container">
            <button class="btn btn-primary" onclick="history.back()"><img src="../img/volver1.png" alt="Volver" style="width: 30px; height: 40px;">Volver</button>
            <button class="pdf-button" onclick="descargarPDF()"><img src="../img/PDF_file_icon.svg.png" alt="PDF" style="width: 30px; height: 40px;"> Generar PDF</button>
            <button class="csv-button" onclick="descargarCSV()"><img src="../img/excel.png" alt="EXCEL" style="width: 30px; height: 40px;"> Descargar CSV</button>
        </div>
        
        <div>
           <!-- Selector de curso -->
           <center><form id="formCurso" method="POST">
                <label for="curso">Seleccionar Curso:</label>
                <select name="curso_id" id="curso" required>
                    <option value="">Seleccione un curso</option>
                    <option value="todos">Todos los cursos</option> <!-- Opción para todos los cursos -->
                    <?php
                        // Obtener los cursos disponibles
                        $cursos = obtenerCursos();
                        foreach ($cursos as $curso) 
                        {
                            echo "<option value='{$curso['id']}'>{$curso['grado']} - {$curso['seccion']}</option>";
                        }
                    ?>
                </select>
    <button type="submit" class="btn btn-primary">Ver Estadísticas</button>
</form>
</center>

<div id="estadisticas"></div>

        </div>

        <div class="table-container" id="tabla-container" style="display: none;">
    <table id="tabla" class="table table-hover table-condensed table-striped">
                <thead>
                    <tr>
                        <th>Año</th>
                        <th>Grado</th>
                        <th>Total Estudiantes</th>
                        <th>Total Masculinos</th>
                        <th>Total Femeninos</th>
                        <th>Total Nuevos</th>
                        <th>Total Regulares</th>
                        <th>Total Repitientes</th>
                    </tr>
                </thead>
                <tbody id="tabla-body">
                    <?php if (!empty($estadisticas)): ?>
                        <?php foreach ($estadisticas as $estadistica): ?>
                            <tr>
                                <td><?= htmlspecialchars($estadistica['año']); ?></td>
                                <td><?= htmlspecialchars($estadistica['grado']); ?></td>
                                <td><?= htmlspecialchars($estadistica['total_estudiantes']); ?></td>
                                <td><?= htmlspecialchars($estadistica['total_masculinos']); ?></td>
                                <td><?= htmlspecialchars($estadistica['total_femeninos']); ?></td>
                                <td><?= htmlspecialchars($estadistica['total_nuevos']); ?></td>
                                <td><?= htmlspecialchars($estadistica['total_regulares']); ?></td>
                                <td><?= htmlspecialchars($estadistica['total_repitientes']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="8" class="text-center">No hay datos disponibles</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        

        <div class="canvas-container">
            <canvas id="graficaBarras" class="grafico" width="1600" height="300"></canvas>
            <canvas id="graficaTorta" class="grafico grafico-pequeno" width="400" height="300"></canvas>
        </div>
    </div>

    <div class="footer">
        <?php include '../modulos/footer.php'; ?>
    </div>

    <script>
     document.getElementById('formCurso').addEventListener('submit', function(e) {
    e.preventDefault(); // Evitar el envío tradicional del formulario

    // Obtener el curso seleccionado
    var curso_id = document.getElementById('curso').value;

    // Verificar si se ha seleccionado un curso
    if (curso_id !== "") {
        // Mostrar la tabla
        document.getElementById('tabla-container').style.display = "block";
        
        // Enviar la solicitud al servidor
        fetch('funcion_e.php', {
            method: 'POST',
            body: new URLSearchParams({ curso_id: curso_id })
        })
        .then(response => response.json())
        .then(data => {
            // Aquí puedes actualizar la tabla y las gráficas con los datos obtenidos
            actualizarTabla(data);
            actualizarGraficas(data);
        })
        .catch(error => console.error('Error:', error));
    } else {
        alert("Por favor, seleccione un curso.");
    }
});

        function actualizarTabla(data) {
            const tablaBody = document.getElementById('tabla-body');
            tablaBody.innerHTML = '';

            data.forEach(function(item) {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.año}</td>
                    <td>${item.grado}</td>
                    <td>${item.total_estudiantes}</td>
                    <td>${item.total_masculinos}</td>
                    <td>${item.total_femeninos}</td>
                    <td>${item.total_nuevos}</td>
                    <td>${item.total_regulares}</td>
                    <td>${item.total_repitientes}</td>
                `;
                tablaBody.appendChild(row);
            });
        }

        let chartBarras = null; // Variable global para el gráfico de barras
let chartTorta = null; // Variable global para el gráfico de torta

function actualizarGraficas(data) {
    var labels = [];
    var totalEstudiantes = [];
    var totalMasculinos = [];
    var totalFemeninos = [];
    var totalNuevos = 0;
    var totalRegulares = 0;
    var totalRepitientes = 0;

    data.forEach(function(item) {
        labels.push(item.grado + ' ' + item.año);
        totalEstudiantes.push(item.total_estudiantes);
        totalMasculinos.push(item.total_masculinos);
        totalFemeninos.push(item.total_femeninos);

        var nuevos = parseInt(item.total_nuevos, 10) || 0;
        var regulares = parseInt(item.total_regulares, 10) || 0;
        var repitientes = parseInt(item.total_repitientes, 10) || 0;

        totalNuevos += nuevos;
        totalRegulares += regulares;
        totalRepitientes += repitientes;
    });

    // Gráfico de Barras
    if (chartBarras) {
        chartBarras.destroy();
    }
    const ctxBarras = document.getElementById('graficaBarras').getContext('2d');
    chartBarras = new Chart(ctxBarras, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Total Estudiantes',
                    data: totalEstudiantes,
                    backgroundColor: 'rgba(0, 255, 198, 0.2)',
                    borderColor: 'rgba(0, 255, 198, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Masculinos',
                    data: totalMasculinos,
                    backgroundColor: 'rgba(0, 123, 255, 0.2)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Femeninos',
                    data: totalFemeninos,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }
            ]
        }
    });

    // Gráfico de Torta
    if (chartTorta) {
        chartTorta.destroy();
    }
    const ctxTorta = document.getElementById('graficaTorta').getContext('2d');
    chartTorta = new Chart(ctxTorta, {
        type: 'pie',
        data: {
            labels: ['Nuevos', 'Regulares', 'Repitientes'],
            datasets: [
                {
                    data: [totalNuevos, totalRegulares, totalRepitientes],
                    backgroundColor: ['#00ff99', '#ffcc00', '#ff0033'],
                }
            ]
        },
        options: {
            plugins: {
                datalabels: {
                    formatter: (value, context) => {
                        let total = totalNuevos + totalRegulares + totalRepitientes;
                        let percentage = (value / total * 100).toFixed(2) + '%';
                        return percentage; // Muestra el porcentaje
                    },
                    color: '#fff',
                    font: {
                        weight: 'bold',
                        size: 14
                    }
                }
            }
        }
    });
}

function descargarPDF() {
    var canvasBarras = document.getElementById('graficaBarras');
    var canvasTorta = document.getElementById('graficaTorta');

    var imgDataBarras = canvasBarras.toDataURL('image/png');
    var imgDataTorta = canvasTorta.toDataURL('image/png');

    var curso_id = document.getElementById('curso').value;

    var tablaHTML = document.getElementById('tabla-container').innerHTML;

    var formData = new FormData();
    formData.append('graficoBarras', imgDataBarras);
    formData.append('graficoTorta', imgDataTorta);
    formData.append('curso_id', curso_id);
    formData.append('tablaHTML', tablaHTML); // Enviar la tabla como HTML

    fetch('pdf_e.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.blob())
    .then(blob => {
        var link = document.createElement('a');
        link.href = window.URL.createObjectURL(blob);
        link.download = 'estadisticas.pdf';
        link.click();
    })
    .catch(error => console.error('Error:', error));
}


function descargarCSV() {
    const rows = [];
    const headers = ['Año', 'Grado', 'Total Estudiantes', 'Total Masculinos', 'Total Femeninos', 'Total Nuevos', 'Total Regulares', 'Total Repitientes'];
    rows.push(headers);

    const tablaBody = document.getElementById('tabla-body');
    const rowsData = tablaBody.querySelectorAll('tr');
    rowsData.forEach(row => {
        const cells = row.querySelectorAll('td');
        const rowData = [];
        cells.forEach(cell => {
            rowData.push(cell.innerText);
        });
        rows.push(rowData);
    });

    let csvContent = "data:text/csv;charset=utf-8,";
    rows.forEach(rowArray => {
        let row = rowArray.join(',');
        csvContent += row + "\r\n";
    });

    const encodedUri = encodeURI(csvContent);
    const link = document.createElement('a');
    link.setAttribute('href', encodedUri);
    link.setAttribute('download', 'estadisticas.csv');
    document.body.appendChild(link);
    link.click();
}

    </script>
</body>
</html>