document.addEventListener('DOMContentLoaded', (event) => {
    setTimeout(() => {
        let loader = document.getElementsByClassName('loader-box-page')[0];
        loader.style.display = "none";
    }, 5);
});

function profile_menu(e) {
    if (e.nextElementSibling.style.display == 'flex') {
        e.nextElementSibling.style.display = 'none'
    } else {
        e.nextElementSibling.style.display = 'flex'
    }
}