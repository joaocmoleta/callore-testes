@extends('templates.dashboard.main')
@section('content')
    <div class="home-dashboard">
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Painel</a>
            <span>></span>
            <a href="{{ route('users.index') }}">Usuários</a>
        </div>

        @include('flash-message')

        <h3 class="title">Novo</h3>

        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <label for="name">Nome</label>
            <div class="input">
                <input type="text" name="name" placeholder="Ronaldo Silva" value="{{ old('name') }}">
                @error('name')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label for="doc">CPF</label>
            <div class="input">
                <input type="text" name="doc" placeholder="00000000000" value="{{ old('doc') }}">
                @error('doc')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label for="name">Email</label>
            <div class="input">
                <input type="email" name="email" placeholder="usuario@dominio.com.br" value="{{ old('email') }}">
                @error('email')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label>Telefone</label>
            <div class="input-phone-ddi-ddd-n">
                +<input type="text" name="ddi" placeholder="55" maxlength="2" class="ddi" value="55">
                (<input type="text" name="ddd" placeholder="41" maxlength="2" class="ddd"
                    value="{{ old('ddd') }}">)
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="988889999"
                    onkeyup="this.value = mtels(this.value)" maxlength="10">
            </div>
            @error('phone')
                <div class="msg-error">{{ $message }}</div>
            @enderror

            <label for="city">Cidade</label>
            <div class="input">
                <input type="text" name="city" placeholder="Curitiba" value="{{ old('city') }}">
                @error('city')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label for="name">Crie uma senha</label>
            <div class="input">
                <input type="password" name="password" placeholder="Nova senha" value="{{ old('password') }}">
            </div>

            <label>Confirmação de senha</label>
            <div class="input">
                <input type="password" name="password_confirmation" placeholder="Confirmação de nova senha"
                    value="{{ old('password_confirmation') }}">
                @error('password')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="submit">
                <button class="bt-primary-one">Adicionar</button>
            </div>
        </form>
    </div>
@endsection
