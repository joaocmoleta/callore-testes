function search(e) {
    if (e.value.length < 3) {
        return;
    }

    let backend = document.getElementsByName('request_search')[0].value;
    let csrf_p = document.getElementsByName('_token')[0].value;

    fetch(backend, {
        method: "POST",
        body: JSON.stringify({ term: e.value }),
        headers: {
            "Content-type": "application/json; charset=UTF-8",
            "X-CSRF-TOKEN": csrf_p
        }
    })
        .then(res => res.json())
        .then(json => {
            let result = '';
            e.nextElementSibling.style.display = 'block';

            if (json.posts.length === 0) {
                e.nextElementSibling.innerHTML = 'Nenhum resultado encontrado para o termo';
                return;
            }
            e.nextElementSibling.innerHTML = '';
            json.posts.map(post => {
                let div = document.createElement('div');
                div.classList.add('result');
                let link = document.createElement('a');
                link.setAttribute('href', 'publicacoes/' + post.slug);
                link.appendChild(document.createTextNode(post.title));
                div.appendChild(link);
                e.nextElementSibling.appendChild(div);
            })
        });
}

function searchBlur(e) {
    e.nextElementSibling.style.display = 'none';
}

function openSearch(e) {
    if (e.nextElementSibling.getAttribute('style') == null) {
        e.nextElementSibling.style.width = 0;
    } else {
        e.nextElementSibling.removeAttribute('style');
    }
    e.nextElementSibling.children[2].focus();
}