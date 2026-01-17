{{-- // Cartão de crédito --}}
<div class="option-box">
    <h4 onclick="selectPay(this)"><svg clip-rule="evenodd" width="15" fill-rule="evenodd" stroke="black"
            fill="{{ session()->get('pay') == 'credit_card' ? 'rgb(51, 51, 51)' : 'none' }}" stroke-linejoin="round"
            stroke-miterlimit="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <circle cx="11.998" cy="11.998" fill-rule="nonzero" r="9.998" />
        </svg> Cartão de crédito</h4>
    <div class="option" style="display: {{ session()->get('pay') == 'credit_card' ? 'flex;' : 'none;' }}">
        <form action="{{ route('pay.credit_card') }}" method="POST" id="form-card" onsubmit="checkFill(this, event)">
            @csrf

            <input type="hidden" name="reference_id" value="{{ $db_order->id }}">
            <input type="hidden" name="encrypted" id="encrypted">

            <label>Telefone</label>
            <div class="input">
                <select id="countries" name="ddi" onmousedown="loadCountries(this)">
                    <option value="{{ old('ddi') ?? ($user->phone ? substr($user->phone, 0, 2) : '55') }}" selected>
                        {{ old('ddi') ?? ($user->phone ? substr($user->phone, 0, 2) : '+55 Brasil') }}</option>
                </select>
            </div>
            @error('ddi')
                <div class="input-error">{{ $message }}</div>
            @enderror

            <div class="input-phone-ddi-ddd-n" style="margin-bottom: 20px">
                (<input type="text" name="ddd" value="{{ old('ddd') ?? substr($user->phone, 2, 2) }}"
                    placeholder="ddd" maxlength="2" class="ddd">)
                <input type="text" name="phone" value="{{ old('phone') ?? substr($user->phone, 4) }}"
                    placeholder="988889999" onkeyup="this.value = mtels(this.value)" maxlength="10" class="phone">
            </div>
            @error('ddd')
                <div class="input-error">{{ $message }}</div>
            @enderror
            @error('phone')
                <div class="input-error">{{ $message }}</div>
            @enderror

            <script src="/js/countries.min.js"></script>
            <script>
                function mtels(v) {
                    v = v.replace(/\D/g, "") //Remove tudo o que não é dígito
                    if (v.length > 9) {
                        v = v.replace(/(\d{9})(\d)$/, "$1")
                    }
                    v = v.replace(/(\d)(\d{4})$/, "$1-$2"); //Coloca hífen entre o quarto e o quinto dígitos
                    return v
                }

                function loadCountries(e) {
                    for (const [key, value] of Object.entries(countries)) {
                        let option = document.createElement('option')
                        option.textContent = `+${value['ddi']} ${value['pais']}`
                        option.setAttribute('value', '')
                        e.append(option)
                    }
                }
            </script>

            <label>Nome impresso no cartão</label>
            <div class="input">
                <input type="text" name="card_holder_name" id="card_holder_name" placeholder="José Silva"
                    value="{{ old('card_holder_name') ?? '' }}" onkeyup="insertHolder(this)"
                    onfocus="frontCard(this, 'credit')">
                @error('card_holder_name')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label>N⁰ Cartão de crédito</label>
            <div class="input">
                <input type="number" name="card_number" id="card_number" placeholder="4111111111111111"
                    value="{{ old('card_number') ?? '' }}" onkeyup="insertCardNumber(this, 'credit')"
                    onblur="loadInstallments(this)" maxlength="16"
                    oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                @error('card_number')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label>Mês vencimento</label>
            <div class="input">
                <input type="number" id="card_exp_month" placeholder="12" value="{{ old('card_exp_month') ?? '' }}"
                    onkeyup="insertExpMonth(this, 'credit')" maxlength="2"
                    oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                @error('card_exp_month')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label>Ano vencimento</label>
            <div class="input">
                <input type="number" id="card_exp_year" placeholder="2030" value="{{ old('card_exp_year') ?? '' }}"
                    onkeyup="insertExpYear(this, 'credit')" maxlength="4"
                    oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                @error('card_exp_year')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label>Código de segurança</label>
            <div class="input">
                <input type="number" name="card_security_code" id="card_security_code" placeholder="123"
                    value="{{ old('card_security_code') ?? '' }}" onkeyup="insertSecurity(this, 'credit')"
                    onfocus="backCard(this, 'credit')">
                @error('card_security_code')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- parcelas --}}
            <label>Parcelas</label>
            <div class="input" id="installments">
                <p>Entre com o número do cartão</p>
                @error('installments')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <script type="text/javascript" src="{{ env('PAGSEGURO_SCRIPT') }}"></script>

            <script>
                // Parcelamento
                const options = {
                    style: 'currency',
                    currency: 'BRL',
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 3
                }

                const formatNumber = new Intl.NumberFormat('pt-BR', options)

                function loadInstallments(e) {
                    if (e.value.length < 6) {
                        let target = document.getElementById('installments')
                        target.innerHTML =
                            '<span class="msg-error">Insira o número do cartão para liberar o parcelamento disponível.</span>'
                        return false;
                    }

                    document.getElementById('installments').innerText = ''

                    PagSeguroDirectPayment.setSessionId('{{ $session_pag_seguro }}')

                    PagSeguroDirectPayment.getBrand({
                        // cardBin: 550209,
                        cardBin: document.getElementById('card_number').value.substr(0, 6),
                        success: function(response) {
                            getInstallmentsMol(response.brand.name)
                        },
                        error: function(response) {
                            console.log(response);
                            let target = document.getElementById('installments')
                            target.innerHTML =
                                '<span class="msg-error">Número do cartão inválido.</span>'
                        }
                    })
                }

                function getInstallmentsMol(brand) {
                    PagSeguroDirectPayment.getInstallments({
                        amount: '{{ $amount_complete['amount'] }}',
                        brand: brand,
                        maxInstallmentNoInterest: 6,
                        success: function(response) {
                            createSelect(response.installments[brand])
                        },
                        error: function(response) {
                            console.log(response)
                            let target = document.getElementById('installments')
                            target.innerHTML =
                                '<span class="msg-error">Número do cartão inválido.</span>'
                        }
                    })
                }

                function createSelect(installments) {
                    let select = document.createElement("select")
                    select.setAttribute('name', 'installments')
                    let target = document.getElementById('installments')

                    for (i = 0; i < installments.length; i++) {
                        // console.log(installments[i]);
                        let option = document.createElement('option')

                        let parcela = formatNumber.format(installments[i].installmentAmount)
                        let total = formatNumber.format(installments[i].totalAmount)

                        option.setAttribute('value', installments[i].quantity)

                        option.append(`${installments[i].quantity}x de ${parcela} (${total})`)
                        select.append(option)
                    }
                    target.append(select)
                }
            </script>

            <script src="https://assets.pagseguro.com.br/checkout-sdk-js/rc/dist/browser/pagseguro.min.js"></script>

            <script>
                function checkFill(e, ev) {
                    ev.preventDefault()
                    if (
                        document.getElementById('card_holder_name').value == '' ||
                        document.getElementById('card_number').value == '' ||
                        document.getElementById('card_exp_month').value == '' ||
                        document.getElementById('card_exp_year').value == '' ||
                        document.getElementById('card_security_code').value == ''
                    ) {
                        console.log('Favor preencher os campos necessários.')
                        window.scrollTo(0, 0)
                        if (document.getElementsByClassName('single-message')[0]) {
                            document.getElementsByClassName('single-message')[0].innerHTML =
                                '<div class="msg-error">Favor preencher os campos necessários.</div>'
                        } else {
                            let div = document.createElement('div')
                            div.classList.add('single-message')
                            div.innerHTML = '<div class="msg-error">Favor preencher os campos necessários.</div>'

                            document.getElementsByClassName('cart').parent.prepend(div)
                        }
                        return false
                    }
                    // else {
                    //     e.submit()
                    // }

                    getPublicKey()
                }

                function getPublicKey() {
                    const headers = new Headers()

                    const init = {
                        method: 'GET',
                        headers: headers,
                    }

                    fetch('/get-public-key', init)
                        .then((response) => response.json())
                        .then(function(response) {
                            // console.log(JSON.parse(response).public_key)
                            criptografar(JSON.parse(response).public_key)
                            // return JSON.parse(response).public_key
                        })
                        .catch(err => {
                            console.log(err)
                            return false
                        })
                }

                function criptografar(public_key) {
                    // Tratar ano de vencimento
                    let year = document.getElementById('card_exp_year').value
                    if(year.length == 2) {
                        year = '20' + year
                    }
                    var card = PagSeguro.encryptCard({
                        publicKey: public_key,
                        holder: document.getElementById('card_holder_name').value,
                        number: document.getElementById('card_number').value,
                        expMonth: document.getElementById('card_exp_month').value,
                        expYear: year,
                        securityCode: document.getElementById('card_security_code').value
                    });

                    if (card.hasErrors) {
                        console.log(card.errors)
                    } else {
                        var encrypted = card.encryptedCard;
                        document.getElementById('encrypted').value = encrypted
                        document.getElementById('form-card').submit()
                    }

                }
            </script>

            <div class="submit">
                <button class="bt-primary-danger">Efetuar o pagamento</button>
            </div>
        </form>
        <div class="card-view">
            <div class="credit-frente">
                @include('card', ['prefix' => 'credit', 'type' => 'CREDIT_CARD'])
            </div>
            <div class="credit-verso">
                @include('card-back', ['prefix' => 'credit', 'type' => 'CREDIT_CARD'])
            </div>
        </div>
    </div>
</div>
