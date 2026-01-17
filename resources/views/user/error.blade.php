@extends('templates.main', [
    'description' => '',
    'og_title' => 'Não encontrado - Desinscrição de newsletter Callore Aquecedores de Toalhas',
    'nofollow' => true,
    'noindex' => true
])
@section('content')
    @include('flash-message')

    <div class="unsubscribe-newsletter">
        <h1>Desinscrição de newsletter</h1>

        <p>Sentimos muito, mas este e-mail não foi encontrado em nossa base de dados.</p>
    </div>
@endsection
