var datosEstadisticas = <?php echo json_encode($estadisticas); ?>;
        var años = [];
        var grados = [];
        var totalEstudiantes = {};

        datosEstadisticas.forEach(function (item) {
            if (!años.includes(item.año)) {
                años.push(item.año);
            }
            if (!grados.includes(item.grados)) {
                grados.push(item.grados);
            }
            if (!totalEstudiantes[item.grados]) {
                totalEstudiantes[item.grados] = [];
            }
            totalEstudiantes[item.grados].push(item.total_estudiantes);
        });

        var colores = [
            'rgba(54, 162, 235, 0.5)',
            'rgba(75, 192, 192, 0.5)',
            'rgba(255, 206, 86, 0.5)',
            'rgba(153, 102, 255, 0.5)',
            'rgba(255, 159, 64, 0.5)',
            'rgba(255, 99, 132, 0.5)'
        ];

        var bordeColores = [
            'rgba(54, 162, 235, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)',
            'rgba(255, 99, 132, 1)'
        ];

        var datasets = grados.map(function (grado, index) {
            return {
                label: grado || 'Sin Nombre',
                data: totalEstudiantes[grado],
                backgroundColor: colores[index % colores.length],
                borderColor: bordeColores[index % bordeColores.length],
                borderWidth: 1
            };
        });

        var ctx = document.getElementById('graficaBarras').getContext('2d');
        var graficaBarras = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: años,
                datasets: datasets
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        function descargarPDF() {
            var canvas = document.getElementById('graficaBarras');
            var imgData = canvas.toDataURL('image/png');
            
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'pdf_e.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.responseType = 'blob';
            xhr.onload = function () {
                if (xhr.status === 200) {
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(xhr.response);
                    link.download = 'estadistica_estudiantes.pdf';
                    link.click();
                }
            };
            xhr.send('imgData=' + encodeURIComponent(imgData));
        }