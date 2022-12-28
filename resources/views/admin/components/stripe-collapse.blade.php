<label for="card-details" class="mt-3">Card Details</label>
<div id="cardElement"></div>
<small class="form-text text-muted" id="cardErrors" role="alert"></small>

@section('js')
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
@stop