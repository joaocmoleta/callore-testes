@extends('templates.main', [
    'description' => 'Ficou com alguma dúvida? Envie uma mensagem para nós. Será um prazer atender você.',
    'og_title' => 'Contato com a Callore Aquecedores de Toalhas',
])
@section('content')
    <div class="single-breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span>></span>
        <span>Contato com a Callore Aquecedores de Toalhas</span>
    </div>

    <div class="contato-callore-page">
        <h1>Contato com a Callore Aquecedores de Toalhas</h1>
        @include('flash-message')
        
        <p>Ficou com alguma dúvida? Envie uma mensagem para nós. Será um prazer atender você.</p>

        <div class="contact-info">
            <form action="{{ route('contact.send') }}" method="POST">
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
                <img src="{{ asset('img/atendimento-callore-800.webp') }}" alt="Atendimento Callore">
            </div>
        </div>
    </div>
@endsection
