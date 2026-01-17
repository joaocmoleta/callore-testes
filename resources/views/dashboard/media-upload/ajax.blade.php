@extends('templates.dashboard.main')
@section('content')
    <div class="home-dashboard">
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Painel</a>
            <span>></span>
            <a href="{{ route('coupons.index') }}">Cupons</a>
        </div>

        @include('flash-message')

        <h3 class="title">Novo</h3>

        <button class="bt-primary-one" onclick="document.getElementsByClassName('modal-crop-upload')[0].style.display = 'flex'">Abrir modal</button>

        <div class="modal-crop-upload" style="display: flex">
            <div class="content-modal">
                <span class="modal-bt-close" onclick="this.parentElement.parentElement.style.display = 'none'">
                    <svg width="24" height="24" version="1.1" viewBox="0 0 24 24" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"><path d="m1.8662 1.8662 20.268 20.268" style="fill:none;stroke-linecap:round;stroke-linejoin:round;stroke-width:1.989;stroke:#000"/><path d="m22.134 1.8662-20.268 20.268" style="fill:none;stroke-linecap:round;stroke-linejoin:round;stroke-width:1.989;stroke:#000"/></svg>
                </span>

                <label>Nome do arquivo</label>
                <div class="input">
                    <input type="text" id="file_name" value="{{ old('file_name') }}"
                        placeholder="Aquecedor Callore Branco Compacto">
                </div>

                <label>Selecione tipo de corte</label>
                <div class="select-image-crop">
                    <button class="crop-type selected" onclick="selectCropType(this, 0)">
                        <img src="img/resize-image.jpg">
                    </button>
                    <button class="crop-type" onclick="selectCropType(this, 1)">
                        <img src="img/resize-image-white.jpg">
                    </button>
                </div>

                <label>Selecionar um arquivo ({{ env('FILES_ACCEPT_IMG') }})</label>
                <div class="input">
                    <input type="file" id="file" value="{{ old('file') }}" accept="{{ env('FILES_ACCEPT_IMG') }}"
                        onchange="chooseFile(this)" nameFile="file_name">
                </div>

                <div class="input">
                    <input type="hidden" id="arquivo_uploaded" name="arquivo_uploaded">
                </div>

                <div class="submit">
                    <span class="bt-primary-one" onclick="finishUpload()">Finalizar</span>
                </div>
            </div>
        </div>

        @csrf

        <script>
            let cropper_route = '{{ route('cropped.by-ajax') }}'
            let upload_route = '{{ route('upload.by-ajax') }}'

            // Set cut propreties to upload
            let crop_type = 0
            let crop_width = 790
            let crop_height = 1200

            let target_upload = document.getElementById('arquivo_uploaded')
            // let file_name = document.getElementById('file_name')
        </script>
        <script src="js/upload-media.min.js"></script>

    </div>
@endsection
