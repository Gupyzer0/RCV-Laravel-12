 <!-- Change Pass Modal -->
<div class="modal fade" id="changePass" tabindex="-1" role="dialog" aria-labelledby="changePassLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="changePassLabel">Cambiar Contraseña</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="/admin/change-password/{{Auth::user()->id}}" id="CPF">
        @csrf
        <input type="hidden" name="_method" value="PUT">

        <div class="form-group col-md-12">
          <label for="password" class="col-form-label text-md-right">Nueva Contraseña</label>
          <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="off-password">
          @error('password')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>

        <div class="form-group col-md-12">
          <label for="password-confirm" class="col-form-label text-md-right">Confirmar Contraseña</label>
          <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="off-password">
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Cambiar contraseña</button>
        </div>
      </form>

      </div>
    </div>
  </div>
</div>

<script src="{{asset('js/Form-Validations/Change.js')}}"></script>
