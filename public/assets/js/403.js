// Caminho para o arquivo JSON da animação Lottie
var animationDataUrl = 'https://lottie.host/926d43d2-55a6-4cd7-8d88-49d02729b85a/QAi7ER3e7Z.json';


// Opções de configuração para a animação Lottie
var options = {
    container: document.getElementById('lottie'),
    renderer: 'svg', // renderiza a animação como SVG
    loop: true, // faz a animação loop
    autoplay: true, // inicia a animação automaticamente
    path: animationDataUrl // caminho para o arquivo JSON da animação
};

// Carrega a animação Lottie com as opções especificadas
var anim = lottie.loadAnimation(options);