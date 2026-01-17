let toAddAuthor = document.getElementsByClassName('author-to-add')[0];
let searchAuthor = document.getElementById('searchAuthor');
let authorName = document.getElementById('author_name');
let alreadyAuthor = document.getElementsByName('author')[0];

/*
 * On load page fill list tags added
 */
startAuthor();
function startAuthor() {
    if (alreadyTag.value.length > 0) {
        let a = JSON.parse(alreadyTag.value);
        a.forEach(ele => {
            listTag.appendChild(createItemAdded('div', 'item', ele.value, ele.title, 'removeTag'));
        })
    }
}

function sAuthor(e) {
    if (e.value.length < 3) {
        return;
    }
    toAddAuthor.innerText = '';
    toAddAuthor.style.height = 'initial'
    fetch(backend + '/painel/authors/search/' + e.value)
        .then(res => res.json())
        .then(json => {
            if (json.length > 0) {
                json.forEach(item => {
                    toAddAuthor.appendChild(createItemToAdd('div', 'item', item.id, item.title, 'selectAuthor'));
                })
            }
        });
}

function addAuthor(e) {
    if (e.keyCode == 13) {
        e.preventDefault();
        let abs = toAddAuthor.firstChild.getAttribute('onclick');
        let val = abs.substring(
            abs.indexOf('"') + 1,
            abs.lastIndexOf('"')
        );
        let valId = abs.substring(
            abs.indexOf('(') + 1,
            abs.lastIndexOf(',')
        );

        toAddAuthor.style.height = 0
        searchAuthor.value = '';
        authorName.value = val;
        alreadyAuthor.value = valId;
        return false;
    }
}
/*
 * Update field with tags and append element on tags list
 * @param valId = id of category
 * @param val = value of category
 */
function selectAuthor(valId, val) {
    toAddAuthor.style.height = 0
    searchAuthor.value = '';
    authorName.value = val;
    alreadyAuthor.value = valId;
}

