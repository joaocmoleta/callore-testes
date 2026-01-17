@extends('templates.dashboard.main')
@section('content')
<div class="home-dashboard">
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">Painel</a>
        <span>></span>
        <a href="{{ route('users.index') }}">Usuários</a>
    </div>

    @include('flash-message')
    
    <h3 class="title">Edição</h3>
    <form action="{{ route('users.update', $user->id) }}" method="POST" autocomplete="off">
        @csrf
        @method('PUT')

        <label for="name">Nome</label>
        <div class="input">
            <input type="text" name="name" placeholder="Ronaldo Silva"
                value="{{ old('name') ? old('name') : $user->name }}">
                @error('name')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
        </div>
        
        <label for="doc">CPF</label>
        <div class="input">
            <input type="text" name="doc" placeholder="00000000000"
                value="{{ old('doc') ? old('doc') : $user->doc }}">
                @error('doc')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
        </div>

        <label for="name">Email</label>
        <div class="input">
            <input type="email" name="email" placeholder="usuario@dominio.com.br"
                value="{{ old('email') ? old('email') : $user->email }}">
                @error('email')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
        </div>

        <label>Telefone</label>
                <div class="input-phone-ddi-ddd-n">
                    +<input type="text" name="ddi" placeholder="55" maxlength="2" class="ddi" value="{{ old('ddi') ? old('ddi') : substr($user->phone, 0, 2) }}">
                    (<input type="text" name="ddd" placeholder="41" maxlength="2" class="ddd"
                    value="{{ old('ddd') ? old('ddd') : substr($user->phone, 2, 2) }}">)
                    <input type="text" name="phone" value="{{ old('phone') ? old('phone') : substr($user->phone, 4) }}" placeholder="988889999"
                        onkeyup="this.value = mtels(this.value)" maxlength="10">
                </div>
                @error('phone')
                    <div class="msg-error">{{ $message }}</div>
                @enderror

        <label for="city">Cidade</label>
        <div class="input">
            <input type="text" name="city" placeholder="Curitiba"
                value="{{ old('city') ? old('city') : $user->city }}">
                @error('city')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
        </div>

        <label for="name">Nova senha (deixe em branco para não alterar)</label>
        <div class="input">
            <input type="password" name="password" placeholder="Nova senha" value="{{ old('password') }}"
                readonly onclick="edit_pass()">
        </div>

        <label>Confirmação de senha</label>
        <div class="input">
            <input type="password" name="password_confirmation" placeholder="Confirmação de nova senha"
                value="{{ old('password_confirmation') }}" readonly>
                @error('password')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
        </div>

        <div class="submit">
            <button class="bt-primary-one">Atualizar</button>
        </div>
    </form>

    <h3>Papéis</h3>
    <span>Clique para excluir</span>
    <div class="list-roles">
        @if ($user->roles)
            @foreach ($user->roles as $user_role)
                <form action="{{ route('users.roles.remove', [$user->id, $user_role->id]) }}" method="POST"
                    onsubmit="return confirm('Você tem certeza?');">
                    @csrf
                    @method('DELETE')
                    <button class="bt-primary-danger">{{ $user_role->name }}</button>
                </form>
            @endforeach
        @endif
    </div>
    
    <h3>Atribuir a um papel</h3>
    <div class="roles-add">
        <form action="{{ route('users.roles.add', $user->id) }}" method="POST">
            @csrf
            <input type="hidden" name="role">
            @foreach ($roles as $role)
                <button class="bt-primary-one-small"
                    onclick="document.getElementsByName('role')[0].value = this.innerText">{{ $role->name }}</button>
            @endforeach
        </form>
    </div>
</div>
<script>
    function edit_pass() {
        let password = document.getElementsByName('password')[0];
        let password_confirmation = document.getElementsByName('password_confirmation')[0];
        
        password.removeAttribute('readonly')
        password_confirmation.removeAttribute('readonly')
    }
</script>
@endsection