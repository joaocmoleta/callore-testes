@extends('templates.dashboard.main')
@section('content')
    <div class="home-dashboard">
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Painel</a>
            <span>></span>
            <a href="{{ route('products.index') }}">Produtos</a>
        </div>

        @include('flash-message')

        <h3 class="title">Edição <span class="slug"><a href="{{ route('products.single', $product->slug) }}" target="_blank">slug: {{ $product->slug }}</a></span></h3>

        <p><a href="{{ route('products.edit', $product->id + 1)}}">Próximo produto</a></p>

        <form action="{{ route('products.update', $product) }}" method="POST" class="form-default">
            @csrf
            @method('PUT')

            <div class="field-size">
                @include('field', [
                    'field_label' => 'SKU',
                    'field_type' => 'text',
                    'field_name' => 'sku',
                    'field_value' => old('sku') ?? ($product->sku ?? ''),
                    'field_placeholder' => '05.000007',
                ])
            </div>

            <div class="field-size-full">
                <label>Título</label>
                <div class="input">
                    <input type="text" name="title" placeholder="Aquecedor Callore VERSÁTIL"
                        value="{{ old('title') ? old('title') : $product->title }}">
                    @error('title')
                        <div class="msg-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="field-size">
                @include('field', [
                    'field_label' => 'Grupo',
                    'field_type' => 'text',
                    'field_name' => 'group',
                    'field_value' => old('group') ?? ($product->group ?? ''),
                    'field_placeholder' => '1',
                ])
            </div>

            <div class="field-size">
                @include('field', [
                    'field_label' => 'Modelo',
                    'field_type' => 'text',
                    'field_name' => 'model',
                    'field_value' => old('model') ?? ($product->model ?? ''),
                    'field_placeholder' => '1',
                ])
            </div>

            <div class="field-size">
                <label>Valor</label>
                <div class="input">
                    <input type="text" name="value" placeholder="1.233,50"
                        value="{{ old('value') ? old('value') : $product->value }}">
                    @error('value')
                        <div class="msg-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="field-size">
                @include('field', [
                    'field_label' => 'Valor promocional',
                    'field_type' => 'text',
                    'field_name' => 'discount',
                    'field_value' => old('discount') ?? ($product->discount ?? ''),
                    'field_placeholder' => '1.100,00',
                ])
            </div>

            <div class="field-size">
                <label>Qtd. Estoque</label>
                <div class="input">
                    <input type="number" name="qtd" placeholder="100"
                        value="{{ old('qtd') ? old('qtd') : $product->qtd }}">
                    @error('qtd')
                        <div class="msg-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <label>Resumo</label>
            <div class="input">
                <textarea name="abstract" id="abstract"
                    placeholder="O aquecedor CALLORE VERSÁTIL é excelente para quem mora sozinho e em lugares pequenos.">{{ old('abstract') ? old('abstract') : $product->abstract }}</textarea>
                @error('abstract')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <h3 style="width: 100%">Especificações técnicas:</h3>
            @include('dashboard.products.technical-speccification')

            <label>Descrição</label>
            <div class="input">
                <textarea name="description" id="description"
                    placeholder="O aquecedor CALLORE VERSÁTIL é excelente para quem mora sozinho e em lugares pequenos. Ele economiza espaço e aquece suas toalhas de maneira que você precisa, economizando tempo e números de lavagens.">{{ old('description') ? old('description') : $product->description }}</textarea>
                @error('description')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label>Imagens</label>
            <textarea hidden name="images" placeholder="...">{{ old('images') ? old('images') : $product->images }}</textarea>
            @error('images')
                <div class="msg-error">{{ $message }}</div>
            @enderror

            <div class="box-add-image">
                <div class="box-add-image-content">
                    @if ($items = json_decode(old('images') ? old('images') : $product->images))
                        @for ($i = 0; $i < count($items); $i++)
                            <div class="add-image-item">
                                <span class="add-image-bt-remove" onclick="removeImage(this, {{ $i }})">
                                    <svg width="24" height="24" version="1.1" viewBox="0 0 24 24"
                                        xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
                                        <path d="m1.8662 1.8662 20.268 20.268"
                                            style="fill:none;stroke-linecap:round;stroke-linejoin:round;stroke-width:1.989;stroke:#000" />
                                        <path d="m22.134 1.8662-20.268 20.268"
                                            style="fill:none;stroke-linecap:round;stroke-linejoin:round;stroke-width:1.989;stroke:#000" />
                                    </svg>
                                </span>
                                <img src="{{ $items[$i] }}">
                            </div>
                        @endfor
                    @endif
                </div>
                <span class="bt-primary-one"
                    onclick="document.getElementsByClassName('modal-crop-upload')[0].style.display = 'flex'">Adicionar nova
                    imagem</span>
            </div>

            <div class="modal-crop-upload" style="display: none">
                <div class="content-modal">
                    <span class="modal-bt-close" onclick="this.parentElement.parentElement.style.display = 'none'">
                        <svg width="24" height="24" version="1.1" viewBox="0 0 24 24" xml:space="preserve"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="m1.8662 1.8662 20.268 20.268"
                                style="fill:none;stroke-linecap:round;stroke-linejoin:round;stroke-width:1.989;stroke:#000" />
                            <path d="m22.134 1.8662-20.268 20.268"
                                style="fill:none;stroke-linecap:round;stroke-linejoin:round;stroke-width:1.989;stroke:#000" />
                        </svg>
                    </span>

                    <label>Nome do arquivo</label>
                    <div class="input">
                        <input type="text" id="file_name" value="{{ old('file_name') }}"
                            placeholder="Aquecedor Callore Branco Compacto">
                    </div>

                    <label>Selecione tipo de corte</label>
                    <div class="select-image-crop">
                        <span class="crop-type selected" onclick="selectCropType(this, 0)">
                            <img src="/img/resize-image.jpg">
                        </span>
                        <span class="crop-type" onclick="selectCropType(this, 1)">
                            <img src="/img/resize-image-white.jpg">
                        </span>
                    </div>

                    <label>Selecionar um arquivo ({{ env('FILES_ACCEPT_IMG') }})</label>
                    <div class="input">
                        <input type="file" id="file" value="{{ old('file') }}"
                            accept="{{ env('FILES_ACCEPT_IMG') }}" onchange="chooseFile(this)" nameFile="file_name">
                    </div>

                    <div class="input">
                        <input type="text" id="arquivo_uploaded" name="arquivo_uploaded">
                    </div>

                    <div class="submit">
                        <span class="bt-primary-one" onclick="finishUpload()">Finalizar</span>
                    </div>
                </div>
            </div>

            <div class="submit">
                <button class="bt-primary-one">Atualizar</button>
            </div>
        </form>
    </div>

    <script>
        let cropper_route = '{{ route('cropped.by-ajax') }}'
        let upload_route = '{{ route('upload.by-ajax') }}'

        // Set cut propreties to upload
        let crop_type = 0
        let crop_width = 790
        let crop_height = 1200

        let target_upload = document.getElementById('arquivo_uploaded')
        // let file_name = document.getElementById('file_name')

        let afterFinishUpload = function() {
            let tg = document.getElementsByName('images')[0]

            let lis = []
            if (tg.value != '') {
                lis = JSON.parse(tg.value)
            }

            lis.push(target_upload.value)

            // Criar objeto com imagem
            createImageObj(parseInt(lis.length - 1))

            // Adicionar imagem na textarea
            document.getElementsByName('images')[0].value = JSON.stringify(lis)
        }
    </script>
    <script src="/js/upload-media.min.js"></script>

    <script src="/js/tinymce/tinymce.min.js"></script>
    <script src="/js/tinyWUpload.min.js"></script>
@endsection
