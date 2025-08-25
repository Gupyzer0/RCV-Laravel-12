@extends('layouts.admin-modules')

@section('module')

{{-- Asegúrate de que la variable $user esté disponible en esta vista, pasada desde el controlador --}}
{{-- Por ejemplo, en tu controlador: return view('users.edit', compact('user')); --}}

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10"> {{-- Aumentado el ancho para más campos --}}
            <div class="card">
                <div class="card-header">{{ __('Editar Usuario') }}</div> {{-- Título cambiado a Editar --}}

                <div class="card-body">
                    {{-- Formulario de Edición --}}
                    {{-- La acción apunta a la ruta de actualización y se usa el método PUT --}}
                    <form method="POST" action="/admin/edit-user/{{$id}}" id="edit_form" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') {{-- Directiva para usar el método PUT en Laravel --}}

                        {{-- SECCIÓN 1: IDENTIFICACIÓN PRINCIPAL --}}
                        <div class="form-row border-bottom border-dark pb-3 mb-3">
                            <div class="form-group col-md-4">
                                <label for="id_type" class="col-form-label text-md-right">Tipo de Persona/Documento</label>
                                {{-- Se carga el valor existente del usuario, o el old() si hay error de validación --}}
                                <select name="id_type" class="form-control @error('id_type') is-invalid @enderror" required id="id_type">
                                    <option value="">- Seleccione -</option>
                                    <option value="V-" {{ old('id_type', $user->id_type) == 'V-' ? 'selected' : '' }}>Natural - Venezolano (V)</option>
                                    <option value="E-" {{ old('id_type', $user->id_type) == 'E-' ? 'selected' : '' }}>Natural - Extranjero (E)</option>
                                    <option value="J-" {{ old('id_type', $user->id_type) == 'J-' ? 'selected' : '' }}>Jurídica - (J)</option>
                                    {{-- Agrega la opción G- si aplica, basándote en tu registro original --}}
                                    {{-- <option value="G-" {{ old('id_type', $user->id_type) == 'G-' ? 'selected' : '' }}>G-</option> --}}
                                </select>
                                @error('id_type')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                {{-- La etiqueta y placeholder cambian con JS, pero aquí se pone el valor actual --}}
                                <label for="ci" id="ci_label" class="col-form-label text-md-right">Documento Principal</label>
                                <input id="ci" type="text" class="form-control @error('ci') is-invalid @enderror" name="ci" value="{{ old('ci', $user->ci) }}" autocomplete="off" placeholder="Número">
                                @error('ci')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="ci_document" class="col-form-label text-md-right">Cargar Documento Principal</label>
                                <input id="ci_document" type="file" class="form-control-file @error('ci_document') is-invalid @enderror" name="ci_document">
                                @error('ci_document')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                                {{-- Mostrar el archivo actual si existe --}}
                                @if($user->ci_document_path) {{-- Asumiendo que guardas la ruta en un campo llamado ci_document_path --}}
                                    <small class="form-text text-muted">Archivo actual: <a href="{{ asset('storage/' . $user->ci_document_path) }}" target="_blank">{{ basename($user->ci_document_path) }}</a></small>
                                @endif
                            </div>
                        </div>

                        {{-- SECCIÓN 2: CAMPOS PARA PERSONA NATURAL (V, E) --}}
                        {{-- El display:none inicial se mantiene, el JS lo mostrará si aplica --}}
                        <div id="persona_natural_fields" style="display:none;">
                            <h5 class="mt-3">Datos Persona Natural</h5>
                            <div class="form-row border-bottom border-dark pb-3 mb-3">
                                <div class="form-group col-md-6">
                                    <label for="name" class="col-form-label text-md-right">Nombres</label>
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" autocomplete="off">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="lastname" class="col-form-label text-md-right">Apellidos</label>
                                    <input id="lastname" type="text" class="form-control @error('lastname') is-invalid @enderror" name="lastname" value="{{ old('lastname', $user->lastname) }}" autocomplete="off">
                                    @error('lastname')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="rif_pn" class="col-form-label text-md-right">RIF (Personal)</label>
                                    <input id="rif_pn" type="text" class="form-control @error('rif_pn') is-invalid @enderror" name="rif_pn" value="{{ old('rif_pn', $user->rif_pn) }}" autocomplete="off">
                                    @error('rif_pn')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="rif_pn_document" class="col-form-label text-md-right">Cargar Documento RIF (Personal)</label>
                                    <input id="rif_pn_document" type="file" class="form-control-file @error('rif_pn_document') is-invalid @enderror" name="rif_pn_document">
                                    @error('rif_pn_document')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                    @if($user->rif_pn_document_path)
                                        <small class="form-text text-muted">Archivo actual: <a href="{{ asset('storage/' . $user->rif_pn_document_path) }}" target="_blank">{{ basename($user->rif_pn_document_path) }}</a></small>
                                    @endif
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="direccion_pn" class="col-form-label text-md-right">Dirección</label>
                                    <input id="direccion_pn" type="text" class="form-control @error('direccion_pn') is-invalid @enderror" name="direccion_pn" value="{{ old('direccion_pn', $user->direccion_pn) }}" autocomplete="off">
                                    @error('direccion_pn')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="google_maps_url_pn" class="col-form-label text-md-right">URL Google Maps</label>
                                    <input id="google_maps_url_pn" type="url" class="form-control @error('google_maps_url_pn') is-invalid @enderror" name="google_maps_url_pn" value="{{ old('google_maps_url_pn', $user->google_maps_url_pn) }}" autocomplete="off" placeholder="https://maps.google.com/...">
                                    @error('google_maps_url_pn')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="foto_establecimiento_pn_document" class="col-form-label text-md-right">Foto del Establecimiento</label>
                                    <input id="foto_establecimiento_pn_document" type="file" class="form-control-file @error('foto_establecimiento_pn_document') is-invalid @enderror" name="foto_establecimiento_pn_document">
                                    @error('foto_establecimiento_pn_document')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                    @if($user->foto_establecimiento_pn_document_path)
                                        <small class="form-text text-muted">Archivo actual: <a href="{{ asset('storage/' . $user->foto_establecimiento_pn_document_path) }}" target="_blank">{{ basename($user->foto_establecimiento_pn_document_path) }}</a></small>
                                    @endif
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="foto_carnet_pn_document" class="col-form-label text-md-right">Foto Tipo Carnet (Fondo Blanco)</label>
                                    <input id="foto_carnet_pn_document" type="file" class="form-control-file @error('foto_carnet_pn_document') is-invalid @enderror" name="foto_carnet_pn_document">
                                    @error('foto_carnet_pn_document')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                    @if($user->foto_carnet_pn_document_path)
                                        <small class="form-text text-muted">Archivo actual: <a href="{{ asset('storage/' . $user->foto_carnet_pn_document_path) }}" target="_blank">{{ basename($user->foto_carnet_pn_document_path) }}</a></small>
                                    @endif
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="instagram_pn" class="col-form-label text-md-right">Instagram (Opcional)</label>
                                    <input id="instagram_pn" type="text" class="form-control @error('instagram_pn') is-invalid @enderror" name="instagram_pn" value="{{ old('instagram_pn', $user->instagram_pn) }}" autocomplete="off" placeholder="@usuario">
                                    @error('instagram_pn')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="facebook_pn" class="col-form-label text-md-right">Facebook (Opcional)</label>
                                    <input id="facebook_pn" type="text" class="form-control @error('facebook_pn') is-invalid @enderror" name="facebook_pn" value="{{ old('facebook_pn', $user->facebook_pn) }}" autocomplete="off" placeholder="facebook.com/usuario">
                                    @error('facebook_pn')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- SECCIÓN 3: CAMPOS PARA PERSONA JURÍDICA (J, G) --}}
                        {{-- El display:none inicial se mantiene, el JS lo mostrará si aplica --}}
                        <div id="persona_juridica_fields" style="display:none;">
                            <h5 class="mt-3">Datos Persona Jurídica</h5>
                            <div class="form-row border-bottom border-dark pb-3 mb-3">
                                <div class="form-group col-md-6">
                                    <label for="razon_social_pj" class="col-form-label text-md-right">Razón Social</label>
                                    <input id="razon_social_pj" type="text" class="form-control @error('razon_social_pj') is-invalid @enderror" name="razon_social_pj" value="{{ old('razon_social_pj', $user->razon_social_pj) }}" autocomplete="off">
                                    @error('razon_social_pj')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="registro_mercantil_pj" class="col-form-label text-md-right">Registro Mercantil</label>
                                    <input id="registro_mercantil_pj" type="text" class="form-control @error('registro_mercantil_pj') is-invalid @enderror" name="registro_mercantil_pj" value="{{ old('registro_mercantil_pj', $user->registro_mercantil_pj) }}" autocomplete="off">
                                    @error('registro_mercantil_pj')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="cedula_rl_pj" class="col-form-label text-md-right">Cédula Representante Legal</label>
                                    <input id="cedula_rl_pj" type="text" class="form-control @error('cedula_rl_pj') is-invalid @enderror" name="cedula_rl_pj" value="{{ old('cedula_rl_pj', $user->cedula_rl_pj) }}" autocomplete="off">
                                    @error('cedula_rl_pj')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="cedula_rl_pj_document" class="col-form-label text-md-right">Cargar Doc. Cédula Rep. Legal</label>
                                    <input id="cedula_rl_pj_document" type="file" class="form-control-file @error('cedula_rl_pj_document') is-invalid @enderror" name="cedula_rl_pj_document">
                                    @error('cedula_rl_pj_document')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                    @if($user->cedula_rl_pj_document_path)
                                        <small class="form-text text-muted">Archivo actual: <a href="{{ asset('storage/' . $user->cedula_rl_pj_document_path) }}" target="_blank">{{ basename($user->cedula_rl_pj_document_path) }}</a></small>
                                    @endif
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="direccion_pj" class="col-form-label text-md-right">Dirección Fiscal</label>
                                    <input id="direccion_pj" type="text" class="form-control @error('direccion_pj') is-invalid @enderror" name="direccion_pj" value="{{ old('direccion_pj', $user->direccion_pj) }}" autocomplete="off">
                                    @error('direccion_pj')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="google_maps_url_pj" class="col-form-label text-md-right">URL Google Maps (Establecimiento)</label>
                                    <input id="google_maps_url_pj" type="url" class="form-control @error('google_maps_url_pj') is-invalid @enderror" name="google_maps_url_pj" value="{{ old('google_maps_url_pj', $user->google_maps_url_pj) }}" autocomplete="off" placeholder="https://maps.google.com/...">
                                    @error('google_maps_url_pj')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="telefono_pj" class="col-form-label text-md-right">Número de teléfono (Principal)</label>
                                    <div class="input-group mt-0">
                                        <div class="input-group-prepend">
                                            {{-- Se carga el valor existente del prefijo --}}
                                            <select name="sp_prefixj" class="form-control custom-select @error('sp_prefixj') is-invalid @enderror" required id="number_code_pj"> {{-- ID único para PJ --}}
                                                <option value="">-</option>
                                                <option value="212-" {{ old('sp_prefixj', $user->sp_prefixj) == '212-' ? 'selected' : '' }}>212</option>
                                                <option value="412-" {{ old('sp_prefixj', $user->sp_prefixj) == '412-' ? 'selected' : '' }}>412</option>
                                                <option value="416-" {{ old('sp_prefixj', $user->sp_prefixj) == '416-' ? 'selected' : '' }}>416</option>
                                                <option value="426-" {{ old('sp_prefixj', $user->sp_prefixj) == '426-' ? 'selected' : '' }}>426</option>
                                                <option value="414-" {{ old('sp_prefixj', $user->sp_prefixj) == '414-' ? 'selected' : '' }}>414</option>
                                                <option value="424-" {{ old('sp_prefixj', $user->sp_prefixj) == '424-' ? 'selected' : '' }}>424</option>
                                            </select>
                                        </div>
                                        {{-- Se carga el valor existente del número --}}
                                        <input type="text" name="telefono_pj" id="telefono_pj" value="{{ old('telefono_pj', $user->telefono_pj) }}" class="form-control @error('telefono_pj') is-invalid @enderror" placeholder="Número">
                                        @error('telefono_pj')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                        @error('sp_prefixj')
                                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="correo_pj" class="col-form-label text-md-right">Correo (Empresa)</label>
                                    <input id="correo_pj" type="email" class="form-control @error('correo_pj') is-invalid @enderror" name="correo_pj" value="{{ old('correo_pj', $user->correo_pj) }}" autocomplete="off">
                                    @error('correo_pj')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="islr_pj_document" class="col-form-label text-md-right">Impuesto Sobre la Renta (ISLR)</label>
                                    <input id="islr_pj_document" type="file" class="form-control-file @error('islr_pj_document') is-invalid @enderror" name="islr_pj_document">
                                    @error('islr_pj_document')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                    @if($user->islr_pj_document_path)
                                        <small class="form-text text-muted">Archivo actual: <a href="{{ asset('storage/' . $user->islr_pj_document_path) }}" target="_blank">{{ basename($user->islr_pj_document_path) }}</a></small>
                                    @endif
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="foto_establecimiento_pj_document" class="col-form-label text-md-right">Foto del Establecimiento</label>
                                    <input id="foto_establecimiento_pj_document" type="file" class="form-control-file @error('foto_establecimiento_pj_document') is-invalid @enderror" name="foto_establecimiento_pj_document">
                                    @error('foto_establecimiento_pj_document')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                    @if($user->foto_establecimiento_pj_document_path)
                                        <small class="form-text text-muted">Archivo actual: <a href="{{ asset('storage/' . $user->foto_establecimiento_pj_document_path) }}" target="_blank">{{ basename($user->foto_establecimiento_pj_document_path) }}</a></small>
                                    @endif
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="foto_carnet_rl_pj_document" class="col-form-label text-md-right">Foto Carnet Rep. Legal</label>
                                    <input id="foto_carnet_rl_pj_document" type="file" class="form-control-file @error('foto_carnet_rl_pj_document') is-invalid @enderror" name="foto_carnet_rl_pj_document">
                                    @error('foto_carnet_rl_pj_document')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                    @if($user->foto_carnet_rl_pj_document_path)
                                        <small class="form-text text-muted">Archivo actual: <a href="{{ asset('storage/' . $user->foto_carnet_rl_pj_document_path) }}" target="_blank">{{ basename($user->foto_carnet_rl_pj_document_path) }}</a></small>
                                    @endif
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="instagram_pj" class="col-form-label text-md-right">Instagram (Empresa, Opcional)</label>
                                    <input id="instagram_pj" type="text" class="form-control @error('instagram_pj') is-invalid @enderror" name="instagram_pj" value="{{ old('instagram_pj', $user->instagram_pj) }}" autocomplete="off" placeholder="@empresa">
                                    @error('instagram_pj')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="facebook_pj" class="col-form-label text-md-right">Facebook (Empresa, Opcional)</label>
                                    <input id="facebook_pj" type="text" class="form-control @error('facebook_pj') is-invalid @enderror" name="facebook_pj" value="{{ old('facebook_pj', $user->facebook_pj) }}" autocomplete="off" placeholder="facebook.com/empresa">
                                    @error('facebook_pj')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- SECCIÓN 4: DATOS DE CONTACTO Y CUENTA (Comunes) --}}
                        <h5 class="mt-3">Datos de Contacto Principal y Cuenta</h5>
                        <div class="form-row border-bottom border-dark pb-3 mb-3">
                            <div class="form-group col-md-6">
                                <label for="phone_number" class="col-form-label text-md-right">Número de teléfono (Principal)</label>
                                <div class="input-group mt-0">
                                    <div class="input-group-prepend">
                                        {{-- Se carga el valor existente del prefijo --}}
                                        <select name="sp_prefix" class="form-control custom-select @error('sp_prefix') is-invalid @enderror" required id="number_code">
                                            <option value="">-</option>
                                            <option value="212-" {{ old('sp_prefix', $user->sp_prefix) == '212-' ? 'selected' : '' }}>212</option>
                                            <option value="412-" {{ old('sp_prefix', $user->sp_prefix) == '412-' ? 'selected' : '' }}>412</option>
                                            <option value="416-" {{ old('sp_prefix', $user->sp_prefix) == '416-' ? 'selected' : '' }}>416</option>
                                            <option value="426-" {{ old('sp_prefix', $user->sp_prefix) == '426-' ? 'selected' : '' }}>426</option>
                                            <option value="414-" {{ old('sp_prefix', $user->sp_prefix) == '414-' ? 'selected' : '' }}>414</option>
                                            <option value="424-" {{ old('sp_prefix', $user->sp_prefix) == '424-' ? 'selected' : '' }}>424</option>
                                        </select>
                                    </div>
                                    {{-- Se carga el valor existente del número --}}
                                    <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}" class="form-control @error('phone_number') is-invalid @enderror" placeholder="Número">
                                    @error('phone_number')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                    @error('sp_prefix')
                                    <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="email" class="col-form-label text-md-right">Correo Eléctronico (Principal)</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" autocomplete="off">
                                @error('email')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row border-bottom border-dark pb-3 mb-3">
                             <div class="form-group col-md-4">
                                <label for="username" class="col-form-label text-md-right">Nombre de usuario</label>
                                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username', $user->username) }}" autocomplete="off">
                                @error('username')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            {{-- Nota: Por seguridad, los campos de contraseña no se pre-llenan. --}}
                            {{-- Generalmente se pide al usuario que ingrese la contraseña actual o una nueva. --}}
                            {{-- Aquí dejo los campos para cambiarla si se desea. --}}
                            <div class="form-group col-md-4">
                                <label for="password" class="col-form-label text-md-right">Contraseña (dejar vacío para no cambiar)</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
                                @error('password')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="password-confirm" class="col-form-label text-md-right">Confirmar Contraseña</label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-row border-bottom border-dark pb-3 mb-3">
                            <div class="form-group col-md-4">
                                <label for="office_id" class="col-form-label text-md-right">Oficina</label>
                                <select id="office_id" name="office_id" class="form-control custom-select @error('office_id') is-invalid @enderror">
                                    <option value="">- Seleccionar Oficina -</option>
                                    @foreach($offices as $office) {{-- Asumiendo que $offices está disponible --}}
                                    {{-- Se marca como seleccionado si coincide con el valor del usuario o el old() --}}
                                    <option value="{{$office->id}}" {{ old('office_id', $user->office_id) == $office->id ? 'selected' : '' }}>{{$office->office_address}}</option>
                                    @endforeach
                                </select>
                                @error('office_id')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="form-group col-md-2">
                                <label for="profit_percentage" class=" col-form-label text-md-right">% Vendedor</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" id="profit_percentage" value="{{ old('profit_percentage', $user->profit_percentage) }}" name="profit_percentage" class="form-control @error('profit_percentage') is-invalid @enderror">
                                    <div class="input-group-append"><span class="input-group-text">%</span></div>
                                    @error('profit_percentage')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="ncontra" class="col-form-label text-md-right">N° Contratos</label>
                                <input id="ncontra" type="number" class="form-control @error('ncontra') is-invalid @enderror" name="ncontra" value="{{ old('ncontra', $user->ncontra) }}" autocomplete="off">
                                @error('ncontra')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0 mt-3">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Actualizar Datos
                                </button>
                                {{-- Ajusta la ruta de volver si es necesario, quizás a la vista 'show' o al índice --}}
                                <a href="{{ route('index.users') }}" class="btn btn-danger" style="width: 90px;">
                                    Cancelar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Incluir el script JavaScript adaptado --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const idTypeSelect = document.getElementById('id_type');
    const ciInput = document.getElementById('ci');
    const ciLabel = document.getElementById('ci_label');

    const naturalFields = document.getElementById('persona_natural_fields');
    const juridicaFields = document.getElementById('persona_juridica_fields');

    // Campos de Persona Natural que estaban antes fuera
    const nameField = document.getElementById('name');
    const lastnameField = document.getElementById('lastname');
    // const phoneFields = document.getElementById('phone_fields_group'); // Si agrupas el teléfono
    // const emailField = document.getElementById('email'); // El email principal ya está fuera

    function toggleFields() {
        const selectedType = idTypeSelect.value;

        // Ocultar todos los campos condicionales inicialmente
        naturalFields.style.display = 'none';
        juridicaFields.style.display = 'none';

        // Deshabilitar campos para que no se envíen si están ocultos (opcional, pero bueno para validación)
        // Puedes hacerlo más granular si es necesario
        setRequired(naturalFields, false);
        setRequired(juridicaFields, false);

        if (selectedType === 'V-' || selectedType === 'E-') {
            naturalFields.style.display = 'block';
            // Marcar campos como requeridos, excepto opcionales.
            // En edición, la validación 'required' para archivos puede ser más compleja
            // si no se sube un nuevo archivo. Laravel lo manejará en el backend.
            // Aquí solo aplicamos el atributo 'required' a los campos de texto/select.
            setRequired(naturalFields, true, ['rif_pn', 'rif_pn_document', 'instagram_pn', 'facebook_pn']);
            ciLabel.textContent = 'Cédula';
            ciInput.placeholder = 'Número de Cédula';
            // Asegurar que name y lastname sean requeridos
            if(nameField) nameField.required = true;
            if(lastnameField) lastnameField.required = true;
             if(document.getElementById('razon_social_pj')) document.getElementById('razon_social_pj').required = false;


        } else if (selectedType === 'J-' || selectedType === 'G-') {
            juridicaFields.style.display = 'block';
            // Marcar campos como requeridos, excepto opcionales (archivos y redes sociales).
            setRequired(juridicaFields, true, ['instagram_pj', 'facebook_pj', 'islr_pj_document', 'foto_establecimiento_pj_document', 'foto_carnet_rl_pj_document']);
            ciLabel.textContent = 'RIF';
            ciInput.placeholder = 'Número de RIF';
            // Asegurar que razon_social_pj sea requerido y name/lastname no
            if(document.getElementById('razon_social_pj')) document.getElementById('razon_social_pj').required = true;
            if(nameField) nameField.required = false;
            if(lastnameField) lastnameField.required = false;
        } else {
            ciLabel.textContent = 'Documento Principal';
            ciInput.placeholder = 'Número';
             if(nameField) nameField.required = false;
            if(lastnameField) lastnameField.required = false;
             if(document.getElementById('razon_social_pj')) document.getElementById('razon_social_pj').required = false;
        }
    }

    function setRequired(container, isRequired, optionalFields = []) {
        // Selecciona inputs, selects, textareas que no sean de tipo file y no estén en la lista de opcionales
        container.querySelectorAll('input:not([type="file"]):not([type="button"]):not([type="submit"]), select, textarea').forEach(function(element) {
            let isOptional = optionalFields.some(optFieldName => element.name === optFieldName || element.id === optFieldName);
            if (!isOptional) {
                 element.required = isRequired;
            } else {
                 element.required = false; // Asegurar que los opcionales no sean requeridos
            }
        });
         // Para los campos de archivo, la validación de 'required' es más compleja con JS puro si se quiere
         // mostrar el asterisco, Laravel se encargará en el backend.
         // Aquí solo nos aseguramos que los campos de texto/select etc. tengan el atributo.
    }


    idTypeSelect.addEventListener('change', toggleFields);

    // Ejecutar al cargar la página para mostrar los campos correctos basados en los datos del usuario
    toggleFields();
});
</script>
@endsection
