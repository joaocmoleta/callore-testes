@extends('templates.dashboard.main')
@section('content')
    <a href="{{ route('dashboard-tags') }}">
        <h2 class="title">Tags</h2>
    </a>
    <div class="sub-header">
        <a href="{{ route('dashboard-tags') }}" class="bt-thirdary-one">Voltar</a>
    </div>
    <div>
        <form action="{{ route('dashboard-media-store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="width" value="1200">
            <input type="hidden" name="height" value="700">
            <label for="name_img">Name</label>
            <div class="input">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path
                        d="M9.777 2l11.394 11.395-7.78 7.777-11.391-11.391v-7.781h7.777zm.828-2h-10.605v10.609l13.391 13.391 10.609-10.604-13.395-13.396zm-4.104 5c.4 0 .776.156 1.059.438.585.586.585 1.539.001 2.123-.285.283-.661.439-1.061.439s-.777-.156-1.06-.438c-.585-.586-.586-1.538-.001-2.123.284-.283.661-.439 1.062-.439zm0-1c-.641 0-1.28.244-1.769.732-.977.976-.977 2.558 0 3.536.489.488 1.128.732 1.768.732s1.279-.244 1.768-.733c.977-.977.977-2.558 0-3.537-.488-.486-1.127-.73-1.767-.73z" />
                </svg>
                <input type="text" name="name_img" placeholder="Legenda, descrição, bom para SEO">
            </div>
            <label for="url">URL</label>
            <div class="input">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M16.949 7.051c.39.389.391 1.022.001 1.413l-8.485 8.486c-.392.391-1.023.391-1.414 0-.39-.39-.39-1.021.001-1.412l8.485-8.488c.39-.39 1.024-.387 1.412.001zm-5.805 10.043c-.164.754-.541 1.486-1.146 2.088l-1.66 1.66c-1.555 1.559-3.986 1.663-5.413.235-1.429-1.428-1.323-3.857.234-5.413l1.661-1.663c.603-.601 1.334-.98 2.087-1.144l1.934-1.934c-1.817-.306-3.829.295-5.313 1.783l-1.662 1.661c-2.342 2.34-2.5 5.978-.354 8.123 2.145 2.146 5.783 1.985 8.123-.354l1.66-1.66c1.486-1.487 2.089-3.496 1.783-5.314l-1.934 1.932zm3.222-15.231l-1.66 1.66c-1.486 1.488-2.089 3.499-1.783 5.317l1.935-1.935c.162-.753.54-1.485 1.146-2.087l1.66-1.66c1.556-1.559 3.984-1.663 5.413-.234 1.429 1.427 1.324 3.857-.233 5.415l-1.66 1.66c-.602.603-1.334.981-2.089 1.145l-1.934 1.934c1.818.306 3.827-.295 5.317-1.783l1.658-1.662c2.34-2.339 2.498-5.976.354-8.121-2.145-2.146-5.78-1.987-8.124.351z"/></svg>
                <input type="text" name="url" id="aaa" placeholder="Só utilizar para colar URL externas">
            </div>
            <label for="url">Tipo de corte</label>
            <div class="cut-type">
                <div class="option selected">
                    <img src="/img/resize-image.jpg" onclick="selectType(this, 0)">
                </div>
                <div class="option">
                    <img src="/img/resize-image-white.jpg" onclick="selectType(this, 1)">
                </div>
                <input type="hidden" name="cut_type" value="0">
            </div>
            <label for="file">Imagem</label>
            <div class="pre-view">
                <input type="file" name="file" style="display: none">
                <img src="/img/image.svg" id="zyz" onclick="selectFile(this)">
            </div>
            <div class="submit">
                <a href="javascript:void(0)" class="bt-primary-one" onclick="addMedia(this, 'aaa', 'zyz')">Adicionar</a>
            </div>
        </form>

    </div>
    <script src="/js/img_upload.js"></script>
@endsection
