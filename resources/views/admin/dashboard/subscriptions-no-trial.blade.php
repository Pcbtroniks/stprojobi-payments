@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content')
	<div
		class="container-fuid">
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1>
                            Suscripción de {{ session()->get('projobi_user.name') ?? '' }} {{ session()->has('projobi_user') && session()->get('projobi_user.is_subscriber') == 'yes' ? 'Activa' : 'Inactiva' }}
                        </h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active">Suscripción</li>
						</ol>
					</div>
				</div>
			</div>
			<!-- /.container-fluid -->
		</section>

		<!-- Main content -->
		<section
			class="content">

			<!-- Primary box -->
			<div class="card card-primary card-outline">
				<div class="card-body">

                    <div class="container-fluid mb-4">
                        <div class="row">
                            <div class="mb-5">
                                <h2>Gracias por interesarte en ser parte de Projobi a continuación te mostramos nuestros planes.</h2>
                            </div>
                            <div class="col-md-4">
                                <h3 class="text-center">Suscripción Mensual <br> <small>$99 MXN al mes</small></h3>

                                <p class="text-center"><b>Renovación automática</b></p>

                                <div class="form-group text-center">
                                    <div class="form-check">
                                        <input onchange="SetPlanID('plan_mensual_nt')" class="form-check-input" type="radio" name="plan" value="plan_mensual" id="planMensual" checked>
                                        <label class="form-check-label" for="planMensual">Plan mensual</label>
                                        <p class="form-check-label">1 Anuncio</p>
                                        <p class="form-check-label">Reporte de Visitas</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <h3 class="text-center">Suscripción Semestral <br> <small>$499 MXN cada 6 meses</small></h3>

                                <p class="text-center"><b>Renovación automática</b></p>

                                <div class="form-group text-center">
                                    <div class="form-check">
                                        <input onchange="SetPlanID('plan_semestral_nt')" class="form-check-input" type="radio" name="plan" value="plan_mensual" id="planSemestral">
                                        <label class="form-check-label" for="planSemestral">Plan semestral</label>
                                        <p class="form-check-label">2 Anuncios</p>
                                        <p class="form-check-label">Reporte de Visitas</p>
                                        <p class="form-check-label text-danger font-weight-bold"><u>Ahorra $100</u></p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <h3 class="text-center">Suscripción Anual <br><small>$899 MXN al año</small></h3>

                                <p class="text-center"><b>Renovación automática</b></p>

                                <div class="form-group text-center">
                                    <div class="form-check">
                                        <input onchange="SetPlanID('plan_anual_nt')" class="form-check-input" type="radio" name="plan" value="plan_anual" id="planAnual">
                                        <label class="form-check-label" for="planAnual">Plan anual</label>
                                        <p class="form-check-label">3 Anuncios</p>
                                        <p class="form-check-label">Reporte de Visitas</p>
                                        <p class="form-check-label text-danger font-weight-bold"><u>Ahorra $300</u></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h3>Método de Pago</h3>
                    <div class="separator py-3"></div>
                    <form action="{{ route('subscribe.store') }}" method="POST" id="paymentForm">
                        @csrf

                        {{-- Payment platform select --}}
                        <div class="row mt-3">
                            <div class="col">
                                <div class="form-group" id="toggler">
                                    <div class="btn-group btn-group-toggle text-center" data-toggle="buttons">
                                        @foreach ($paymentPlatforms as $paymentPlatform)
                                            <label class="btn btn-outline-secondary rounded m-2 p-1" 
                                              data-target="#{{ $paymentPlatform->name }}Collapse" 
                                              data-toggle="collapse">
                                                <input type="radio" name="payment_platform" value="{{ $paymentPlatform->id }}" @if($paymentPlatform->id == '3') checked @endif>
                                                <img src="{{ asset($paymentPlatform->image) }}" class="img-thumbnail" style="width:100px">
                                            </label>
                                        @endforeach
                                    </div>

                                    @foreach ($paymentPlatforms as $paymentPlatform)
                                    <div 
                                      id="{{ $paymentPlatform->name }}Collapse"
                                      class="{{ $paymentPlatform->id == '3' ? '' : 'collapse'}}"
                                      data-parent="#toggler">    
                                        @includeIf('admin.components.' . strtolower($paymentPlatform->name) . '-collapse')
                                    </div>
                                    @endforeach

                                </div>
                            </div>

                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block rounded-0" id="payButton">Suscribirse</button>
                        </div>
                        <input type="hidden" name="plan" id="plan" value=""/>
                        <input type="hidden" name="value" id="value" value=""/>
                        <input type="hidden" name="currency" id="currency" value="MXN"/>
                        <input type="hidden" name="payment_method" id="payment_method"/>
                        

                    </form>

				</div>
				<!-- /.card-body -->
				<div class="card-footer">
				</div>
				<!-- /.card-footer-->
			</div>
			<!-- /.card -->

		</section>
		<!-- /.content -->
	</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script src="{{ asset('js/CheckOut.js') }}"></script>

    <script>
        const plans = {
            'plan_mensual_nt': 99.00,
            'plan_semestral_nt': 499.00,
            'plan_anual_nt': 899.00
        }

        function SetPlanValue(PlanValue){           
            const planValue = $('#value');
            planValue.attr('value',PlanValue);
        }

        function SetPlanID(PlanID){
            
            setPlanSelectedInformation(PlanID, {
                'planPrice': 'selectedPlanPriceString',
                'planSlug': 'selectedPlanSlugString',
                'planAds': 'selectedPlanAdsString',
                'planDiscount': 'selectedPlanDiscountString',
                'planTotalPrice': 'selctedPlanTotalPriceString',
                'planBeforeDiscountPrice': 'selectedPlanBeforeDiscountPrice',
            });

            const planInput = $('#plan');

            planInput.attr('value',PlanID);
            SetPlanValue(plans[PlanID] ?? 100.00);
        }

        SetPlanID('plan_mensual_nt');
    </script>

    <!-- Success Alert -->
    @if (session()->has('success'))
        
    <script>
        Swal.fire(
            'Good job!',
            '{{ session()->get('success') }}',
            'success'
            )
    </script>

    @endif

    <!-- Errors Alert -->
    @if ($errors->any())
    @foreach ($errors->all() as $error)
        
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ $error }}',
                })
        </script>
    @endforeach
    @endif


    <!-- Stripe -->
    <script src="https://js.stripe.com/v3/"></script>

    <script>
        const stripe = Stripe('{{ config("services.stripe.key") }}')

        const elements = stripe.elements({locale: 'es'});

        const cardElement = elements.create('card', {
            style: {
                iconStyle: 'solid',
                base: {
                    iconColor: '#1e1d85',
                    color: '#2f3542',
                    fontWeight: '500',
                    fontFamily: 'Roboto, Open Sans, Segoe UI, sans-serif',        
                    fontSmoothing: 'antialiased',
                    ':-webkit-autofill': {
                        color: '#fce883',
                    },
                    '::placeholder': {
                        color: '#020024',
                    },
                },
                invalid: {
                    iconColor: '#ff4757',
                    color: '#ff4757',
                },
            },
        });


        cardElement.mount('#cardElement');
    </script>

    <script>
        const stripeID = 3;
        const paymentForm = document.getElementById('paymentForm');

        async function useStripe() {

            const userName = document.getElementById('stripe_user_name');
            const userEmail = document.getElementById('stripe_user_email');

            const { paymentMethod, error } = await stripe.createPaymentMethod('card', cardElement, {
                billing_details: {
                    "name": userName,
                    "email": userEmail
                }
            });

            if (error) {

                Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: error.message
                })
                
                return null;
            } else {
                const tokenInput = document.getElementById('payment_method');
                tokenInput.setAttribute('value', paymentMethod.id);

                paymentForm.submit();
            }

        }

        paymentForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            if(paymentForm.elements.payment_platform.value == stripeID) {

                await useStripe();
                return null;


            } else {

                paymentForm.submit();
            
            }

        });


    </script>
@endsection