function mostrarMensaje(texto, tipo = 'exito', duracion = 3000) {
    const mensaje = document.getElementById('mensaje-temporal');
    mensaje.innerText = texto;

    // Colores según tipo de mensaje
    const colores = {
        exito: '#2ecc71',
        error: '#e74c3c',
        info: '#3498db',
        advertencia: '#f1c40f'
    };

    mensaje.style.backgroundColor = colores[tipo] || '#2ecc71';
    mensaje.style.display = 'block';
    mensaje.style.opacity = 1;

    setTimeout(() => {
        mensaje.style.transition = 'opacity 0.5s ease';
        mensaje.style.opacity = 0;
        setTimeout(() => {
            mensaje.style.display = 'none';
            mensaje.style.opacity = 1;
        }, 500);
    }, duracion);
}
