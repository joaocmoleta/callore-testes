function addMedia(e, ind) {
    let target = document.getElementById(mc[ind].target);
    let legend = document.getElementById(mc[ind].img_leg);
    let cut_type = document.getElementById(mc[ind].cut_type);
    let act = document.getElementById(mc[ind].ac);
    let fileF = document.getElementById(mc[ind].file);
    let csrf = document.getElementsByName('_token')[0].value;
    
    let f = document.createElement("form");
    f.setAttribute('enctype', 'multipart/form-data')
    
    let formData = new FormData(f);
    formData.append('file', fileF.files[0], Blob);
    formData.append('name_img', legend.value);
    formData.append('cut_type', cut_type.value);
    formData.append('width', mc[ind].width);
    formData.append('height', mc[ind].height);

    let xhttp = new XMLHttpRequest();

    cleanMsgs();

    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            let res = JSON.parse(this.responseText);

            if (res.status) {
                e.parentElement.appendChild(createItem('div', 'msg-success', res.status));
                target.value = res.public_path + '/' + res.filename;
                document.getElementById(mc[ind].img_preview).setAttribute('src', '/file/' + res.filename);
                e.parentElement.parentElement.style.display = 'none';
                document.getElementById('preview_img_on_up_' + mc[ind].prefix).setAttribute('src', '/file/' + res.filename);
                if(mc[ind].debug) {
                    console.log(res)
                }
            } else {
                e.parentElement.appendChild(createItem('div', 'msg-error', res[0].file[0]));
            }
        }
    };
    xhttp.open("POST", act.value, true);
    xhttp.setRequestHeader('X-CSRF-Token', csrf);
    xhttp.send(formData);
};

function selectType(e, val) {
    e.parentElement.classList.add('selected');
    if (e.parentElement.previousElementSibling) {
        e.parentElement.previousElementSibling.classList.remove('selected');
    }
    if (e.parentElement.nextElementSibling) {
        e.parentElement.nextElementSibling.classList.remove('selected');
    }
    e.parentElement.parentElement.lastElementChild.value = val
}

function selectFile(e) {
    e.previousElementSibling.click();
}

function selectedFile(e, a, b) {
    addMedia(e, a, b)
}

function cleanMsgs() {
    let oldSucMsgs = document.getElementsByClassName('msg-success');
    let oldErrMsgs = document.getElementsByClassName('msg-error');

    if (oldSucMsgs.length > 0) {
        for (i = 0; i < oldSucMsgs.length; i++) {
            oldSucMsgs[i].remove();
        }
    }

    if (oldErrMsgs.length > 0) {
        for (i = 0; i < oldErrMsgs.length; i++) {
            oldErrMsgs[i].remove();
        }
    }
}

/*
 * Create element
 * @param type = type of elemnt ex: div, span
 * @param className = name of element class
 * @param value = value of element
 * Return element
 */
function createItem(type, className, value) {
    let it = document.createElement(type);
    it.classList.add(className);
    it.appendChild(document.createTextNode(value))
    return it
}