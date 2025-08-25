<div class="modal fade" id="modal-pagar-manualmente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Pagar Manualmente</h5>
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
                <div class="alert alert-info">
                    <p>El soporte de pago debe ser un documento pdf ó una imágen que sirva de soporte del pago.</p>
                    <ul>
                        <li>
                            Debe ser un archivo <b>jpg, jpeg, png</b> ó un documento <b>pdf</b>
                        </li>
                        <li>
                            No debe persar más de 4MB.
                        </li>
                    </ul>
                </div>                    
                <form id="formulario-pagar-manualmente">
                    <div class="form-group">
                        <label for="soporte_pago">Adjunte el soporte del pago</label>
                        <input type="file" name="soporte_pago" class="form-control-file" id="soporte_pago">
                    </div>
                    <div id="soporte_pago_error" class="invalid-feedback alert alert-danger"></div>
                </form>
            </div>
            
            <div class="modal-footer">
                <button id="submit-pago-manual" class="btn btn-primary">
                    Registrar Pago
                    <span id="loading-spinner-pago-manual" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none"></span>
                </button>
            </div>
        </div>
    </div>
</div>