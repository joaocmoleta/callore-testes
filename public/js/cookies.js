function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new window.Date();
        date.setTime(date.setTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

function cookies_accept(e) {
    e.parentElement.style.display = 'none';
    setCookie('accept_cookie', true);
    console.log(getCookie('accept_cookie'));
}

function banner_pop_up(e) {
    e.parentElement.parentElement.style.display = 'none';
    setCookie('banner_pop_up', true);
    console.log(getCookie('banner_pop_up'));
}

document.addEventListener('DOMContentLoaded', (event) => {
    if(getCookie('accept_cookie')) {
        let banner_accept_cookie = document.getElementById('cookies-accept')
        banner_accept_cookie.style.display = 'none'
    }
});

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function eraseCookie(name) {
    document.cookie = name + '=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}