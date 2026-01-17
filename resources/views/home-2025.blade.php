<div class="home-2025">
    {{-- <img src="{{ asset('img/imagem-teste.webp') }}" alt="Banner 2025"> --}}
    <video width="100%" height="auto" autoplay muted id="video-produtos" class="landscape">
        <source src="{{ asset('/videos/projeto.mp4') }}" type="video/mp4">
        Seu navegador não suporta vídeos
    </video>
    <video width="100%" height="auto" autoplay muted class="portrait">
        <source src="{{ asset('/videos/projeto-retrato.mp4') }}" type="video/mp4">
        Seu navegador não suporta vídeos
    </video>

    <span class="bt-continuar"><img src="{{ asset('icons/chevron-down.svg') }}" alt="Continuar para o conteúdo" onclick="continuarConteudo()"></span>
</div>
<script>
    function continuarConteudo() {
        const alturaViewport = window.innerHeight

        window.scrollTo({
            top: alturaViewport,
            behavior: 'smooth'
        });
    }

    const video = document.getElementById('video-produtos')

    video.addEventListener('ended', (event) => {
        const bt_continuar = document.querySelector('.bt-continuar')

        bt_continuar.style.display = 'inline-block'
    });

    // let banner = document.querySelector('.home-2025')

    // let counter = 0
    // let images = [
    //     '/videos/banho-com-callore-aquecedores-de-toalhas.mp4',
    //     '/videos/aquecedor-de-toalhas-callore.mp4',
    // ]

    // function startVideoChanges() {
    //     setInterval(() => {
    //         banner.children[0].setAttribute('src', images[counter])
    //         // banner.style.background = `url('${images[counter]}')`
    //         // banner.style.backgroundSize = "cover"
    //         if(counter == images.length - 1) {
    //             counter = 0
    //         } else {
    //             counter++
    //         }
    //     }, 3000);
    // }
</script>
