@extends('layouts.admin-modules')
<?php use \App\Http\Controllers\PaymentsController; ?>

@section('module')
<a class="btn btn-success shadow" href="{{ route('index.payments')}}">Ver pagados</a>
<a class="btn btn-success shadow" href="{{ route('index.notpaid')}}">Por Vendedor</a>
<div class="card shadow mb-2">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary">No pagados por Supervisor</h6>
	</div>

    <nav class="navbar navbar-light" style="text-align: right"></nav>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0" style="font-size: 12px;">
				<thead>
                    <tr>
                        <th>Supervisor</th>
                        <th>Aliados</th>
                        <th>Pólizas Vendidas</th>
                        <th>Pólizas Reportadas</th>
                        <th>Pólizas Nulas</th>
                        <th>Total Vendido</th>
                        <th>Comisión</th>
                        <th>Total a Recibir</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($moderators as $mod)
                        <tr>
                            <td>{{ $mod->names }}</td>
                            <td>{{ $mod->supervisados->count() }}</td>
                            <td>{{ $mod->sumario->notPaidCount }}</td>
                            
                            <td>{{ $mod->sumario->reportPaidCount }}</td>
                            <td>{{ $mod->sumario->nulasCount }}</td>
                            <td>{{ number_format($mod->sumario->totalVendido, 2) }}</td>
                            <td>{{ number_format($mod->comision, 2) }}</td>
                            <td>{{ number_format($mod->totalARecibir, 2) }}</td>                                   

                            {{-- Acciones --}}
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-success btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                        Acciones
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('export.policies.pdf', $mod->id) }}" target="_blank" >Exportar</a>
                                        <a class="dropdown-item" href="{{ route('mod.all.report.policies', $mod->id) }}">Hacer Cierre</a>
                                    </div>
                                </div>
                            </td>
                            {{-- /Acciones --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
		</div>
	</div>
</div>
<div class="modal fade" id="reporte" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"> <strong class="text-danger">Realizar Cierre</strong></h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Al Realizar el cierre se mostraran todas las polizas realizadas hasta hoy </div>
            <div class="modal-footer">
                <form action="/admin/reportpaymenta" method="POST">
                    @csrf

                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Continuar</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<script>
    document.getElementById('moderatorInput').addEventListener('input', function(e) {
        var options = document.querySelectorAll('#listamod option');
        var inputValue = e.target.value;

        for (var i = 0; i < options.length; i++) {
            if (options[i].value === inputValue) {
                document.getElementById('moderatorId').value = options[i].getAttribute('data-id');
                break;
            }
        }
    });
</script>

<script>
$(document).ready(function() {
	$("tbody").find('tr').each(function() {
		let objects = $(this).find('span.prices_se');
		console.log(objects);
		for(object of objects){
			console.log(object.innerText);
			object.innerText = number_format(object.innerText);
		}
	})
});

function number_format(number, decimals, dec_point, thousands_sep) {
  // *     example: number_format(1234.56, 2, ',', ' ');
  // *     return: '1 234,56'
  number = (number + '').replace(',', '').replace(' ', '');
  var n = !isFinite(+number) ? 0 : +number,
  prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
  sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
  dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
  s = '',
  toFixedFix = function(n, prec) {
  	var k = Math.pow(10, prec);
  	return '' + Math.round(n * k) / k;
  };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
  if (s[0].length > 3) {
  	s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '').length < prec) {
  	s[1] = s[1] || '';
  	s[1] += new Array(prec - s[1].length + 1).join('0');
  }
  return s.join(dec);
}
</script>
@endsection
