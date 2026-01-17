<div class="cookies-accept" id="cookies-accept">
    <p>Nosso site usa cookies para melhorar sua experiência na navegação do site, em acordo com a <a
            href="http://www.planalto.gov.br/ccivil_03/_Ato2015-2018/2018/Lei/L13709compilado.htm" target="_blank">LGPD -
            Lei Geral de Proteção de Dados Pessoais, lei 13.709/2018</a>. Ao continuar
        navegando, você concorda com nossos <a href="{{ route('terms') }}">Termos de uso</a> e nossa <a
            href="{{ route('privacy') }}">Política de Privacidade</a>.</p>
    <button class="bt-primary-one" onclick="cookies_accept(this)">Aceitar</button>
</div>

@if (env('APP_ENV') == 'production')
    <link rel="stylesheet" href="{{ asset('css/styles.min.css?v=4.4') }}">
    <script src="{{ asset('js/cookies.min.js') }}"></script>
@else
    <link rel="stylesheet" href="{{ asset('css/styles.css?v=4.4') }}">
    <script src="{{ asset('js/cookies.js') }}"></script>
@endif

<script>
    let cookie_exp = "{{ \Carbon\Carbon::now()->addYear()->format('D, d M Y H:i:s e') }}"
    let route_pop_up_leave = "{{ route('pop-up-leave') }}"
    let csrf_token = "{{ csrf_token() }}"
</script>
