<div class="image-on-single-box">
    @php
        $gallery = json_decode($product->images);
    @endphp
    <div class="gallery-product-new" onscroll="scrollHideNext(this)"  ondragstart="dragStart(this, event)" ondrag="dragWhile(this, event)">
        <div class="gallery-content" style="width: {{ 100 * count($gallery) }}%">
            {{-- <div class="gallery-content" style="width: {{ 100 * (count($gallery) + 1) }}%">
            <div class="item">
                @include('3d-product')
            </div> --}}
            @foreach ($gallery as $item)
                <div class="item">
                    <figure>
                        <img src="{{ asset($item) }}" alt="{{ $product->title }}" title="{{ $product->title }}">
                    </figure>
                </div>
            @endforeach
        </div>
        <img src="{{ asset('icons/next.svg') }}" alt=">" class="next-on-slide" style="display: flex;"
            onclick="rollLittle(this)">
    </div>
    <div class="others-colors">
        @if ($tech_spec->color != 'Cromado')
            <a href="{{ route('products.single', 'aquecedor-de-toalhas-callore-versatil-branco-' . $tech_spec->voltage . 'v') }}"
                title="Branco" class="white {{ $tech_spec->color == 'Branco' ? 'active' : '' }}"></a>
            <a href="{{ route('products.single', 'aquecedor-de-toalhas-callore-versatil-preto-' . $tech_spec->voltage . 'v') }}"
                title="Preto" class="black {{ $tech_spec->color == 'Preto' ? 'active' : '' }}"></a>
            <a href="{{ route('products.single', 'aquecedor-de-toalhas-callore-versatil-bege-' . $tech_spec->voltage . 'v') }}"
                title="Bege" class="bege {{ $tech_spec->color == 'Bege' ? 'active' : '' }}"></a>
        @endif
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

    function rollLittle(e) {
        e.parentElement.scroll(e.parentElement.offsetWidth, 0)
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
</script>
