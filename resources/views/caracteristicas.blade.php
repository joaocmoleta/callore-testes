<section class="caracteristicas" style="background: url('{{ asset('img/compacto-branco-com-fio--3-opacity.webp') }}')">
    <img src="{{ asset('icons/prev.svg') }}" alt="<" class="prev-on-slide" onclick="prevSlide(this)">
    <div class="caracteristicas-scroll">
        <div class="caracteristicas-content">
            <a href="{{ route('aquecedores-de-toalhas-callore') }}" class="item reveal-item">
                <img src="{{ asset('img/soldagem-aquecedor-de-toalhas-callore.webp') }}"
                    alt="Tecnologia em Foco">
                <p>Tecnologia em Foco</p>
            </a>
            <a href="{{ route('aquecedores-de-toalhas-callore') }}" class="item reveal-item">
                <img src="{{ asset('img/seque-e-aqueca-toalhas-callore.webp') }}"
                    alt="Segurança e Praticidade">
                <p>Segurança e Praticidade</p>
            </a>
            <a href="{{ route('aquecedores-de-toalhas-callore') }}" class="item reveal-item">
                <img src="{{ asset('img/saude-e-conforto.webp') }}"
                    alt="Saúde e Conforto">
                <p>Saúde e Conforto</p>
            </a>
            <a href="{{ route('aquecedores-de-toalhas-callore') }}" class="item reveal-item">
                <img src="{{ asset('img/sustentabilidade-aquecedor-de-toalhas-callore-home.webp') }}"
                    alt="Sustentabilidade">
                <p>Sustentabilidade</p>
            </a>
        </div>
    </div>
    <img src="{{ asset('icons/next.svg') }}" alt=">" class="next-on-slide" onclick="nextSlide(this)">
</section>
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

    // document.addEventListener("scroll", (event) => {
    //     animateShow(window.scrollY)
    // })

    // var animate = true

    // function animateShow(position) {

    //     if(position > 1612 && animate) {
    //         animate = false

    //         let a = document.querySelector('.caracteristicas-content')
    //         let a_arr = Array.from(a.children)
    //         let counter = 0

    //         a_arr.forEach(element => {
    //             setTimeout(() => {
    //                 element.style.opacity = 1
    //             }, counter * 1000);
    //             counter++
    //         });
    //     }
    // }

    // document.addEventListener('DOMContentLoaded', () => {
    //     const revealItems = document.querySelectorAll('.reveal-item');
    //     const delayBetweenItems = 100; // Atraso em milissegundos (0.2s)

    //     const observerOptions = {
    //         root: null, // Observa a partir da viewport
    //         rootMargin: '0px',
    //         threshold: 0.8 // Dispara quando 10% do item estiver visível
    //     };

    //     const observer = new IntersectionObserver((entries, observer) => {
    //         entries.forEach(entry => {
    //             if (entry.isIntersecting) {
    //                 const target = entry.target;

    //                 // Calcular e aplicar o atraso sequencial
    //                 // O índice pode ser obtido de várias maneiras, 
    //                 // por exemplo, usando a posição do elemento em um array.
    //                 // Neste exemplo, vamos iterar e aplicar um atraso.

    //                 // Uma maneira simples de aplicar o atraso sequencial para todos os itens em um grupo:
    //                 const allItems = Array.from(revealItems);
    //                 const index = allItems.indexOf(target);
    //                 if (index !== -1) {
    //                     // target.style.setProperty('--delay', `${index * delayBetweenItems}ms`);
    //                 }

    //                 setTimeout(() => {
    //                     target.classList.add('is-visible');

    //                 }, `${index * delayBetweenItems}ms`);

    //                 observer.unobserve(target); // Para de observar após a animação
    //             }
    //         });
    //     }, observerOptions);

    //     revealItems.forEach(item => {
    //         observer.observe(item);
    //     });
    // });
</script>
