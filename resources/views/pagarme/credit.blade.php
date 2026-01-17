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
        <form action="{{ route('pay.credit_card_pagarme') }}" method="POST" id="form-card" data-pagarmecheckout-form>
            @csrf

            <input type="hidden" name="reference_id" value="{{ $db_order->id }}">

            <div class="field-box">
                <label>Nome impresso no cartão</label>
                <div class="input">
                    <input type="text" name="holder-name" placeholder="JOSE DA SILVA"
                        data-pagarmecheckout-element="holder_name" class="pagarme"
                        onblur="this.nextElementSibling.value = this.value" onkeyup="insertHolder(this)">
                    <input type="hidden" name="name">
                </div>
            </div>

            <div class="field-box">
                <label>Número do cartão</label>
                <div class="input">
                    <span data-pagarmecheckout-element="brand"></span>
                    <input type="text" name="card-number" placeholder="4000000000000010"
                        data-pagarmecheckout-element="number" class="pagarme"
                        onblur="this.nextElementSibling.value = this.value" onkeyup="insertCardNumber(this)">
                    <input type="hidden" name="number">
                </div>
            </div>

            <div class="field-box">
                <label>Mês vencimento</label>
                <div class="input">
                    <input type="text" name="card-exp-month" placeholder="01" data-pagarmecheckout-element="exp_month"
                        class="pagarme" onkeyup="insertExpMonth(this)">
                    <input type="hidden" name="card-exp-month-cp">
                </div>
            </div>

            <div class="field-box">
                <label>Ano vencimento</label>
                <div class="input">
                    <input type="text" name="card-exp-year" placeholder="30" data-pagarmecheckout-element="exp_year"
                        class="pagarme" onkeyup="insertExpYear(this)">
                    <input type="hidden" name="card-exp-year-cp">
                </div>
            </div>

            <div class="field-box">
                <label>Código de segurança</label>
                <div class="input">
                    <input type="text" name="cvv" placeholder="123" data-pagarmecheckout-element="cvv"
                        onkeyup="insertSecurity(this)">
                </div>
            </div>

            <label>Parcelamento</label>
            <input type="hidden" name="installments" value="1">
            <div class="parcelamento">
                {{-- <div class="resumo" onclick="parcelas(this)">
                    <div class="valor">1 x de R$ {{ number_format($amount_complete['amount'], 2, ',', '.') }}</div>
                    <div class="selecionar">
                        <span class="parcela-opt1">Escolher outro</span>
                    </div>
                </div> --}}
                <div class="parcelas">
                    @for ($i = 1; $i <= 6; $i++)
                        <div class="parcela {{ $i == 1 ? 'selected' : '' }}"
                            onclick="selectParcela(this, {{ $i }})">
                            <div class="valor">{{ $i }} x de R$
                                {{ number_format($amount_complete['amount'] / $i, 2, ',', '.') }} (sem juros)</div>
                            <div class="selecionar">
                                <span class="parcela-opt {{ $i == 1 ? 'selected' : '' }}"><span
                                        class="parcela-opt-bt"></span></span>
                            </div>
                        </div>
                    @endfor
                    @for ($i = 7; $i <= 12; $i++)
                        <div class="parcela" onclick="selectParcela(this, {{ $i }})">
                            <div class="valor">{{ $i }} x de R$
                                {{ number_format(($amount_complete['amount'] + ($amount_complete['amount'] * json_decode(env('PAGARME_PARCELAMENTO'), 1)[$i]) / 100) / $i, 2, ',', '.') }}
                                (Total de R$
                                {{ number_format($amount_complete['amount'] + ($amount_complete['amount'] * json_decode(env('PAGARME_PARCELAMENTO'), 1)[$i]) / 100, 2, ',', '.') }})
                            </div>
                            <div class="selecionar">
                                <span class="parcela-opt"><span class="parcela-opt-bt"></span></span>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
            <div class="submit">
                <button type="submit" class="bt-primary-one">Pagar com cartão</button>
            </div>
        </form>

        <p><a href="{{ route('orders.show_order', $db_order->id) }}" class="bt-thirdary-one"
                style="text-decoration: none">← Voltar (escolher outra forma de pagamento)</a></p>

        <form action="{{ route('orders.add_coupon') }}" method="POST" class="form-coupon">
            @csrf
            <input type="hidden" name="order" value="{{ $db_order->id }}">

            <label>Tem um cupom de desconto?</label>
            <div class="input input-and-bt-inter">
                <input type="text" name="coupon" placeholder="D9PKK5E4LGXA1GO" value="{{ $db_order->coupon ?? '' }}">
                <button class="bt-primary-one bt-inter">{{ $db_order->coupon ? 'Atualizar' : 'Adicionar' }}
                    cupom</button>
            </div>
            {{-- <p>(saiba como conseguir o seu <a href="{{ route('cupons') }}">aqui</a>)</p> --}}
        </form>

    </div>

    <script src="https://checkout.pagar.me/v1/tokenizecard.js" data-pagarmecheckout-app-id="{{ env('PAGARME_APP_ID') }}">
    </script>
    <script>
        function parcelas(e) {
            let element = e.nextElementSibling.style
            if (element.height == '225px') {
                element.height = '0px'
            } else {
                element.height = '225px'
            }
        }

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
            // // Adicionar o valor no resumo
            // e.parentElement.previousElementSibling.children[0].innerText = e.innerText
            // // Fecha opções
            // e.parentElement.style.height = '0px'
            // Adicionar valor ao input de parcelas
            e.parentElement.parentElement.previousElementSibling.value = parcelas
        }

        window.addEventListener("load", (event) => {
            let elementosForm = document.getElementById('form-card').elements
            let elementosFormArray = Array.from(elementosForm)

            function copiar_todos(e) {
                if (e.classList.contains('pagarme')) {
                    e.nextElementSibling.value = e.value
                }
            }

            elementosFormArray.forEach((element) => copiar_todos(element));
        });

        function createMsg(text, who, type) {
            if (document.getElementsByName(who)[0].parentElement.lastElementChild.classList.contains(type)) {
                document.getElementsByName(who)[0].parentElement.lastElementChild.remove()
            }

            let msg = document.createElement('div')
            msg.classList.add(type)
            let span = document.createElement('span')
            span.innerText = text
            msg.append(span)
            document.getElementsByName(who)[0].parentElement.append(msg)
        }

        function success(data) {
            console.log('Sucesso')
            console.log(data)
            return true;
        };

        function fail(error) {
            console.error(error);
            console.error(error['errors'])

            if (error['errors']['request.card.holder_name']) {
                createMsg('Nome impresso no cartão é necessário.', 'holder-name', 'input-error')
            }

            if (error['errors']['request.card.number']) {
                createMsg('Número do cartão precisa ser um número de 13 a 19 caracteres.', 'card-number', 'input-error')
            }

            if (error['errors']['request.card.exp_month']) {
                createMsg('O mês tem que ser um número de 1 a 12.', 'card-exp-month', 'input-error')
            }

            if (error['errors']['request.card.exp_year']) {
                createMsg('O ano tem que ser um número de 1 a 12.', 'card-exp-year', 'input-error')
            }

            if (error['errors']['request.card.cvv']) {
                createMsg('O CVV precisa ser um número de 3 a 4 caracteres.', 'cvv', 'input-error')
            }

            if (document.getElementsByClassName('msg-error').length) {
                document.getElementsByClassName('msg-error')[0].remove()
            }
            let div_msg = document.createElement('div')
            div_msg.classList.add('msg-error')
            let span1 = document.createElement('span')
            span1.innerText =
                'Erro ao processar seu pagamento, verifique os dados digitados. Todos os campos são necessários.'
            div_msg.append(span1)
            if (error['errors']['request.card']) {
                let span2 = document.createElement('span')
                span2.style.display = 'block'
                span2.innerText = error['errors']['request.card']
                div_msg.append(span2)
            }
            document.getElementById('form-card').parentElement.prepend(div_msg)
            return false;
        };

        PagarmeCheckout.init(success, fail)
    </script>
@endsection
