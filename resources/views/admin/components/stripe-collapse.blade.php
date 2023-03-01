<h3 for="card-details" class="mt-3">Detalles de pago</h3>

<div class="row">

    
    <div class="form-group col-md-6">
        <label for="card-name" class="">Nombre</label>
        <input type="text" class="form-control" id="stripe_user_name" placeholder="Nombre" required value="{{ session()->get('projobi_user.name') }}">
    </div>
    
    <div class="form-group col-md-6">
        <label for="card-email" class="">Correo electrónico</label>
        <input type="email" class="form-control" id="stripe_user_email" placeholder="Correo electrónico" required value="{{ session()->get('projobi_user.email') }}" disabled>
    </div>

</div>
    
<div>
    <label for="card-details2" class="">Detalles de la tarjeta</label>
    <div id="cardElement" class="stripe-frame mb-4 py-3 box-shadow"></div>
</div>
<small class="form-text text-muted" id="cardErrors" role="alert"></small>