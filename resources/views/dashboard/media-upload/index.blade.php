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

        <form action="{{ route('uploader.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <label>Nome do arquivo</label>
            <div class="input">
                <input type="text" name="file_name" value="{{ old('file_name') }}"
                    placeholder="Manual Aquecedor Callore Branco Compacto">
                @error('file_name')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label>Selecionar um arquivo ({{ env('FILES_ACCEPT') }})</label>
            <div class="input">
                <input type="file" name="file" value="{{ old('file') }}" accept="{{ env('FILES_ACCEPT') }}">
                @error('file')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="submit">
                <button class="bt-primary-one">Gravar arquivo</button>
            </div>
        </form>

        <form action="{{ route('uploader.upload-pdf') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <label>Nome do arquivo</label>
            <div class="input">
                <input type="text" name="file_name" value="{{ old('file_name') }}"
                    placeholder="Manual Aquecedor Callore Branco Compacto">
                @error('file_name')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label>Selecionar um arquivo (.pdf)</label>
            <div class="input">
                <input type="file" name="file" value="{{ old('file') }}" accept=".pdf">
                @error('file')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="submit">
                <button class="bt-primary-one">Gravar arquivo</button>
            </div>
        </form>

        <form action="{{ route('uploader.upload-image') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <label>Nome da imagem (SEO)</label>
            <div class="input">
                <input type="text" name="file_name" value="{{ old('file_name') }}"
                    placeholder="Aquecedor Callore Branco Compacto">
                @error('file_name')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label>Selecionar uma imagem ({{ env('FILES_ACCEPT_IMG') }})</label>
            <div class="input">
                <input type="file" name="file" value="{{ old('file') }}" accept="{{ env('FILES_ACCEPT_IMG') }}">
                @error('file')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="submit">
                <button class="bt-primary-one">Gravar imagem</button>
            </div>
        </form>

        {{-- <form action="{{ route('uploader.upload-image') }}" method="POST" enctype="multipart/form-data"> --}}
        <form action="{{ route('upload.by-ajax') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <label>Nome da imagem (SEO)</label>
            <div class="input">
                <input type="text" name="file_name" value="{{ old('file_name') }}"
                    placeholder="Aquecedor Callore Branco Compacto">
                @error('file_name')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label>Selecionar uma imagem ({{ env('FILES_ACCEPT_IMG') }})</label>
            <div class="input">
                <input type="file" name="file" value="{{ old('file') }}" accept="{{ env('FILES_ACCEPT_IMG') }}">
                @error('file')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="submit">
                <button class="bt-primary-one">Gravar imagem</button>
            </div>
        </form>

        <form action="{{ route('cropped.by-ajax') }}" method="POST">
            @csrf

            <label>Path</label>
            <div class="input">
                <input type="text" name="path" 
                    value="/uploads/">
                @error('path')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label>Nome do arquivo</label>
            <div class="input">
                <input type="text" name="file_name" 
                    value="aquecedor-callore-compacto">
                @error('file_name')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label>Extensão do arquivo</label>
            <div class="input">
                <input type="text" name="ext" 
                    value="webp">
                @error('ext')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label>Largura</label>
            <div class="input">
                <input type="text" name="width" 
                    value="646.7808160000001">
                @error('width')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label>Altura</label>
            <div class="input">
                <input type="text" name="height" 
                    value="861.944952">
                @error('height')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label>Posição X</label>
            <div class="input">
                <input type="text" name="pos_x" 
                    value="-131.90552553333345">
                @error('pos_x')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label>Posição Y</label>
            <div class="input">
                <input type="text" name="pos_y" 
                    value="10.225862912499998">
                @error('pos_y')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label>Escala X</label>
            <div class="input">
                <input type="text" name="sca_x" 
                    value="2">
                @error('sca_x')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label>Escala Y</label>
            <div class="input">
                <input type="text" name="sca_y" 
                    value="1">
                @error('sca_y')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="submit">
                <button class="bt-primary-one">Cortar imagem</button>
            </div>
        </form>
    </div>
@endsection
