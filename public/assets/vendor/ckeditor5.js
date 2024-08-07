import {
    ClassicEditor,
    AccessibilityHelp,
    Alignment,
    AutoImage,
    AutoLink,
    Autosave,
    BalloonToolbar,
    BlockQuote,
    Bold,
    Code,
    CodeBlock,
    Essentials,
    FontBackgroundColor,
    FontColor,
    FontFamily,
    FontSize,
    GeneralHtmlSupport,
    HorizontalLine,
    ImageBlock,
    ImageInsert,
    ImageInsertViaUrl,
    ImageResize,
    ImageToolbar,
    ImageUpload,
    Indent,
    IndentBlock,
    Italic,
    Link,
    Paragraph,
    RemoveFormat,
    SelectAll,
    SimpleUploadAdapter,
    SourceEditing,
    SpecialCharacters,
    SpecialCharactersArrows,
    SpecialCharactersCurrency,
    SpecialCharactersEssentials,
    SpecialCharactersLatin,
    SpecialCharactersMathematical,
    SpecialCharactersText,
    Strikethrough,
    Style,
    Table,
    TableToolbar,
    Underline,
    Undo
} from 'ckeditor5';

// import 'ckeditor5/ckeditor5.css';
//
// import './style.css';

const editorConfig = {
    toolbar: {
        items: [
            'undo',
            'redo',
            '|',
            'sourceEditing',
            'selectAll',
            '|',
            'style',
            '|',
            'fontSize',
            'fontFamily',
            'fontColor',
            'fontBackgroundColor',
            '|',
            'bold',
            'italic',
            'underline',
            'strikethrough',
            'code',
            'removeFormat',
            '|',
            'specialCharacters',
            'horizontalLine',
            'link',
            'insertImage',
            'insertTable',
            'blockQuote',
            'codeBlock',
            '|',
            'alignment',
            '|',
            'outdent',
            'indent',
            '|',
            'accessibilityHelp'
        ],
        shouldNotGroupWhenFull: true
    },
    plugins: [
        AccessibilityHelp,
        Alignment,
        AutoImage,
        AutoLink,
        Autosave,
        BalloonToolbar,
        BlockQuote,
        Bold,
        Code,
        CodeBlock,
        Essentials,
        FontBackgroundColor,
        FontColor,
        FontFamily,
        FontSize,
        GeneralHtmlSupport,
        HorizontalLine,
        ImageBlock,
        ImageInsert,
        ImageInsertViaUrl,
        ImageResize,
        ImageToolbar,
        ImageUpload,
        Indent,
        IndentBlock,
        Italic,
        Link,
        Paragraph,
        RemoveFormat,
        SelectAll,
        SimpleUploadAdapter,
        SourceEditing,
        SpecialCharacters,
        SpecialCharactersArrows,
        SpecialCharactersCurrency,
        SpecialCharactersEssentials,
        SpecialCharactersLatin,
        SpecialCharactersMathematical,
        SpecialCharactersText,
        Strikethrough,
        Style,
        Table,
        TableToolbar,
        Underline,
        Undo
    ],
    balloonToolbar: ['bold', 'italic', '|', 'link', 'insertImage'],
    fontFamily: {
        supportAllValues: true
    },
    fontSize: {
        options: [10, 12, 14, 'default', 18, 20, 22],
        supportAllValues: true
    },
    htmlSupport: {
        allow: [
            {
                name: /^.*$/,
                styles: true,
                attributes: true,
                classes: true
            }
        ]
    },
    image: {
        toolbar: ['imageTextAlternative', '|', 'resizeImage']
    },
   link: {
        addTargetToExternalLinks: true,
        defaultProtocol: 'https://',
        decorators: {
            toggleDownloadable: {
                mode: 'manual',
                label: 'Downloadable',
                attributes: {
                    download: 'file'
                }
            }
        }
    },
    simpleUpload: {
        uploadUrl: '/upload',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    },
    placeholder: 'Type or paste your content here!',
    style: {
        definitions: [
            {
                name: 'Article category',
                element: 'h3',
                classes: ['category']
            },
            {
                name: 'Title',
                element: 'h2',
                classes: ['document-title']
            },
            {
                name: 'Subtitle',
                element: 'h3',
                classes: ['document-subtitle']
            },
            {
                name: 'Info box',
                element: 'p',
                classes: ['info-box']
            },
            {
                name: 'Side quote',
                element: 'blockquote',
                classes: ['side-quote']
            },
            {
                name: 'Marker',
                element: 'span',
                classes: ['marker']
            },
            {
                name: 'Spoiler',
                element: 'span',
                classes: ['spoiler']
            },
            {
                name: 'Code (dark)',
                element: 'pre',
                classes: ['fancy-code', 'fancy-code-dark']
            },
            {
                name: 'Code (bright)',
                element: 'pre',
                classes: ['fancy-code', 'fancy-code-bright']
            }
        ]
    },
    table: {
        contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
    }
};

ClassicEditor.create(document.querySelector('#editor'), editorConfig).then( editor => {
    window.ckeditor = editor;
} )
    .catch( err => console.error( err.stack ) );
// document.addEventListener('livewire:init', () => {
//     ClassicEditor.create(document.querySelector('#editor1'), editorConfig).then(editor => {
//         window.ckeditorEdit = editor;
//     })
//         .catch(err => console.error(err.stack));
// });
// ClassicEditor.create(document.querySelector('#editor1'), editorConfig).then( editor => {
//     window.ckeditorEdit = editor;
// } )
//     .catch( err => console.error( err.stack ) );


/*.then(editor => {
    editor.model.document.on('change:data', () => {
        const content = editor.getData();
        Livewire.dispatch('contentUpdated', content);
    });

    Livewire.on('contentUpdated', content => {
        editor.setData(content);
    });
    // editor.ui.view.editable.element.style.height = '500px';
})
    .catch(error => {
        console.error(error);
    });*/
