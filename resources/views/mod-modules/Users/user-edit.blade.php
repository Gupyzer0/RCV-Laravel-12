@extends('layouts.app')

@section('module')
<div class="container mt-3 mb-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Actualizar Usuario</div>

                <div class="card-body">
                    <form method="POST" id="register_form" action="/admin/edit-user/{{$id}}">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">

                        <div class="form-row border-bottom border-dark">
                            <div class="form-group col-md-6">
                                <label for="name" class="col-form-label text-md-right">Nombres</label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{$user->name}}" autocomplete="off" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="lastname" class="col-form-label text-md-right">Apellidos</label>
                                <input id="lastname" type="text" class="form-control @error('lastname') is-invalid @enderror" name="lastname" value="{{$user->lastname}}" autocomplete="off">

                                @error('lastname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row border-bottom border-dark">
                            <div class="form-group col-md-6">
                                <label for="ci" class="col-form-label text-md-right">Documento de identificación</label>
                                <div class="input-group mt-2">
                                    <div class="input-group-prepend">
                                        <select name="id_type" class="form-control custom-select" required>
                                            <option value="{{$identification[2]}}">{{$identification[2]}}</option>
                                            <option value="V-">V</option>
                                            <option value="E-">E</option>
                                            <option value="R-">R</option>
                                            <option value="P-">P</option>
                                            <option value="C-">C</option>
                                            <option value="J-">J</option>
                                            <option value="G-">G</option>
                                        </select>
                                    </div>
                                    <input type="text" name="ci" id="ci" value="{{$identification[1]}}" class="form-control @error('ci') is-invalid @enderror" placeholder="Cédula">

                                    @error('ci')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="phone_number" class="col-form-label text-md-right">Número de teléfono</label>
                                <div class="input-group mt-2">
                                    <div class="input-group-prepend">
                                        <select name="sp_prefix" class="form-control custom-select">
                                            <option value="{{$phone_number[0]}}-">{{$phone_number[0]}}</option>
                                            <option value="212-">212</option>
                                            <option value="412-">412</option>
                                            <option value="416-">416</option>
                                            <option value="426-">426</option>
                                            <option value="414-">414</option>
                                            <option value="424-">424</option>
                                        </select>
                                    </div>
                                    <input type="text" name="phone_number" id="phone_number" value="{{$phone_number[1]}}" class="form-control @error('phone_number') is-invalid @enderror" placeholder="...">

                                    @error('phone_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-row border-bottom border-dark">
                            <div class="form-group col-md-6">
                                <label for="username" class="col-form-label text-md-right">Nombre de usuario</label>
                                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{$user->username }}" autocomplete="off">

                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="email" class="col-form-label text-md-right">Correo Electrónico</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{$user->email }}" autocomplete="off">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row border-bottom border-dark">
                            <div class="form-group col-md-6">
                                <label for="office_id" class="col-form-label text-md-right">Oficina</label>
                                <select id="office_id" name="office_id" class="form-control custom-select">
                                    <option value="{{$user->office_id}}">{{$user->office->office_address}}</option>
                                    @foreach($offices as $office)
                                    <option value="{{$office->id}}">{{$office->office_address}}</option>
                                    @endforeach
                                </select>
                            </div>

                            @if( Auth::user()->type == 4 )
                            <div class="form-group col-md-6">
                                <label for="admint" class="col-form-label text-md-right">Admin: {{Auth::user()->type}} </label>
                                <select id="admint" name="admint" class="form-control custom-select">
                                    @foreach($admin as $admins)
                                    <option value="{{$admins->type}}">{{$admins->name.' '.$admins->type}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            <div class="form-group col-md-6">
                                <label for="superv" class="col-form-label text-md-right">Supervisor: </label>
                                <select id="superv" name="superv" class="form-control custom-select">
                                    @if(!$user->mod_id)
                                    <option value="">No Posee</option>
                                    @else
                                    <option value="{{$user->mod_id}}">{{$user->moderator->name}}</option>
                                    @endif
                                    @foreach($supervisor as $supervisors)
                                    <option value="{{$supervisors->id}}">{{$supervisors->name}}</option>
                                    @endforeach
                                    <option value="">No Posee</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="google_maps_link" class="col-form-label text-md-right">Url de GoogleMaps</label>
                                <div class="input-group">
                                    <input type="text" id="google_maps_link" name="google_maps_link" class="form-control @error('google_maps_link') is-invalid @enderror" value="{{$user->url}}">
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="profit_percentage" class="col-form-label text-md-right">Porcentaje de Ganancias</label>
                                <div class="input-group">
                                    <input type="text" id="porcen" name="profit_percentage" class="form-control @error('porcen') is-invalid @enderror" value="{{$user->profit_percentage}}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                                @error('porcen')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="ncontra" class="col-form-label text-md-right">Cantidad de Contratos</label>
                                <input id="ncontra" type="number" class="form-control @error('ncontra') is-invalid @enderror" name="ncontra" value="{{$user->ncontra }}" autocomplete="off">

                                @error('ncontra')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0 mt-2">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Actualizar
                                </button>

                                <a href="{{ route('index.users') }}" class="btn btn-danger" style="width: 90px;">
                                    Volver
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <a href="/admin/edit-user/password/{{$id}}" class="btn btn-warning ml-auto">
                Cambiar contraseña
            </a>
        </div>
    </div>
</div>
<script src="{{asset('js/Form-Validations/Users.js')}}"></script>
@endsection