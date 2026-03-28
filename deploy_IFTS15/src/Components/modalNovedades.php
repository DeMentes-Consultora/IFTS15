
<?php
// Componente: Solo Modal de Novedades (botón y ventana)
?>

<div class="modal fade" id="modalNovedad" tabindex="-1" aria-labelledby="modalNovedadLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalNovedadLabel">Agregar Novedad</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <form method="POST" action="<?php echo BASE_URL; ?>/src/Controllers/novedadesController.php">
        <div class="modal-body">
          <div class="mb-3">
            <label for="novedad" class="form-label">Novedad</label>
            <input type="text" name="novedad" id="novedad" class="form-control" placeholder="Escribe la novedad..." required maxlength="250">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Aceptar</button>
        </div>
      </form>
    </div>
  </div>
</div>
