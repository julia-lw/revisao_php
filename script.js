document.addEventListener('DOMContentLoaded', () => {
    // DOM Navigation & Element Selection
    const formCadastro  = document.getElementById('form-cadastro');
    const formLogin     = document.getElementById('form-login');
    const formUpload    = document.getElementById('form-upload');
    const authSection   = document.getElementById('auth-section');
    const appSection    = document.getElementById('app-section');
    const galleryGrid   = document.getElementById('gallery-grid');
    const userStatus    = document.getElementById('user-status');
    const modal         = document.getElementById('upload-modal');
    const statsBox      = document.getElementById('stats-box');

    // DOM Events - Controle do Modal
    document.getElementById('btn-open-modal').addEventListener('click', () => modal.classList.remove('hidden'));
    document.getElementById('btn-close-modal').addEventListener('click', () => modal.classList.add('hidden'));

    // DOM Event: Submit Cadastro (AJAX POST via Fetch API)
    formCadastro.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(formCadastro);
        formData.append('acao', 'cadastrar');

        const response = await fetch('auth.php', { method: 'POST', body: formData });
        const res = await response.json();
        
        alert(res.mensagem);
        if (res.sucesso) formCadastro.reset();
    });

    // DOM Event: Submit Login (AJAX POST via Fetch API)
    formLogin.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(formLogin);
        formData.append('acao', 'login');

        const response = await fetch('auth.php', { method: 'POST', body: formData });
        const res = await response.json();

        if (res.sucesso) {
            authSection.classList.add('hidden');
            appSection.classList.remove('hidden');
            userStatus.innerHTML = `Usuário: <strong>${res.nome}</strong>`;
            carregarFotos();
        } else {
            alert(res.mensagem);
        }
    });

    // DOM Event: Submit Upload com Arq. via Fetch API
    formUpload.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(formUpload);

        const response = await fetch('upload.php', { method: 'POST', body: formData });
        const res = await response.json();

        alert(res.mensagem);
        if (res.sucesso) {
            formUpload.reset();
            modal.classList.add('hidden');
            carregarFotos();
        }
    });

    // Fetch API GET (com parâmetro na URL para $_GET)
    async function carregarFotos() {
        const response = await fetch('api.php?tipo=fotos');
        const res = await response.json();

        if (res.sucesso) {
            galleryGrid.innerHTML = '';
            
            // DOM Elements Manipulation
            res.fotos.forEach(foto => {
                const card = document.createElement('div');
                card.className = 'photo-card';
                card.innerHTML = `
                    <img src="${foto.caminho}" alt="${foto.titulo}">
                    <div class="photo-info">
                        <h4>${foto.titulo}</h4>
                        <small>Enviado por: <strong>${foto.autor}</strong></small>
                    </div>
                `;
                galleryGrid.appendChild(card);
            });
        }
    }

    // DOM Event: Clique para buscar agregadores (COUNT / GROUP BY)
    document.getElementById('btn-stats').addEventListener('click', async () => {
        const response = await fetch('api.php?tipo=estatisticas');
        const res = await response.json();

        if (res.sucesso) {
            statsBox.classList.toggle('hidden');
            statsBox.innerHTML = '<strong>Total de fotos por usuário (COUNT):</strong>' +
                res.estatisticas.map(s => `<p>• ${s.nome}: ${s.total_fotos} foto(s)</p>`).join('');
        }
    });
});