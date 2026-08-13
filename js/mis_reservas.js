document.addEventListener('click', event => {
  const boton = event.target.closest('[data-bs-target^="#editar"]');
  if (!boton) return;

  event.preventDefault();
  event.stopPropagation();

  const formulario = document.querySelector(boton.dataset.bsTarget)?.querySelector('form');
  if (!formulario) return;

  document.getElementById('modalEditarReserva')?.remove();
  const modal = document.createElement('div');
  modal.className = 'modal fade';
  modal.id = 'modalEditarReserva';
  modal.tabIndex = -1;
  modal.innerHTML = '<div class="modal-dialog modal-dialog-centered"><div class="modal-content edit-reserva-modal border-0"><div class="modal-header border-0 pb-0"><h2 class="modal-title h5">Editar reserva</h2><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button></div><div class="modal-body pt-3"></div></div></div>';

  const copia = formulario.cloneNode(true);
  const etiquetas = { fecha: 'Fecha', hora: 'Hora', personas: 'Personas' };
  copia.querySelectorAll('input[name]').forEach(input => {
    if (!etiquetas[input.name]) return;
    const etiqueta = document.createElement('label');
    etiqueta.className = 'form-label edit-reserva-label';
    etiqueta.textContent = etiquetas[input.name];
    etiqueta.htmlFor = `editar-${input.name}`;
    input.id = etiqueta.htmlFor;
    input.parentElement.prepend(etiqueta);
  });

  modal.querySelector('.modal-body').appendChild(copia);
  document.body.appendChild(modal);
  bootstrap.Modal.getOrCreateInstance(modal).show();
});
