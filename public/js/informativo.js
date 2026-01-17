document.addEventListener("DOMContentLoaded", (event) => {
    let container = document.getElementsByClassName('info-home')[0]
    let info = container.children[0]

    let clone = info.cloneNode(true) // Cópia antes da manipulação do nó

    // Deixa como base para o tamanho do nó padrão
    info.style.position = 'relative'
    info.style.visibility = 'hidden'

    // Cria três nós para movimentação e os organiza com
    // Um escondido na esquerda, um aparente no meio e outro escondido na direita
    let cp = clone
    container.appendChild(cp)

    let cp1 = cp.cloneNode(true)
    cp1.style.left = info.offsetWidth + 20 + 'px'
    container.appendChild(cp1)

    let cp2 = cp.cloneNode(true)
    cp2.style.left = info.offsetWidth * 2 + 20 + 'px'
    container.appendChild(cp2)

    container.children[1].style.left = info.offsetWidth * -1 - 20 + 'px'
    container.children[2].style.left = 0
    container.children[3].style.left = info.offsetWidth + 20 + 'px'

    // A cada certo tempo movimenta os três nós
    // O primeiro excluí, o do meio move o da esquerda move e adiciona mais um no final
    setInterval(function () {
        container.children[1].remove()
        let cp = container.children[1].cloneNode(true)
        cp.style.left = info.offsetWidth * 2 + 'px'
        container.appendChild(cp)

        container.children[1].style.left = info.offsetWidth * -1 - 20 + 'px'
        container.children[2].style.left = 0
        container.children[3].style.left = info.offsetWidth + 20 + 'px'

    }, (9 * 1000)) // 10000 para 10 segundos - 1000 para 1
})