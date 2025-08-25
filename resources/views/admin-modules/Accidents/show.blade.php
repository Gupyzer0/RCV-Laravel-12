@extends('layouts.admin-modules')

@section('module')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-gradient-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-car-crash mr-2"></i>Detalles del Siniestro #{{ $siniestro->id }}
                        </h4>
                        <a href="{{ route('index.siniestros') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i> Volver
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Tarjeta de información básica -->
                    <div class="card mb-4 border-left-primary">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <h5 class="font-weight-bold text-primary">
                                        <i class="fas fa-calendar-alt mr-2"></i>Fecha del Siniestro
                                    </h5>
                                    <p class="lead">{{ \Carbon\Carbon::parse($siniestro->accident_date)->format('d/m/Y') }} a las {{ \Carbon\Carbon::parse($siniestro->accident_time)->format('H:i') }}</p>
                                </div>
                                <div class="col-md-4">
                                    <h5 class="font-weight-bold text-primary">
                                        <i class="fas fa-map-marker-alt mr-2"></i>Ubicación
                                    </h5>
                                    <p class="lead">{{ $siniestro->location }}, {{ $siniestro->district }}</p>
                                </div>
                                <div class="col-md-4">
                                    <h5 class="font-weight-bold text-primary">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>Tipo de Siniestro
                                    </h5>
                                    <p class="lead">{{ $siniestro->accident_type }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Secciones en pestañas -->
                    <ul class="nav nav-tabs" id="siniestroTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="policy-tab" data-toggle="tab" href="#policy" role="tab">
                                <i class="fas fa-file-contract mr-1"></i>Póliza
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="vehicle-tab" data-toggle="tab" href="#vehicle" role="tab">
                                <i class="fas fa-car mr-1"></i>Vehículo
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="details-tab" data-toggle="tab" href="#details" role="tab">
                                <i class="fas fa-info-circle mr-1"></i>Detalles
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="third-parties-tab" data-toggle="tab" href="#third-parties" role="tab">
                                <i class="fas fa-users mr-1"></i>Terceros
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="docs-tab" data-toggle="tab" href="#docs" role="tab">
                                <i class="fas fa-file-upload mr-1"></i>Documentos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="payment-tab" data-toggle="tab" href="#payment" role="tab">
                                <i class="fas fa-money-bill-wave mr-1"></i>Pago
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content p-3 border border-top-0 rounded-bottom" id="siniestroTabsContent">
                        <!-- Pestaña de Póliza -->
                        <div class="tab-pane fade show active" id="policy" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-box bg-light">
                                        <span class="info-box-icon bg-primary"><i class="fas fa-id-card"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Número de Póliza: </span>
                                            <span class="info-box-number">{{ $siniestro->policy_id }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box bg-light">
                                        <span class="info-box-icon bg-primary"><i class="fas fa-user"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text"><strong>Asegurado:</strong> </span>
                                            <span class="info-box-number">{{ $siniestro->policy->client_name.' '.$siniestro->policy->client_lastname ?? 'N/A' }}</span>
                                            <small>C.I/RIF: {{ $siniestro->policy->client_ci ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h5 class="mb-0">Detalles de la Póliza</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <tbody>
                                                        <tr>
                                                            <th width="30%">Plan de Póliza</th>
                                                            <td>{{ $siniestro->policy->price->description ?? 'N/A' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Fecha de Emisión</th>                                                            
                                                            <td>{{\Carbon\Carbon::parse($siniestro->policy->created_at ?? 'N/A')->format('d/m/Y')}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Vigencia</th>
                                                            <td>{{\Carbon\Carbon::parse($siniestro->policy->created_at ?? 'N/A')->format('d/m/Y')}} al {{\Carbon\Carbon::parse($siniestro->policy->expiring_date ?? 'N/A')->format('d/m/Y')}}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pestaña de Vehículo -->
                        <div class="tab-pane fade" id="vehicle" role="tabpanel">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card bg-light mb-4">
                                        <div class="card-header bg-primary text-white">
                                            <h6 class="mb-0">Datos Básicos</h6>
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    Marca
                                                    <span class="badge badge-primary badge-pill">{{ $siniestro->policy->vehicle_brand ?? 'N/A' }}</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    Modelo
                                                    <span class="badge badge-primary badge-pill">{{ $siniestro->policy->vehicle_model ?? 'N/A' }}</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    Año
                                                    <span class="badge badge-primary badge-pill">{{ $siniestro->policy->vehicle_year ?? 'N/A' }}</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    Color
                                                    <span class="badge badge-primary badge-pill">{{ $siniestro->policy->vehicle_color ?? 'N/A' }}</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light mb-4">
                                        <div class="card-header bg-primary text-white">
                                            <h6 class="mb-0">Identificación</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="text-center mb-3">
                                                <i class="fas fa-car fa-4x text-primary"></i>
                                            </div>
                                            <div class="text-center">
                                                <h4 class="font-weight-bold">{{ $siniestro->policy->vehicle_registration ?? 'N/A' }}</h4>
                                                <p class="text-muted">Matrícula</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light mb-4">
                                        <div class="card-header bg-primary text-white">
                                            <h6 class="mb-0">Uso y Características</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="text-center mb-3">
                                                <i class="fas fa-info-circle fa-3x text-primary"></i>
                                            </div>
                                            <p class="text-center">
                                                <strong>Uso:</strong> {{ $siniestro->policy->used_for ?? 'N/A' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pestaña de Detalles del Siniestro -->
                        <div class="tab-pane fade" id="details" role="tabpanel">
                            <div class="card bg-light">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Descripción del Incidente</h5>
                                </div>
                                <div class="card-body">
                                    <p class="lead">{{ $siniestro->description }}</p>
                                </div>
                            </div>
                            
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">Ubicación Exacta</h5>
                                        </div>
                                        <div class="card-body">
                                            <div id="map" style="height: 200px; background-color: #eee; border-radius: 4px;" class="mb-3">
                                                <!-- Aquí podrías integrar un mapa si tienes coordenadas -->
                                                <div class="d-flex justify-content-center align-items-center h-100">
                                                    <p class="text-muted"><i class="fas fa-map-marked-alt fa-2x mr-2"></i> Mapa no disponible</p>
                                                </div>
                                            </div>
                                            <address>
                                                <strong>Lugar:</strong> {{ $siniestro->location }}<br>
                                                <strong>Estado/Municipio:</strong> {{ $siniestro->district }}
                                            </address>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">Detalles Temporales</h5>
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    Fecha del siniestro
                                                    <span class="badge badge-primary badge-pill">{{ \Carbon\Carbon::parse($siniestro->accident_date)->format('d/m/Y') }}</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    Hora del siniestro
                                                    <span class="badge badge-primary badge-pill">{{ \Carbon\Carbon::parse($siniestro->accident_time)->format('H:i') }}</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    Fecha de reporte
                                                    <span class="badge badge-primary badge-pill">{{ \Carbon\Carbon::parse($siniestro->created_at)->format('d/m/Y H:i') }}</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pestaña de Terceros -->
                        <div class="tab-pane fade" id="third-parties" role="tabpanel">
                            @if($siniestro->third_party_name || $siniestro->third_party_dni || $siniestro->third_party_insurance || $siniestro->third_party_plate)
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card bg-light">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">Información del Tercero Involucrado</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Nombre del Conductor:</label>
                                                        <p class="form-control-plaintext">{{ $siniestro->third_party_name ?? 'N/A' }}</p>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">C.I./RIF:</label>
                                                        <p class="form-control-plaintext">{{ $siniestro->third_party_dni ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Aseguradora:</label>
                                                        <p class="form-control-plaintext">{{ $siniestro->third_party_insurance ?? 'N/A' }}</p>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Placa del Vehículo:</label>
                                                        <p class="form-control-plaintext">{{ $siniestro->third_party_plate ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-2"></i>No hay terceros involucrados registrados en este siniestro.
                            </div>
                            @endif
                        </div>

                        <!-- Pestaña de Documentos -->
                        <div class="tab-pane fade" id="docs" role="tabpanel">
                            <div class="row">
                                <!-- Fotos del daño -->
                                <div class="col-md-6">
                                    <div class="card bg-light h-100">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0"><i class="fas fa-images mr-2"></i>Fotos del Daño</h5>
                                        </div>
                                        <div class="card-body">
                                            @if ($siniestro->photos)
                                                @php
                                                    $photos = is_string($siniestro->photos) ? json_decode($siniestro->photos, true) : $siniestro->photos;
                                                @endphp
                                                @if (is_array($photos) && count($photos) > 0)
                                                    <div class="row">
                                                        @foreach ($photos as $photoPath)
                                                            <div class="col-6 mb-3">
                                                                <a href="{{ Storage::url($photoPath) }}" data-toggle="lightbox" data-gallery="damage-gallery">
                                                                    <img src="{{ Storage::url($photoPath) }}" class="img-fluid img-thumbnail" alt="Foto del daño {{ $loop->iteration }}">
                                                                </a>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div class="text-center py-4">
                                                        <i class="fas fa-camera fa-3x text-muted mb-3"></i>
                                                        <p class="text-muted">No hay fotos adjuntas</p>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="text-center py-4">
                                                    <i class="fas fa-camera fa-3x text-muted mb-3"></i>
                                                    <p class="text-muted">No hay fotos adjuntas</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Otros documentos -->
                                <div class="col-md-6">
                                    <div class="card bg-light h-100">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0"><i class="fas fa-file-alt mr-2"></i>Documentos Legales</h5>
                                        </div>
                                        <div class="card-body">
                                            <!-- Parte policial -->
                                            <div class="mb-4">
                                                <h6><i class="fas fa-file-contract mr-2"></i>Parte Policial</h6>
                                                @if ($siniestro->police_report)
                                                    <a href="{{ Storage::url($siniestro->police_report) }}" class="btn btn-outline-primary btn-sm" target="_blank">
                                                        <i class="fas fa-eye mr-1"></i> Ver documento
                                                    </a>
                                                    <a href="{{ Storage::url($siniestro->police_report) }}" class="btn btn-outline-success btn-sm" download>
                                                        <i class="fas fa-download mr-1"></i> Descargar
                                                    </a>
                                                @else
                                                    <p class="text-muted"><small>No hay parte policial adjunto</small></p>
                                                @endif
                                            </div>
                                            
                                            <!-- Otros documentos -->
                                            <div>
                                                <h6><i class="fas fa-file-upload mr-2"></i>Otros Documentos</h6>
                                                @if ($siniestro->other_documents)
                                                    @php
                                                        $otherDocs = is_string($siniestro->other_documents) ? json_decode($siniestro->other_documents, true) : $siniestro->other_documents;
                                                    @endphp
                                                    @if (is_array($otherDocs) && count($otherDocs) > 0)
                                                        <ul class="list-group">
                                                            @foreach ($otherDocs as $docPath)
                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                    Documento {{ $loop->iteration }}
                                                                    <span>
                                                                        <a href="{{ Storage::url($docPath) }}" class="btn btn-sm btn-outline-primary mr-1" target="_blank">
                                                                            <i class="fas fa-eye"></i>
                                                                        </a>
                                                                        <a href="{{ Storage::url($docPath) }}" class="btn btn-sm btn-outline-success" download>
                                                                            <i class="fas fa-download"></i>
                                                                        </a>
                                                                    </span>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        <p class="text-muted"><small>No hay otros documentos adjuntos</small></p>
                                                    @endif
                                                @else
                                                    <p class="text-muted"><small>No hay otros documentos adjuntos</small></p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pestaña de Pago -->
                        <div class="tab-pane fade" id="payment" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0"><i class="fas fa-university mr-2"></i>Datos Bancarios</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label font-weight-bold">Banco:</label>
                                                <div class="col-sm-8">
                                                    <p class="form-control-plaintext">{{ $siniestro->bank->name ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label font-weight-bold">Tipo de Cuenta:</label>
                                                <div class="col-sm-8">
                                                    <p class="form-control-plaintext">{{ $siniestro->account_type ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label font-weight-bold">Número de Cuenta:</label>
                                                <div class="col-sm-8">
                                                    <p class="form-control-plaintext">{{ $siniestro->account_number ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-light h-100">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0"><i class="fas fa-money-bill-wave mr-2"></i>Indemnización</h5>
                                        </div>
                                        <div class="card-body d-flex align-items-center justify-content-center">
                                            <div class="text-center">
                                                <h2 class="text-primary font-weight-bold">{{ number_format($siniestro->amount, 2, ',', '.') ?? '0,00' }} €</h2>
                                                <p class="text-muted">Monto a pagar</p>
                                                <div class="mt-3">
                                                    <span class="badge badge-{{ $siniestro->payment_status === 'pagado' ? 'success' : 'warning' }} p-2">
                                                        {{ $siniestro->payment_status === 'pagado' ? 'PAGADO' : 'PENDIENTE' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">
                            <i class="fas fa-clock mr-1"></i> 
                            Creado el {{ \Carbon\Carbon::parse($siniestro->created_at)->format('d/m/Y H:i') }}
                            @if($siniestro->created_at != $siniestro->updated_at)
                                | Actualizado el {{ \Carbon\Carbon::parse($siniestro->updated_at)->format('d/m/Y H:i') }}
                            @endif
                        </small>
                        <div>
                            <a href="#" class="btn btn-outline-secondary btn-sm mr-2">
                                <i class="fas fa-print mr-1"></i> Imprimir
                            </a>
                            <a href="{{ route('admin.edit.siniestro', $siniestro->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit mr-1"></i> Editar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .info-box {
        min-height: 80px;
        margin-bottom: 1rem;
    }
    .info-box-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 70px;
        font-size: 30px;
    }
    .info-box-content {
        padding: 5px 10px;
    }
    .info-box-text {
        display: block;
        font-size: 14px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .info-box-number {
        display: block;
        font-weight: bold;
        font-size: 18px;
    }
    .nav-tabs .nav-link {
        font-weight: 500;
    }
    .bg-gradient-primary {
        background: linear-gradient(87deg, #2dce89 0, #2dcecc 100%) !important;
    }
</style>
@endpush

@push('scripts')
<!-- Lightbox para las imágenes -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js"></script>
<script>
    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox();
    });
</script>
@endpush