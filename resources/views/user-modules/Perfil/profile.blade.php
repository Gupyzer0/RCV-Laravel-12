@extends('layouts.app')
@section('module')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Información del Usuario</h5>
                    <a href="{{ route('index.users') }}" class="text-white text-decoration-none">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
                <div class="card-body">
                    <!-- Foto de perfil -->
                    <div class="text-center mb-4">
                        @if ($user->image)
                            <img src="{{ asset('uploads/fotosperfil/'. $user->id.'/'. $user->image) }}"
                                 alt="Foto de perfil"
                                 class="img-thumbnail rounded-circle"
                                 style="width: 150px; height: 150px;">
                        @else
                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center"
                                 style="width: 150px; height: 150px; margin: 0 auto;">
                                <i class="fas fa-user text-white" style="font-size: 80px;"></i>
                            </div>
                        @endif

                        <div class="mt-3 d-flex justify-content-center gap-2">
                            <a href="{{ route('user.download_profile', $user->id) }}"
                               class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-download"></i> Descargar Foto
                            </a> <a>&nbsp;</a>
                            <a href="#" data-toggle="modal" data-target="#fotoperfil"
                               class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-upload"></i> Cargar Foto
                            </a>
                        </div>
                    </div>


                    <!-- Información del sistema -->
                    <h5 class="card-title text-center border-bottom pb-2">Información del Sistema</h5>
                    <div class="row mb-4">
                        <div class="col-md-4 text-center">
                            <h6 class="card-subtitle mb-2 text-muted">Fecha de Ingreso</h6>
                            <strong>{{ \Carbon\Carbon::parse($user->created_at)->format('d-m-Y') }}</strong>
                        </div>
                        <div class="col-md-4 text-center">
                            <h6 class="card-subtitle mb-2 text-muted">Nombre de Usuario</h6>
                            <strong>{{ $user->username }}</strong>
                        </div>
                        <div class="col-md-4 text-center">
                            <h6 class="card-subtitle mb-2 text-muted">Porcentaje de Ganancia</h6>
                            <strong>{{ $user->profit_percentage }}%</strong>
                        </div>
                    </div>

                    <!-- Información personal -->
                    <h5 class="card-title text-center border-top border-bottom py-2">Información Personal</h5>
                    <div class="row mb-4">
                        <div class="col-md-4 text-center">
                            <h6 class="card-subtitle mb-2 text-muted">Nombres</h6>
                            <strong>{{ $user->name }}</strong>
                        </div>
                        <div class="col-md-4 text-center">
                            <h6 class="card-subtitle mb-2 text-muted">Apellidos</h6>
                            <strong>{{ $user->lastname }}</strong>
                        </div>
                        <div class="col-md-4 text-center">
                            <h6 class="card-subtitle mb-2 text-muted">Cédula</h6>
                            <strong>{{ $user->ci }}</strong>
                        </div>
                    </div>

                    <!-- Correo -->
                    <h5 class="card-title text-center border-top border-bottom py-2">Correo</h5>
                    <div class="row mb-4">
                        <div class="col-12 text-center">
                            <strong>{{ $user->email }}</strong>
                        </div>
                    </div>

                    <!-- Oficina -->
                    <h5 class="card-title text-center border-top border-bottom py-2">Oficina</h5>
                    <div class="row mb-4">
                        <div class="col-12 text-center">
                            <strong>{{ $user->office->office_address . ', ' . $user->office->estado->estado . ', ' . $user->office->municipio->municipio . ', ' . $user->office->parroquia->parroquia }}</strong>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
      <!-- IMAGE Modal-->
      <div class="modal fade" id="fotoperfil" tabindex="-1" role="dialog" aria-labelledby="fotoperfilModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cargar foto de perfil</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/user/upload-profile/{{Auth::user()->id}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label for="image">Seleccionar una imagen (JPG):</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" id="image" accept="image/jpeg" required>

                        @error('image')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <!-- Preview de la imagen -->
                        <div class="mt-3">
                            <img id="imagePreview" src="#" alt="Vista previa de la imagen" style="max-width: 100px; max-height: 100px; display: none;">
                        </div>

                        <div class="modal-footer">
                            <input type="submit" name="submit" class="btn btn-primary" value="Cargar">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
