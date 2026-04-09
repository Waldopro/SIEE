function cambiarEstado(id) {
    $.ajax({
        url: 'cambiar_estado.php',
        type: 'POST',
        data: { id: id },
        success: function(response) {
            location.reload();
        }
    });
}