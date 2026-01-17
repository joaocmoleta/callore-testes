@if ($message = Session::get('success'))
    <div class="msg-success">
        <strong>{{ $message }}</strong>
    </div>
@endif


@if ($message = Session::get('error'))
    <div class="msg-error">
        <strong>{{ $message }}</strong>
    </div>
@endif


@if ($message = Session::get('warning'))
    <div class="msg-warning">
        <strong>{{ $message }}</strong>
    </div>
@endif


@if ($message = Session::get('info'))
    <div class="msg-info">
        <strong>{{ $message }}</strong>
    </div>
@endif

@if ($errors->any())
    <div class="msg-error">
        <p>Houve algum erro no preechimento, verifique o formulário. {{ $message }}</p>
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

@if (session('status'))
    <div class="msg-info">
        {{ session('status') }}
    </div>
@endif
