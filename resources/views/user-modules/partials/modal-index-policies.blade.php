{{-- resources/views/user-modules/partials/modals.blade.php --}}

<!-- Modal para filtro por fecha -->
<div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">Filtrar Pólizas por Fecha</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('user.filter.policies') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="start_date">Fecha de Inicio</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                    </div>
                    <div class="form-group">
                        <label for="end_date">Fecha de Fin</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- MODAL DE NUEVOS PRECIOS --}}
 {{-- <div class="modal fade" id="modal-pago" tabindex="-1" role="dialog" aria-labelledby="modal-pago-label" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"><strong  class="text-danger">Nuevos precios emitidos por la SUDEASEG</strong></h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body text-info">
          <!-- Imagen a mostrar -->
          <div style="text-align: center;">
            <img src="{{asset('/uploads/preciosm.png')}}" alt="Precios SUDEASEG" style="max-width: 100%; height: auto;"/>
          </div>

          <!-- Enlace para descargar la imagen -->
          <div style="text-align: center; margin-top: 15px;">
            <a href="{{asset('/uploads/precios.png')}}" download="precios.png" class="btn btn-primary">Descargar Lista de Precios</a>
          </div>
        </div>

      </div>
    </div>
  </div> --}}

{{-- MODAL DE REPORTAR PAGO --}}

<!-- Modal  de pago-->

{{-- anular poliza --}}




{{-- modal renovar precio --}}



