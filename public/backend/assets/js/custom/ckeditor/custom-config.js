CKEDITOR.editorConfig = function (config) {
    config.removePlugins = 'image,table,link,blockquote,colorbutton,preview,iframe,uploadimage,templates,elementspath,div,pagebreak,magicline,selectall,smiley,specialchar,showblocks,showborders,tableselection,tabletools,editorplaceholder,newpage';

    config.plugins =
        'basicstyles,' +
        'clipboard,' +
        'contextmenu,' +
        'enterkey,' +
        'entities,' +
        'floatingspace,' +
        'format,' +  // ✔️ Biçimlendirme eklendi
        'justify,' +
        'removeformat,' +
        'toolbar,' +
        'undo,' +
        'sourcearea,' +
        'font,' +
        'wysiwygarea,' +
        'list';

    config.toolbar = [
        { name: 'document', items: ['Source'] },
        { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat'] },
        { name: 'paragraph', items: ['JustifyLeft', 'JustifyCenter', 'JustifyRight'] },
        { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'Undo', 'Redo'] },
        { name: 'styles', items: ['Font', 'FontSize', 'Format'] }, // ✔️ Biçimlendirme menüsü eklendi
        { name: 'lists', items: ['NumberedList', 'BulletedList'] }
    ];

    config.height = 300;
    config.language = 'tr';
};
