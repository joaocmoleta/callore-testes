function add(e, ev) {
    ev.preventDefault()
    e.previousElementSibling.value = parseInt(e.previousElementSibling.value) + 1
}

function remove(e, ev) {
    ev.preventDefault()
    if (e.nextElementSibling.value > 1) {
        e.nextElementSibling.value = parseInt(e.nextElementSibling.value) - 1
    }
}