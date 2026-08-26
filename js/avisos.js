(() => {
  function cargarEstilos() {
    if (document.querySelector('link[href="/css/alerts.css"]')) return;

    const estilos = document.createElement('link');
    estilos.rel = 'stylesheet';
    estilos.href = '/css/alerts.css';
    document.head.appendChild(estilos);
  }

  window.mostrarAviso = (mensaje, titulo = 'Aviso') => {
    cargarEstilos();
    document.querySelector('.app-alert-backdrop')?.remove();

    const fondo = document.createElement('div');
    fondo.className = 'app-alert-backdrop';

    const aviso = document.createElement('div');
    aviso.className = 'app-alert app-alert-info';
    aviso.setAttribute('role', 'alertdialog');
    aviso.setAttribute('aria-modal', 'true');

    const icono = document.createElement('div');
    icono.className = 'app-alert-icon';
    icono.setAttribute('aria-hidden', 'true');
    icono.textContent = 'i';

    const encabezado = document.createElement('h2');
    encabezado.textContent = titulo;

    const contenido = document.createElement('p');
    contenido.textContent = String(mensaje);

    const boton = document.createElement('button');
    boton.type = 'button';
    boton.textContent = 'Entendido';
    boton.addEventListener('click', () => fondo.remove());

    aviso.append(icono, encabezado, contenido, boton);
    fondo.appendChild(aviso);
    document.body.appendChild(fondo);
    boton.focus();
  };
})();
