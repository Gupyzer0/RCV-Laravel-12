@extends('layouts.app')

@section('module')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Actualizar Uso de vehículo</div>

                <div class="card-body">
                    <form method="POST" action="/admin/edit-type/{{$vehicle_type->id}}" id="types_form">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">

                        <div class="form-group col-md-12">
                            <label for="type" class="col-form-label text-md-right">Uso de vehículo</label>
                            <input id="type" type="text" class="form-control @error('type') is-invalid @enderror" name="type" placeholder="..." autocomplete="off" value="{{$vehicle_type->type}}">

                            @error('type')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Actualizar
                                </button>

                                <a href="{{ route('index.vehicle.types') }}" class="btn btn-danger" style="width: 90px;">
                                    Volver
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
const formTypes = document.querySelector('#types_form');
const typeInput = document.querySelector('#type');
let notValidatedTypes = [];

function removeA(arr) {
    var what, a = arguments, L = a.length, ax;
    while (L > 1 && arr.length) {
        what = a[--L];
        while ((ax= arr.indexOf(what)) !== -1) {
            arr.splice(ax, 1);
        }
    }
    return arr;
}

typeInput.addEventListener('keyup', () => {
    if (typeInput.value === '' || typeInput.value == null) {
        typeInput.classList.add('is-invalid');
        if (!notValidatedTypes.includes(1)) {
            notValidatedTypes.push(1);
        }
    }else {
        typeInput.classList.remove('is-invalid');
        typeInput.classList.add('is-valid');
        removeA(notValidatedTypes, 1);
    }
});

formTypes.addEventListener('submit', (e) => {
    if (notValidatedTypes.length > 0) {
        e.preventDefault();
    }
});
</script>
@endsection