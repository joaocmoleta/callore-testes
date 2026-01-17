@extends('templates.main', [
    'description' => '',
    'og_title' => '',
])
@section('content')
    @include('flash-message')

    <div class="thanks-page">
        <div class="thanks-content">
            <h2>Sua solicitação de orçamento foi registrada.</h2>
            <p>Em breve, nosso time comercial retornará seu contato. Em caso de dúvidas ou urgência, estamos à disposição em
                nossos canais de atendimento.</p>
        </div>
    </div>
@endsection
