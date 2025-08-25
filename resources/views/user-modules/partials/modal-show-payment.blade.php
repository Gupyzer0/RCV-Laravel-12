<!-- Modal de pago-->

<div class="modal fade" id="payModal" tabindex="-1" role="dialog" aria-labelledby="payModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="payModalLabel">Monto Total a Pagar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>El monto total a pagar es: $<span id="modalTotalAmount">0.00</span></p>
                    <div class="form-group">
                        <label for="paymentMethod">Seleccione la forma de pago:</label>
                        <select class="form-control" id="paymentMethod" name="paymentMethod" onchange="togglePaymentFields()">
                            <option value="cash">Efectivo</option>
                            <option value="mobile_payment">Pago Móvil</option>
                            <option value="bank_transfer">Transferencia Bancaria</option>
                        </select>
                    </div>
                    <div id="paymentFields">
                        <div class="form-group" id="referenceNumberField">
                            <label for="referenceNumber">Número de Referencia:</label>
                            <input type="text" class="form-control" name="referenceNumber" id="referenceNumber">
                        </div>
                        <div class="form-group" id="bankField">
                            <label for="bank">Banco Emisor</label>
                            <select class="form-control" id="bank" name="bank">
                                <option value="">Seleccione</option>
                                @foreach ($banks as $bank)
                                <option value="{{$bank->id}}">{{$bank->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" id="amountField">
                            <label for="amount">Monto:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="amount" id="amount">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="currencySymbol">$</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Confirmar Pago</button>
                </div>
        </div>
    </div>
</div>
  {{-- modal notificacion --}}

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
                 <p style="text-align: center;"> <strong>RESIVAR LOS DATOS</strong><br>
                    Al Momento de Procesar la Cotización y Generar la Póliza, No Podrá Editar ni Anular.</p>
              </div>

              <div class="modal-footer">
                  <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
              </div>
          </div>
      </div>
  </div>

