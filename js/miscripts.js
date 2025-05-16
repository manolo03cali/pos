document.addEventListener('DOMContentLoaded', function() {
    /*Creamos un script para que busque nuestro modal le agregamos una funtion que a su vez busca el evento del boton btn-ok y recibe y redirecciona la URL
que es enviada de la vista buscamos la clase btn.ok y le agregamos el atributo href  viene desde data-href en la vista  aplica para todas las vistas donde se implemente
el modal*/
    $('#modal-confirma').on('show.bs.modal', function(e) {
        $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    });

    // Mostrar SweetAlert si hay un mensaje de éxito
    const successMessage = document.getElementById('successMessage');
    if (successMessage && successMessage.value) {
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: successMessage.value,
            confirmButtonText: 'OK'
        });
    }
    


    
});