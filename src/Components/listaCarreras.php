<?php
/**
 * Componente: Lista de Carreras (panel derecho)
 * Permite crear, editar, eliminar carreras
 * Cada carrera es una drop-zone para recibir materias
 */

use App\Model\Carrera;
use App\Model\Materia;

// Las carreras ya vienen de la vista principal en $carreras
// La conexión $conn también está disponible para operaciones adicionales
?>

<div class="card">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0">
            <i class="bi bi-mortarboard me-2"></i>
            Carreras
        </h5>
    </div>
    <div class="card-body">
        <!-- Formulario para crear carrera -->
        <div class="mb-3">
            <label class="form-label fw-bold" for="input-nueva-carrera">Nueva Carrera</label>
            <div class="input-group">
                  <input type="text" 
                      class="form-control" 
                      id="input-nueva-carrera" 
                      name="nombreCarrera"
                      placeholder="Nombre de la carrera"
                      autocomplete="organization">
                <button class="btn btn-success" type="button" id="btn-crear-carrera">
                    <i class="bi bi-plus-circle me-1"></i> Crear
                </button>
            </div>
        </div>

        <hr>

        <!-- Listado de carreras (drop zones) -->
        <div id="carreras-container" style="max-height: 500px; overflow-y: auto;">
            <?php if (empty($carreras)): ?>
                <p class="text-muted text-center py-3">
                    <i class="bi bi-inbox"></i><br>
                    No hay carreras registradas
                </p>
            <?php else: ?>
                <?php foreach ($carreras as $carrera): 
                    $materiasAsociadas = Carrera::obtenerMaterias($conn ?? $this->conn, $carrera['id_carrera']);
                ?>
                    <div class="card carrera-card mb-3" data-id-carrera="<?= $carrera['id_carrera'] ?>">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center" 
                             style="cursor: pointer;"
                             onclick="toggleCarrera(<?= $carrera['id_carrera'] ?>)">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-chevron-down me-2 collapse-icon" id="icon-<?= $carrera['id_carrera'] ?>"></i>
                                <strong><?= htmlspecialchars($carrera['carrera'] ?? '') ?></strong>
                                <small class="text-muted ms-2">(<?= count($materiasAsociadas) ?> materias)</small>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-sm-icon btn-outline-primary me-1 btn-editar-carrera" 
                                    data-id="<?= $carrera['id_carrera'] ?>" 
                                    data-nombre="<?= htmlspecialchars($carrera['carrera'] ?? '') ?>"
                                    title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-sm-icon btn-outline-danger btn-eliminar-carrera" 
                                        data-id="<?= $carrera['id_carrera'] ?>"
                                        title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body collapse-content" id="body-<?= $carrera['id_carrera'] ?>">
                            <div class="drop-zone carrera-drop-zone" 
                                 data-id-carrera="<?= $carrera['id_carrera'] ?>">
                                <?php if (empty($materiasAsociadas)): ?>
                                    <p class="text-muted text-center mb-0">
                                        <small>
                                            <i class="bi bi-arrow-down-circle"></i><br>
                                            Arrastra materias aquí
                                        </small>
                                    </p>
                                <?php else: ?>
                                    <?php foreach ($materiasAsociadas as $materia): ?>
                                        <div class="materia-item" 
                                            data-id="<?= $materia['id_materia'] ?>"
                                            data-id-materia="<?= $materia['id_materia'] ?>">
                                            <span><?= htmlspecialchars($materia['nombre_materia']) ?></span>
                                            <button class="btn btn-sm btn-sm-icon btn-outline-danger btn-desasociar-materia" 
                                                    data-id-carrera="<?= $carrera['id_carrera'] ?>"
                                                    data-id-materia="<?= $materia['id_materia'] ?>"
                                                    title="Quitar de la carrera">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Crear carrera
    document.getElementById('btn-crear-carrera').addEventListener('click', function() {
        const input = document.getElementById('input-nueva-carrera');
        const nombre = input.value.trim();
        
        if (!nombre) {
            showToast('El nombre de la carrera es obligatorio', 'warning');
            return;
        }

        const formData = new FormData();
        formData.append('nombre', nombre);

        fetch('<?= BASE_URL ?>/src/Controllers/carreraController.php?action=crear', {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(data.message || 'Carrera creada exitosamente', 'success');
                input.value = '';
                        recargarCarreras();
            } else {
                showToast(data.error || 'Error al crear carrera', 'danger');
            }
        })
        .catch(err => {
            console.error('Error:', err);
            showToast('Error de comunicación con el servidor', 'danger');
        });
    });

    // Editar carrera
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-editar-carrera')) {
            e.stopPropagation();
            const btn = e.target.closest('.btn-editar-carrera');
            const id = btn.dataset.id;
            const nombre = btn.dataset.nombre;
            
            const nuevoNombre = prompt('Editar carrera:', nombre);
            if (nuevoNombre && nuevoNombre.trim() !== nombre) {
                const formData = new FormData();
                formData.append('id', id);
                formData.append('nombre', nuevoNombre.trim());

                fetch('<?= BASE_URL ?>/src/Controllers/carreraController.php?action=actualizar', {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message || 'Carrera actualizada', 'success');
                        recargarCarreras();
                    } else {
                        showToast(data.error || 'Error al actualizar', 'danger');
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    showToast('Error de comunicación', 'danger');
                });
            }
        }
    });

    // Eliminar carrera
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-eliminar-carrera')) {
            e.stopPropagation();
            const btn = e.target.closest('.btn-eliminar-carrera');
            const id = btn.dataset.id;
            
            if (confirm('¿Eliminar esta carrera? Las materias asociadas se liberarán.')) {
                const formData = new FormData();
                formData.append('id', id);

                fetch('<?= BASE_URL ?>/src/Controllers/carreraController.php?action=eliminar', {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message || 'Carrera eliminada', 'success');
                        Promise.all([recargarCarreras(), recargarMaterias()]);
                    } else {
                        showToast(data.error || 'Error al eliminar', 'danger');
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    showToast('Error de comunicación', 'danger');
                });
            }
        }
    });

    // Desasociar materia de carrera
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-desasociar-materia')) {
            const btn = e.target.closest('.btn-desasociar-materia');
            const idCarrera = btn.dataset.idCarrera;
            const idMateria = btn.dataset.idMateria;
            
            const formData = new FormData();
            formData.append('id_carrera', idCarrera);
            formData.append('id_materia', idMateria);

            fetch('<?= BASE_URL ?>/src/Controllers/carreraController.php?action=desasociar', {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message || 'Materia liberada', 'success');
                    Promise.all([recargarCarreras(), recargarMaterias()]);
                } else {
                    showToast(data.error || 'Error al desasociar', 'danger');
                }
            })
            .catch(err => {
                console.error('Error:', err);
                showToast('Error de comunicación', 'danger');
            });
        }
    });

    // Inicializar drop zones
    initDropZones();
});

/**
 * Inicializar zonas de drop (carreras) con SortableJS
 */
function initDropZones() {
    const dropZones = document.querySelectorAll('.carrera-drop-zone');
    
    dropZones.forEach((zone) => {
        const idCarrera = zone.dataset.idCarrera;
        
        // Destruir instancia anterior si existe para evitar duplicados
        if (zone.sortableInstance) {
            zone.sortableInstance.destroy();
        }
        
        // Crear instancia de Sortable para esta zona
        zone.sortableInstance = Sortable.create(zone, {
            group: 'materias', // Mismo grupo que materias libres para permitir drag & drop
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            onAdd: function(evt) {
                // Evento disparado cuando se suelta una materia en esta carrera
                const materiaElement = evt.item;
                const idMateria = materiaElement.dataset.id || materiaElement.dataset.idMateria;
                
                if (!idMateria) {
                    console.error('Error: No se encontró id de materia');
                    return;
                }
                
                // Asociar en backend
                const formData = new FormData();
                formData.append('id_carrera', idCarrera);
                formData.append('id_materia', idMateria);

                fetch('<?= BASE_URL ?>/src/Controllers/carreraController.php?action=asociar', {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToast('Materia asociada exitosamente', 'success');
                        Promise.all([recargarCarreras(), recargarMaterias()]);
                    } else {
                        showToast(data.error || 'Error al asociar', 'danger');
                        // Revertir cambio visual si falló
                        Promise.all([recargarCarreras(), recargarMaterias()]);
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    showToast('Error de comunicación', 'danger');
                    Promise.all([recargarCarreras(), recargarMaterias()]);
                });
            }
        });
    });
}

// Función para contraer/expandir carreras
function toggleCarrera(idCarrera) {
    const body = document.getElementById('body-' + idCarrera);
    const icon = document.getElementById('icon-' + idCarrera);
    
    if (body.style.display === 'none') {
        body.style.display = 'block';
        icon.classList.remove('bi-chevron-right');
        icon.classList.add('bi-chevron-down');
    } else {
        body.style.display = 'none';
        icon.classList.remove('bi-chevron-down');
        icon.classList.add('bi-chevron-right');
    }
}
</script>

