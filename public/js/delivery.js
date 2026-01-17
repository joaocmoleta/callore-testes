function calculateDelivery(e) {
    let prev_msg_err = document.getElementById('msg-error-delivery')
    if (prev_msg_err) {
        prev_msg_err.remove()
    }

    let prev_msg = document.getElementById('msg-delivery')
    if (prev_msg) {
        prev_msg.remove()
    }

    if (e == '') {
        let div = document.createElement('div')
        div.setAttribute('id', 'msg-error-delivery')
        div.innerText = `É necessário um CEP válido, verifique por favor.`
        document.getElementById('delivery-simulator').append(div)

        return;
    }

    let form = new FormData(document.getElementById('delivery-simulator-form'))

    fetch(route_calculator, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrf
        },
        body: form
    })
        .then((response) => response.json())
        .then(function (response) {
            // console.log(response);
            let div = document.createElement('div')

            if (response.status == 'success') {
                div.setAttribute('id', 'msg-delivery')
                div.innerText =
                    `Receba gratuitamente o produto em ${response.msg} dias (úteis e sábados), após a confirmação de pagamento.`
            }

            if (response.status == 'error') {
                div.setAttribute('id', 'msg-error-delivery')
                div.innerText = `${response.msg}`
            }

            if (response[0] != undefined) {
                div.setAttribute('id', 'msg-error-delivery')
                div.innerText = `${response[0].cep[0]}`
            }

            document.getElementById('delivery-simulator').append(div)
        })
}