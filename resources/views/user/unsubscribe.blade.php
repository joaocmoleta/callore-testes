@extends('templates.main', [
    'description' => '',
    'og_title' => 'Desinscrição de newsletter Callore Aquecedores de Toalhas',
    'nofollow' => true,
    'noindex' => true
])
@section('content')
    @include('flash-message')

    <div class="unsubscribe-newsletter">
        <h1>Desinscrição de newsletter</h1>

        <h2>Sentiremos sua falta!</h2>

        <p>Tudo certo! Sua escolha foi registrada com sucesso.</p>

        <p>Agradecemos por ter feito parte da nossa comunidade — e, se um dia quiser voltar, estaremos aqui.</p>
    </div>
@endsection
