document.addEventListener("DOMContentLoaded", function() {
    // Verificar si la cookie de primer inicio existe
    if (document.cookie.includes('primer_inicio')) {
        // Crear la alerta con SweetAlert
        swal({
            title: '¡Bienvenido al Sistema de Inscripción!',
            text: 'Nos complace darle la más cordial bienvenida a nuestra plataforma de inscripción escolar, diseñada especialmente para facilitar y agilizar el proceso de registro. Aquí, usted encontrará una experiencia sencilla, segura y eficiente que le permitirá acceder rápidamente a todas las opciones disponibles para usted.\n' +
            'Su satisfacción es nuestra prioridad, por lo que hemos puesto especial énfasis en la seguridad y privacidad de sus datos. Puede estar tranquilo sabiendo que toda la información que proporcione será tratada con el máximo respeto y confidencialidad.\n\n' +
            'No importa si es la primera vez que utiliza nuestro sistema o si es un usuario experimentado, estamos aquí para brindarle asistencia en cada etapa del proceso. Si surge alguna duda o necesita ayuda, nuestro equipo de soporte estará encantado de atender sus consultas y guiarlo en todo momento.\n\n' +
            'Así que, adelante, comencemos este emocionante viaje de inscripción juntos. Estamos seguros de que encontrará justo lo que está buscando y que esta experiencia será el inicio de una gratificante relación con nuestros servicios.\n\n' +
            'Gracias por confiar en nosotros y por formar parte de esta comunidad. ¡Bienvenido al Sistema de Inscripción escolar de la U.E.B “Eneas Morantes”!',
            icon: 'info',
            button: 'Entendido',
            width: '800px' // Ajusta el ancho del modal aquí

        });

        // Eliminar la cookie de primer inicio para que no se muestre la alerta en futuras sesiones
        document.cookie = 'primer_inicio=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
    }
});