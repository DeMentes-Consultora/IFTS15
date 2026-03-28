<?php
/**
 * Componente: Lista de Materias (panel izquierdo)
 * Permite crear, editar, eliminar materias
 * Materias libres (no asociadas) son draggable
 */

// Las materias libres ya vienen desde la vista principal en la variable $materiasLibres
?>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="bi bi-book me-2"></i>
            Materias Disponibles
        </h5>
    </div>
    <div class="card-body">
        <!-- Formulario para crear materia -->
        <div class="mb-3">
            <label class="form-label fw-bold">Nueva Materia</label>
            <div class="input-group">
                <input type="text" 
                       class="form-control" 
                       id="input-nueva-materia" 
                       placeholder="Nombre de la materia"
                       autocomplete="off">
                <button class="btn btn-success" type="button" id="btn-crear-materia">
                    <i class="bi bi-plus-circle me-1"></i> Crear
                </button>
            </div>
        </div>

        <hr>

        <!-- Listado de materias libres (draggable) -->
        <div class="mb-2">
            <small class="text-muted">
                <i class="bi bi-info-circle me-1"></i>
                Arrastra una materia a una carrera para asociarla
            </small>
        </div>
        
        <div id="materias-libres-container" class="drop-zone" style="max-height: 500px; overflow-y: auto;">
            <?php if (empty($materiasLibres)): ?>
                <p class="text-muted text-center py-3">
                    <i class="bi bi-inbox"></i><br>
                    No hay materias disponibles
                </p>
            <?php else: ?>
                <?php foreach ($materiasLibres as $materia): ?>
                    <div class="materia-item" 
                         data-id="<?= $materia['id_materia'] ?>">
                        <span><?= htmlspecialchars($materia['nombre_materia']) ?></span>
                        <div>
                            <button class="btn btn-sm btn-sm-icon btn-outline-primary me-1 btn-editar-materia" 
                                    data-id="<?= $materia['id_materia'] ?>" 
                                    data-nombre="<?= htmlspecialchars($materia['nombre_materia']) ?>"
                                    title="Editar">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-sm-icon btn-outline-danger btn-eliminar-materia" 
                                    data-id="<?= $materia['id_materia'] ?>"
                                    title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Crear materia
    document.getElementById('btn-crear-materia').addEventListener('click', function() {
        const input = document.getElementById('input-nueva-materia');
        const nombre = input.value.trim();
        
        if (!nombre) {
            showToast('El nombre de la materia es obligatorio', 'warning');
            return;
        }

        const formData = new FormData();
        formData.append('nombre', nombre);

        fetch('<?= BASE_URL ?>/src/Controllers/materiaController.php?action=crear', {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(data.message || 'Materia creada exitosamente', 'success');
                input.value = '';
                recargarMaterias();
            } else {
                showToast(data.error || 'Error al crear materia', 'danger');
            }
        })
        .catch(err => {
            console.error('Error:', err);
            showToast('Error de comunicación con el servidor', 'danger');
        });
    });

    // Editar materia
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-editar-materia')) {
            const btn = e.target.closest('.btn-editar-materia');
            const id = btn.dataset.id;
            const nombre = btn.dataset.nombre;
            
            const nuevoNombre = prompt('Editar materia:', nombre);
            if (nuevoNombre && nuevoNombre.trim() !== nombre) {
                const formData = new FormData();
                formData.append('id', id);
                formData.append('nombre', nuevoNombre.trim());

                fetch('<?= BASE_URL ?>/src/Controllers/materiaController.php?action=actualizar', {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message || 'Materia actualizada', 'success');
                        recargarMaterias();
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

    // Eliminar materia
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-eliminar-materia')) {
            const btn = e.target.closest('.btn-eliminar-materia');
            const id = btn.dataset.id;
            
            if (confirm('¿Eliminar esta materia?')) {
                const formData = new FormData();
                formData.append('id', id);

                fetch('<?= BASE_URL ?>/src/Controllers/materiaController.php?action=eliminar', {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message || 'Materia eliminada', 'success');
                        recargarMaterias();
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

    // Inicializar drag de materias
    initDragMaterias();
});

/**
 * Inicializar drag & drop con SortableJS para materias libres
 */
function initDragMaterias() {
    const container = document.getElementById('materias-libres-container');
    if (!container) return;
    
    // Destruir instancia anterior si existe para evitar duplicados
    if (window.sortableMaterias) {
        window.sortableMaterias.destroy();
    }
    
    // Crear nueva instancia de Sortable
    window.sortableMaterias = Sortable.create(container, {
        group: 'materias', // Permite mover entre grupos (materias <-> carreras)
        animation: 150,
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        dragClass: 'sortable-drag',
        sort: false // No permitir reordenar dentro de materias libres
    });
}
</script>

