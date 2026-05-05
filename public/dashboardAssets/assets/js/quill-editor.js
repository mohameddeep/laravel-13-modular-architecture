/**
 * Quill Editor Helper
 * Initialize Quill editors and handle form submissions
 */

class QuillEditorManager {
    constructor() {
        this.editors = new Map();
        this.toolbarConfig = [
            [{ 'header': [1, 2, 3, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'color': [] }, { 'background': [] }],
            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
            [{ 'align': [] }],
            ['link'],
            ['clean']
        ];
    }

    /**
     * Initialize a Quill editor
     */
    init(selector, options = {}) {
        const container = document.querySelector(selector);
        if (!container) {
            console.warn(`Quill editor container not found: ${selector}`);
            return null;
        }

        const config = {
            theme: 'snow',
            modules: {
                toolbar: options.toolbar || this.toolbarConfig
            },
            placeholder: options.placeholder || ''
        };

        const editor = new Quill(selector, config);
        this.editors.set(selector, editor);
        return editor;
    }

    /**
     * Initialize all Quill editors on the page
     */
    initAll() {
        const editorElements = document.querySelectorAll('.quill-editor');
        editorElements.forEach(el => {
            const id = '#' + el.id;
            const placeholder = el.getAttribute('data-placeholder') || '';
            this.init(id, { placeholder });
        });
    }

    /**
     * Sync all editor contents to their hidden textareas
     */
    syncAll() {
        this.editors.forEach((editor, selector) => {
            const container = document.querySelector(selector);
            if (container) {
                const textarea = container.nextElementSibling;
                if (textarea && textarea.tagName === 'TEXTAREA') {
                    textarea.value = editor.root.innerHTML;
                }
            }
        });
    }

    /**
     * Get editor instance by selector
     */
    get(selector) {
        return this.editors.get(selector);
    }

    /**
     * Attach to form submission
     */
    attachToForm(formSelector) {
        const form = document.querySelector(formSelector);
        if (form) {
            form.addEventListener('submit', () => {
                this.syncAll();
            });
        }
    }
}

// Initialize on document ready
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Quill !== 'undefined') {
        window.quillManager = new QuillEditorManager();
        window.quillManager.initAll();
        window.quillManager.attachToForm('form');
    }
});
