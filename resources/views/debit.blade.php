{{-- // Cartão de débito --}}
<div class="option-box">
    <h4 onclick="selectPay(this)"><svg clip-rule="evenodd" width="15" fill-rule="evenodd" stroke="black" fill="{{ old('type') == 'DEBIT_CARD' ? 'rgb(51, 51, 51)' : 'none' }}"
            stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <circle cx="11.998" cy="11.998" fill-rule="nonzero" r="9.998" />
        </svg> Cartão de débito</h4>
    <div class="option" style="display: {{ old('type') == 'DEBIT_CARD' ? 'flex;' : 'none;' }}">
        <form action="{{ route('pay.credit_card') }}" method="POST">
            @csrf

            <input type="hidden" name="reference_id" value="{{ $db_order->id }}">
            <input type="hidden" name="description" value="Pagamento do pedido {{ $db_order->id }}">
            <input type="hidden" name="amount" value="{{ $cart['amount'] }}">
            <input type="hidden" name="type" value="DEBIT_CARD">

            {{-- parcelas --}}
            <input type="hidden" name="installments" value="1">

            <label>Nome impresso no cartão</label>
            <div class="input">
                <input type="text" name="card_holder_name" placeholder="José Silva"
                    value="{{ old('card_holder_name') }}" onkeyup="insertHolder(this, 'debit')"
                    onfocus="frontCard(this, 'debit')">
                @error('card_holder_name')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label>N⁰ Cartão de débito</label>
            <div class="input">
                <input type="text" name="card_number" placeholder="4111111111111111" value="{{ old('card_number') }}"
                    onkeyup="insertCardNumber(this, 'debit')">
                @error('card_number')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label>Mês vencimento</label>
            <div class="input">
                <input type="text" name="card_exp_month" placeholder="12" value="{{ old('card_exp_month') }}"
                    onkeyup="insertExpMonth(this, 'debit')">
                @error('card_exp_month')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label>Ano vencimento</label>
            <div class="input">
                <input type="text" name="card_exp_year" placeholder="30" value="{{ old('card_exp_year') }}"
                    onkeyup="insertExpYear(this, 'debit')">
                @error('card_exp_year')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label>Código de segurança</label>
            <div class="input">
                <input type="text" name="card_security_code" placeholder="123"
                    value="{{ old('card_security_code') }}" onkeyup="insertSecurity(this, 'debit')"
                    onfocus="backCard(this, 'debit')">
                @error('card_security_code')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <button class="bt-primary-one"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24">
                    <path
                        d="M10.975 8l.025-.5c0-.517-.066-1.018-.181-1.5h5.993l-.564 2h-5.273zm-2.475 10c-.828 0-1.5.672-1.5 1.5 0 .829.672 1.5 1.5 1.5s1.5-.671 1.5-1.5c0-.828-.672-1.5-1.5-1.5zm11.305-15l-3.432 12h-10.428l-.455-1.083c-.324.049-.652.083-.99.083-.407 0-.805-.042-1.191-.114l1.306 3.114h13.239l3.474-12h1.929l.743-2h-4.195zm-6.305 15c-.828 0-1.5.671-1.5 1.5s.672 1.5 1.5 1.5 1.5-.671 1.5-1.5c0-.828-.672-1.5-1.5-1.5zm-9-15c-2.486 0-4.5 2.015-4.5 4.5s2.014 4.5 4.5 4.5c2.484 0 4.5-2.015 4.5-4.5s-2.016-4.5-4.5-4.5zm-.469 6.484l-1.687-1.636.695-.697.992.94 2.115-2.169.697.696-2.812 2.866z" />
                </svg> Efetuar o pagamento</button>
        </form>
        <div class="card-view">
            <div class="debit-frente">
                @include('card', ['prefix' => 'debit', 'type' => 'DEBIT_CARD'])
            </div>
            <div class="debit-verso">
                @include('card-back', ['prefix' => 'debit', 'type' => 'DEBIT_CARD'])
            </div>
        </div>
    </div>
</div>
