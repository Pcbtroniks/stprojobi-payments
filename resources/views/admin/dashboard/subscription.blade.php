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
                            Suscripción
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
                            <div class="col-md-6">
                                <h3 class="text-center">Suscripcion Mensual - <small>100 MXN al mes</small></h3>

                                <p class="text-center"><b>Renovación automática</b></p>

                                <div class="form-group text-center">
                                    <div class="form-check">
                                        <input onchange="SetPlanID('plan_mensual')" class="form-check-input" type="radio" name="plan" value="plan_mensual" id="planMensual" checked>
                                        <label class="form-check-label" for="planMensual">Plan mensual</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h3 class="text-center">Suscripcion Anual - <small>800 MXN al año</small></h3>

                                <p class="text-center"><b>Renovación automática</b></p>

                                <div class="form-group text-center">
                                    <div class="form-check">
                                        <input onchange="SetPlanID('plan_anual')" class="form-check-input" type="radio" name="plan" value="plan_anual" id="planAnual">
                                        <label class="form-check-label" for="planAnual">Plan anual</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h3>Método de Pago</h3>
                    <hr>

                    <form action="{{ route('pay') }}" method="POST" id="paymentForm">
                        @csrf

                        {{-- Payment platform select --}}
                        <div class="row mt-3">
                            <div class="col">
                                <div class="form-group" id="toggler">
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        @foreach ($paymentPlatforms as $paymentPlatform)
                                            <label class="btn btn-outline-secondary rounded m-2 p-1" 
                                              data-target="#{{ $paymentPlatform->name }}Collapse" 
                                              data-toggle="collapse">
                                                <input type="radio" name="payment_platform" value="{{ $paymentPlatform->id }}">
                                                <img src="{{ asset($paymentPlatform->image) }}" class="img-thumbnail" style="width:100px">
                                            </label>
                                        @endforeach
                                    </div>

                                    @foreach ($paymentPlatforms as $paymentPlatform)
                                    <div 
                                      id="{{ $paymentPlatform->name }}Collapse"
                                      class="collapse"
                                      data-parent="#toggler">    
                                        @includeIf('admin.components.' . strtolower($paymentPlatform->name) . '-collapse')
                                    </div>
                                    @endforeach

                                </div>
                            </div>

                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">Suscribirse</button>
                        </div>
                        <input type="hidden" name="plan" id="plan" value=""/>
                        <input type="hidden" name="value" id="value" value=""/>
                        <input type="hidden" name="currency" id="currency" value="MXN"/>

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
    <script>
        const plans = {
            'plan_mensual': 100.00,
            'plan_anual': 800.00
        }

        function SetPlanValue(PlanValue){           
            const planValue = $('#value');
            planValue.attr('value',PlanValue);
            console.log(planValue);
        }

        function SetPlanID(PlanID){

            const planInput = $('#plan');

            planInput.attr('value',PlanID);
            SetPlanValue(plans[PlanID] ?? 100.00);
        }

        SetPlanID('plan_mensual');
    </script>

    @if (session()->has('success'))
    @foreach (session()->get('success') as $message)
        
    <script>
        Swal.fire(
            'Good job!',
            '{{ $message }}',
            'success'
            )
    </script>
    @endforeach
    @endif
    @if ($errors->any())
    @foreach ($errors->all() as $error)
        
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ $error }}',
                footer: '<a href>Why do I have this issue?</a>'
                })
        </script>
    @endforeach
    @endif


    {{-- Stripe --}}
    <script src="https://js.stripe.com/v3/"></script>

    <script>
        const stripe = Stripe('{{ config('services.stripe.key') }}')
        const appearance = {
            theme: 'night'
        };

        const elements = stripe.elements({locale: 'es', appearance});

        const cardElement = elements.create('card');

        cardElement.mount('#cardElement');
    </script>
@endsection
