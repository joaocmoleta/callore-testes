<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }}</title>

    @include('templates.css-acima-da-borda')
</head>

<body>
    <div class="loader-box-page">
        <span class="loader-page"></span>
    </div>

    <header>
        <aside class="top-bar-inter">
            <div class="menu-left" onclick="openMenu(this, 1)" oncontextmenu="console.log('saiu')">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" class="bt-open-menu">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <nav class="menu">
                    <ul>
                        <li>
                            <a href="{{ route('dashboard') }}">Painel</a>
                        </li>
                        <li>
                            <a href="{{ route('home') }}" target="_blank">Visitar a loja</a>
                        </li>
                        @role('admin|super')
                            <li>
                                <a href="{{ route('products.index') }}">Produtos</a>
                                <ul>
                                    <li>
                                        <a href="{{ route('products.create') }}">Criar</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="{{ route('orders.index') }}">Pedidos</a>
                                <ul>
                                    <li>
                                        <a href="{{ route('orders.create') }}">Criar</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="{{ route('coupons.index') }}">Cupons</a>
                                <ul>
                                    <li>
                                        <a href="{{ route('coupons.create') }}">Criar</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a>CRM</a>
                                <ul>
                                    <li>
                                        <a href="{{ route('leads.index') }}">LEADs</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('actions.index') }}">Ações</a>
                                        <ul>
                                            <li>
                                                <a href="{{ route('actions.create') }}">Criar</a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="{{ route('carts.index') }}">Carrinhos</a>
                                <ul>
                                    <li>
                                        <a href="{{ route('carts.create') }}">Criar</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="{{ route('dashboard-posts') }}">Blog</a>
                                <ul>
                                    <li>
                                        <a href="{{ route('dashboard-posts') }}">Publicações</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('dashboard-categories') }}">Categorias</a>
                                    </li>
                                </ul>
                            </li>
                        @endrole
                        @role('super')
                            <li>
                                <a href="{{ route('roles.index') }}">Papéis</a>
                                <ul>
                                    <li>
                                        <a href="{{ route('roles.create') }}">Criar</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="{{ route('permissions.index') }}">Permissões</a>
                                <ul>
                                    <li>
                                        <a href="{{ route('permissions.create') }}">Criar</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="{{ route('users.index') }}">Usuários</a>
                                <ul>
                                    <li>
                                        <a href="{{ route('users.create') }}">Criar</a>
                                    </li>
                                </ul>
                            </li>
                        @endrole
                    </ul>
                </nav>
            </div>
            <div class="logo">
                <a href="{{ route('dashboard') }}">{{ env('APP_NAME') }}</a>
            </div>

            <div class="menu-right" onclick="openMenu(this, 2)">
                <span class="user-name">{{ Auth::user()->name }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path
                        d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm7.753 18.305c-.261-.586-.789-.991-1.871-1.241-2.293-.529-4.428-.993-3.393-2.945 3.145-5.942.833-9.119-2.489-9.119-3.388 0-5.644 3.299-2.489 9.119 1.066 1.964-1.148 2.427-3.393 2.945-1.084.25-1.608.658-1.867 1.246-1.405-1.723-2.251-3.919-2.251-6.31 0-5.514 4.486-10 10-10s10 4.486 10 10c0 2.389-.845 4.583-2.247 6.305z" />
                </svg>
                <nav class="menu">
                    <ul>
                        <li>
                            <form class="bt-logout-menu-top" method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button style="color: red">Sair</button>
                            </form>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>
    </header>

    @yield('content')

    <footer>
        <aside class="copy-right">
            <p>Desenvolvido por <a href="https://moleta.com.br" target="_blank">Mol Tecnologia e Inovação</a>
                &copy; Todos os direitos reservados <strong>{{ env('APP_NAME') }}</strong>
                {{ \Carbon\Carbon::now()->year }}</p>
        </aside>
    </footer>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/reset-css@5.0.1/reset.min.css">
    @if (env('APP_ENV') == 'production')
        <link rel="stylesheet" href="{{ asset('/css/styles.min.css?v=4.4') }}">
    @else
        <link rel="stylesheet" href="{{ asset('/css/styles.css?v=4.4') }}">
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            setTimeout(() => {
                let loader = document.getElementsByClassName('loader-box-page')[0];
                loader.style.display = "none";
            }, 500);
        });

        function openMenu(e, i) {
            if (e.children[i].style.display === 'initial') {
                e.children[i].style.display = 'none';
            } else {
                e.children[i].style.display = 'initial';
            }
        }
    </script>
</body>

</html>
