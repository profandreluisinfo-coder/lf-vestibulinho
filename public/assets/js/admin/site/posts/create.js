$(document).ready(function () {
    // Inicializar Summernote
    $('.summernote').summernote({
        height: 200,
        lang: 'pt-BR',
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ],
        placeholder: 'Digite a resposta aqui...',
        dialogsInBody: true,
        callbacks: {
            onInit: function () {
                // Garantir que o editor funcione dentro do modal
                $('.note-editor').css('z-index', '9999');
            },
            onChange: function (contents) {
                // Sincronizar automaticamente quando o conteúdo muda
                $(this).val(contents);
                // Disparar evento de validação
                $(this).valid();
            }
        }
    });
});

// ── Preview de imagem de capa ─────────────────────────────────────────
const imageInput    = document.getElementById('image');
const previewWrap   = document.getElementById('image-preview-wrap');
const previewImg    = document.getElementById('image-preview');
const removeBtn     = document.getElementById('remove-image');

imageInput.addEventListener('change', function () {
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            previewImg.src       = e.target.result;
            previewWrap.style.display = 'block';
        };
        reader.readAsDataURL(this.files[0]);
    }
});

removeBtn.addEventListener('click', function () {
    imageInput.value          = '';
    previewImg.src            = '';
    previewWrap.style.display = 'none';
});

// ── Drop zone de anexos ───────────────────────────────────────────────
const dropZone    = document.getElementById('drop-zone');
const fileInput   = document.getElementById('attachments');
const attList     = document.getElementById('attachment-list');
let   selectedFiles = [];

dropZone.addEventListener('click', () => fileInput.click());

dropZone.addEventListener('dragover', e => {
    e.preventDefault();
    dropZone.classList.add('dragover');
});

dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));

dropZone.addEventListener('drop', e => {
    e.preventDefault();
    dropZone.classList.remove('dragover');
    addFiles(Array.from(e.dataTransfer.files));
});

fileInput.addEventListener('change', function () {
    addFiles(Array.from(this.files));
});

function addFiles(files) {
    files.forEach(f => {
        if (!selectedFiles.find(sf => sf.name === f.name && sf.size === f.size)) {
            selectedFiles.push(f);
        }
    });
    syncFileInput();
    renderAttachmentList();
}

function removeFile(index) {
    selectedFiles.splice(index, 1);
    syncFileInput();
    renderAttachmentList();
}

function syncFileInput() {
    const dt = new DataTransfer();
    selectedFiles.forEach(f => dt.items.add(f));
    fileInput.files = dt.files;
}

function renderAttachmentList() {
    attList.innerHTML = '';
    selectedFiles.forEach((f, i) => {
        const ext  = f.name.split('.').pop().toUpperCase();
        const size = f.size < 1024 * 1024
            ? (f.size / 1024).toFixed(1) + ' KB'
            : (f.size / 1024 / 1024).toFixed(1) + ' MB';

        const div = document.createElement('div');
        div.className = 'attachment-item';
        div.innerHTML = `
            <i class="bi bi-file-earmark-text"></i>
            <span class="att-name" title="${f.name}">${f.name}</span>
            <span class="att-size">${ext} · ${size}</span>
            <button type="button" class="att-remove" onclick="removeFile(${i})" title="Remover">
                <i class="bi bi-x-lg"></i>
            </button>`;
        attList.appendChild(div);
    });
}

// ── Toggle data de publicação ─────────────────────────────────────────
const publishedSwitch  = document.getElementById('published');
const publishedAtWrap  = document.getElementById('published-at-wrap');

function togglePublishedAt() {
    publishedAtWrap.style.display = publishedSwitch.checked ? 'block' : 'none';
}

publishedSwitch.addEventListener('change', togglePublishedAt);
togglePublishedAt(); // estado inicial