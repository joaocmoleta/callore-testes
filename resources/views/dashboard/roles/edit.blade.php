@extends('templates.dashboard.main')
@section('content')
<div class="home-dashboard">
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">Painel</a>
        <span>></span>
        <a href="{{ route('roles.index') }}">Papéis</a>
    </div>

    @include('flash-message')
    
    <h3 class="title">Edição</h3>
    <form action="{{ route('roles.update', $role) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="input">
            <input type="text" name="name" value="{{ $role->name }}" placeholder="admin">
            @error('name')
                <div class="msg-error">{{ $message }}</div>
            @enderror
        </div>
        <div class="submit">
            <button class="bt-primary-one">Atualizar</button>
        </div>
    </form>

    <div class="permissions">
        <h3>Permissões atribuídas</h3>
        <span>Clique para excluir</span>
        <div class="list">
            @if ($role->permissions)
                @foreach ($role->permissions as $role_permission)
                    <form action="{{ route('roles.permissions.revoke', [$role->id, $role_permission->id]) }}"
                        method="post" onsubmit="return confirm('Você tem certeza?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bt-primary-one-small">{{ $role_permission->name }}</button>
                    </form>
                @endforeach
            @endif
        </div>
    </div>

    <div class="permissions-add">
        <h3>Atribuir permissão</h3>
        <div class="list">
            @foreach ($permissions as $permission)
                <form action="{{ route('roles.permissions', $role->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="permission">
                    <button class="bt-primary-one"
                        onclick="this.previousElementSibling.value = this.innerText">{{ $permission->name }}</button>
                </form>
            @endforeach
        </div>

    </div>
</div>
<script>
    function add(e, tg) {
        for (i = 0; i < e.parentElement.children.length; i++) {
            e.parentElement.children[i].classList.remove('bt-primary-one');
            e.parentElement.children[i].classList.add('bt-secondary-one');
        }
        e.classList.remove('bt-secondary-one');
        e.classList.add("bt-primary-one");
        tg.value = e.innerText;
    }
</script>
@endsection