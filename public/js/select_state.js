function show_opts_states(e) {
    // mostrar regiões primeiro
    e.nextElementSibling.style.display = 'initial'
    e.nextElementSibling.innerText = '' // Limpa selects
    
    regions_br_set = Object.keys(regions_br)
    regions_br_set.forEach(region => {
        let opt = document.createElement('div')
        opt.classList.add('sel-option')
        opt.setAttribute('onclick', `sel_estate_rg(this, '${region}')`)
        opt.value = region
        opt.innerText = region
        e.nextElementSibling.append(opt)
    })
}

function sel_estate_rg(e, region) {
    let base = e.parentElement // captura pai antes de limpar
    e.parentElement.innerText = '' // Limpa selects
    
    states_set = Object.keys(regions_br[region])
    states_set.forEach(state => {
        let opt = document.createElement('div')
        opt.classList.add('sel-option')
        opt.setAttribute('onclick', `sel_estate(this)`)
        opt.value = regions_br[region][state].sigla
        opt.innerText = regions_br[region][state].nome
        base.append(opt)
    })
}

function sel_estate(e) {
    e.parentElement.style.display = 'none'
    e.parentElement.previousElementSibling.value = e.innerText
    e.parentElement.previousElementSibling.previousElementSibling.value = e.value
}