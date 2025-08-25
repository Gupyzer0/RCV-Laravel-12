@extends('layouts.app')

@section('module')
<a class="btn btn-light shadow" href="{{ route('user.index.show.notpaid')}}">Polizas por Pagar</a>
<div class="card shadow mb-4">
	<div class="card-header py-2">
		<h6 class="m-0 font-weight-bold text-primary d-inline-block py-2">Polizas Reportadas</h6>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th>Fecha de Reporte</th>
                        <th>Pólizas Reportadas</th>
                        <th>Tasa $</th>
                        <th>Total de Vendido</th>
                        <th>Forma de Pago</th>
                        <th>Total pagado</th>
                        <th>Ganancia</th>
                        <th>Estatus</th>
                        <th>Comprobante</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportes as $report)
                        <tr>
                            <td>{{\Carbon\Carbon::parse($report->created_at)->format('d-m-Y')}}</td>
                            <td>{{ $report->total_policies }}</td>
                            <td>{{ $report->tasae}} Bs</td>
                            <td>{{ $report->total}} $</td>
                            <td>
                                @if ($report->type_payment == 'cash')
                                Efectivo @if($report->currency == 'usd') Dolar @else Bolivar @endif
                                @elseif ($report->type_payment == 'transfer')
                               Transferencia (Bs)
                                @elseif ($report->type_payment == 'pm')
                                Pago Movil (Bs)
                                @endif
                            </td>
                            <td>{{ $report->amount }}  @if($report->currency == 'usd') $ @else Bs @endif </td>
                            <td>
                            @if($report->currency == 'usd')
                            {{$report->amount * $comision}} $
                            @else
                            {{(($report->amount * $comision) / 100)* $report->tasae}} Bs
                            </td>
                            @endif
                            <td>@if($report->status > 0)
                                <span class="badge badge-sm bg-gradient-warning text-white">En revisión</span>
                                @else
                                <span class="badge badge-sm bg-gradient-success text-white">Pago Exitoso</span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown mb-4">
                                    <button class="btn btn-primary dropdown-toggle" type="button"
                                        id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        Acciones
                                    </button>
                                    <div class="dropdown-menu animated--fade-in"
                                    aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="{{ route('user.report.export', $report->id) }}" target="_blank">Ver Correlativo</a>
                                    @if($report->voucher)
                                    <a class="dropdown-item"  target="blank" href="/user/user-exportpdf/">Ver Comprobante</a>
                                    @endif
                                </div>


                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

		</div>
	</div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
	var closeBtn = document.getElementById('closeBtn');

	// modalBtn.addEventListener('click', openModal);
	window.addEventListener('click', clickOutside);

	//open imgModal
	let thismodal;
	function openImg(id){
		let imgModal = document.getElementById(id);
		imgModal.style.display = 'block';
		thismodal = imgModal;
		return thismodal;
	}

	function closeImgModal(){
		thismodal.style.display = 'none';
	}

	function clickOutside(e){
		if(e.target == thismodal){
			thismodal.style.display = 'none';
		}
	}
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
