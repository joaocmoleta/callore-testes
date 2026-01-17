<section class="depoimentos-home">
    <h2>Depoimentos</h2>
    <p class="call-depoimentos">Quem comprou, <strong>comprova a qualidade</strong> do Aquecedor de Toalhas Callore</p>

    {{-- @include('depoimentos-pc')
    
    @include('depoimentos-mob') --}}


    <div class="depoimentos-2-0">
        <img src="{{ asset('icons/prev.svg') }}" alt="<" class="prev-on-slide" onclick="prevSlide(this)">
        <div class="depoimentos-box" onscroll="manualScroll(this)">
            <div class="depoimentos-content">
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
                            {!! Str::limit(strip_tags($testimonies[$i]->text), 160, '...') !!}
                            <p>
                                <button class=""
                                    onclick="this.parentElement.parentElement.parentElement.children[0].style.display = 'initial'">Leia
                                    mais</button>
                            </p>
                        </div>
                        <div class="stars">
                            <img src="{{ asset('icons/stars.svg') }}" width="200" alt="Avaliação 5 estrelas">
                        </div>
                        <p><img src="{{ asset('icons/depoimentos-user.svg') }}" alt="" width="110" class="icon"></p>
                        <p class="who"><strong>{{ $testimonies[$i]->name }}</strong></p>
                        <p>{{ $testimonies[$i]->occupation }}</p>
                        <p>{{ $testimonies[$i]->city_state }}</p>
                    </div>
                @endfor
            </div>
        </div>
        <img src="{{ asset('icons/next.svg') }}" alt=">" class="next-on-slide" onclick="nextSlide(this)">
    </div>
    <script>
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
</section>