/* src/Public/js/novedades.js
   Módulo para manejar el formulario/modal de Novedades.
   - Intercepta el submit del form dentro de #modalNovedad
   - Envía por fetch al controlador existente
   - Cierra el modal y recarga la página al completar correctamente
*/
(function (){
  'use strict';

  function ensureToastContainer(){
    let c = document.getElementById('toasts-container');
    if (!c) {
      c = document.createElement('div');
      c.id = 'toasts-container';
      c.className = 'position-fixed bottom-0 end-0 p-3';
      c.style.zIndex = '1080';
      document.body.appendChild(c);
    }
    return c;
  }

  function showToast(message, type='info', timeout=3500){
    const container = ensureToastContainer();
    const toastEl = document.createElement('div');
    toastEl.className = 'toast align-items-center text-bg-' + (type === 'danger' ? 'danger' : (type === 'success' ? 'success' : 'secondary')) + ' border-0';
    toastEl.setAttribute('role','alert');
    toastEl.setAttribute('aria-live','assertive');
    toastEl.setAttribute('aria-atomic','true');

    toastEl.innerHTML = `
      <div class="d-flex">
        <div class="toast-body">${message}</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    `;

    container.appendChild(toastEl);
    const bsToast = new bootstrap.Toast(toastEl, { delay: timeout });
    bsToast.show();
    toastEl.addEventListener('hidden.bs.toast', function(){ toastEl.remove(); });
  }

  async function submitNovedad(form){
    const url = (window.APP && window.APP.baseUrl ? window.APP.baseUrl : '') + '/src/Controllers/novedadesController.php';
    const fd = new FormData(form);
    try {
      const res = await fetch(url, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: fd,
        redirect: 'follow'
      });
      if (!res.ok) {
        showToast('Error al guardar la novedad (status ' + res.status + ')', 'danger');
        return false;
      }
      // Si el servidor responde JSON lo procesamos
      const ct = (res.headers.get('content-type') || '').toLowerCase();
      if (ct.includes('application/json')) {
        const data = await res.json();
        if (data && data.success) {
          showToast(data.message || 'Novedad guardada', 'success');
          return true;
        }
        showToast(data.message || 'Error al guardar la novedad', 'danger');
        return false;
      }

      // Fallback si no es JSON: asumir éxito si status OK
      showToast('Novedad guardada', 'success');
      return true;
    } catch (err){
      console.error('submitNovedad', err);
      showToast('Error de red al guardar novedad', 'danger');
      return false;
    }
  }

  function init(){
    document.addEventListener('DOMContentLoaded', function(){
      const modal = document.getElementById('modalNovedad');
      if (!modal) return;
      const form = modal.querySelector('form');
      if (!form) return;

      form.addEventListener('submit', function(e){
        e.preventDefault();
        const submitBtn = form.querySelector('[type="submit"]');
        if (submitBtn) submitBtn.disabled = true;
        submitNovedad(form).then(ok => {
          if (submitBtn) submitBtn.disabled = false;
          if (ok) {
            // cerrar modal y recargar para ver la nueva novedad
            try{
              const bsModal = bootstrap.Modal.getInstance(modal) || bootstrap.Modal.getOrCreateInstance(modal);
              bsModal.hide();
            }catch(e){/*ignore*/}
            // pequeña demora para que el modal termine su animación
            setTimeout(()=> location.reload(), 300);
          }
        });
      });
    });
  }

  // Exponer init para pruebas
  window.Novedades = window.Novedades || {};
  window.Novedades.init = init;
  // Inicializar inmediatamente
  init();

})();
