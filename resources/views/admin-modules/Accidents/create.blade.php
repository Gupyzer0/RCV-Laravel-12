@extends('layouts.admin-modules')

@section('module')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Registro de Siniestro</h5>
                    </div>

                    <div class="card-body">
                    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
                        <form method="POST" action="{{ route('register.siniestro.submit') }}" id="accident_form" enctype="multipart/form-data">
                            @csrf

                            {{-- Sección de Datos de Póliza y Autocompletado --}}
                            <h5 class="mb-3 border-bottom pb-2">1. Datos de Póliza</h5>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="policy_number" class="form-label">Número de Póliza *</label>
                                        <input id="policy_number" type="text" class="form-control @error('policy_number') is-invalid @enderror" 
                                               name="policy_number" value="{{ old('policy_number') }}" required autofocus>
                                        @error('policy_number')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <small class="form-text text-muted">Ingrese el número y presione Tab para autocompletar</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="insured_name" class="form-label">Nombre del Asegurado *</label>
                                        <input id="insured_name" type="text" class="form-control @error('insured_name') is-invalid @enderror" 
                                               name="insured_name" value="{{ old('insured_name') }}" readonly>
                                        @error('insured_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="insured_ci" class="form-label">C.I/RIF del Asegurado *</label>
                                        <input id="insured_ci" type="text" class="form-control @error('insured_ci') is-invalid @enderror" 
                                               name="insured_ci" value="{{ old('insured_ci') }}" readonly>
                                        @error('insured_ci')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="policy_plan" class="form-label">Plan de Póliza</label>
                                        <input id="policy_plan" type="text" class="form-control" name="policy_plan" readonly>
                                    </div>
                                </div>
                            </div>

                            {{-- Sección de Datos del Vehículo --}}
                            <h5 class="mb-3 border-bottom pb-2">2. Datos del Vehículo Asegurado</h5>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="vehicle_brand" class="form-label">Marca *</label>
                                        <input id="vehicle_brand" type="text" class="form-control @error('vehicle_brand') is-invalid @enderror" 
                                               name="vehicle_brand" required readonly>
                                        @error('vehicle_brand')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="vehicle_model" class="form-label">Modelo *</label>
                                        <input id="vehicle_model" type="text" class="form-control @error('vehicle_model') is-invalid @enderror" 
                                               name="vehicle_model" required readonly>
                                        @error('vehicle_model')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="vehicle_registration" class="form-label">Matrícula *</label>
                                        <input id="vehicle_registration" type="text" class="form-control @error('vehicle_registration') is-invalid @enderror" 
                                               name="vehicle_registration" required readonly>
                                        @error('vehicle_registration')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="vehicle_year" class="form-label">Año *</label>
                                        <input id="vehicle_year" type="text" class="form-control @error('vehicle_year') is-invalid @enderror" 
                                               name="vehicle_year" required readonly>
                                        @error('vehicle_year')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="vehicle_color" class="form-label">Color *</label>
                                        <input id="vehicle_color" type="text" class="form-control @error('vehicle_color') is-invalid @enderror" 
                                               name="vehicle_color" required readonly>
                                        @error('vehicle_color')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                        
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="used_for" class="form-label">Uso</label>
                                        <input id="used_for" type="text" class="form-control @error('used_for') is-invalid @enderror" 
                                               name="used_for" required readonly>
                                        @error('used_for')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Sección de Detalles del Siniestro --}}
                            <h5 class="mb-3 border-bottom pb-2">3. Detalles del Siniestro</h5>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="accident_date" class="form-label">Fecha *</label>
                                        <input id="accident_date" type="date" class="form-control @error('accident_date') is-invalid @enderror" 
                                               name="accident_date" value="{{ old('accident_date', now()->format('Y-m-d')) }}" required>
                                        @error('accident_date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="accident_time" class="form-label">Hora *</label>
                                        <input id="accident_time" type="time" class="form-control @error('accident_time') is-invalid @enderror" 
                                               name="accident_time" value="{{ old('accident_time', now()->format('H:i')) }}" required>
                                        @error('accident_time')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="accident_type" class="form-label">Tipo de Siniestro *</label>
                                        <select id="accident_type" name="accident_type" class="form-control @error('accident_type') is-invalid @enderror" required>
                                            <option value="">- Seleccionar -</option>
                                            <option value="Colisión" {{ old('accident_type') == 'Colisión' ? 'selected' : '' }}>Colisión</option>
                                            <option value="Volcadura" {{ old('accident_type') == 'Volcadura' ? 'selected' : '' }}>Volcadura</option>
                                            <option value="Atropello" {{ old('accident_type') == 'Atropello' ? 'selected' : '' }}>Atropello</option>
                                            <option value="Daños por fenómeno natural" {{ old('accident_type') == 'Daños por fenómeno natural' ? 'selected' : '' }}>Daños por fenómeno natural</option>
                                            <option value="Robo" {{ old('accident_type') == 'Robo' ? 'selected' : '' }}>Robo</option>
                                            <option value="Incendio" {{ old('accident_type') == 'Incendio' ? 'selected' : '' }}>Incendio</option>
                                            <option value="Otro" {{ old('accident_type') == 'Otro' ? 'selected' : '' }}>Otro</option>
                                        </select>
                                        @error('accident_type')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="accident_location" class="form-label">Lugar Exacto *</label>
                                        <input id="accident_location" type="text" class="form-control @error('accident_location') is-invalid @enderror" 
                                               name="accident_location" value="{{ old('accident_location') }}" required>
                                        @error('accident_location')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <small class="form-text text-muted">Ej: Av. Libertador, altura del puente peatonal</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="accident_district" class="form-label">Estado *</label>
                                        <input id="accident_district" type="text" class="form-control @error('accident_district') is-invalid @enderror" 
                                               name="accident_district" value="{{ old('accident_district') }}" required>
                                        @error('accident_district')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="accident_description" class="form-label">Descripción Detallada *</label>
                                        <textarea id="accident_description" class="form-control @error('accident_description') is-invalid @enderror" 
                                                  name="accident_description" rows="3" required>{{ old('accident_description') }}</textarea>
                                        @error('accident_description')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <small class="form-text text-muted">Describa cómo ocurrió el siniestro, condiciones climáticas, etc.</small>
                                    </div>
                                </div>
                            </div>

                            {{-- Sección de Terceros Involucrados --}}
                            <h5 class="mb-3 border-bottom pb-2">4. Terceros Involucrados</h5>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="third_party_name" class="form-label">Nombre del Conductor</label>
                                        <input id="third_party_name" type="text" class="form-control @error('third_party_name') is-invalid @enderror" 
                                               name="third_party_name" value="{{ old('third_party_name') }}">
                                        @error('third_party_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="third_party_dni" class="form-label">C.I./RIF</label>
                                        <input id="third_party_dni" type="text" class="form-control @error('third_party_dni') is-invalid @enderror" 
                                               name="third_party_dni" value="{{ old('third_party_dni') }}">
                                        @error('third_party_dni')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="third_party_insurance" class="form-label">Aseguradora</label>
                                        <input id="third_party_insurance" type="text" class="form-control @error('third_party_insurance') is-invalid @enderror" 
                                               name="third_party_insurance" value="{{ old('third_party_insurance') }}">
                                        @error('third_party_insurance')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="third_party_plate" class="form-label">Placa del Vehículo</label>
                                        <input id="third_party_plate" type="text" class="form-control @error('third_party_plate') is-invalid @enderror" 
                                               name="third_party_plate" value="{{ old('third_party_plate') }}">
                                        @error('third_party_plate')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Sección de Documentación --}}
                            <h5 class="mb-3 border-bottom pb-2">5. Documentación Adjunta</h5>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="accident_photos" class="form-label">Fotos del Daño *</label>
                                        <input id="accident_photos" type="file" class="form-control-file @error('accident_photos') is-invalid @enderror" 
                                               name="accident_photos[]" multiple accept="image/*" required>
                                        @error('accident_photos')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <small class="form-text text-muted">Mínimo 3 fotos, máximo 5MB cada una</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="police_report" class="form-label">Parte Policial</label>
                                        <input id="police_report" type="file" class="form-control-file @error('police_report') is-invalid @enderror" 
                                               name="police_report" accept=".pdf,.doc,.docx,.jpg,.png">
                                        @error('police_report')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <small class="form-text text-muted">PDF o imágenes (máx. 10MB)</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="other_documents" class="form-label">Otros Documentos</label>
                                        <input id="other_documents" type="file" class="form-control-file @error('other_documents') is-invalid @enderror" 
                                               name="other_documents[]" multiple accept=".pdf,.doc,.docx,.jpg,.png">
                                        @error('other_documents')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <small class="form-text text-muted">Presupuestos, informes, etc.</small>
                                    </div>
                                </div>
                            </div>

                            {{-- Sección de Datos de Pago --}}
                            <h5 class="mb-3 border-bottom pb-2">6. Datos de Pago (Para Indemnización)</h5>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="bank_id" class="form-label">Banco *</label>
                                        <select id="bank_id" name="bank_id" class="form-control @error('bank_id') is-invalid @enderror" required>
                                            <option value="">- Seleccionar Banco -</option>
                                            @foreach($banks as $bank)
                                                <option value="{{ $bank->id }}" {{ old('bank_id') == $bank->id ? 'selected' : '' }}>{{ $bank->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('bank_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="account_type" class="form-label">Tipo de Cuenta *</label>
                                        <select id="account_type" name="account_type" class="form-control @error('account_type') is-invalid @enderror" required>
                                            <option value="">- Seleccionar -</option>
                                            <option value="Ahorros" {{ old('account_type') == 'Ahorros' ? 'selected' : '' }}>Ahorros</option>
                                            <option value="Corriente" {{ old('account_type') == 'Corriente' ? 'selected' : '' }}>Corriente</option>
                                        </select>
                                        @error('account_type')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="account_number" class="form-label">Número de Cuenta *</label>
                                        <input id="account_number" type="text" class="form-control @error('account_number') is-invalid @enderror" 
                                               name="account_number" value="{{ old('account_number') }}" required>
                                        @error('account_number')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                            </div>

                            {{-- Botones de Acción --}}
                            <div class="form-group row mb-0">
                                <div class="col-md-12 text-right">
                                    <a href="{{ route('index.siniestros') }}" class="btn btn-secondary mr-2">
                                        <i class="fas fa-arrow-left"></i> Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Registrar Siniestro
                                    </button>
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
    document.getElementById('policy_number').addEventListener('blur', function() {
        const policyNumber = this.value.trim();
        const fieldsToUpdate = {
            'insured_name': '',
            'insured_ci': '',
            'vehicle_brand': '',
            'vehicle_model': '',
            'vehicle_registration': '',
            'vehicle_color': '',
            'vehicle_year': '',
            'policy_plan': '',
            'used_for': ''

        };

        // Limpiar campos
        Object.keys(fieldsToUpdate).forEach(fieldId => {
            document.getElementById(fieldId).value = '';
        });

        if (policyNumber) {
            const loadingIndicator = document.createElement('span');
            loadingIndicator.textContent = 'Buscando...';
            loadingIndicator.className = 'text-muted small';
            this.parentNode.appendChild(loadingIndicator);

            fetch(`{{ url('/admin/get-policy-data') }}/${policyNumber}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error HTTP: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Actualizar todos los campos con los datos recibidos
                        Object.keys(fieldsToUpdate).forEach(fieldId => {
                            if (data[fieldId]) {
                                document.getElementById(fieldId).value = data[fieldId];
                            }
                        });
                    } else {
                        alert(data.message || 'No se encontraron datos para esta póliza.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al buscar la póliza: ' + error.message);
                })
                .finally(() => {
                    if (this.parentNode.contains(loadingIndicator)) {
                        this.parentNode.removeChild(loadingIndicator);
                    }
                });
        }
    });

    // Validación adicional para el formulario
    document.getElementById('accident_form').addEventListener('submit', function(e) {
        const accidentPhotos = document.getElementById('accident_photos');
        if (accidentPhotos.files.length < 3) {
            alert('Debe subir al menos 3 fotos del daño.');
            e.preventDefault();
        }
    });
</script>
@endsection