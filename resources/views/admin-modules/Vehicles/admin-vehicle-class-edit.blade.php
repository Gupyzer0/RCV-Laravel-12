@extends('layouts.app')

@section('module')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Actualizar clase de vehículo</div>

                <div class="card-body">
                    <form method="POST" action="/admin/edit-class/{{$vehicle_class->id}}" id="classes_form">
                        @csrf

                        <input type="hidden" name="_method" value="PUT">

                        <div class="form-group col-md-12">
                            <label for="class" class="col-form-label text-md-right">Clase de vehículo</label>
                            <input id="class" type="text" class="form-control @error('class') is-invalid @enderror" name="class" placeholder="..." autocomplete="off" value="{{$vehicle_class->class}}">

                            @error('class')
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

                                <a href="{{ route('index.vehicle.classes') }}" class="btn btn-danger" style="width: 90px;">
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
const formClass = document.getElementById('classes_form');
const classInput = document.getElementById('class');

let notValidatedClass = [];

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

classInput.addEventListener('keyup', () => {
    if (classInput.value === '' || classInput.value == null) {
        classInput.classList.add('is-invalid');
        if (!notValidatedClass.includes(1)) {
            notValidatedClass.push(1);
        }
    }else {
        classInput.classList.remove('is-invalid');
        classInput.classList.add('is-valid');
        removeA(notValidatedClass, 1);
    }
});

formClass.addEventListener('submit', (e) => {
    if (notValidatedClass.length > 0) {
        e.preventDefault();
    }
});
</script>
@endsection