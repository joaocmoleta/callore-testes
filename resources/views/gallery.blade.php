<div class="image-on-single-box">
    @php
        $gallery = json_decode($product->images);
    @endphp
    <div class="gallery-thumbs">
        @foreach ($gallery as $item)
            <div class="item">
                <figure>
                    <img src="{{ asset($item) }}" alt="{{ $product->title }}" title="{{ $product->title }}"
                        onclick="openModalImage(this)">
                </figure>
            </div>
        @endforeach
    </div>
    <div class="gallery-area">
        <div class="gallery-scroll-2">
            <img src="{{ asset('icons/prev.svg') }}" alt="<" class="prev-on-slide" onclick="prevSlide(this)">
            <div class="gallery-box" onscroll="manualScroll(this)">
                <div class="gallery-content" style="width: {{ count($gallery) * 100 }}%">
                    @foreach ($gallery as $item)
                        <div class="item">
                            <figure>
                                <img src="{{ asset($item) }}" alt="{{ $product->title }}"
                                    title="{{ $product->title }}">
                            </figure>
                        </div>
                    @endforeach
                </div>
            </div>
            <img src="{{ asset('icons/next.svg') }}" alt=">" class="next-on-slide" onclick="nextSlide(this)">
        </div>

        <p style="text-align: center">Acabamentos</p>
        <div class="others-colors-new">
            @if ($tech_spec->color != 'Cromado')
                @php
                    $prefix = explode('-', $product->slug);

                    $remove_prefix = 1;
                    if ($prefix[count($prefix) - 1] == '127v' || $prefix[count($prefix) - 1] == '220v') {
                        $remove_prefix = 2;
                    }

                    $new_prefix = '';
                    for ($i = 0; $i < count($prefix) - $remove_prefix; $i++) {
                        $new_prefix .= $prefix[$i];
                        $new_prefix .= '-';
                    }

                    $branco =
                        $remove_prefix == 2
                            ? $new_prefix . 'branco-' . $tech_spec->voltage . 'v'
                            : $new_prefix . 'branco';
                    $preto =
                        $remove_prefix == 2
                            ? $new_prefix . 'preto-' . $tech_spec->voltage . 'v'
                            : $new_prefix . 'preto';
                    $bege =
                        $remove_prefix == 2 ? $new_prefix . 'bege-' . $tech_spec->voltage . 'v' : $new_prefix . 'bege';
                @endphp
                <a href="{{ route('products.single', $branco) }}" title="Branco"
                    class="white {{ $tech_spec->color == 'Branco' ? 'active' : '' }}"><img
                        src="{{ asset('img/acabamento-aquecedor-de-toalhas-callore-branco.webp') }}"
                        alt="Acabamento na cor branca"></a>
                <a href="{{ route('products.single', $preto) }}" title="Preto"
                    class="black {{ $tech_spec->color == 'Preto' ? 'active' : '' }}"><img
                        src="{{ asset('img/acabamento-aquecedor-de-toalhas-callore-preto.webp') }}"
                        alt="Acabamento na cor preta"></a>
                <a href="{{ route('products.single', $bege) }}" title="Bege"
                    class="bege {{ $tech_spec->color == 'Bege' ? 'active' : '' }}"><img
                        src="{{ asset('img/acabamento-aquecedor-de-toalhas-callore-bege.webp') }}"
                        alt="Acabamento na cor bege"></a>
            @endif
        </div>
    </div>
</div>
<script>
    let start = 0
    let end = 0
    let dragged = 0

    function dragStart(e, eventName) {
        // console.log(eventName.clientX)
        start = eventName.clientX
    }

    function dragWhile(e, eventName) {
        end = eventName.clientX
        dragged = end - start

        let element = document.querySelector('.gallery-product-new')

        element.scrollLeft = dragged
    }

    function dragEnd(e, eventName) {
        // console.log(eventName.clientX)
        end = eventName.clientX
        dragged = end - start
        console.log(dragged)

        let element = document.querySelector('.gallery-product-new')

        element.scrollLeft = dragged
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
        if (e.scrollLeft > e.children[0].offsetWidth * .8) {
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


    function setGalleryImage(e) {
        document.getElementById('main-gallery-image').setAttribute('src', e.getAttribute('src'))
    }

    function previewImg(e, ev) {
        let viewer = document.createElement('div')
        viewer.classList.add('viewer')
        // viewer.style.width = e.offsetWidth + 'px'

        let img = document.createElement('img')
        img.setAttribute('src', e.getAttribute('src'))
        img.setAttribute('id', 'image-viewer')
        img.style.transform = `scale(1.5) translate(${ev.offsetX}px, ${ev.offsetY}px)`

        viewer.append(img)

        let fjaljfl = document.getElementsByClassName('side-description')[0]

        fjaljfl.append(viewer)
    }

    function positionMouse(e, ev) {
        let x = e.offsetWidth - ev.offsetX * 2
        let y = e.offsetHeight - ev.offsetY * 2
        if (document.getElementById('image-viewer')) {
            document.getElementById('image-viewer').style.transform = `scale(2) translate(${x}px, ${y}px)`
        }
    }

    function closePreviewImg(e) {
        // console.log(e.parentElement.children[e.parentElement.children.length - 1])
        // e.parentElement.children[e.parentElement.children.length - 1].remove()
        if (document.getElementsByClassName('viewer')[0]) {
            document.getElementsByClassName('viewer')[0].remove();
        }
    }

    function openModalImage(e) {
        constructModalImg(e.getAttribute('src'))
    }

    function constructModalImg(image) {
        let modal = document.createElement('div')
        modal.classList.add('modal-image')

        let modal_content = document.createElement('div')
        modal_content.classList.add('modal-image-content')

        let modal_close = document.createElement('img')
        modal_close.setAttribute('src', '{{ asset('icons/close.svg') }}')
        modal_close.setAttribute('onclick', 'closeModal(this)')
        modal_close.classList.add('bt-close')

        let modal_image = document.createElement('img')
        modal_image.setAttribute('src', image)
        modal_image.classList.add('image')

        modal_content.appendChild(modal_image)
        modal_content.appendChild(modal_close)
        modal.appendChild(modal_content)
        document.body.appendChild(modal)
    }

    function closeModal(e) {
        e.parentElement.parentElement.remove()
    }
</script>
