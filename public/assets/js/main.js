// public/assets/js/main.js
document.addEventListener('DOMContentLoaded', function() {
    // Script para pasar el ID del préstamo al modal "Cambiar Estado"
    var cambiarEstadoModal = document.getElementById('cambiarEstadoModal');
    if (cambiarEstadoModal) {
        cambiarEstadoModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var prestamoId = button.getAttribute('data-prestamo-id');
            var modalInput = cambiarEstadoModal.querySelector('#prestamo_id');
            modalInput.value = prestamoId;
        });
    }
});
