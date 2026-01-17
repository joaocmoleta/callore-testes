@extends('templates.main', [
    'description' => 'Escolha o modelo, a cor e os acessórios do seu aquecedor de toalhas — personalize cada detalhe conforme seu estilo.',
    'og_title' => 'Personalize seu Aquecedores de Toalhas Callore',
])
@section('content')
    <div class="single-breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span>></span>
        <span>Contato com a Callore Aquecedores de Toalhas</span>
    </div>

    <div class="default-page personalizacao-page">
        <h1>Personalize seu Aquecedores de Toalhas Callore</h1>
        @include('flash-message')

        <p>Escolha o modelo, a cor e os acessórios do seu aquecedor de toalhas — personalize cada detalhe conforme seu
            estilo.</p>

        <form action="{{ route('contact.personal') }}" method="POST">
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

            <label for="">Selecione o modelo</label>
            <input type="hidden" name="model" value="{{ old('model') ?? 'Versátil' }}">
            <div class="modern-select">
                <img src="{{ asset('icons/prev.svg') }}" alt="<" class="prev-on-slide" onclick="prevSlide(this)">
                <div class="content-modern-select" onscroll="manualScroll(this)">
                    <div class="options">
                        <div class="option selected" onclick="selectModel(this, 'Versátil')">
                            <p>Versátil</p>
                            <img src="{{ asset('img/aquecedor-de-toalhas-versatil-preto.webp') }}" alt="Versátil">
                        </div>
                        <div class="option" onclick="selectModel(this, 'Compacto')">
                            <p>Compacto</p>
                            <img src="{{ asset('img/aquecedor-de-toalhas-compacto-preto.webp') }}" alt="Compacto">
                        </div>
                        <div class="option" onclick="selectModel(this, 'Família')">
                            <p>Família</p>
                            <img src="{{ asset('img/aquecedor-de-toalhas-familia-preto.webp') }}" alt="Família">
                        </div>
                        <div class="option" onclick="selectModel(this, 'Stilo 8')">
                            <p>Stilo 8</p>
                            <img src="{{ asset('img/aquecedor-de-toalhas-stilo-8.webp') }}" alt="Stilo 8">
                        </div>
                        <div class="option" onclick="selectModel(this, 'Stilo 10')">
                            <p>Stilo 10</p>
                            <img src="{{ asset('img/aquecedor-de-toalhas-stilo-10.webp') }}" alt="Stilo 10">
                        </div>
                    </div>
                </div>
                <img src="{{ asset('icons/next.svg') }}" alt=">" class="next-on-slide" onclick="nextSlide(this)">
            </div>
            @error('model')
                <div class="msg-error">{{ $message }}</div>
            @enderror
            <br>
            <script>
                function selectModel(e, model) {
                    let options = e.parentElement.children
                    let options_arr = Array.from(options)
                    let target = document.getElementsByName('model')[0]

                    target.value = model

                    options_arr.forEach(ele => {
                        ele.classList.remove('selected')
                    })

                    e.classList.add('selected')
                }

                function scrollHideNext(e) {
                    if (e.scrollLeft > 2) {
                        e.children[1].style.display = 'none'
                    } else {
                        e.children[1].style.display = 'flex'
                    }
                }

                function prevSlide(e) {
                    let gallery_box = e.parentElement.children[1]
                    let item = gallery_box.children[0].children[0]

                    gallery_box.scrollLeft = gallery_box.scrollLeft - item.offsetWidth

                    e.parentElement.children[2].style.display = 'flex'

                    if (gallery_box.scrollLeft <= item.offsetWidth) {
                        e.style.display = 'none'
                    }
                }

                function nextSlide(e) {
                    let gallery_box = e.parentElement.children[1]
                    let item = gallery_box.children[0].children[0]

                    gallery_box.scrollLeft = gallery_box.scrollLeft + item.offsetWidth

                    e.parentElement.children[0].style.display = 'flex'

                    if (gallery_box.scrollLeft > gallery_box.children[0].offsetWidth * .6) {
                        e.style.display = 'none'
                    }
                }

                function manualScroll(e) {
                    if (e.scrollLeft > e.children[0].offsetWidth * .568) {
                        e.parentElement.children[2].style.display = 'none'
                    } else {
                        e.parentElement.children[2].style.display = 'flex'
                    }

                    if (e.scrollLeft == 0) {
                        e.parentElement.children[0].style.display = 'none'
                    } else {
                        e.parentElement.children[0].style.display = 'flex'
                    }
                }
            </script>

            <label>Cor do equipamento:</label>
            <div class="input">
                <input type="text" name="color" value="{{ old('color') ?? '' }}" placeholder="Cromado">
                @error('color')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label>Cor do cabo:</label>
            <div class="radio">
                <div>
                    <input type="radio" id="branco" name="cable_color" value="Branco"
                        {{ old('cable_color') ? (old('cable_color') == 'Branco' ? 'checked' : '') : 'checked' }} />
                    <label for="branco">Branco</label>
                </div>

                <div>
                    <input type="radio" id="preto" name="cable_color" value="Preto"
                        {{ old('cable_color') == 'Preto' ? 'checked' : '' }} />
                    <label for="preto">Preto</label>
                </div>
            </div>
            @error('cable_color')
                <div class="msg-error">{{ $message }}</div>
            @enderror

            <label for="message">Informações extras:</label>
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
    </div>
@endsection
