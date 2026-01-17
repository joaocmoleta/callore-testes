@extends('templates.dashboard.main')
@section('content')
    <div class="sub-header">
        <a href="{{ route('dashboard-posts') }}" class="bt-thirdary-one">Voltar</a>
    </div>
    <form action="{{ route('dashboard-posts-store') }}" method="post" class="post-form">
        <div class="post">
            <div class="post-area">
                <label>Título</label>
                <div class="input">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path
                            d="M17 18.597v.403h-10v-.417c-.004-1.112.044-1.747 1.324-2.043 1.402-.324 2.787-.613 2.121-1.841-1.972-3.637-.562-5.699 1.555-5.699 2.077 0 3.521 1.985 1.556 5.699-.647 1.22.688 1.51 2.121 1.841 1.284.297 1.328.936 1.323 2.057zm-1-14.597v2h3v16h-14v-16h3v-2h-5v20h18v-20h-5zm-6-4v6h4v-6h-4zm2 4c-.553 0-1-.448-1-1s.447-1 1-1 1 .448 1 1-.447 1-1 1z" />
                    </svg>
                    <input type="text" name="title" placeholder="Entrevista com o especialista Dr. Fulano da Silva"
                        value="{{ old('title') }}">
                    @error('title')
                        <div class="msg-error">{{ $message }}</div>
                    @enderror
                </div>
                <label>Resumo</label>
                <div class="input">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path
                            d="M17 18.597v.403h-10v-.417c-.004-1.112.044-1.747 1.324-2.043 1.402-.324 2.787-.613 2.121-1.841-1.972-3.637-.562-5.699 1.555-5.699 2.077 0 3.521 1.985 1.556 5.699-.647 1.22.688 1.51 2.121 1.841 1.284.297 1.328.936 1.323 2.057zm-1-14.597v2h3v16h-14v-16h3v-2h-5v20h18v-20h-5zm-6-4v6h4v-6h-4zm2 4c-.553 0-1-.448-1-1s.447-1 1-1 1 .448 1 1-.447 1-1 1z" />
                    </svg>
                    <textarea id="abstract" name="abstract"
                        placeholder="Nesta edição você poderá conferir uma entrevista exclusiva com o Dr. Fulano da Silva">{{ old('abstract') }}</textarea>
                    @error('abstract')
                        <div class="msg-error">{{ $message }}</div>
                    @enderror
                </div>
                <label>Corpo</label>
                <div class="input">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path
                            d="M17 18.597v.403h-10v-.417c-.004-1.112.044-1.747 1.324-2.043 1.402-.324 2.787-.613 2.121-1.841-1.972-3.637-.562-5.699 1.555-5.699 2.077 0 3.521 1.985 1.556 5.699-.647 1.22.688 1.51 2.121 1.841 1.284.297 1.328.936 1.323 2.057zm-1-14.597v2h3v16h-14v-16h3v-2h-5v20h18v-20h-5zm-6-4v6h4v-6h-4zm2 4c-.553 0-1-.448-1-1s.447-1 1-1 1 .448 1 1-.447 1-1 1z" />
                    </svg>
                    <textarea id="body" name="body"
                        placeholder="Matéria na integra com fotos, links, conteúdos, parágrafos, substítulos...">{{ old('body') }}</textarea>
                    <input type="hidden" id="uploadTiny" value="{{ route('dashboard-media-store') }}">
                    @error('body')
                        <div class="msg-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="sidebar">
                <div class="submit">
                    <button class="bt-primary-one">Salvar</button>
                    <div class="input">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path
                                d="M17 3v-2c0-.552.447-1 1-1s1 .448 1 1v2c0 .552-.447 1-1 1s-1-.448-1-1zm-12 1c.553 0 1-.448 1-1v-2c0-.552-.447-1-1-1-.553 0-1 .448-1 1v2c0 .552.447 1 1 1zm13 13v-3h-1v4h3v-1h-2zm-5 .5c0 2.481 2.019 4.5 4.5 4.5s4.5-2.019 4.5-4.5-2.019-4.5-4.5-4.5-4.5 2.019-4.5 4.5zm11 0c0 3.59-2.91 6.5-6.5 6.5s-6.5-2.91-6.5-6.5 2.91-6.5 6.5-6.5 6.5 2.91 6.5 6.5zm-14.237 3.5h-7.763v-13h19v1.763c.727.33 1.399.757 2 1.268v-9.031h-3v1c0 1.316-1.278 2.339-2.658 1.894-.831-.268-1.342-1.111-1.342-1.984v-.91h-9v1c0 1.316-1.278 2.339-2.658 1.894-.831-.268-1.342-1.111-1.342-1.984v-.91h-3v21h11.031c-.511-.601-.938-1.273-1.268-2z" />
                        </svg>
                        <input type="datetime-local" name="created_at" value="{{ old('created_at') }}">
                    </div>
                </div>
                <label>Status</label>
                <input type="hidden" name="status">
                <div class="input">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path
                            d="M17 18.597v.403h-10v-.417c-.004-1.112.044-1.747 1.324-2.043 1.402-.324 2.787-.613 2.121-1.841-1.972-3.637-.562-5.699 1.555-5.699 2.077 0 3.521 1.985 1.556 5.699-.647 1.22.688 1.51 2.121 1.841 1.284.297 1.328.936 1.323 2.057zm-1-14.597v2h3v16h-14v-16h3v-2h-5v20h18v-20h-5zm-6-4v6h4v-6h-4zm2 4c-.553 0-1-.448-1-1s.447-1 1-1 1 .448 1 1-.447 1-1 1z" />
                    </svg>
                    <div class="select" onclick="changeSelect(this, 'status')">
                        <span class="option selected">Rascunho</span>
                        <span class="option">Publish</span>
                    </div>
                    @error('status')
                        <div class="msg-error">{{ $message }}</div>
                    @enderror
                </div>
                <label>Autor</label>
                <div class="input">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path
                            d="M17 18.597v.403h-10v-.417c-.004-1.112.044-1.747 1.324-2.043 1.402-.324 2.787-.613 2.121-1.841-1.972-3.637-.562-5.699 1.555-5.699 2.077 0 3.521 1.985 1.556 5.699-.647 1.22.688 1.51 2.121 1.841 1.284.297 1.328.936 1.323 2.057zm-1-14.597v2h3v16h-14v-16h3v-2h-5v20h18v-20h-5zm-6-4v6h4v-6h-4zm2 4c-.553 0-1-.448-1-1s.447-1 1-1 1 .448 1 1-.447 1-1 1z" />
                    </svg>
                    <input type="text" name="author" id="author_name" readonly value="Redação">
                    @error('author')
                        <div class="msg-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="input">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path
                            d="M23.111 20.058l-4.977-4.977c.965-1.52 1.523-3.322 1.523-5.251 0-5.42-4.409-9.83-9.829-9.83-5.42 0-9.828 4.41-9.828 9.83s4.408 9.83 9.829 9.83c1.834 0 3.552-.505 5.022-1.383l5.021 5.021c2.144 2.141 5.384-1.096 3.239-3.24zm-20.064-10.228c0-3.739 3.043-6.782 6.782-6.782s6.782 3.042 6.782 6.782-3.043 6.782-6.782 6.782-6.782-3.043-6.782-6.782zm2.01-1.764c1.984-4.599 8.664-4.066 9.922.749-2.534-2.974-6.993-3.294-9.922-.749z" />
                    </svg>
                    <input type="text" placeholder="Digite o nome do autor" id="searchAuthor" onkeyup="sAuthor(this)"
                        onkeydown="addAuthor(event)" autocomplete="off">
                    <div class="author-to-add"></div>
                    <div class="author-list"></div>
                </div>

                <label>Categorias</label>
                <div class="input">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path
                            d="M23.111 20.058l-4.977-4.977c.965-1.52 1.523-3.322 1.523-5.251 0-5.42-4.409-9.83-9.829-9.83-5.42 0-9.828 4.41-9.828 9.83s4.408 9.83 9.829 9.83c1.834 0 3.552-.505 5.022-1.383l5.021 5.021c2.144 2.141 5.384-1.096 3.239-3.24zm-20.064-10.228c0-3.739 3.043-6.782 6.782-6.782s6.782 3.042 6.782 6.782-3.043 6.782-6.782 6.782-6.782-3.043-6.782-6.782zm2.01-1.764c1.984-4.599 8.664-4.066 9.922.749-2.534-2.974-6.993-3.294-9.922-.749z" />
                    </svg>
                    <input type="text" name="categories" id="searchCategory" onkeyup="sCategory(this)"
                        onkeydown="addFirstItemDDCat(event)" autocomplete="off">
                    <div class="categories-to-add"></div>
                    <div class="categories-list"></div>
                    @error('categories')
                        <div class="msg-error">{{ $message }}</div>
                    @enderror
                </div>
                <label>Tags</label>
                <div class="input">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path
                            d="M23.111 20.058l-4.977-4.977c.965-1.52 1.523-3.322 1.523-5.251 0-5.42-4.409-9.83-9.829-9.83-5.42 0-9.828 4.41-9.828 9.83s4.408 9.83 9.829 9.83c1.834 0 3.552-.505 5.022-1.383l5.021 5.021c2.144 2.141 5.384-1.096 3.239-3.24zm-20.064-10.228c0-3.739 3.043-6.782 6.782-6.782s6.782 3.042 6.782 6.782-3.043 6.782-6.782 6.782-6.782-3.043-6.782-6.782zm2.01-1.764c1.984-4.599 8.664-4.066 9.922.749-2.534-2.974-6.993-3.294-9.922-.749z" />
                    </svg>
                    <input type="text" name="tags" id="searchTag" onkeyup="sTag(this)"
                        onkeydown="addFirstItemDDTag(event)" autocomplete="off">
                    <div class="tags-to-add"></div>
                    <div class="tags-list"></div>
                    @error('tags')
                        <div class="msg-error">{{ $message }}</div>
                    @enderror
                </div>
                <label>Imagem de destaque</label>
                @error('thumbnail')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
                {{-- name="img_legend" --}}
                {{-- name="thumbnail" --}}
                <x-new-media name="image" cut="1" idp="thumbnail" preImg="/img/image.svg" />
            </div>

        </div>
    </form>
    <script>
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

    <script src="/js/categories.js"></script>
    <script src="/js/tags.js"></script>
    <script src="/js/img_upload.js"></script>
    <script src="/js/tinymce/tinymce.min.js"></script>
    <script src="/js/tiny-w-upload.js"></script>
    <script src="/js/author.js"></script>
@endsection
