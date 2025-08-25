<div class="modal fade" id="classModal" tabindex="-1" role="dialog" aria-labelledby="classModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="classModalLabel">Registrar clase de vehículo</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{route('register.class.submit')}}" id="classes_form">
          @csrf

          <div class="form-group col-md-12">
            <label for="class" class="col-form-label text-md-right">Clase de vehículo</label>
            <input id="class" type="text" class="form-control @error('class') is-invalid @enderror is-invalid" name="class" placeholder="..." autocomplete="off">

            @error('class')
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

<script src="{{asset('js/Form-Validations/Classes.js')}}"></script>