@extends('templates.main', ['og_title' => 'Blog da Callore Aquecedores de Toalhas'])
@section('content')
<div class="single-message">
    @include('flash-message')
</div>
    <section class="guaranteed">
        <h2>Blog da Callore Aquecedores de Toalhas</h2>
        <img src="{{ asset('img/blog-aquecedor-de-toalhas-callore.jpg') }}" alt="Blog da Callore Aquecedores de Toalhas">
        <p>Em breve novidades para você ficar por dentro de dicas maravilhosas que irão ajudar a manter o conforto e higiêne do seu banheiro.</p>
    </section>
@endsection
