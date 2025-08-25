@extends('layouts.app')

@section('module')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Registrar tipo de vehículo</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('user.register.type.submit') }}" id="types_form">
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

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Registrar
                                </button>

                                <a href="{{ route('user.index.vehicle.types') }}" class="btn btn-danger" style="width: 90px;">
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
<script src="{{asset('js/Form-Validations/Types.js')}}"></script>
@endsection