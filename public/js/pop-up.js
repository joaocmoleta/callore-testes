// Deletar cookies
// document.cookie = "cadastrando_pop_up=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;"
// document.cookie = "cadastrar_pop_up=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;"
// document.cookie = "cadastrar_pop_upfffff=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;"
// console.log(document.cookie)

if (document.getElementsByClassName('msg-success')[0]) {
    document.cookie =
        `cadastrar_pop_up=true; expires=${cookie_exp}; path=/;`
}

if (document.cookie.match(/cadastrando_pop_up/g)) {
    montarPopUp()
    document.cookie = "cadastrando_pop_up=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;"
}

if (!document.cookie.match(/cadastrar_pop_up/g)) {
    document.body.setAttribute('onmouseleave', 'goMontarPopUp(event)')

    // document.body.addEventListener("mouseleave", function (event) {
    //     if (event.screenY <= 220 && document.getElementsByClassName('pop-up-leave-page').length == 0) {
    //         montarPopUp()
    //     }
    // })
}

function goMontarPopUp(ev) {
    if (ev.screenY <= 220 && document.getElementsByClassName('pop-up-leave-page').length == 0) {
        montarPopUp()
    }
}

function montarPopUp() {
    let div = document.createElement('div')
    div.classList.add('pop-up-leave-page')
    div.append(montarPopUpContent())
    document.body.append(div)
    document.documentElement.style.overflowY = 'hidden'
    if (document.getElementById('name')) {
        inicializarListeners()
    }
}

function closePopUp(e) {
    document.documentElement.style.overflowY = 'scroll'
    e.remove()
    document.cookie =
        `cadastrar_pop_up=true; expires=${cookie_exp}; path=/;`
    document.body.removeAttribute('onmouseleave')
}

function btClosePopUp() {
    let bt = document.createElement('div')
    bt.classList.add('pop-up-close')
    bt.innerText = 'x'
    bt.setAttribute('onclick', 'closePopUp(this.parentElement.parentElement)')
    return bt
}

function montarPopUpContent() {
    let div = document.createElement('div')
    div.classList.add('pop-up-content')
    div.append(btClosePopUp())
    if (document.getElementsByClassName('msg-error')[0]) {
        div.append(document.getElementsByClassName('msg-error')[0])
    }
    if (document.getElementsByClassName('msg-success')[0]) {
        div.append(document.getElementsByClassName('msg-success')[0])

        let tx1 = document.createElement('div')
        tx1.classList.add('texto-pop-up-success')
        tx1.innerHTML = '<h2>Você ganhou o cupom <span class="label">LEADSITE</span> para utilizar na sua compra.</h2>'
        div.append(tx1)
        
        let tx2 = document.createElement('div')
        div.append(tx2)
        tx2.classList.add('texto-pop-up-success')
        tx2.innerHTML = '<p>Basta inserir o código LEADSITE e aproveitar.</p>'
        div.append(tx2)
        
        let tx3 = document.createElement('div')
        tx3.classList.add('texto-pop-up-success')
        tx3.innerHTML = '<p>* Cupom não acomulativo a outras promoções e descontos.</p>'
        div.append(tx3)
    } else {
        div.append(montarPopUpTitle('Não vá embora'))
        div.append(montarPopUpTitle2('Antes garanta um desconto exclusivo'))
        div.append(montarPopUpCall('Insira seu nome, telefone e e-mail abaixo e receba seu cupom'))
        div.append(montarPopUpForm(route_pop_up_leave, 'POST'))
    }
    return div
}

function montarPopUpTitle(title) {
    let div = document.createElement('h3')
    div.classList.add('pop-up-title')
    div.innerText = title
    return div
}

function montarPopUpTitle2(title) {
    let div = document.createElement('h4')
    div.classList.add('pop-up-title-2')
    div.innerText = title
    return div
}

function montarPopUpCall(call) {
    let div = document.createElement('p')
    div.innerText = call
    return div
}

function montarPopUpForm(action, method) {
    let form = document.createElement('form')
    form.setAttribute('method', method)
    form.setAttribute('action', action)
    form.setAttribute('onsubmit', 'cadastrarPopUp()')
    form.append(montarPopUpCSRF())
    form.append(montarPopUpLabel('Nome'))
    form.append(montarPopUpInput('name', 'John Doe'))
    form.append(montarPopUpLabel('Telefone'))
    form.append(montarPopUpInput('phone', '+55 51 99999-9999'))
    form.append(montarPopUpLabel('E-mail'))
    form.append(montarPopUpInput('email', 'john@gmail.com'))
    form.append(montarPopUpSubmit('Garantir'))
    return form
}

function montarPopUpCSRF() {
    let input = document.createElement('input')
    input.setAttribute('type', 'hidden')
    input.setAttribute('name', '_token')
    input.setAttribute('value', csrf_token)
    return input
}

function montarPopUpLabel(str) {
    let label = document.createElement('label')
    label.innerText = str
    return label
}

function montarPopUpInput(name, placeholder) {
    let div = document.createElement('div')
    div.classList.add('input')
    let input = document.createElement('input')
    input.setAttribute('id', name)
    input.setAttribute('name', name)
    input.setAttribute('placeholder', placeholder)
    div.append(input)
    return div
}

function montarPopUpSubmit(str) {
    let div = document.createElement('div')
    div.classList.add('submit')
    let button = document.createElement('button')
    button.classList.add('bt-primary-danger')
    button.innerText = str
    div.append(button)
    return div
}

function cadastrarPopUp() {
    document.cookie = "cadastrando_pop_up=true"
}

function createMessage(msg) {
    let div = document.createElement('div')
    div.classList.add('msg-error')
    div.innerText = msg
    return div
}

function inicializarListeners() {
    document.getElementById('name').addEventListener('blur', function () {
        if (this.parentElement.children.length > 1) {
            this.parentElement.children[1].remove()
        }
        if (this.value.length < 3) {
            this.parentElement.append(createMessage('Insira um nome válido de no mínimo 3 letras.'))
        }
    })

    document.getElementById('phone').addEventListener('blur', function () {
        if (this.parentElement.children.length > 1) {
            this.parentElement.children[1].remove()
        }
        if (this.value.length < 10) {
            this.parentElement.append(createMessage('Insira um telefone válido.'))
        }
    })

    document.getElementById('email').addEventListener('blur', function () {
        let email_rx = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/

        if (this.parentElement.children.length > 1) {
            this.parentElement.children[1].remove()
        }

        if (!this.value.match(email_rx)) {
            this.parentElement.append(createMessage('Insira um e-mail válido.'))
        }
    })
}