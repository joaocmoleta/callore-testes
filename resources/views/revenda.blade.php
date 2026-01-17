@extends('templates.main', [
    'description' => 'Torne-se revendedor do Aquecedor de Toalhas Callore, produzido pela WECO. Produtos de alta qualidade, excelente margem e suporte completo para sua revenda.',
    'og_title' => 'Revenda o Aquecedores de Toalhas',
])
@section('content')
    <div class="single-breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span>></span>
        <span>Revenda o Aquecedores de Toalhas</span>
    </div>

    <div class="contato-callore-page">
        <h1>Revenda o Aquecedores de Toalhas</h1>
        
        <div class="contact-info">
            @include('flash-message')
            <form action="{{ route('contact.send') }}" method="POST">
                <p>Torne-se revendedor do Aquecedor de Toalhas Callore, produzido pela WECO. Produtos de alta qualidade, excelente margem e suporte completo para sua revenda.</p>
                <p>Conheça mais sobre o produto acessando o <a href="{{ route('flip') }}">catálogo</a>.</p>
                <p>Conheça mais sobre o Callore <a href="{{ route('about') }}">sobre</a>.</p>
                <p>Conheça mais sobre os benefícios do aquecedor <a href="{{ route('aquecedores-de-toalhas-callore') }}">benefícios</a>.</p>
                <br>
                <p>Preencha os dados abaixos que entraremos em contato:</p>
                @csrf
                <label for="name">Nome</label>
                <div class="input">
                    <input type="text" name="name" value="{{ old('name') ?? '' }}" placeholder="Ex: João Souza">
                    @error('name')
                        <div class="msg-error">{{ $message }}</div>
                    @enderror
                </div>

                <label>País DDI</label>
                <div class="input">
                    <select id="countries" name="ddi">
                        <option value="{{ old('ddi') ?? '55' }}">{{ old('ddi') ? '+' . old('ddi') : '+55 Brasil' }}</option>
                    </select>
                </div>

                <label>Telefone DDD + número</label>
                <div class="input-phone-ddi-ddd-n">
                    (<input type="text" name="ddd" value="{{ old('ddd') }}" placeholder="dd" maxlength="2"
                        class="ddd">)
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="988889999"
                        onkeyup="this.value = mtels(this.value)" maxlength="10" class="phone">
                </div>
                @error('ddi')
                    <div class="msg-error">{{ $message }}</div>
                @enderror

                <script src="{{ asset('js/countries.min.js') }}"></script>
                <script>
                    let html_countries = document.getElementById("countries")

                    for (const [key, value] of Object.entries(countries)) {
                        let option = document.createElement('option')
                        option.textContent = `+${value['ddi']} ${value['pais']}`
                        option.setAttribute('value', '')
                        html_countries.append(option)
                    }
                </script>

                @error('ddd')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
                @error('phone')
                    <div class="msg-error">{{ $message }}</div>
                @enderror


                <label for="email">E-mail</label>
                <div class="input">
                    <input type="text" name="email" value="{{ old('email') ?? '' }}"
                        placeholder="Ex: joaosouza@empresa.com">
                    @error('email')
                        <div class="msg-error">{{ $message }}</div>
                    @enderror
                </div>

                <label for="Business">Empresa</label>
                <div class="input">
                    <input type="text" name="Business" value="{{ old('Business') ?? '' }}">
                    @error('Business')
                        <div class="msg-error">{{ $message }}</div>
                    @enderror
                </div>

                <label for="message">Escreva sobre sua solicitação:</label>
                <div class="input">
                    <textarea name="message"></textarea>
                    @error('message')
                        <div class="msg-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="agree">
                    <div class="checkbox">
                        <div class="bt-check {{ old('agree') ? 'on' : '' }}"
                            onclick="checkboxTermos(this, document.getElementsByClassName('submit')[0].children[0])"></div>
                        <input type="hidden" name="agree" {{ old('agree') ? 'value="1"' : '' }}>
                    </div>
                    <span>Você concorda com nossos <a href="{{ route('privacity-polices') }}" target="_blank">Termos de
                            Uso</a> e nossas <a href="{{ route('privacity-polices') }}" target="_blank">Políticas de
                            Privacidade</a>?</span>
                </div>
                @error('agree')
                    <div class="msg-error">{{ $message }}</div>
                @enderror

                <script>
                    function checkboxTermos(e, tg) {
                        if (e.classList.contains('on')) {
                            e.classList.remove('on')
                            tg.setAttribute('disabled', 'true')
                            e.nextElementSibling.value = 0
                        } else {
                            e.classList.add('on')
                            tg.removeAttribute('disabled')
                            e.nextElementSibling.value = 1
                        }
                    }
                </script>

                <div class="submit">
                    <button class="bt-primary-one">Enviar minha mensagem</button>
                </div>
            </form>
            <div class="contato-arte">
                <img src="{{ asset('img/familia-preto-suporte-movel.webp') }}" alt="Aquecedor de Toalhas Callore Família com suporte móvel" title="Aquecedor de Toalhas Callore Família com suporte móvel">
            </div>
        </div>
    </div>
@endsection
