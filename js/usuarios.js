document.addEventListener('DOMContentLoaded',function() {
    var urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('edicion_exitosa')) {
        swal("¡Exito!", "Usuario editado correctamente.", "success")
        .then((value) => {
            window.location.href = 'procesalogin.php';
        });
    }
    if (urlParams.has('registro_exitoso')) {
        swal("¡Éxito!", "El usuario se registró con éxito.", "success");
    } else if (urlParams.has('eliminacion_exitosa')) {
        swal("¡Éxito!", "El Usuario se eliminó con éxito.", "success");
    }

    // Inicializar DataTable
    $('#tabla').DataTable({
        'language':{
        "lengthMenu": "Mostrar <select class='custom-select custom-select-sm form-control form-control-sm' >\
                        <option value='5' >5</option>\
                        <option value='10'>10</option>\
                        <option value='50'>50</option> \
                        <option value='-1'>Todos</option>\
                        </select > Registros", 
        "zeroRecords":"Nada encontrado - disculpa",
        "info":"mostrando la pagina _PAGE_ de _PAGES_",
        "infoEmpty":"No hay resultados disponibles",
        "infoFiltered":"(filtrado de _MAX_ registros totales)",
        "search":"Buscar",
        "paginate":{
            "next":"siguiente",
            "previous":"Anterior"
        },                    
        "emptyTable":"No hay Registros",                   
    }
    });
});

// Función auxiliar para enviar formularios POST con CSRF
function enviarPostSeguro(url, id) {
    var form = document.createElement('form');
    form.method = 'POST';
    form.action = url;
    form.style.display = 'none';

    var inputId = document.createElement('input');
    inputId.type = 'hidden';
    inputId.name = 'id';
    inputId.value = id;
    form.appendChild(inputId);

    var inputCsrf = document.createElement('input');
    inputCsrf.type = 'hidden';
    inputCsrf.name = 'csrf_token';
    inputCsrf.value = csrfToken; // Variable global definida en la página
    form.appendChild(inputCsrf);

    document.body.appendChild(form);
    form.submit();
}

function eliminarUsuario(id, rol) {
    if (rol == 1) {
        $.get('../contar_admin.php', function(data) {
            var num_administradores = Number(data);
            if (num_administradores <= 1) {
                swal("Error", "Debe haber al menos un administrador activo.", "error");
            } else {
                mostrarAlertaEliminar(id);
            }
        });
    } else {
        mostrarAlertaEliminar(id);
    }
}

function desactivarUsuario(id, rol) {
    if (rol == 1) {
        $.get('../contar_admin.php', function(data) {
            if (Number(data) <= 1) {
                swal("Error", "Debe haber al menos un administrador activo.", "error");
            } else {
                mostrarAlertaDesactivar(id);
            }
        });
    } else {
        mostrarAlertaDesactivar(id);
    }
}

function mostrarAlertaEliminar(id) {
    swal({
        title: "¿Estás seguro?",
        text: "¿Deseas eliminar este usuario?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            enviarPostSeguro('../eliminar/eliminar_u.php', id);
        }
    });
}

function mostrarAlertaDesactivar(id) {
    swal({
        title: "¿Estás seguro?",
        text: "¿Deseas desactivar este usuario?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            enviarPostSeguro('../desactivar_usuario.php', id);
        }
    });
}

function activarUsuario(id) {
    swal({
        title: "¿Estás seguro?",
        text: "¿Deseas activar este usuario?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            enviarPostSeguro('../activar_usuario.php', id);
        }
    });
}

function editarUsuario(id, rol) {
    if (rol == 1) {
        $.get('../contar_admin.php', function(data) {
            var num_administradores = Number(data);
            if (num_administradores <= 1) {
                swal("Error", "No puede editar el último administrador activo.", "error");
            } else {
                window.location.href = '../modificar/edi_usuario.php?id=' + id;
            }
        });
    } else {
        window.location.href = '../modificar/edi_usuario.php?id=' + id;
    }
}
