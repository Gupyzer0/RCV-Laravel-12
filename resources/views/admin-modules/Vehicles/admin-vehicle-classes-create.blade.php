@extends('layouts.app')

@section('module')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Registrar clase de vehículo</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register.class.submit') }}" id="classes_form">
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

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Registrar
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
<script src="{{asset('js/Form-Validations/Classes.js')}}"></script>
@endsection