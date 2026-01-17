<section class="depoimentos-home">
    <h2>Depoimentos</h2>
    <p class="call-depoimentos">Quem comprou, <strong>comprova a qualidade</strong> do Aquecedor de Toalhas Callore</p>

    {{-- @include('depoimentos-pc')
    
    @include('depoimentos-mob') --}}

    <div class="car-mol-23-multi-box" id="depoimentos-pc-home-box">
        <div class="car-mol-23-multi" id="depoimentos-pc-home">
            @for ($i = 0; $i < count($testimonies); $i++)
                <div class="item">
                    <div class="text">
                        {!! $testimonies[$i]->text !!}
                        <p class="name">{{ $testimonies[$i]->name }}</p>
                        <p class="occupation">{{ $testimonies[$i]->occupation }}</p>
                        <p class="city_state">{{ $testimonies[$i]->city_state }}</p>
                        <p class="bt-depoimentos">
                            <a href="javascript:void(0)" rel="nofollow"
                                onclick="this.parentElement.parentElement.style.display = 'none'">X</a>
                        </p>
                    </div>
                    <div class="abstract">
                        {!! Str::words(strip_tags($testimonies[$i]->text), 13, '...') !!}
                        <p>
                            <button class="bt-thirdary-one"
                                onclick="this.parentElement.parentElement.previousElementSibling.style.display = 'initial'">Leia
                                mais</button>
                        </p>
                    </div>
                    <div class="stars">
                        <img src="{{ asset('icons/stars.svg') }}" width="10" alt="Avaliação 5 estrelas">
                    </div>
                        <p><img src="{{ asset('icons/feliz.gif')}}" alt="" width="10" class="icon"></p>
                        <p class="who"><strong>{{ $testimonies[$i]->name }}</strong></p>
                    <p>{{ $testimonies[$i]->occupation }}</p>
                    <p>{{ $testimonies[$i]->city_state }}</p>
                </div>
            @endfor
        </div>
    </div>
</section>
<script>
    configurations_carousel.push({
                id: 2,
                htmlObj: '#depoimentos-pc-home',
                automatic: false,
                duration: 5000,
                type: 'multi',
                nav: true,
                bubbles: true
            })
</script>
