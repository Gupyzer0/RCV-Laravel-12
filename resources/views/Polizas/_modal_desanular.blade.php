<div class="modal fade" id="restoreModal-{{$poliza->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">¿Seguro que desea <strong class="text-info">revocar la anulación</strong> de esta poliza?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Seleccione "continuar" si desea <span class="text-info">revocar la anulación</span> de esta poliza</div>
            <div class="modal-footer">
                <form action="{{ route('polizas.desanular', $poliza) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Continuar</button>
                </form>
            </div>
        </div>
    </div>
</div>