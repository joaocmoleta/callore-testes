<div class="car-mol-23-box" id="depoimentos-mob-home-box">
    <div class="car-mol-23" id="depoimentos-mob-home">
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
                <figure class="thumbnail-depoimentos">
                    <img
                        src="{{ $testimonies[$i]->thumbnail == '' ? '/img/user-balao.webp' : $testimonies[$i]->thumbnail }}" alt="Depoimento de {{ $testimonies[$i]->name }}" title="Depoimento de {{ $testimonies[$i]->name }}">
                </figure>
                <div class="stars">
                    <img src="{{ asset('icons/stars.svg')}}" width="10" alt="Classificação">
                </div>
                <p class="who"><strong>{{ $testimonies[$i]->name }}</strong></p>
                <p>{{ $testimonies[$i]->occupation }}</p>
                <p>{{ $testimonies[$i]->city_state }}</p>
            </div>
        @endfor
    </div>
</div>
<script>
    configCarousel('depoimentos-mob-home', true, 4000, false);
</script>
