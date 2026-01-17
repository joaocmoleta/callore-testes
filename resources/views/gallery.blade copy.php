<div class="car-mol-23-box" style="height: 100%">
    <div class="car-mol-23" id="gallery" ontouchstart="touchStart(event)"
    onmouseout="closePreviewImg(this)" onmousemove="positionMouse(this, event)">
    <div class="item">
        @include('3d-product')
    </div>
        @foreach (json_decode($product->images) as $item)
            <div class="item">
                <figure>
                    <img src="{{ asset($item) }}" alt="{{ $product->title }}" title="{{ $product->title }}" onclick="setGalleryImage(this)"
                        onmouseover="previewImg(this, event)">
                </figure>
            </div>
        @endforeach
    </div>
    <div class="others-colors">
        @if ($tech_spec->color != 'Cromado')
            <a href="{{ route('products.single', 'aquecedor-de-toalhas-callore-versatil-branco-' . $tech_spec->voltage . 'v') }}" title="Branco" class="white {{ $tech_spec->color == 'Branco' ? 'active' : '' }}"></a>
            <a href="{{ route('products.single', 'aquecedor-de-toalhas-callore-versatil-preto-' . $tech_spec->voltage . 'v') }}" title="Preto" class="black {{ $tech_spec->color == 'Preto' ? 'active' : '' }}"></a>
            <a href="{{ route('products.single', 'aquecedor-de-toalhas-callore-versatil-bege-' . $tech_spec->voltage . 'v') }}" title="Bege" class="bege {{ $tech_spec->color == 'Bege' ? 'active' : '' }}"></a>
        @endif
    </div>
</div>
<script>
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
        if(document.getElementById('image-viewer')) {
            document.getElementById('image-viewer').style.transform = `scale(2) translate(${x}px, ${y}px)`
        }
    }

    function closePreviewImg(e) {
        // console.log(e.parentElement.children[e.parentElement.children.length - 1])
        // e.parentElement.children[e.parentElement.children.length - 1].remove()
        if(document.getElementsByClassName('viewer')[0]) {
            document.getElementsByClassName('viewer')[0].remove();
        }
    }
</script>
