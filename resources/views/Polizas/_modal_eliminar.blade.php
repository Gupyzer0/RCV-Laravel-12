<div class="modal fade" id="deleteModal-{{$poliza->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">¿Seguro que desea <strong class="text-danger">eliminar</strong> esta poliza?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Seleccione "continuar" si desea <span class="text-danger">eliminar</span> esta poliza</div>
            <div class="modal-footer">
                <form action="{{ route('polizas.delete', $poliza) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Continuar</button>
                </form>
            </div>
        </div>
    </div>
</div>