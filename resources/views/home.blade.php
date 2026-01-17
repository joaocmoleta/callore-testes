@extends('templates.main', [
    'description' => 'Procurando por aquecedor de toalhas? Confira os modelos produzidos pela Callore. Facilidade no pagamento e entrega. Baixo consumo de energia.',
    'og_title' => 'Callore Aquecedores de Toalhas',
])
@section('content')
    @include('flash-message')

    {{-- Natal 2023
    @include('home-video') --}}

    {{-- @include('home-destaque') --}}
    {{-- @include('black-friday-2023') --}}
    {{-- @include('banner-home') --}}
    {{-- @include('info-home') --}}
    {{-- @include('about') --}}

    {{-- video do aquecedor --}}
    @include('home-2025')
    
    {{-- @include('banner-black-friday-2025') --}}
    
    <div class="info-home-20">
        <p><a href="javascript:void(0)" onclick="montarPopUp()">Cadastre-se e ganhe um cupom de desconto para sua 1ª compra.</a></p>
    </div>
    
    @include('cores')

    @include('linhas-home')

    @include('caracteristicas')

    @include('about-home')

    @include('depoimentos-home')

    @include('revenda-catalogo-home')
@endsection
