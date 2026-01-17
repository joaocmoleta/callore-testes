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

        <form action="{{ route('coupons.store') }}" method="POST">
            @csrf

            <label>Código</label>
            <div class="input">
                <input type="text" name="code" placeholder="BLACKFRIDAY2023" value="{{ old('code') }}">
                @error('code')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label>Descrição</label>
            <div class="input">
                <input type="text" name="name" placeholder="Cupom para campanha de black friday 2023"
                    value="{{ old('name') }}">
                @error('name')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label>Desconto</label>
            <div class="input">
                <input type="number" name="discount" value="{{ old('discount') ?? 0 }}" placeholder="10">
                @error('discount')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label>Porcentagem ou valor</label>
            <div class="radio">
                <div class="option" onclick="radioSelect(this, 'discount_type', 1)">
                    <svg clip-rule="evenodd" width="15" fill-rule="evenodd" stroke="black" fill="{{ old('discount_type') == 1 ? '#333' : 'none' }}"
                        stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="11.998" cy="11.998" fill-rule="nonzero" r="9.998" />
                    </svg> <span>Porcentagem por carrinho (ex: 10%)</span>
                </div>
                <div class="option" onclick="radioSelect(this, 'discount_type', 2)">
                    <svg clip-rule="evenodd" width="15" fill-rule="evenodd" stroke="black" fill="{{ old('discount_type') == 2 ? '#333' : 'none' }}"
                        stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="11.998" cy="11.998" fill-rule="nonzero" r="9.998" />
                    </svg> <span>Decimal por carrinho (R$ 100,00)</span>
                </div>
                <div class="option" onclick="radioSelect(this, 'discount_type', 3)">
                    <svg clip-rule="evenodd" width="15" fill-rule="evenodd" stroke="black" fill="{{ old('discount_type') == 3 ? '#333' : 'none' }}"
                        stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="11.998" cy="11.998" fill-rule="nonzero" r="9.998" />
                    </svg> <span>Porcentagem por produto (ex: 10%)</span>
                </div>
                <div class="option" onclick="radioSelect(this, 'discount_type', 4)">
                    <svg clip-rule="evenodd" width="15" fill-rule="evenodd" stroke="black" fill="{{ old('discount_type') == 4 ? '#333' : 'none' }}"
                        stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="11.998" cy="11.998" fill-rule="nonzero" r="9.998" />
                    </svg> <span>Decimal por produto (R$ 100,00)</span>
                </div>
                <div class="input" id="product-list" style="display: {{ old('discount_type') == 3 || old('discount_type') == 4 ? 'initial' : 'none' }};">
                    <input type="hidden" name="product">
                    <select onchange="selectProduct(this.value)">
                        <option value="">Selecione um produto</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->title }}</option>
                        @endforeach
                    </select>
                    @error('product')
                        <div class="msg-error">{{ $message }}</div>
                    @enderror
                </div>
                @error('discount_type')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>
            <input type="hidden" name="discount_type" value="{{ old('discount_type') ?? '' }}">

            <label>Quantidade de cupons, -1 para ilimitada</label>
            <div class="input">
                <input type="number" name="valid" value="{{ old('valid') ?? -1 }}">
                @error('valid')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label>Validade</label>
            <div class="box-bt-on">
                <div class="bt-on">
                    <div class="switch" onclick="able(this)"></div>
                </div>
            </div>
            <div class="input">
                <input type="datetime-local" id="limit"
                    value="{{ old('limit') ? old('limit') : date('Y-m-d\TH:i', strtotime(date('Y-m-d\TH'))) }}" readonly style="color: white">
                    @error('limit')
                        <div class="msg-error">{{ $message }}</div>
                    @enderror
            </div>
            <script>
                function radioSelect(e, tg, opt) {
                    let options = document.getElementsByClassName(e.classList[0])
                    for (i = 0; i < options.length; i++) {
                        options[i].firstElementChild.style.fill = 'none'
                    }
                    e.firstElementChild.style.fill = '#313131'
                    document.getElementsByName(tg)[0].value = opt

                    if(opt == 3 || opt == 4) {
                        document.getElementById('product-list').style.display = 'initial'
                    } else {
                        document.getElementById('product-list').style.display = 'none'
                    }
                }

                function selectProduct(e) {
                    document.getElementsByName('product')[0].value = e
                }

                function able(e) {
                    if(e.style.width == '100%') {
                        document.getElementById('limit').removeAttribute('name')
                        document.getElementById('limit').setAttribute('readonly', 'readonly')
                        document.getElementById('limit').style.color = 'white'
                        e.style.width = '60%'
                    } else {
                        document.getElementById('limit').setAttribute('name', 'limit')
                        document.getElementById('limit').removeAttribute('readonly')
                        document.getElementById('limit').style.color = 'initial'
                        e.style.width = '100%'
                    }
                }
            </script>

            <label>Restrito a um tipo de pagamento</label>
            <div class="input">
                <select name="pay_restrict">
                    <option value="">Sem restrições</option>
                    <option value="PIX">Pix</option>
                    <option value="Credit">Cartão de crédito
                    </option>
                </select>
                @error('pay_restrict')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="submit">
                <button class="bt-primary-one">Adicionar</button>
            </div>
        </form>
    </div>
@endsection
