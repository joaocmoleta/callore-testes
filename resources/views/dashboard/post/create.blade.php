@extends('templates.dashboard.main')
@section('content')
    <div class="edit-product">
        <div class="breadcrumb">
            <a href="{{ route('dashboard-posts') }}">Publicações</a>
            <span>></span>
            <span>Adicionando nova publicação</span>
        </div>

        <h2>Adicionando nova publicação</h2>

            @include('flash-message')

        <form action="{{ route('dashboard-posts-store') }}" method="post" style="display: none;"
            onsubmit="pre_process(event, this)">
            @csrf
            <input type="text" name="title" value="{{ old('title') }}">
            <textarea name="abstract">{{ old('abstract') }}</textarea>
            <textarea name="body">{{ old('body') }}</textarea>
            <input type="text" name="created_at" value="{{ old('created_at') ? old('created_at') : '' }}">
            <input type="text" name="status" value="{{ old('status') ? old('status') : 'Rascunho' }}">
            <input type="text" name="author" value="{{ old('author') ? old('author') : 1 }}">
            <textarea style="" name="categories">{{ old('categories') }}</textarea>
            <textarea style="" name="tags">{{ old('tags') }}</textarea>
            <input type="text" name="img_legend">
            <input type="text" name="thumbnail" id="thumbnail"
                value="{{ old('thumbnail') ? old('thumbnail') : '/img/image.svg' }}">
            <input type="submit" id="submit_form">
        </form>
        <div class="content-edit-product">
            <div class="post-area">
                <label>Título</label>
                <div class="input">
                    <input type="text" placeholder="Entrevista com o especialista Dr. Fulano da Silva"
                        value="{{ old('title') }}" onchange="mirror(this, 'title')">
                    @error('title')
                        <div class="msg-error">{{ $message }}</div>
                    @enderror
                </div>
                <label>Resumo</label>
                <div class="input">
                    <textarea id="abstract"
                        placeholder="Nesta edição você poderá conferir uma entrevista exclusiva com o Dr. Fulano da Silva">{{ old('abstract') }}</textarea>
                    @error('abstract')
                        <div class="msg-error">{{ $message }}</div>
                    @enderror
                </div>
                <label>Corpo</label>
                <div class="input">
                    <textarea id="body" placeholder="Matéria na integra com fotos, links, conteúdos, parágrafos, substítulos...">{{ old('body') }}</textarea>
                    <input type="hidden" id="uploadTiny" value="{{ route('dashboard-media-store') }}">
                    @error('body')
                        <div class="msg-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="sidebar">
                <div class="submit-sidebar">
                    <button class="bt-primary-one" onclick="savePost('submit_form')">Salvar</button>
                </div>

                <label>Data da publicação</label>
                <div class="input">
                    <input type="datetime-local"
                        value="{{ old('created_at') ? old('created_at') : date('Y-m-d\TH:i') }}"
                        onchange="mirror(this, 'created_at')">
                </div>

                <label>Status</label>
                <div class="input">
                    <div class="select" onclick="changeSelect(this, 'status')">
                        <span class="option selected">Rascunho</span>
                        <span class="option">Publish</span>
                    </div>
                    @error('status')
                        <div class="msg-error">{{ $message }}</div>
                    @enderror
                </div>

                <label>Categorias</label>
                <div class="categories-on-post">
                    @foreach ($cats as $cat)
                        <div class="item" onclick="selectOptions(this)">{{ $cat->title }}</div>
                    @endforeach
                </div>

                <label>Tags</label>
                <p>Digite o nome da tag e pressione enter</p>
                <div class="tags-on-post">
                    <input type="text" onkeypress="addTags(event, this)" placeholder="ex: {{ env('APP_NAME')}}">
                </div>

                <label>Imagem de destaque</label>
                <input type="hidden" name="thumbnail">
                <img id="thumbnail_post" src="{{ asset('img/image.svg') }}"
                    onclick="document.getElementsByClassName('modal-crop-upload')[0].style.display = 'flex'">
                @error('thumbnail')
                    <div class="msg-error">{{ $message }}</div>
                @enderror

                <div class="modal-crop-upload" style="display: none">
                    <div class="content-modal">
                        <span class="modal-bt-close" onclick="this.parentElement.parentElement.style.display = 'none'">
                            <img src="{{ asset('icons/close.svg') }}" alt="Fechar janela">
                        </span>

                        <p class="modal-upload-info"><img src="{{ asset('icons/info.svg') }}" alt="informativo"
                                width="20">
                            Tamanho final da imagem 1080 x 400px</p>
                        <br>

                        <label>Nome do arquivo</label>
                        <div class="input">
                            <input type="text" id="file_name" placeholder="Aquecedor Callore Branco Compacto">
                        </div>

                        <label>Selecione tipo de corte</label>
                        <div class="select-image-crop">
                            <span class="crop-type selected" onclick="selectCropType(this, 0)">
                                <img src="{{ asset('img/resize-image.jpg') }}">
                            </span>
                            <span class="crop-type" onclick="selectCropType(this, 1)">
                                <img src="{{ asset('img/resize-image-white.jpg') }}">
                            </span>
                        </div>

                        <label>Selecionar um arquivo ({{ env('FILES_ACCEPT_IMG') }})</label>
                        <div class="input">
                            <input type="file" id="file" value="{{ old('file') }}"
                                accept="{{ env('FILES_ACCEPT_IMG') }}" onchange="chooseFile(this)" nameFile="file_name">
                        </div>

                        <div class="input">
                            <input type="text" id="arquivo_uploaded" name="arquivo_uploaded"
                                value="{{ 'img/image.svg' }}">
                        </div>

                        <div class="submit">
                            <span class="bt-primary-one" onclick="finishUpload()">Finalizar</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let cropper_route = '{{ route('cropped.by-ajax') }}'
        let upload_route = '{{ route('upload.by-ajax') }}'
        let assets = '{{ asset('') }}'

        // Set cut propreties to upload
        let crop_type = 0
        let crop_width = 1080
        let crop_height = 400

        let target_upload = document.getElementById('arquivo_uploaded')
        // let file_name = document.getElementById('file_name')

        let afterFinishUpload = function() {
            let updated_value = document.getElementById('arquivo_uploaded').value

            document.getElementsByName('thumbnail')[0].value = updated_value
            document.getElementById('thumbnail_post').src = assets + updated_value
        }

        function deleteTag(e) {
            let tg = document.getElementsByName('tags')[0];
            let neww = JSON.parse(tg.value)

            const index = neww.findIndex(element => {
                if (element.includes(e.innerText)) {
                    return true;
                }
            });

            if (index != -1) {
                neww.splice(index, 1);
            }

            tg.value = JSON.stringify(neww)

            e.remove();
        }

        function addTags(ev, e) {
            if (ev.keyCode != 13) {
                return
            }

            let tg = document.getElementsByName('tags')[0];
            let neww = [];

            if (tg.value != '') {
                neww = JSON.parse(tg.value)
            }

            const index = neww.findIndex(element => {
                if (element.includes(e.value)) {
                    return true;
                }
            });

            if (index == -1) {
                neww.push(e.value);
            } else {
                neww.splice(index, 1);
            }

            tg.value = JSON.stringify(neww)

            let span = document.createElement("span");
            span.classList.add('item')
            span.setAttribute('onclick', 'deleteTag(this)')
            span.innerText = e.value

            e.parentElement.append(span);

            e.value = ''
        }

        function selectOptions(e) {
            let tg = document.getElementsByName('categories')[0];
            let neww = [];

            if (tg.value != '') {
                neww = JSON.parse(tg.value)
            }

            const index = neww.findIndex(element => {
                if (element.includes(e.innerText)) {
                    return true;
                }
            });

            if (index == -1) {
                neww.push(e.innerText);
            } else {
                neww.splice(index, 1);
            }

            tg.value = JSON.stringify(neww)

            if (e.style.background == 'black') {
                e.style.background = 'none'
                e.style.color = 'black';
            } else {
                e.style.background = 'black';
                e.style.color = 'white';
            }
        }

        let mc = [{
            'target': 'tg_thumbnail',
            'file': 'file_thumbnail',
            'ac': 'action_thumbnail',
            'img_preview': 'preview_img_thumbnail',
            'img_leg': 'leg_thumbnail',
            'cut_type': 'cut_type_thumbnail',
            'width': 1200,
            'height': 700,
        }];

        function pre_process(event, e) {
            let img_legend = document.getElementsByName('img_legend')[0]
            let leg = document.getElementById('leg_thumbnail');
            img_legend.value = leg.value;

            let img_thumbnail = document.getElementsByName('thumbnail')[0]
            let th = document.getElementById('tg_thumbnail');
            img_thumbnail.value = th.value;
        }


        let backend = '{{ env('APP_URL') }}';
        let csrf_p = document.getElementsByName('_token')[0].value;
        let status = document.getElementsByName('status')[0];
        let select = document.getElementsByClassName('select')[0];

        function changeSelect(e, targ) {
            let target = document.getElementsByName(targ)[0];

            if (e.children[0].classList[1] == 'selected') {
                e.children[0].classList.remove('selected');
                e.children[1].classList.add('selected');
                target.value = e.children[1].innerText;
            } else {
                e.children[1].classList.remove('selected');
                e.children[0].classList.add('selected');
                target.value = e.children[0].innerText;
            }
        }

        function mirror(e, tg) {
            let targ = document.getElementsByName(tg)[0];
            targ.value = e.value;
        }

        function savePost(tg) {
            let targ = document.getElementById(tg);
            targ.click();
        }

        /*
         * Fill field categories on json stringfied
         * Return true in case success
         * Return false case already added
         */
        function upTextarea(valId, val, target) {
            let a = [];
            if (target.value.length) {
                a = JSON.parse(target.value);
                if (a.find(s => s.value == valId)) {
                    return 0;
                }
            }
            a.push({
                title: val,
                value: valId
            });
            target.value = JSON.stringify(a);
            return 1;
        }
        /*
         * Remove category of final field
         */
        function dwTextarea(val, target) {
            if (target.value.length != '') {
                let a = JSON.parse(target.value);
                let index = a.indexOf(a.find(s => s.value == val));
                a.splice(index, 1);
                target.value = JSON.stringify(a);
            }
        }
        /*
         * Create item in drop down list
         * @param type= type of element ex: div, span
         * @param className = name of class attribute on element
         * @param value = value to add on bt action
         * Return item how element with class and action
         */
        function createAddBt(type, className, val, fName, newValue) {
            let it = document.createElement(type);
            it.classList.add(className);
            it.setAttribute('onclick', fName + '("' + newValue + '")')
            it.appendChild(document.createTextNode(val))
            return it
        }
        /*
         * Create element
         * @param type = type of element ex: div, span
         * @param className = name of element class
         * @param valId = id of category
         * @param value = title of category
         * Return element
         */
        function createItemToAdd(type, className, valId, value, fName) {
            let it = document.createElement(type);
            it.classList.add(className);
            it.setAttribute('onclick', fName + '(' + valId + ', "' + value + '")')
            it.appendChild(document.createTextNode(value))
            return it
        }
        /*
         * Create element
         * @param type = type of elemnt ex: div, span
         * @param className = name of element class
         * @param value = value of element
         * Return element
         */
        function createItem(type, className, value) {
            let it = document.createElement(type);
            it.classList.add(className);
            it.appendChild(document.createTextNode(value))
            return it
        }
        /*
         * Create element
         * @param type = type of element ex: div, span
         * @param className = name of elemnt class
         * @param valId = id of category
         * @param value = value of category
         * Return element
         */
        function createItemAdded(type, className, valId, value, fName) {
            let it = document.createElement(type);
            it.classList.add(className);
            let text = document.createElement('span');
            text.appendChild(document.createTextNode(value));
            let delBt = document.createElement('span');
            delBt.classList.add('bt-primary-danger-small');
            delBt.appendChild(document.createTextNode('Deletar'));
            delBt.setAttribute('onclick', fName + '(this, ' + valId + ')')
            it.appendChild(text)
            it.appendChild(delBt)
            return it
        }
        /*
         * Add message error
         * @param box = element that receive message
         * @param msg = string of error message
         */
        function addMsgError(box, msg) {
            it = document.createElement('div');
            it.classList.add('msg-error');
            it.appendChild(document.createTextNode(msg));
            box.appendChild(it);
        }
    </script>

    <script src="/js/tinymce/tinymce.min.js"></script>

    @if (env('APP_ENV') == 'local')
        <script src="{{ asset('js/upload-media.js') }}"></script>
        <script src="{{ asset('js/categories.js') }}"></script>
        <script src="{{ asset('js/tags.js') }}"></script>
        <script src="{{ asset('js/img_upload.js') }}"></script>
        <script src="{{ asset('js/tiny-w-upload.js') }}"></script>
    @else
        <script src="{{ asset('js/upload-media.min.js') }}"></script>
        <script src="{{ asset('js/categories.min.js') }}"></script>
        <script src="{{ asset('js/tags.min.js') }}"></script>
        <script src="{{ asset('js/img_upload.min.js') }}"></script>
        <script src="{{ asset('js/tiny-w-upload.min.js') }}"></script>
    @endif
@endsection
