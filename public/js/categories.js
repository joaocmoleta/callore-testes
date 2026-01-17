let toAddCat = document.getElementsByClassName('categories-to-add')[0];
let listCat = document.getElementsByClassName('categories-list')[0];
let searchCat = document.getElementById('searchCategory');
let alreadyCat = document.getElementsByName('categories')[0];

/*
* On load page fill list categories added
*/
// startCategories();
// function startCategories() {
//     if (alreadyCat.value.length > 0) {
//         let a = JSON.parse(alreadyCat.value);
//         a.forEach(ele => {
//             listCat.appendChild(createItemAdded('div', 'item', ele.value, ele.title, 'removeCat'));
//         })
//     }
// }
/*
 * Set first item from drop down how new category
 * Return false to prevent default action post
 */
function addFirstItemDDCat(e) {
    if (e.keyCode == 13) {
        let abs = toAddCat.firstChild.getAttribute('onclick');
        let val = abs.substring(
            abs.indexOf('"') + 1,
            abs.lastIndexOf('"')
        );
        let valId = abs.substring(
            abs.indexOf('(') + 1,
            abs.lastIndexOf(',')
        );
        if (valId == 'saveCategory(') {
            saveCategory(searchCat.value);
        } else {
            selectCategory(valId, val);
        }
        e.preventDefault();
        return false;
    }
}
/*
 * Search categories by term and fill drop down list with bt actions add category
 */
function sCategory(e) {
    if (e.value.length < 3) {
        return;
    }
    toAddCat.innerText = '';
    toAddCat.style.height = 'initial'
    fetch(backend + '/painel/categorias/search/' + e.value, {
        mode:"no-cors",
        headers: {  
            "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"  
        },
    })
        .then(res => res.json())
        .then(json => {
            if (json.length > 0) {
                json.forEach(item => {
                    toAddCat.appendChild(createItemToAdd('div', 'item', item.id, item.title, 'selectCategory'));
                })
            }
            // toAddCat.appendChild(createAddBt('div', 'item', 'Adicionar', 'saveCategory', searchCat.value))
        });
}
/*
 * Add new category on DB, textarea field and visual list
 * @param val = string for title category
 * @data backend = link of backend point to add
 * @data csrf = code to security
 */
function saveCategory(val) {
    fetch(backend + '/painel/categorias/search/add', {
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
                selectCategory(json.id, json.title)
            } else {
                addMsgError(searchCat.parentElement, json[0].title)
                toAddCat.style.height = 0;
                searchCat.value = '';
            }
        })
}
/*
 * Update field with categories and append element on categories list
 * @param valId = id of category
 * @param val = value of category
 */
function selectCategory(valId, val) {
    if (upTextarea(valId, val, alreadyCat)) {
        toAddCat.style.height = 0
        listCat.appendChild(createItemAdded('div', 'item', valId, val, 'removeCat'))
        searchCat.value = '';
    }
}
/*
 * Remove category of list and textarea field
 * @param e = element for remove of list
 * @param valId = id category for remove of textarea field
 */
function removeCat(e, valId) {
    e.parentElement.remove();
    dwTextarea(valId, alreadyCat);
}