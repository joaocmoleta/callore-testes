{{-- // Cartão de crédito pagarme testes criptografa cartão --}}

<div style="" class="pagamento-cartao-testes">
    <form action="{{ route('pay.credit_card_pagarme') }}" method="POST" data-pagarmecheckout-form id="form-card"
        onsubmit="criptografarCartao(this)">
        @csrf

        <label>Nome impresso no cartão</label>
        <div class="input">
            <input type="text" name="holder-name" placeholder="JOSE DA SILVA" value="JOSE DA SILVA"
                data-pagarmecheckout-element="holder_name" class="pagarme"
                onblur="this.nextElementSibling.value = this.value">
            <input type="hidden" name="name">
        </div>
        <label>Número do cartão</label>
        <div class="input">
            <span data-pagarmecheckout-element="brand"></span>
            <input type="text" name="card-number" placeholder="4000000000000010" value="4000000000000010"
                data-pagarmecheckout-element="number" class="pagarme"
                onblur="this.nextElementSibling.value = this.value">
            <input type="hidden" name="number">
        </div>
        <label>Mês vencimento</label>
        <div class="input">
            <input type="text" name="card-exp-month" placeholder="01" value="01"
                data-pagarmecheckout-element="exp_month" class="pagarme">
            <input type="hidden" name="card-exp-month-cp">
        </div>
        <label>Ano vencimento</label>
        <div class="input">
            <input type="text" name="card-exp-year" placeholder="30" value="30"
                data-pagarmecheckout-element="exp_year" class="pagarme">
            <input type="hidden" name="card-exp-year-cp">
        </div>
        <label>Código de segurança</label>
        <div class="input">
            <input type="text" name="cvv" placeholder="123" value="123" data-pagarmecheckout-element="cvv">
        </div>
        <div class="submit">
            <button type="submit" class="bt-primary-one">Enviar</button>
        </div>
    </form>
    <div class="load-cart-cripto">
        <div class="loader-cripto"></div>
        <div>Verificando as informações...</div>
    </div>
</div>

@if (env('APP_ENV') == 'production')
    <link rel="stylesheet" href="{{ asset('css/css/styles.min.css?v=4.4') }}">
@else
    <link rel="stylesheet" href="{{ asset('css/css/styles.css?v=4.4') }}">
@endif

<script src="https://checkout.pagar.me/v1/tokenizecard.js" data-pagarmecheckout-app-id="pk_test_Xp52l3kTwHOYea9k">
</script>
<script>
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

    function criptografarCartao(e) {
        document.getElementsByClassName('load-cart-cripto')[0].style.display = 'flex'
    }

    PagarmeCheckout.init(success, fail)
</script>
