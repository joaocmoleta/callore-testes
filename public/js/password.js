function sendForm(e) {
    // Show load
    // Hidde form
    e.style.display = 'none'
}
function viewPass(e) {
    let fff = ''
    if (e.nextElementSibling.getAttribute('type') == 'password') {
        fff = 'text'
    } else {
        fff = 'password'
    }
    e.nextElementSibling.setAttribute('type', fff)
}

document.addEventListener('DOMContentLoaded', (event) => {
    setTimeout(() => {
        let loader = document.getElementsByClassName('loader-box')[0];
        loader.style.display = "none";
    }, 5);
});