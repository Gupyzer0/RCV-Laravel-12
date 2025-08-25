
<div class="modal fade" id="modal-price{{$policy->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-price-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">¿Seguro que desea renovar el precio de esta póliza?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Seleccione "continuar" si desea renovar el precio de esta poliza</div>
        <div class="modal-footer">
          <form action="/user/renew-policy-price/{{$policy->id}}" method="POST">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Continuar</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal  de pago-->

  <style>
    /* Estilo para botón deshabilitado */
    #submitPaymentBtn[disabled] {
        opacity: 0.65;
        cursor: not-allowed;
    }

    </style>

 <div class="modal fade" id="payModal{{ $policy->id }}" tabindex="-1" role="dialog" aria-labelledby="payModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="paymentForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="payModalLabel">Pago de Póliza #{{ $policy->id }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- Aquí van los datos bancarios --}}
                    <div class="mb-3 p-2 bg-light rounded">
                        <strong class="d-block mb-2" style="font-size: 1.1rem;">Datos Bancarios (Receptor - BNC):</strong>
                        <div class="d-flex flex-wrap">
                            <div class="mr-3 mb-1"><strong>Teléfono:</strong> 0414-1234567</div>
                            <div class="mr-3 mb-1"><strong>Rif:</strong> J-500168696</div>
                            <div class="mr-3 mb-1"><strong>Banco:</strong> BNC</div>
                            <div class="mb-1"><strong>N° Cuenta:</strong> 010202366262266 (Corriente)</div>
                        </div>
                    </div>
                    <div id="paymentResponse" class="alert d-none mt-3" role="alert"></div>

                    <div class="alert alert-info">
                        Monto total:
                        <strong>{{ number_format($policy->total_premium * $foreign_reference, 2) }} Bs.S</strong>
                        ({{ number_format($policy->total_premium, 2) }} €)
                    </div>

                    <div class="form-group">
                        <label for="Reference">Referencia de Pago:</label>
                        <input type="text" class="form-control" id="Reference" name="Reference">
                    </div>

                    <div class="form-group">
                        <label for="fecha">Fecha de Pago</label>
                        <input type="date" class="form-control" id="DateMovement" name="DateMovement" required>
                    </div>

                    <div class="form-group">
                        <label for="Amount">Monto Pagado (Bs.S):</label>
                        {{ number_format($policy->total_premium * $foreign_reference, 2) }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="submitPaymentBtn" onclick="submitPayment()">
                        <i class="fas fa-check-circle"></i> Confirmar Pago
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
  {{-- modal notificacion --}}

{{-- 
  <div class="modal fade" id="modal-poliza" tabindex="-1" role="dialog" aria-labelledby="modal-poliza" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title text-danger" id="modal-poliza">ATENCION</h5>
                  <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                  </button>
              </div>
              <div class="modal-body" style="font-size: 1.25rem; text-align: justify;">
                 <p style="text-align: center;"> <strong>REVISAR LOS DATOS</strong><br>
                    Al Momento de Procesar la Cotización y Generar la Póliza, No Podrá Editar ni Anular.</p>
              </div>

              <div class="modal-footer">
                  <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
              </div>
          </div>
      </div>
  </div> --}}

@push('scripts')
    <script>
        function submitPayment() {
            const $btn = $('#submitPaymentBtn');
            const $form = $('#paymentForm');
            const $response = $('#paymentResponse');

            // Listener para resetear alertas cuando se oculte la modal
            $('#reporte').on('hidden.bs.modal', function (e) {
                $response.addClass('d-none');
            })
            
            // Validación visual
            $form.find('.is-invalid').removeClass('is-invalid');
            $response.addClass('d-none');

            // Deshabilitar botón
            $btn.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Validando...'
            );

            // Enviar datos
            $.ajax({
                method: "POST",
                url: "{{ route('api.validate-p2p',$policy) }}",
                data: $form.serialize(),
                dataType: "json",
                success: function(response) {
                    console.log(response);
                    if (response.status === 'success') {
                        $response.removeClass('alert-danger').addClass('alert-success')
                            .text('Pago móvil verificado exitosamente')
                            .removeClass('d-none');
                        window.location.href = '{{ route('user.index.policies') }}' + '?poliza_registrada=' + response.policy
                        
                    } else if(response.status === 'no-existe') {
                        $response.removeClass('alert-danger').addClass('alert-danger')
                            .text('El pago móvil registrado no existe')
                            .removeClass('d-none');
                    
                    } else if (response.status === 'duplicado') {
                        $response.removeClass('alert-danger').addClass('alert-danger')
                            .text('Este pago móvil ya se encuentra registrado en el sistema')
                            .removeClass('d-none');
                    } else {
                        showErrors(response.errors);
                    }
                },
                error: function(xhr) {

                    let message = "Error en la conexión";

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }

                    $response.removeClass('alert-success').addClass('alert-danger')
                        .text(message)
                        .removeClass('d-none');
                    
                    if(xhr.status == '422') {
                        showErrors(xhr.responseJSON.errors)
                    }
                },
                complete: function() {
                    $btn.prop('disabled', false).text('Validar Pago');
                }
            });
        }

        // Mostrar errores de validación
        function showErrors(errors) {
            console.log("errores")
            $.each(errors, function(field, messages) {
                const $field = $('#' + field);
                $field.addClass('is-invalid');
                $field.after('<div class="invalid-feedback">' + messages.join(', ') + '</div>');
            });
        }
    </script>
@endpush



