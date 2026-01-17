let toAddTag = document.getElementsByClassName('tags-to-add')[0];
let listTag = document.getElementsByClassName('tags-list')[0];
let searchTag = document.getElementById('searchTag');
let alreadyTag = document.getElementsByName('tags')[0];

/*
 * On load page fill list tags added
 */
startTags();
function startTags() {
    if (alreadyTag.value.length > 0) {
        let a = JSON.parse(alreadyTag.value);
        a.forEach(ele => {
            // listTag.appendChild(createItemAdded('div', 'item', ele.value, ele.title, 'removeTag'));
        })
    }
}
/*
 * Set first item from drop down how new category
 * Return false to prevent default action post
 */
function addFirstItemDDTag(e) {
    if (e.keyCode == 13) {
        let abs = toAddTag.firstChild.getAttribute('onclick');
        let val = abs.substring(
            abs.indexOf('"') + 1,
            abs.lastIndexOf('"')
        );
        let valId = abs.substring(
            abs.indexOf('(') + 1,
            abs.lastIndexOf(',')
        );
        if (valId == 'saveTag(') {
            saveTag(searchTag.value);
        } else {
            selectTag(valId, val);
        }
        e.preventDefault();
        return false;
    }
}
/*
 * Search tags by term and fill drop down list with bt actions add category
 */
function sTag(e) {
    if (e.value.length < 3) {
        return;
    }
    toAddTag.innerText = '';
    toAddTag.style.height = 'initial'
    fetch(backend + '/painel/tags/search/' + e.value)
        .then(res => res.json())
        .then(json => {
            if (json.length > 0) {
                json.forEach(item => {
                    toAddTag.appendChild(createItemToAdd('div', 'item', item.id, item.title, 'selectTag'));
                })
            }
            toAddTag.appendChild(createAddBt('div', 'item', 'Adicionar', 'saveTag', searchTag.value))
        });
}
/*
 * Add new category on DB, textarea field and visual list
 * @param val = string for title category
 * @data backend = link of backend point to add
 * @data csrf = code to security
 */
function saveTag(val) {
    fetch(backend + '/painel/tags/search/add', {
        method: "POST",
        body: JSON.stringify({ title: val }),
        headers: {
            "Content-type": "application/json; charset=UTF-8",
            "X-CSRF-TOKEN": csrf_p
        }
    })
        .then(res => res.json())
        .then(json => {
            if (json.title) {
                selectTag(json.id, json.title)
            } else {
                addMsgError(searchTag.parentElement, json[0].title)
                toAddTag.style.height = 0;
                searchTag.value = '';
            }
        })
}
/*
 * Update field with tags and append element on tags list
 * @param valId = id of category
 * @param val = value of category
 */
function selectTag(valId, val) {
    if (upTextarea(valId, val, alreadyTag)) {
        toAddTag.style.height = 0
        listTag.appendChild(createItemAdded('div', 'item', valId, val, 'removeTag'))
        searchTag.value = '';
    }
}
/*
 * Remove category of list and textarea field
 * @param e = element for remove of list
 * @param valId = id category for remove of textarea field
 */
function removeTag(e, valId) {
    e.parentElement.remove();
    dwTextarea(valId, alreadyTag);
}