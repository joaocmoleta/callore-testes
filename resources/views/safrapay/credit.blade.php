@extends('templates.main', [
    'description' => '',
    'og_title' => 'Seu pedido - Pagamento com Cartão de Crédito',
    'noindex' => true,
])
@section('content')
    <div class="single-message">
        @include('flash-message')
    </div>

    <div class="payment-card-safrapay">
        <h1>Seu pedido - Pagamento com Cartão de Crédito</h1>
        <p>Insira os dados do seu cartão abaixo para concluir:</p>

        <form action="{{ route('safra.credit.process') }}" method="POST" onsubmit="tokenizer(event)" id="form-safra-credit">
            @csrf

            {{-- Pedido --}}
            <input type="hidden" name="merchantChargeId" value="{{ $finded_order->id }}">

            {{-- Parcelas --}}
            <input type="hidden" name="installmentNumber" value="1">

            {{-- Token resultante js --}}
            <input type="hidden" name="temporaryCardToken">

            <input type="hidden" id="brand">

            <div class="field-box">
                <label>Nome do titular</label>
                <div class="input">
                    <input type="text" id="holder-name" placeholder="FULANO SILVA">
                </div>
            </div>

            <div class="field-box">
                <label>CPF/CNPJ do titular</label>
                <div class="input">
                    <input type="text" id="holder-doc" placeholder="65844359038" onkeyup="cpfCnpj(this)"
                        onblur="cpfCnpj(this)">
                </div>
            </div>

            <div class="field-box">
                <label>Número do cartão</label>
                <div class="input card-number-brand">
                    <span class="brand">Bandeira</span>
                    <input type="text" id="card-number" placeholder="5491670214095346" onkeyup="brandCardMol(this)"
                        onblur="brandCardMol(this)">
                </div>
            </div>

            <div class="field-box">
                <label>Mês vencimento</label>
                <div class="input">
                    <input type="text" id="card-exp-month" placeholder="02" onkeyup="validarMonth(this)"
                        onblur="validarMonth(this)">
                </div>
            </div>

            <div class="field-box">
                <label>Ano vencimento</label>
                <div class="input">
                    <input type="text" id="card-exp-year" placeholder="2026" onkeyup="validarYear(this)"
                        onblur="validarYear(this)">
                </div>
            </div>

            <div class="field-box">
                <label>Código de segurança (CVV)</label>
                <div class="input">
                    <input type="text" id="cvv" placeholder="223" onkeyup="validarCVV(this)"
                        onblur="validarCVV(this)">
                </div>
            </div>

            <div class="totais-credit" style="width: 100%; margin-top: 30px">
                <p><strong>Valor a vista</strong>: R$ {{ number_format($amount, 2, ',', '.') }}</p>
            </div>

            <label for="">Selecione a quantidade de parcelas?</label>
            <div class="parcelamento">
                <div class="parcelas">
                    @for ($i = 1; $i <= 6; $i++)
                        <div class="parcela {{ $i == 1 ? 'selected' : '' }}"
                            onclick="selectParcela(this, {{ $i }})">
                            <div class="valor">{{ $i }} x de R$ {{ number_format($amount / $i, 2, ',', '.') }}
                                (sem
                                juros)</div>
                            <div class="selecionar">
                                <span class="parcela-opt {{ $i == 1 ? 'selected' : '' }}"><span
                                        class="parcela-opt-bt"></span></span>
                            </div>
                        </div>
                    @endfor
                    @for ($i = 7; $i <= 12; $i++)
                        <div class="parcela" onclick="selectParcela(this, {{ $i }})">
                            <div class="valor">{{ $i }} x de R$
                                {{ number_format(($amount + ($amount * json_decode(env('SAFRA_PARCELAMENTO'), 1)[$i]) / 100) / $i, 2, ',', '.') }}
                                (Total de R$
                                {{ number_format($amount + ($amount * json_decode(env('SAFRA_PARCELAMENTO'), 1)[$i]) / 100, 2, ',', '.') }})
                            </div>
                            <div class="selecionar">
                                <span class="parcela-opt"><span class="parcela-opt-bt"></span></span>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            <div class="submit">
                <button type="submit" class="bt-primary-one">Efetuar o pagamento</button>
            </div>
        </form>

        <p><a href="{{ route('orders.show_order', $finded_order->id) }}" class="bt-thirdary-one"
                style="text-decoration: none">← Voltar (escolher outra forma de pagamento)</a></p>

        <div class="card-messages"></div>

        <form action="{{ route('orders.add_coupon') }}" method="POST" class="form-coupon">
            @csrf
            <input type="hidden" name="order" value="{{ $finded_order->id }}">

            <label>Tem um cupom de desconto?</label>
            <div class="input input-and-bt-inter">
                <input type="text" name="coupon" placeholder="D9PKK5E4LGXA1GO"
                    value="{{ $finded_order->coupon ?? '' }}">
                <button class="bt-primary-one bt-inter">{{ $finded_order->coupon ? 'Atualizar' : 'Adicionar' }}
                    cupom</button>
            </div>
            {{-- <p>(saiba como conseguir o seu <a href="{{ route('cupons') }}">aqui</a>)</p> --}}
        </form>
    </div>

    <script>
        function selectParcela(e, parcelas) {
            // Varre todas as opções removendo selecao, se houver
            let opcoes = Array.from(e.parentElement.children)
            opcoes.forEach(opt => {
                opt.children[1].children[0].children[0].style.background = 'none'
                opt.style.background = 'none'
            })
            // Colocar como selecionado na lista de opções
            e.children[1].children[0].children[0].style.background = '#000'
            e.style.background = '#e4e4e4'

            document.querySelector('input[name="installmentNumber"]').value = parcelas
        }
    </script>

    <script type="text/javascript" src="{{ env('SAFRA_SCRIPT_TOKENIZACAO') }}"></script>

    <script>
        var cnpj = "{{ env('CNPJ') }}"; // cnpj do lojista
        var merchantId = "{{ env('SAFRA_M_ID') }}"; // merchantId do lojista
        // var cnpj = "62225893000120"; // cnpj do lojista teste
        // var merchantId = "FC1123EE-3150-4768-ADDF-4D4C8D4264B0"; // merchantId do lojista teste
        var card

        const run = () => {
            card = {
                brand: document.querySelector('#brand').value,
                number: document.querySelector('#card-number').value.replace(/\D/g, ''),
                holderName: document.querySelector('#holder-name').value,
                holderDocument: document.querySelector('#holder-doc').value.replace(/\D/g, ''),
                expirationMonth: document.querySelector('#card-exp-month').value.replace(/\D/g, ''),
                expirationYear: document.querySelector('#card-exp-year').value.replace(/\D/g, ''),
                cvv: document.querySelector('#cvv').value,
            };

            // Clear msgs
            document.querySelector('.card-messages').innerText = ''

            if (cnpj == '' || merchantId == '') {
                document.querySelector('.card-messages').appendChild(createMsg('msg-error',
                    'Falha técnica, contate o administrador.'))
                return
            }

            if (card.holderName == '') {
                document.querySelector('.card-messages').appendChild(createMsg('msg-error',
                    'Verifique o nome impresso no cartão'))
                return
            }

            if (card.holderDocument == '') {
                document.querySelector('.card-messages').appendChild(createMsg('msg-error', 'Verifique o CPF digitado'))
                return
            }

            if (card.brand == '' || card.brand == 'Desconhecida') {
                let card_number = document.querySelector('#card-number')
                if (card_number.value == '') {
                    document.querySelector('.card-messages').appendChild(createMsg('msg-error',
                        'Verifique o número do cartão.'))
                    return
                }

                brandCardMol(card_number)
                card.brand = document.querySelector('#brand').value
            }

            if (card.brand == '' || card.number == '' || card.brand == 'Desconhecida') {
                document.querySelector('.card-messages').appendChild(createMsg('msg-error',
                    'Verifique o número do cartão.'))
                return
            }

            if (card.expirationMonth == '') {
                document.querySelector('.card-messages').appendChild(createMsg('msg-error',
                    'Verifique o Mês de vencimento digitado'))
                return
            }

            if (/^\d{2}$/.test(card.expirationYear.toString())) {
                card.expirationYear = 20 + card.expirationYear
            } else if (/^\d{4}$/.test(card.expirationYear.toString())) {

            } else {
                document.querySelector('.card-messages').appendChild(createMsg('msg-error',
                    'Verifique o Ano de vencimento digitado'))
                return
            }

            if (card.expirationYear == '') {
                document.querySelector('.card-messages').appendChild(createMsg('msg-error',
                    'Verifique o Ano de vencimento digitado'))
                return
            }

            if (card.cvv == '') {
                document.querySelector('.card-messages').appendChild(createMsg('msg-error', 'Verifique o CVV digitado'))
                return
            }

            document.querySelector('.card-messages').appendChild(createMsg('msg-success',
                'Todos os campos foram validados, aguarde...'))

            SafraPayTransparent.setCredentials({
                merchantCredential: cnpj,
                merchantId: merchantId,
            });

            goCreateTemporaryCard()
        };

        // Função para Safra
        function goCreateTemporaryCard() {
            console.log(card)
            SafraPayTransparent.createTemporaryCard({
                card,
                success: (body) => {
                    console.log(body)
                    document.querySelector('input[name="temporaryCardToken"]').value = body.temporaryToken
                    document.querySelector('#form-safra-credit').submit()
                },
                error: (body) => {
                    document.querySelector('.card-messages').appendChild(createMsg('msg-error',
                        'Falha técnica, contate o administrador.'))
                    console.error(body);
                },
            });
        }

        // Função para local
        // function goCreateTemporaryCard() {
        //     let card_tokenizaded = {
        //         "cardNumber": card.number,
        //         "brand": card.brand,
        //         "cardholderName": card.holderName,
        //         "cardholderDocument": card.holderDocument,
        //         "expirationMonth": card.expirationMonth,
        //         "expirationYear": card.expirationYear,
        //         "brandName": card.brand
        //     }

        //     document.querySelector('input[name="temporaryCardToken"]').value = JSON.stringify(card_tokenizaded)

        //     document.querySelector('#form-safra-credit').submit()
        // }

        function tokenizer(ev) {
            ev.preventDefault()
            run()
        }

        function brandCardMol(e) {
            formatCreditNumber(e)

            let clear = e.value.replace(/\D/g, '')
            if (clear.length == 4) {
                SafraPayTransparent.getCardBrand({
                    bin: clear.substring(0, 6),
                    success: (body) => {
                        document.querySelector('#brand').value = body.brand
                        document.querySelector('.brand').innerText = body.brand
                    },
                    error: (err) => {
                        console.log(err);
                    },
                });

            }
        }

        function formatCreditNumber(e) {
            // Remove tudo que não for número
            let valor = e.value.replace(/\D/g, '')

            // Limita a 16 dígitos
            if (valor.length > 16) {
                valor = valor.slice(0, 16);
            }

            // Adiciona espaçamento a cada 4 dígitos
            let formatado = valor.replace(/(.{4})/g, '$1 ').trim();

            // Atualiza o campo com o valor formatado
            e.value = formatado;
        }

        function detectarBandeiraCartao(numero) {
            const bandeiras = [{
                    nome: "Visa",
                    regex: /^4[0-9]{12}(?:[0-9]{3})?$/
                },
                {
                    nome: "Mastercard",
                    regex: /^5[1-5][0-9]{14}$/
                },
                {
                    nome: "American Express",
                    regex: /^3[47][0-9]{13}$/
                },
                {
                    nome: "Diners Club",
                    regex: /^3(?:0[0-5]|[68][0-9])[0-9]{11}$/
                },
                {
                    nome: "Discover",
                    regex: /^6(?:011|5[0-9]{2})[0-9]{12}$/
                },
                {
                    nome: "Elo",
                    regex: /^(4011(78|79)|431274|438935|451416|4576(31|32)|504175|5067(0[0-9]|1[0-9]|20)|5090(0[0-9]|1[0-9]|20)|627780|636297|636368|650(03[1-3]|04[0-9]|05[0-9]|06[1-9]|07[0-9]|08[0-9]|09[0-9])|6516(5[2-9]|6[0-9]|7[0-9]|8[0-9]|9[0-9])|6550(0[0-9]|1[0-9]|2[0-9]|3[0-9]|4[1-9]))/
                },
                {
                    nome: "Hipercard",
                    regex: /^(606282|3841(0[0-9]|1[0-9]|2[0-9]))/
                },
                {
                    nome: "Aura",
                    regex: /^50[0-9]{14,17}$/
                },
            ];

            const numeroLimpo = numero.replace(/\D/g, '');

            for (const bandeira of bandeiras) {
                if (bandeira.regex.test(numeroLimpo)) {
                    return bandeira.nome;
                }
            }

            return "Desconhecida";
        }

        function cpfCnpj(e) {
            let value = e.value.replace(/\D/g, ''); // remove tudo que não for número

            if (value.length <= 11) {
                // Formatar como CPF: 000.000.000-00
                value = value.replace(/^(\d{3})(\d)/, '$1.$2');
                value = value.replace(/^(\d{3})\.(\d{3})(\d)/, '$1.$2.$3');
                value = value.replace(/\.(\d{3})(\d)/, '.$1-$2');
            } else {
                // Formatar como CNPJ: 00.000.000/0000-00
                value = value.replace(/^(\d{2})(\d)/, '$1.$2');
                value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
                value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
                value = value.replace(/(\d{4})(\d)/, '$1-$2');
            }

            e.value = value;
        }

        function validarCVV(e) {
            let valor = e.value;

            // Remove tudo que não for número
            valor = valor.replace(/\D/g, '');

            // Limita a 4 dígitos
            valor = valor.substring(0, 4);

            e.value = valor;
        }

        function validarMonth(e) {
            let valor = e.value;

            // Remove tudo que não for número
            valor = valor.replace(/\D/g, '');

            // Limita a 4 dígitos
            valor = valor.substring(0, 2);

            e.value = valor;
        }

        function validarYear(e) {
            let valor = e.value;

            // Remove tudo que não for número
            valor = valor.replace(/\D/g, '');

            // Limita a 4 dígitos
            valor = valor.substring(0, 4);

            e.value = valor;
        }

        function createMsg(msg_class, content) {
            let div = document.createElement('div')
            div.classList.add(msg_class)
            div.innerHTML = content
            return div
        }
    </script>
@endsection
