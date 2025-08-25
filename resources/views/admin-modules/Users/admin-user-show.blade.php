@extends('layouts.admin-modules')

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
                    {{-- Foto de perfil --}}
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
                            @if ($user->image) {{-- Mostrar botón de descarga solo si hay imagen --}}
                                <a href="{{ route('download_profile', $user->id) }}"
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-download"></i> Descargar Foto
                                </a>
                            @endif
                            <a href="#" data-toggle="modal" data-target="#fotoperfil"
                               class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-upload"></i> Cargar Foto
                            </a>
                        </div>
                    </div>

                    {{-- Información del sistema --}}
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

                    {{-- Información personal --}}
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
                            <h6 class="card-subtitle mb-2 text-muted">Cédula/RIF</h6>
                            <strong>{{ $user->ci }}</strong>
                        </div>
                    </div>

                    {{-- Correo y Teléfono --}}
                    <h5 class="card-title text-center border-top border-bottom py-2">Contacto</h5>
                    <div class="row mb-4">
                        <div class="col-md-6 text-center">
                            <h6 class="card-subtitle mb-2 text-muted">Correo Electrónico</h6>
                            <strong>{{ $user->email }}</strong>
                        </div>
                         <div class="col-md-6 text-center">
                            <h6 class="card-subtitle mb-2 text-muted">Número de Teléfono</h6>
                            <strong>{{ $user->phone_number }}</strong>
                        </div>
                    </div>

                    {{-- Información Bancaria --}}
                    <h5 class="card-title text-center border-top border-bottom py-2">Información Bancaria</h5>
                     <div class="row mb-4">
                        <div class="col-md-4 text-center">
                            <h6 class="card-subtitle mb-2 text-muted">Banco</h6>
                            {{-- Asumiendo que bank_id es una relación o un campo que puedes mostrar --}}
                            <strong>{{ $user->bank->name }}</strong>
                        </div>
                        <div class="col-md-4 text-center">
                            <h6 class="card-subtitle mb-2 text-muted">Tipo de Cuenta</h6>
                             <strong>{{ $user->bank_account }}</strong> {{-- Asumiendo que bank_account indica el tipo --}}
                        </div>
                         <div class="col-md-4 text-center">
                            <h6 class="card-subtitle mb-2 text-muted">Número de Cuenta</h6>
                            <strong>{{ $user->bank_number }}</strong>
                        </div>
                         <div class="col-md-12 text-center mt-3">
                            <h6 class="card-subtitle mb-2 text-muted">Teléfono Asociado (Pago Móvil)</h6>
                            <strong>{{ $user->bank_phone }}</strong>
                        </div>
                    </div>

                    {{-- Oficina --}}
                    <h5 class="card-title text-center border-top border-bottom py-2">Oficina</h5>
                    <div class="row mb-4">
                        <div class="col-12 text-center">
                            {{-- Asegúrate de que las relaciones office, estado, municipio, parroquia estén cargadas --}}
                            <strong>{{ $user->office->office_address . ', ' . $user->office->estado->estado . ', ' . $user->office->municipio->municipio . ', ' . $user->office->parroquia->parroquia }}</strong>
                        </div>
                    </div>

                    {{-- Información Adicional y Documentos (Condicional) --}}
                    @if (substr($user->ci, 0, 1) === 'V')
                        {{-- Mostrar información de Persona Natural (PN) si CI empieza con 'V' --}}
                        <h5 class="card-title text-center border-top border-bottom py-2">Información Adicional (Persona Natural)</h5>
                        <div class="row mb-4">
                            <div class="col-md-6 text-center">
                                <h6 class="card-subtitle mb-2 text-muted">Dirección PN</h6>
                                <strong>{{ $user->direccion_pn }}</strong>
                            </div>
                            <div class="col-md-6 text-center">
                                <h6 class="card-subtitle mb-2 text-muted">URL Google Maps PN</h6>
                                <strong><a href="{{ $user->google_maps_url_pn }}" target="_blank">{{ $user->google_maps_url_pn }}</a></strong>
                            </div>
                             <div class="col-md-6 text-center mt-3">
                                <h6 class="card-subtitle mb-2 text-muted">Instagram PN</h6>
                                <strong>{{ $user->instagram_pn }}</strong>
                            </div>
                            <div class="col-md-6 text-center mt-3">
                                <h6 class="card-subtitle mb-2 text-muted">Facebook PN</h6>
                                <strong>{{ $user->facebook_pn }}</strong>
                            </div>
                        </div>

                        {{-- Documentos Descargables PN --}}
                        <h5 class="card-title text-center border-top border-bottom py-2">Documentos Descargables (PN)</h5>
                        <div class="row mb-4 justify-content-center">
                            @if ($user->ci_document)
                                <div class="col-auto mb-2">
                                    <a href="{{ asset($user->ci_document) }}" class="btn btn-outline-secondary" download>
                                        <i class="fas fa-download"></i> Cédula
                                    </a>
                                </div>
                            @endif
                             @if ($user->rif_document)
                                <div class="col-auto mb-2">
                                    <a href="{{ asset($user->rif_document) }}" class="btn btn-outline-secondary" download>
                                        <i class="fas fa-download"></i> RIF
                                    </a>
                                </div>
                            @endif
                             @if ($user->fotolocal) {{-- Usamos el nombre de la columna de la BD --}}
                                <div class="col-auto mb-2">
                                    <a href="{{ asset($user->fotolocal) }}" class="btn btn-outline-secondary" download>
                                        <i class="fas fa-download"></i> Foto Establecimiento
                                    </a>
                                </div>
                            @endif
                             @if ($user->fotocarnet) {{-- Usamos el nombre de la columna de la BD --}}
                                <div class="col-auto mb-2">
                                    <a href="{{ asset($user->fotocarnet) }}" class="btn btn-outline-secondary" download>
                                        <i class="fas fa-download"></i> Foto Carnet
                                    </a>
                                </div>
                            @endif
                        </div>

                    @elseif (substr($user->ci, 0, 1) === 'J')
                        {{-- Mostrar información de Persona Jurídica (PJ) si CI empieza con 'J' --}}
                         <h5 class="card-title text-center border-top border-bottom py-2">Información Adicional (Persona Jurídica)</h5>
                        <div class="row mb-4">
                            <div class="col-md-6 text-center">
                                <h6 class="card-subtitle mb-2 text-muted">Dirección PJ</h6>
                                <strong>{{ $user->direccion_pj }}</strong>
                            </div>
                            <div class="col-md-6 text-center">
                                <h6 class="card-subtitle mb-2 text-muted">URL Google Maps PJ</h6>
                                <strong><a href="{{ $user->google_maps_url_pj }}" target="_blank">{{ $user->google_maps_url_pj }}</a></strong>
                            </div>
                             <div class="col-md-6 text-center mt-3">
                                <h6 class="card-subtitle mb-2 text-muted">Teléfono PJ</h6>
                                <strong>{{ $user->telefono_pj }}</strong>
                            </div>
                            <div class="col-md-6 text-center mt-3">
                                <h6 class="card-subtitle mb-2 text-muted">Correo PJ</h6>
                                <strong>{{ $user->correo_pj }}</strong>
                            </div>
                             <div class="col-md-6 text-center mt-3">
                                <h6 class="card-subtitle mb-2 text-muted">Instagram PJ</h6>
                                <strong>{{ $user->instagram_pj }}</strong>
                            </div>
                            <div class="col-md-6 text-center mt-3">
                                <h6 class="card-subtitle mb-2 text-muted">Facebook PJ</h6>
                                <strong>{{ $user->facebook_pj }}</strong>
                            </div>
                        </div>

                        {{-- Documentos Descargables PJ --}}
                        <h5 class="card-title text-center border-top border-bottom py-2">Documentos Descargables (PJ)</h5>
                        <div class="row mb-4 justify-content-center">
                            @if ($user->ci_document_ju) {{-- Usamos el nombre de la columna de la BD --}}
                                <div class="col-auto mb-2">
                                    <a href="{{ asset($user->ci_document_ju) }}" class="btn btn-outline-secondary" download>
                                        <i class="fas fa-download"></i> Cédula Representante Legal
                                    </a>
                                </div>
                            @endif
                             @if ($user->rif_document)
                                <div class="col-auto mb-2">
                                    <a href="{{ asset($user->rif_document) }}" class="btn btn-outline-secondary" download>
                                        <i class="fas fa-download"></i> RIF Jurídico
                                    </a>
                                </div>
                            @endif
                            @if ($user->islr) {{-- Usamos el nombre de la columna de la BD --}}
                                <div class="col-auto mb-2">
                                    <a href="{{ asset($user->islr) }}" class="btn btn-outline-secondary" download>
                                        <i class="fas fa-download"></i> ISLR
                                    </a>
                                </div>
                            @endif
                             @if ($user->fotolocal) {{-- Usamos el nombre de la columna de la BD --}}
                                <div class="col-auto mb-2">
                                    <a href="{{ asset($user->fotolocal) }}" class="btn btn-outline-secondary" download>
                                        <i class="fas fa-download"></i> Foto Establecimiento
                                    </a>
                                </div>
                            @endif
                             @if ($user->fotocarnet) {{-- Usamos el nombre de la columna de la BD --}}
                                <div class="col-auto mb-2">
                                    <a href="{{ asset($user->fotocarnet) }}" class="btn btn-outline-secondary" download>
                                        <i class="fas fa-download"></i> Foto Carnet Representante Legal
                                    </a>
                                </div>
                            @endif
                        </div>

                    @endif


                    <div class="row justify-content-center mt-4">
                        <form action="/admin/delete-user/{{$user->id}}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">
                            <a href="/admin/edit-user/{{$user->id}}" class="btn btn-secondary me-2">Editar Usuario</a> {{-- Usando me-2 para margen derecho --}}
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Eliminar Usuario</button>
                        </form>
                    </div>


                </div>
            </div>
        </div>
    </div>
    {{-- IMAGE Modal--}}
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
                    <form action="/admin/upload-profile/{{$user->id}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label for="image">Seleccionar una imagen (JPG):</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" id="image" accept="image/jpeg" required>

                        @error('image')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        {{-- Preview de la imagen --}}
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

{{-- Script para mostrar la vista previa de la imagen --}}
<script>
    document.getElementById('image').addEventListener('change', function(event) {
        const [file] = event.target.files;
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imagePreview = document.getElementById('imagePreview');
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });
</script>

@endsection
