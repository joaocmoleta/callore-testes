@extends('templates.main', ['og_title' => 'Aquecedor de Toalhas Callore tem garantia Weco'])
@section('content')
    @include('flash-message')
    <section class="guaranteed">
        <h2>Aquecedor de Toalhas Callore tem garantia Weco</h2>
        <p>Produzido pela WECO, tradicional empresa metalúrgica de Porto Alegre com mais de 50 anos.</p>
        <h3>Conheça as políticas:</h3>
        <ol>
            <li>
                <a href="{{ route('return-term') }}">Política de devoluções</a>
            </li>
            <li>
                <a href="{{ route('delivery-term') }}">Política de entrega</a>
            </li>
            <li>
                <a href="{{ route('privacy') }}">Política de privacidade</a>
            </li>
            <li>
                <a href="{{ route('terms') }}">Termos de uso</a>
            </li>
        </ol>
        <h3 style="margin-top: 30px">Certificação Inmetro</h3>
                <ul>
                    <li>
                        <p>Equipamento certificado pelo OCP 0070 conforme Portaria Inmetro – 148/2022.
                            Normas especificas: ABNT NBR NM 60.335-1/2010
                            IEC 60.335-2-43/2008.</p>
                    </li>
                </ul>
    </section>
@endsection
