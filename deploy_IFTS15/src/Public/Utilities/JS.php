<?php
/**
 * JavaScript Centralizado - IFTS15
 * Archivo: src/Public/Utilities/JS.php
 * Incluye todos los scripts JavaScript necesarios para el sistema
 */
?>

<?php
if (session_status() === PHP_SESSION_NONE) session_start();
// Exponer configuración mínima al cliente
?>
<script>
window.APP = window.APP || {};
window.APP.baseUrl = <?= json_encode(defined('BASE_URL') ? BASE_URL : '/') ?>;
</script>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" 
        crossorigin="anonymous"></script>

<!-- 🔧 FIX CRÍTICO PARA BOOTSTRAP FOCUS TRAP -->
<script>

    // JavaScript personalizado del sistema
    document.addEventListener('DOMContentLoaded', function() {
        try {
            // Auto-ocultar alertas después de 5 segundos
            const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    try {
                        if (alert && alert.parentNode) {
                            alert.style.transition = 'opacity 0.5s';
                            alert.style.opacity = '0';
                            setTimeout(() => {
                                if (alert.parentNode) {
                                    alert.parentNode.removeChild(alert);
                                }
                            }, 500);
                        }
                    } catch (e) {
                        console.log('Error ocultando alerta:', e);
                    }
                }, 5000);
            });
            
            // Inicializar tooltips de forma segura
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                try {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                } catch (e) {
                    console.log('Error inicializando tooltip:', e);
                    return null;
                }
            });
            
            // Smooth scroll para enlaces internos (mejorado y más seguro)
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    try {
                        const href = this.getAttribute('href');
                        // Solo si el href no es solo "#" y no es un modal
                        if (href && href !== '#' && !href.includes('modal')) {
                            const target = document.querySelector(href);
                            if (target) {
                                e.preventDefault();
                                target.scrollIntoView({
                                    behavior: 'smooth'
                                });
                            }
                        }
                    } catch (e) {
                        console.log('Error en smooth scroll:', e);
                    }
                });
            });

            // Manejar modales de forma más segura: solo aplicar fix a consultasModal
            const consultasModal = document.getElementById('consultasModal');
            if (consultasModal) {
                try {
                    // Configurar modal de consultas con fix especial usando getOrCreateInstance
                    const modalInstance = bootstrap.Modal.getOrCreateInstance(consultasModal);
                    // Opcional: puedes setear opciones si necesitas
                    // modalInstance._config = { focus: true, backdrop: true, keyboard: true };
                    consultasModal.addEventListener('show.bs.modal', function () {
                        // Limpiar cualquier modal backdrop previo
                        const backdrops = document.querySelectorAll('.modal-backdrop');
                        if (backdrops.length > 1) {
                            for (let i = 1; i < backdrops.length; i++) {
                                backdrops[i].remove();
                            }

                                        // Inicializar modalNovedad explícitamente si existe (usar getOrCreateInstance para evitar error de _config)
                                        const modalNovedad = document.getElementById('modalNovedad');
                                        if (modalNovedad) {
                                            try {
                                                bootstrap.Modal.getOrCreateInstance(modalNovedad);
                                            } catch (e) {
                                                console.log('Error inicializando modalNovedad:', e);
                                            }
                                        }
                        }
                    });
                    consultasModal.addEventListener('shown.bs.modal', function () {
                        // 🔧 FIX CRÍTICO: Asegurar que todos los inputs sean clickeables
                        const allInputs = consultasModal.querySelectorAll('input, textarea, select, button');
                        allInputs.forEach(function(input) {
                            input.style.pointerEvents = 'auto';
                            input.style.userSelect = 'text';
                            input.tabIndex = input.tabIndex || 0;
                            if (input.tagName === 'INPUT' || input.tagName === 'TEXTAREA') {
                                input.addEventListener('click', function(e) {
                                    e.stopPropagation();
                                    this.focus();
                                });
                                input.addEventListener('focus', function(e) {
                                    e.stopPropagation();
                                });
                            }
                        });
                        // Focus manual y seguro en el primer input disponible
                        const firstInput = consultasModal.querySelector('input:not([readonly]):not([disabled]), textarea:not([readonly]):not([disabled]), select:not([disabled])');
                        if (firstInput && typeof firstInput.focus === 'function') {
                            setTimeout(() => {
                                try {
                                    firstInput.focus();
                                    firstInput.click();
                                } catch (e) {
                                    console.log('Error enfocando elemento:', e);
                                }
                            }, 150);
                        }
                    });
                    consultasModal.addEventListener('hidden.bs.modal', function () {
                        // Limpiar completamente
                        const backdrops = document.querySelectorAll('.modal-backdrop');
                        backdrops.forEach(backdrop => backdrop.remove());
                        document.body.classList.remove('modal-open');
                        document.body.style.removeProperty('padding-right');
                    });
                } catch (e) {
                    console.log('Error configurando consultasModal:', e);
                }
            }

        } catch (e) {
            console.log('Error en inicialización general:', e);
        }
    });

    // 🔧 FILTRAR ERRORES PROBLEMÁTICOS DE BOOTSTRAP
    const originalError = console.error;
    console.error = function(...args) {
        const message = args.join(' ');
        // Filtrar errores específicos que no son críticos
        if (message.includes('runtime.lastError') || 
            message.includes('The message port closed') ||
            message.includes('Maximum call stack size exceeded') ||
            message.includes('focustrap.js') ||
            (message.includes('activate') && message.includes('modal'))) {
            return; // Silenciar estos errores específicos
        }
        originalError.apply(console, args);
    };
</script>

<!-- Módulo específico para novedades (intercepta el submit del modal) -->
<script src="<?= BASE_URL ?>/src/Public/js/novedades.js" defer></script>