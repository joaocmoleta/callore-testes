@extends('templates.main', [
    'description' => '',
    'og_title' => '',
])
@section('content')
    @include('flash-message')

    <p>Teste</p>

    <script src="https://safrastatic-a.akamaihd.net/safrapay/checkout/dev/safrapay-transparent-v2.0.0.js"></script>

    <script>
        SafraPayTransparent.setCredentials({
            merchantCredential: "31698759001276",
            merchantToken: "mk_itfYBP6xB5SAnIm5Haq60vYnNd"
        });

        SafraPayTransparent.getCardBrand({
            bin: "549167",
            success,
            error
        });

        function success(body) {
            console.log(body)
        }

        function error(body) {
            console.log(body)
        }
    </script>
@endsection
