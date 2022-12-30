{{-- Conekta --}}
<div>

    <div class="form-group">
        <label>Token de autorización</label>
        <input type="hidden" class="form-control" name="conektaTokenId" id="conektaTokenId" value="">
    </div>

    <div class="form-row">
        <div class="col form-group">
            <label for="">Nombre del Títular</label>
            <input type="text" class="form-control" data-conekta="card[name]" name="name">
        </div>

        <div class="col form-group">
            <label for="">Número de tarjeta</label>
            <input type="text" class="form-control" data-conekta="card[number]" name="card">
        </div>
    </div>

    <div class="form-row">
        <div class="col form-group">
            <label>CVC</label>
            <input type="number" class="form-control" data-conekta="card[cvc]" maxlength="4">
        </div>

        <div class="col form-group">
    
            <label>Fecha de expiración (MM/AA)</label>
            <div>
                <input style="width:50px; display:inline-block" value="" data-conekta="card[exp_month]" class="form-control" type="text" maxlength="2">
                <input style="width:50px; display:inline-block" value="" data-conekta="card[exp_year]" class="form-control" type="text" maxlength="2">

            </div>

        </div>
    </div>
</div>