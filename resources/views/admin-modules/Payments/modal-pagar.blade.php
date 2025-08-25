<div class="modal fade" id="modal-pagar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Pagar</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning" role="alert">
                    <h5>Monto a pagar</h5>
                    <hr>
                    <div>Bolívares <span class="total-selected-bs">0.00 Bs</span></div>
                    <div>Dólares <span class="total-selected-usd">0.00  $ /</span></div>
                    <div>Euros <span class="total-selected-eur">0.00  € /</span></div>
                </div>

                <div id="pago-automatico_error" class="invalid-feedback alert alert-danger"></div>
            </div>
            
            <div class="modal-footer">
                <button id="submit-pago-automatico" class="btn btn-primary">
                    Registrar Pago
                    <span id="loading-spinner-pago-automatico" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none"></span>
                </button>
            </div>
        </div>
    </div>
</div>