<div class="modal fade" id="typeModal" tabindex="-1" role="dialog" aria-labelledby="typeModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="typeModalLabel">Registrar tipo de vehículo</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{route('register.type.submit')}}" id="types_form">
          @csrf

          <div class="form-group col-md-12">
            <label for="type" class="col-form-label text-md-right">Tipo de vehículo</label>
            <input id="type" type="text" class="form-control @error('type') is-invalid @enderror is-invalid" name="type" placeholder="..." autocomplete="off">

            @error('type')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>

          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Registrar</button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

<script src="{{asset('js/Form-Validations/Types.js')}}"></script>