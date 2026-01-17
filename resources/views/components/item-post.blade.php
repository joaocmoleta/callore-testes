<div class="item">
    <a href="{{ $options == 'restaurar' ? 'javascript:void(0)' : route('dashboard-posts-edit', $item_id) }}">
        <img src="{{ $thumbnail }}">
    </a>
    <div class="posts-list-info">Criada em {{ date('d/m/Y', strtotime($date)) }} - {{ $status }}</div>
    <a href="{{ route('dashboard-posts-edit', $item_id) }}" class="posts-item-title">{{ $item_title }}</a>
    @if ($options == 'restaurar')
        <a href="{{ route('dashboard-posts-restore', $item->id) }}" class="bt-primary-one">Restaurar</a>
        <a href="{{ route('dashboard-posts-force-delete', $item->id) }}" class="bt-primary-danger"
            onclick="delPer(event)">Deletar permanentemente</a>
    @else
        <a href="{{ route('dashboard-posts-edit', $item_id) }}" class="bt-primary-one">Editar</a>
        <a href="{{ route('dashboard-posts-destroy', $item_id) }}" onclick="deletar(this, event)"
            class="bt-primary-danger">Deletar</a>
    @endif
</div>

<script>
    function deletar(e, ev) {
        ev.preventDefault()

        if (confirm('Deseja realmente mover para a lixeira o item?')) {
            window.location.href = e.href
        }
    }
</script>
