<header class="new-menu">
    <nav class="navbar">
        <button class="menu-toggle" aria-label="Abrir menu" aria-expanded="false">
            <img src="{{ asset('icons/menu.svg') }}" alt="Menu principal" width="30px">
        </button>

        <a href="{{ route('home') }}" class="logo">
            <img src="{{ asset('/img/logo-callore-site.svg') }}" alt="Logo {{ env('APP_NAME') }}" width="200">
        </a>

        <ul class="nav-links">
            <li><a href="{{ route('home') }}">Início</a></li>
            {{-- <li><a href="#sobre">Sobre</a></li> --}}

            <li class="has-submenu">
                <a href="javascript:void(0)" class="open">Sobre</a>
                <div class="submenu">
                    <div class="about-menu">
                        <div class="fabricante">
                            <a href="https://weco.ind.br/" target="_blank"><img
                                    src="{{ asset('img/weco-59-anos.webp') }}" alt="Logo 59 anos Weco"></a>
                            <p>Conheça o fabricante por trás do Aquecedor de Toalhas Callore: a WECO, uma empresa com
                                sólida trajetória no setor industrial, reconhecida pela engenharia precisa, rigor nos
                                processos produtivos e compromisso com a qualidade. Cada produto Callore é desenvolvido
                                com foco em durabilidade, eficiência térmica e segurança, refletindo a experiência de
                                quem domina o processo do projeto à fabricação.</p>
                            <p><a href="https://weco.ind.br/" target="_blank">Conheça o fabricante <img
                                        src="{{ asset('icons/external.svg') }}" alt="Link externo"></a></p>
                        </div>
                        <div class="marca">
                            <a href="{{ route('about') }}"><img src="{{ asset('img/logo-callore.webp') }}"
                                    alt="Logo Aquecedor de Toalhas Callore"></a>
                            <p>Produção de aquecedores de toalhas com carinho, precisão e resultados que você sente no
                                dia a
                                dia.</p>
                            <p><a href="{{ route('about') }}">Conheça a marca <img
                                        src="{{ asset('icons/external.svg') }}" alt="Link externo"></a></p>
                            <div class="gaucha">
                                <img src="{{ asset('img/mapa-rio-grande-do-sul.webp') }}"
                                    alt="Mapa do Rio Grande do Sul">
                                Uma empresa gaúcha
                            </div>
                        </div>
                    </div>
                </div>
            </li>

            <li class="has-submenu">
                <a href="javascript:void(0)" class="open">Produtos</a>
                <div class="submenu">
                    <div class="products-menu">
                        <div class="about-aquecedor" style="background: url('{{ asset('img/acabamentos.webp') }}')">
                            {{-- <p><img src="{{ asset('img/compacto-preto-com-fio--2.webp') }}"
                                    alt="Compacto preto com fio">
                            </p> --}}
                            <p><a href="{{ route('aquecedores-de-toalhas-callore') }}">Conheça todos os benefícios do
                                    aquecedor</a></p>
                            <p><a href="{{ route('flip') }}">Catálogo digital <img
                                        src="{{ asset('icons/book-magazine-flip.svg') }}" alt="Catálogo digital"></a>
                            </p>
                            <p><a href="{{ route('products.list') }}">Todos os produtos</a></p>
                        </div>
                        <div class="modelos">
                            <p><strong>Principais modelos</strong></p>
                            <p><a
                                    href="{{ route('products.single', 'aquecedor-de-toalhas-callore-versatil-preto-127v') }}">Aquecedor
                                    de Toalhas Callore Versátil</a></p>
                            <p><a
                                    href="{{ route('products.single', 'aquecedor-de-toalhas-callore-compacto-preto-127v') }}">Aquecedor
                                    de Toalhas Callore Compacto</a></p>
                            <p><a
                                    href="{{ route('products.single', 'aquecedor-de-toalhas-callore-familia-preto-127v') }}">Aquecedor
                                    de Toalhas Callore Família</a></p>
                            <p><a href="{{ route('products.single', 'aquecedor-de-toalhas-callore-stilo-8-127v') }}">Aquecedor
                                    de Toalhas Callore Stilo 8</a></p>
                            <p><a href="{{ route('products.single', 'aquecedor-de-toalhas-callore-stilo-10-127v') }}">Aquecedor
                                    de Toalhas Callore Stilo 10</a></p>
                            <br>
                            <p><a href="{{ route('personalizada') }}" class="bt-primary-one">Personalize seu produto</a></p>
                        </div>
                    </div>
                </div>
            </li>

            <li><a href="{{ route('posts') }}">Blog</a></li>

            <li class="has-submenu">
                <a href="javascript:void(0)" class="open">Contato</a>
                <div class="submenu">
                    <div class="menu-contato">
                        <div class="image">
                            <img src="{{ asset('img/atendimento-callore-800.webp') }}" alt="Atendimento Callore">
                        </div>
                        <div class="info-menu-contato">
                            <p>
                                <a href="{{ route('contact.index') }}">Entre em contato conosco</a>
                            </p>
                            <p><a href="{{ route('personalizada') }}">Personalize seu produto</a></p>
                            <p><a href="{{ route('contact.revenda') }}">Revenda o Aquecedor de Toalhas Callore</a></p>
                        </div>
                    </div>
                </div>
                {{-- <ul class="submenu">
                    <li><a href="{{ route('contact.index') }}">Entre em contato conosco</a></li>
                    <li><a href="{{ route('personalizada') }}">Personalize seu produto</a></li>
                    <li><a href="{{ route('contact.revenda') }}">Revenda o Aquecedor de Toalhas Callore</a></li>
                </ul> --}}
            </li>

            {{-- <li><a href="{{ route('contact.index') }}">Contato</a></li> --}}
        </ul>

        <ul class="nav-actions">
            <li class="login-btn">
                @auth
                    <a href="javascript:void(0)" class="open">
                        <img src="{{ asset('icons/user.svg') }}" width="30px" alt="Login">
                        <span>Gerenciamento</span>
                    </a>
                    <ul class="submenu">
                        <li>
                            <a href="{{ route('profile.edit') }}">Gerenciar meu Perfil</a>
                        </li>
                        @role('admin|super')
                            <li>
                                <a href="{{ route('dashboard') }}" target="_blank">Painel Administrativo</a>
                            </li>
                        @endrole
                        <li>
                            <a href="{{ route('orders.list') }}">Gerenciar meus Pedidos</a>
                        </li>
                        <li>
                            <form class="bt-logout-menu-top bt-logout-menu-mob" method="POST"
                                action="{{ route('logout') }}">
                                @csrf
                                <button class="bt-primary-danger">Deslogar de {{ Auth::user()->name }}</button>
                            </form>
                        </li>
                    </ul>
                @else
                    <a href="{{ route('login') }}">
                        <img src="{{ asset('icons/user.svg') }}" width="30px" alt="Login">
                        <span>Gerenciamento</span>
                    </a>
                @endauth
            </li>
            <li class="cart-btn">
                <a href="{{ route('carts.list') }}" aria-label="Carrinho">
                    <img src="{{ asset('icons/cart-new.svg') }}" width="30" alt="Carrinho"> <span
                        class="cart-count">{{ $cart->qtd ?? '0' }}</span>
                </a>
            </li>
        </ul>
    </nav>
</header>

<script>
    const bts_open_submenus = document.querySelectorAll('.open');
    const toggle = document.querySelector('.menu-toggle')
    const nav_links = document.querySelector('.nav-links')
    const menu_toggle = document.querySelector('.menu-toggle')
    const login_btn_action = document.querySelector('.login-btn-action')
    const submenus = document.querySelectorAll('.submenu')
    const login_btn = document.querySelector('.login-btn')

    function closeAllSubmenus(e) {
        let estado_atual = {
            'botao': e.classList.contains('opened'),
            'menu': e.nextElementSibling.style.display
        }

        for (i = 0; i < submenus.length; i++) {
            submenus[i].style.display = ''

            if (submenus[i].previousElementSibling.classList.contains('opened')) {
                submenus[i].previousElementSibling.classList.remove('opened')
                submenus[i].previousElementSibling.classList.add('open')
            }
        }

        if (estado_atual['botao']) {
            e.classList.remove('opened')
            e.classList.add('open')
            e.nextElementSibling.style.display = ''
        } else {
            e.classList.remove('open')
            e.classList.add('opened')
            e.nextElementSibling.style.display = 'inline-block'
        }
    }

    for (i = 0; i < bts_open_submenus.length; i++) {
        bts_open_submenus[i].addEventListener('click', e => {
            closeAllSubmenus(e.target)
        })
    }

    toggle.addEventListener('click', e => {
        if (nav_links.style.display == 'none' || nav_links.style.display == '') {
            nav_links.style.display = 'initial'
            disableScrollKeepPosition()
        } else {
            nav_links.style.display = ''
            enableScrollKeepPosition()
        }

        if (menu_toggle.style.width == '') {
            menu_toggle.style.width = '100%'
        } else {
            menu_toggle.style.width = ''
        }

        // Abrir menu de perfil .login-btn
        if (login_btn.style.display == 'none' || login_btn.style.display == '') {
            login_btn.style.display = 'inline-block'
        } else {
            login_btn.style.display = 'none'
        }
    })

    if (login_btn_action) {
        login_btn_action.addEventListener('click', e => {
            closeAllSubmenus(e.target)
            // let estado_atual = e.target.nextElementSibling.style.display

            // if (e.target.classList.contains('login-btn-action')) {
            //     if (e.target.nextElementSibling.style.display == '') {
            //         e.target.nextElementSibling.style.display = 'initial'
            //     } else {
            //         e.target.nextElementSibling.style.display = ''
            //     }
            // } else {
            //     if (e.target.parentElement.nextElementSibling.style.display == '') {
            //         e.target.parentElement.nextElementSibling.style.display = 'initial'
            //     } else {
            //         e.target.parentElement.nextElementSibling.style.display = ''
            //     }
            // }
        })
    }

    let scrollY = 0;

    function disableScrollKeepPosition() {
        scrollY = window.scrollY || window.pageYOffset;
        document.body.style.position = 'fixed';
        document.body.style.top = `-${scrollY}px`;
        document.body.style.left = '0';
        document.body.style.right = '0';
    }

    function enableScrollKeepPosition() {
        document.body.style.position = '';
        document.body.style.top = '';
        // restaura a posição
        window.scrollTo(0, scrollY);
    }
</script>
