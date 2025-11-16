document.addEventListener('DOMContentLoaded', function() {
    // Seleciona os elementos relevantes
    const radioNovo = document.getElementById('radio1');
    const radioAntigo = document.getElementById('radio2');
    const divNovoModelo = document.getElementById('newCertificateModel');
    const divsAntigoModelo = document.querySelectorAll('.oldCertificateModel');

    // Função para atualizar a exibição dos campos
    function atualizarCamposCertidao() {
        if (radioNovo.checked) {
            // Mostra campos do modelo novo e oculta os do antigo
            divNovoModelo.classList.remove('d-none');
            divsAntigoModelo.forEach(div => div.classList.add('d-none'));
            
            // Desabilita os campos do modelo antigo para não serem enviados
            document.getElementById('fls').disabled = true;
            document.getElementById('book').disabled = true;
            document.getElementById('old_number').disabled = true;
            document.getElementById('municipality').disabled = true;
            
            // Habilita o campo do modelo novo
            document.getElementById('new_number').disabled = false;
        } else if (radioAntigo.checked) {
            // Mostra campos do modelo antigo e oculta os do novo
            divNovoModelo.classList.add('d-none');
            divsAntigoModelo.forEach(div => div.classList.remove('d-none'));
            
            // Desabilita o campo do modelo novo para não ser enviado
            document.getElementById('new_number').disabled = true;
            
            // Habilita os campos do modelo antigo
            document.getElementById('fls').disabled = false;
            document.getElementById('book').disabled = false;
            document.getElementById('old_number').disabled = false;
            document.getElementById('municipality').disabled = false;
        }
    }

    // Adiciona listeners aos radio buttons
    radioNovo.addEventListener('change', atualizarCamposCertidao);
    radioAntigo.addEventListener('change', atualizarCamposCertidao);

    // Executa a função ao carregar a página para configurar o estado inicial
    // atualizarCamposCertidao();
    // Executa a verificação com pequeno atraso
    setTimeout(atualizarCamposCertidao, 10);
});