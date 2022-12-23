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

                    <form action="#" method="POST" id="paymentForm">
                        @csrf
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

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">Suscribirse</button>
                        </div>
                        <input type="hidden" name="plan" id="plan" value=""/>
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
    {{-- <script>
        Swal.fire(
            'Good job!',
            'You clicked the button!',
            'success'
            )

    </script> --}}
@stop
