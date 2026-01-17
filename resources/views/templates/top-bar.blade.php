<aside class="top-bar-mob">
    <a href="javascript:void(0)" class="close-menu" onclick="this.parentElement.style.width = 0">
        <img src="{{ asset('icons/menu.svg') }}" alt="Menu principal" width="30px">
    </a>
    <nav class="top-bar-mob">
        <ul>
            <li><a href="{{ route('home') }}">Home</a></li>
            <li><a href="/#sobre">Sobre</a></li>
            <li><a href="{{ route('products.list') }}">Produtos</a></li>
            <li><a href="/#contato">Contato</a></li>
            <li>&nbsp;</li>
            <li>
                @auth
                    <span class="user-icon">
                        <img src="{{ asset('icons/user.svg') }}" width="30px" alt="Dados do usuário">
                    </span>
                    <nav class="submenu">
                        <ul>
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
                    </nav>
                @else
                    <a href="{{ route('login') }}">
                        <img src="{{ asset('icons/user.svg') }}" width="30px" alt="Login">
                    </a>
                @endauth
            </li>
            <li class="carrinho-menu">
                <a href="{{ route('carts.list') }}">
                    <img src="{{ asset('icons/cart-new.svg') }}" width="30" alt="Carrinho">
                    <span class="qtd">{{ $cart->qtd ?? '' }}</span>
                    <p>Ver meu carrinho</p>
                </a>
            </li>
        </ul>
    </nav>
</aside>
<header id="header-mol" class="{{ $class }}" role="banner">
    <aside class="top-bar">
        <a href="javascript:void(0)" class="open-menu"
            onclick="this.parentElement.parentElement.previousElementSibling.style.width = '80%'">
            <img src="{{ asset('icons/menu.svg') }}" alt="Menu principal" width="30px">
        </a>
        <a href="{{ route('home') }}" class="logo">
            <img src="{{ asset('/img/logo-callore-site.svg') }}" alt="Logo {{ env('APP_NAME') }}" width="200">
        </a>
        <nav class="nav-main" role="navigation">
            <ul>
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="/#sobre">Sobre</a></li>
                <li><a href="{{ route('products.list') }}">Produtos</a></li>
                <li><a href="/#contato">Contato</a></li>
            </ul>
        </nav>
        <nav class="nav-profile-menu">
            <ul>
                <li class="profile-menu">
                    @auth
                        <span class="user-name" style="cursor: pointer" onclick="profile_menu(this)">
                            <img src="{{ asset('icons/user.svg') }}" width="30px" alt="Login">
                        </span>
                        <nav class="submenu">
                            <ul>
                                <li>
                                    {{-- <a href="{{ route('profile.edit') }}">{{ Auth::user()->name }}</a> --}}
                                    <a href="{{ route('profile.edit') }}">Gerenciar meu Perfil</a>
                                </li>
                                @role('admin|super')
                                    <li>
                                        <a href="{{ route('dashboard') }}">Painel Administrativo</a>
                                    </li>
                                @endrole
                                <li>
                                    <a href="{{ route('orders.list') }}">Gerenciar meus Pedidos</a>
                                </li>
                                <li>
                                    <a href="{{ route('manual') }}" target="_blank">Manual Aquecedor de Toalhas - Revisão
                                        05</a>
                                </li>
                                <li>
                                    <form class="bt-logout-menu-top" method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="bt-primary-danger">Deslogar de {{ Auth::user()->name }}</button>
                                    </form>
                                </li>
                            </ul>
                        </nav>
                    @else
                        <a href="{{ route('login') }}">
                            <img src="{{ asset('icons/user.svg') }}" width="30px" alt="Login">
                        </a>
                    @endauth
                </li>
                <li class="cart-icon">
                    <a href="{{ route('carts.list') }}">
                        <img src="{{ asset('icons/cart-new.svg') }}" width="30" alt="Carrinho">
                        <span class="qtd">{{ $cart->qtd ?? '0' }}</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>


</header>
