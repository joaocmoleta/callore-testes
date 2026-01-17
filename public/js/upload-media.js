async function chooseFile(e) {
    e.setAttribute('disabled', 'disabled')

    if (e.files.length) {
        let f = document.createElement("form");
        f.setAttribute('enctype', 'multipart/form-data')

        let formData = new FormData(f)
        formData.append('file', e.files[0])
        formData.append('file_name', document.getElementById(e.getAttribute('nameFile')).value)
        formData.append('crop_type', crop_type)
        formData.append('crop_width', crop_width)
        formData.append('crop_height', crop_height)

        await fetch(upload_route, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.getElementsByName('_token')[0].value,
            },
            body: formData
        })
            .then((response) => response.json())
            .then(response => {
                console.log(response)
                console.log(response[0].file[0])
                console.log(response[0].file)

                e.removeAttribute('disabled')

                if (response[0].file.path) {
                    arquivo_uploaded.value = response[0].file.path
                }

                e.value = ''

                let msg_exist = document.getElementsByClassName('msg-ajax-upload')
                if (msg_exist[0]) {
                    msg_exist[0].remove()
                }

                let msg = document.createElement('div')
                msg.classList.add('msg-ajax-upload')
                msg.append(response[0].file[0])
                e.parentElement.append(msg)
            })
    }
}

function selectCropType(e, type) {
    let buttons = e.parentElement.children
    for (i = 0; i < buttons.length; i++) {
        buttons[i].classList.remove('selected')
    }
    e.classList.add('selected')
    crop_type = type
}

function finishUpload() {
    if (target_upload.value == '') {
        let msg_exist = document.getElementsByClassName('msg-ajax-upload')
        if (msg_exist[0]) {
            msg_exist[0].remove()
        }

        let msg = document.createElement('div')
        msg.classList.add('msg-ajax-upload')
        msg.append('Selecione um arquivo.')
        target_upload.parentElement.append(msg)
        return
    }

    target_upload.parentElement.parentElement.parentElement.style.display = 'none'

    // Disparar função definida nas configs
    if (afterFinishUpload) {
        afterFinishUpload()
    }
}

function removeImage(e, item) {
    let tg = document.getElementsByName('images')[0]

    let lis = []
    if (tg.value != '') {
        lis = JSON.parse(tg.value)
    }

    let new_lis = []
    for (i = 0; i < lis.length; i++) {
        if (item != i) {
            new_lis.push(lis[i])
        }
    }

    tg.value = JSON.stringify(new_lis)

    e.parentElement.remove()
}

function createImageObj(id) {

    let imageObj = document.createElement('div')
    imageObj.classList.add('add-image-item')
    let imageObjImg = document.createElement('img')
    imageObjImg.setAttribute('src', target_upload.value)
    imageObj.append(imageObjImg)
    let btClose = document.createElement('span')
    btClose.classList.add('add-image-bt-remove')
    btClose.setAttribute('onclick', 'removeImage(this, ' + id + ')')
    btClose.innerHTML = '<svg width="24" height="24" version="1.1" viewBox="0 0 24 24" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"><path d="m1.8662 1.8662 20.268 20.268" style="fill:none;stroke-linecap:round;stroke-linejoin:round;stroke-width:1.989;stroke:#000"></path><path d="m22.134 1.8662-20.268 20.268" style="fill:none;stroke-linecap:round;stroke-linejoin:round;stroke-width:1.989;stroke:#000"></path></svg>'
    let imageBox = document.getElementsByClassName('box-add-image-content')[0]
    imageObj.append(btClose)
    imageBox.append(imageObj)
}