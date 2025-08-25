@extends('layouts.admin-modules')

@section('module')
<div class="card shadow mb-4">
	<div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary d-inline-block py-2">Finanzas</h6>
            <form method="GET" action="{{ route('index.finance') }}">
                @csrf
                <div class="form-row align-items-end mb-3">
                    <div class="col-md-2">
                        <label for="start_date">Fecha de Inicio</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" style="width: 100%;" value="{{ old('start_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="end_date">Fecha de Fin</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" style="width: 100%;" value="{{ old('end_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="percentage">Porcentaje (%)</label>
                        <input type="number" id="percentage" class="form-control" style="width: 100%;" placeholder="Ej. 15" value="">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary mt-4">Filtrar</button>
                        <a href="{{ route('index.finance') }}" class="btn btn-secondary mt-4">Limpiar</a>
                    </div>
                </div>
            </form>


            <a href="{{ route('export.finance.pdf') }}" class="btn btn-primary mt-4">Exportar</a>

        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-center" width="100%" cellspacing="0" style="font-size: 12px;">
                    <thead>
                        <tr>
                            <th>Admin</th>
                            <th>Cantidad de Polizas</th>
                            <th>Total Vendido €</th>
                            <th>Total Vendido Bs</th>
                            <th>10%</th>
                            <th>3.5%</th>
                            <th>% Personalizado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                         @php
                        $totalbs= 0;
                    @endphp
                        @if(isset($sumAndCountByType))
                            @foreach($sumAndCountByType as $item)

                                <tr>
                                    <td>{{ $item->type_name }}</td>
                                    <td>{{ $item->count }}</td>
                                    <td>{{ number_format($item->total, 2) }} €</td>
                                    <td>{{ number_format($item->total_foreign, 2) }} Bs</td>
                                          @php
                                        $totalbs += $item->total_foreign;
                                    @endphp
                                    <td>{{ number_format(($item->total * 10) / 100, 2) }} € / {{ number_format(($item->total_foreign * 10) / 100, 2) }} Bs</td>
                                    <td>{{ number_format(($item->total * 3.5) / 100, 2) }} € / {{ number_format(($item->total_foreign * 3.5) / 100, 2) }} Bs</td>
                                    <td class="custom-percentage" data-total="{{ $item->total }}" data-total-foreign="{{ $item->total_foreign }}">
                                        0 € / 0 Bs
                                    </td>
                                    <td>
                                        <!-- Botón de exportación PDF individual por administrador -->
                                        <a href="{{ route('MOD.export.policies.pdf', ['type' => $item->type, 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
                                           class="btn btn-success btn-sm" target="_blank">
                                            Exportar PDF
                                        </a>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Total General</th>
                            <td><strong>{{ number_format($totalPolicies, 0, ',', '.') }}</strong></td>
                            <th>{{ number_format($grandTotalPremium , 2) }} €</th>
                            <th>{{ number_format($totalbs , 2) }} Bs</th>
                            <th>{{ number_format(($grandTotalPremium * 10) / 100, 2) }} €</th>
                            <th>{{ number_format(($grandTotalPremium * 3.5) / 100, 2) }} €</th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <script>
            document.getElementById('percentage').addEventListener('input', function() {
                const percentage = parseFloat(this.value) || 0;

                document.querySelectorAll('.custom-percentage').forEach(cell => {
                    const total = parseFloat(cell.getAttribute('data-total'));
                    const totalForeign = parseFloat(cell.getAttribute('data-total-foreign'));

                    const customTotal = (total * percentage) / 100;
                    const customTotalForeign = (totalForeign * percentage) / 100;

                    // Formatear los resultados con separadores de miles y dos decimales
                    cell.innerHTML = `${customTotal.toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} € / ${customTotalForeign.toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} Bs`;
                });
            });
        </script>

	</div>
</div>

@endsection
