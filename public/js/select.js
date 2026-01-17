function show_opts(e, arr) {
    e.nextElementSibling.style.display = 'initial'
    
    arr.forEach(element => {
        let opt = document.createElement('div')
        opt.classList.add('sel-option')
        opt.setAttribute('onclick', `selected(this)`)
        opt.value = element.code
        opt.innerText = element.name
        e.nextElementSibling.append(opt)
    });
}

function selected(e) {
    e.parentElement.style.display = 'none'
    e.parentElement.previousElementSibling.value = e.innerText
    e.parentElement.previousElementSibling.previousElementSibling.value = e.value
}