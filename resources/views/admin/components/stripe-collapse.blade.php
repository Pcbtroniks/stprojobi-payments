<h2 for="card-details" class="mt-3 text-center">Pago con tarjeta</h2>

<div class="row justify-content-center">

    
    <div class="col-12 col-md-8 col-lg-6 py-5 px-md-5">

        <div class="mx-auto mb-5">
            <h3 class="text-center font-weight-bold">
                Información de la tarjeta
            </h3>
        </div>

        <div class="form-group">
            <label for="card-name" class="font-weight-normal">Titular de la tarjeta</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fa fa-user"></i></div>
                </div>
                <input type="text" class="form-control" id="stripe_user_name" placeholder="Nombre" required value="{{ session()->get('projobi_user.name') }}">
            </div>
        </div>
        
        <div class="form-group">
            <label for="card-email" class="font-weight-normal">Correo electrónico</label>

            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fa fa-envelope"></i></div>
                </div>
                <input type="email" class="form-control" id="stripe_user_email" placeholder="Correo electrónico" required value="{{ session()->get('projobi_user.email') }}">
            </div>
        </div>

        <div class="form-group">
            <label for="card-details2" class="font-weight-normal">Detalles de la tarjeta</label>
            <div id="cardElement" class="stripe-frame mb-4 py-3 box-shadow"></div>
        </div>
    </div>

    <div class="col-12 col-md-8 col-lg-6 py-md-5 px-md-5 border-left">

        <div class="mx-auto">
            <h3 class="text-center font-weight-bold mb-5">
                Detalles de la membresía
            </h3>
        </div>

        <div class="d-flex justify-content-between mb-2">
            <h5>
                Plan
            </h5>

            <div class="d-flex justify-content-between align-items-baseline">
                <h5 id="selectedPlanPriceString">$1199</h5>
                 <span class="text-sm-left">MXN /<span id="selectedPlanSlugString">anual</span</span>
            </div>
        </div>

        <div class="d-flex justify-content-between mb-2">
            <h5>
                Anuncios
            </h5>

            <div class="d-flex justify-content-between align-items-baseline">
                <h4><span id="selectedPlanAdsString">3</span>&nbsp;</h4> 
                 <span class="text-sm-left"> anuncio/s</span>
            </div>
        </div>

        <div class="d-flex justify-content-between mb-2">
            <h5>
                Reporte de vistas
            </h5>

            <div class="d-flex justify-content-between align-items-baseline">
                <h5>Incluido</h5>
            </div>
        </div>

        <div class="d-flex justify-content-between mb-2">
            <h5>
                Descuento
            </h5>

            <div class="d-flex justify-content-between align-items-baseline">
                <h3 id="selectedPlanDiscountString"> -$300</h3><span class="text-sm-left">MXN</span>
            </div>
        </div>

        <hr>

        <div class="d-flex justify-content-between mb-2">
            <h4>
                <b>
                    Total
                </b>
            </h4>

            <div class="d-flex justify-content-between align-items-baseline">
            <h5> <del id="selectedPlanBeforeDiscountPrice">$1199</del> &nbsp;</h5><h3><b id="selctedPlanTotalPriceString"> $899</b></h3><span class="text-sm-left">MXN</span>
            </div>
        </div>

    </div>

    <!-- <div hidden class="col-12 col-md-8 col-lg-6 py-5">
        <div>
            <div class="card border-primary rounded-0">
                <div class="card-header p-0">
                    <div class="bg-info text-white text-center py-2">
                        <h3 id="SelectedPlanTitle">Plan mensual $99 MXN al mes</h3>
                        <p class="m-0" id="SelectedPlanDescription">¿Tiene algun cupon?</p>
                    </div>
                </div>
                <div class="card-body p-3">

                    
                    <div class="form-group">
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fa-solid fa-ticket text-info">

                                <svg class="text-info" fill="currentColor" color="currentColor" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512"><path d="M64 64C28.7 64 0 92.7 0 128v64c0 8.8 7.4 15.7 15.7 18.6C34.5 217.1 48 235 48 256s-13.5 38.9-32.3 45.4C7.4 304.3 0 311.2 0 320v64c0 35.3 28.7 64 64 64H512c35.3 0 64-28.7 64-64V320c0-8.8-7.4-15.7-15.7-18.6C541.5 294.9 528 277 528 256s13.5-38.9 32.3-45.4c8.3-2.9 15.7-9.8 15.7-18.6V128c0-35.3-28.7-64-64-64H64zm64 112l0 160c0 8.8 7.2 16 16 16H432c8.8 0 16-7.2 16-16V176c0-8.8-7.2-16-16-16H144c-8.8 0-16 7.2-16 16zM96 160c0-17.7 14.3-32 32-32H448c17.7 0 32 14.3 32 32V352c0 17.7-14.3 32-32 32H128c-17.7 0-32-14.3-32-32V160z"/></svg>

                                </i></div>
                            </div>
                            <input type="text" class="form-control" id="couponStripe" name="couponStripe" placeholder="Ingrese su cupon">
                        </div>
                    </div>
                    <div style="display: none;" class="form-group">
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fa fa-envelope text-info"></i></div>
                            </div>
                            <input type="email" class="form-control" id="email" name="email" placeholder="ejemplo@gmail.com" required>
                        </div>
                    </div>

                    <div class="text-center">
                        <input type="submit" value="Aplicar" class="btn btn-info btn-block rounded-0 py-2">
                    </div>
                </div>

            </div>
        </div>
    </div> -->

</div>

<small class="form-text text-muted" id="cardErrors" role="alert"></small>