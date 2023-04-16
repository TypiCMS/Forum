var editorConfig = {
    toolbar: [
        { items: ['Bold', 'Italic', 'Subscript', 'Superscript'] },
        { items: ['NumberedList', 'BulletedList', 'Outdent', 'Indent'] },
        { items: ['RemoveFormat'] },
        { items: ['Source'] },
    ],
    entities: false,
    height: 240,
    contentsCss: ['/css/public.css', '/components/ckeditor4/custom.css'],
    language: document.documentElement.getAttribute('lang'),
    stylesSet: [],
    extraPlugins: ['codemirror'],
    removePlugins: 'elementspath',
    codemirror: {
        theme: 'twilight',
    },
};
var editors = document.querySelectorAll('.ckeditor-forum');
for (var i = 0; i < editors.length; ++i) {
    CKEDITOR.replace(editors[i].id, editorConfig);
}
