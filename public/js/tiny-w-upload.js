
let colorMap = [
  "04f876",
  "Principal",
  "000000",
  "Black",
  "993300",
  "Burnt orange",
  "333300",
  "Dark olive",
  "003300",
  "Dark green",
  "003366",
  "Dark azure",
  "000080",
  "Navy Blue",
  "333399",
  "Indigo",
  "333333",
  "Very dark gray",
  "800000",
  "Maroon",
  "FF6600",
  "Orange",
  "808000",
  "Olive",
  "008000",
  "Green",
  "008080",
  "Teal",
  "0000FF",
  "Blue",
  "666699",
  "Grayish blue",
  "808080",
  "Gray",
  "FF0000",
  "Red",
  "FF9900",
  "Amber",
  "99CC00",
  "Yellow green",
  "339966",
  "Sea green",
  "33CCCC",
  "Turquoise",
  "3366FF",
  "Royal blue",
  "800080",
  "Purple",
  "999999",
  "Medium gray",
  "FF00FF",
  "Magenta",
  "FFCC00",
  "Gold",
  "FFFF00",
  "Yellow",
  "00FF00",
  "Lime",
  "00FFFF",
  "Aqua",
  "00CCFF",
  "Sky blue",
  "993366",
  "Red violet",
  "FFFFFF",
  "White",
  "FF99CC",
  "Pink",
  "FFCC99",
  "Peach",
  "FFFF99",
  "Light yellow",
  "CCFFCC",
  "Pale green",
  "CCFFFF",
  "Pale cyan",
  "99CCFF",
  "Light sky blue",
  "CC99FF",
  "Plum",
];

let styleFormats = [
  {
    title: "Títulos",
    items: [
      { title: "Nível 2", block: "h2" },
      { title: "Nível 3", block: "h3" },
      { title: "Nível 4", block: "h4" },
      { title: "Nível 5", block: "h5" },
      { title: "Nível 6", block: "h6" },
    ],
  },

  {
    title: "Formatações em linha",
    items: [
      { title: "Negrito", inline: "b", icon: "bold" },
      { title: "Itálico", inline: "i", icon: "italic" },
      {
        title: "Sublinhado",
        inline: "span",
        styles: { textDecoration: "underline" },
        icon: "underline",
      },
      {
        title: "Trachado",
        inline: "span",
        styles: { textDecoration: "line-through" },
        icon: "strikethrough",
      },
      { title: "Escrito em cima", inline: "sup", icon: "superscript" },
      { title: "Escrito em baixo", inline: "sub", icon: "subscript" },
      { title: "Máquina", inline: "code", icon: "code" },
    ],
  },

  {
    title: "Formatações em bloco",
    items: [
      { title: "Paragráfo", block: "p" },
      { title: "Citação", block: "blockquote" },
      { title: "Bloco", block: "div" },
      { title: "Pre", block: "pre" },
    ],
  },
];

let uploadProc = document.getElementById("uploadTiny").value;
tinymce.init({
  selector: "#body",
  language: "pt_BR",
  menubar: false,
  statusbar: false,
  toolbar: false,
  paste_as_text: true,
  image_caption: true,
  min_height: 300,
  plugins: "image paste link code preview media textcolor table lists fullscreen autoresize",
  toolbar:
    "undo redo styleselect | bold italic underline strikethrough blockquote | forecolor backcolor | numlist bullist | alignleft aligncenter alignright alignjustify bullist numlist outdent indent | image media | link | table tabledelete | tableprops tablerowprops tablecellprops | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol | fullscreen code removeformat",
  advcode_inline: true,
  textcolor_map: colorMap,
  style_formats: styleFormats,
  images_upload_url: uploadProc,
  images_upload_handler: function (blobInfo, success, failure) {
    let csrf = document.getElementsByName('_token')[0].value;
    let xhttp = new XMLHttpRequest();
    let formData = new FormData();
    formData.append("file", blobInfo.blob(), blobInfo.filename());

    xhttp.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        let res = JSON.parse(this.responseText);

        success(res.public_path + '/' + res.original_filename);
      }
    };
    xhttp.open("POST", uploadProc, true);
    xhttp.setRequestHeader('X-CSRF-Token', csrf);
    xhttp.send(formData);
  },
  init_instance_callback: function (editor) {
    editor.on('ExecCommand', function (e) {
      console.log('The ' + e.command + ' command was fired.');
    });
  },
  setup: function (ed) {
    ed.on('blur', function (e) {
      let trg = document.getElementsByName(ed.targetElm.getAttribute('id'))[0];
      trg.value = tinyMCE.activeEditor.getContent();
    });
  }
});

tinymce.init({
  selector: "#abstract",
  language: "pt_BR",
  menubar: false,
  statusbar: false,
  toolbar: false,
  paste_as_text: true,
  min_height: 300,
  plugins: "paste link code preview textcolor fullscreen autoresize",
  toolbar:
    "undo redo styleselect | bold italic underline strikethrough blockquote | forecolor backcolor | alignleft aligncenter alignright alignjustify bullist numlist outdent indent | link | fullscreen code",
  textcolor_map: colorMap,
  style_formats: styleFormats,
  setup: function (ed) {
    ed.on('blur', function (e) {
      let trg = document.getElementsByName(ed.targetElm.getAttribute('id'))[0];
      trg.value = tinyMCE.activeEditor.getContent();
    });
  }
});
