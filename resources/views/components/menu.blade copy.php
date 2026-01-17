<header>
    <nav class="navbar">
        <a href="#" class="logo">
            <img src="{{ asset('/img/logo-callore-site.svg') }}" alt="Logo {{ env('APP_NAME') }}" width="200">
        </a>
        <button class="menu-toggle" aria-label="Abrir menu">&#9776;</button>
        <ul class="nav-links">
            <li><a href="{{ route('home') }}">Início</a></li>
            <li><a href="{{ route('about') }}">Sobre</a></li>
            <li><a href="{{ route('products.list') }}">Produtos</a></li>
            <li><a href="{{ route('contact.index') }}">Contato</a></li>
            <li class="has-submenu">
                <a href="#produtos">Produtos ▾</a>
                <ul class="submenu">
                    <li><a href="#categoria1">Categoria 1</a></li>
                    <li><a href="#categoria2">Categoria 2</a></li>
                    <li><a href="#categoria3">Categoria 3</a></li>
                </ul>
            </li>
        </ul>

        <div class="nav-actions">
            <a href="#login" class="login-btn">Entrar</a>
            <a href="#carrinho" class="cart-btn" aria-label="Carrinho">
                🛒 <span class="cart-count">2</span>
            </a>
        </div>
    </nav>
</header>

<script>
    const toggle = document.querySelector('.menu-toggle');
    const navLinks = document.querySelector('.nav-links');

    toggle.addEventListener('click', () => {
        navLinks.classList.toggle('active');
    });

    toggle.addEventListener('click', () => {
        const expanded = toggle.getAttribute('aria-expanded') === 'true';
        toggle.setAttribute('aria-expanded', !expanded);
        navLinks.classList.toggle('active');
    });
</script>
