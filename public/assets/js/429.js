// Caminho para o arquivo JSON da animação Lottie
var animationDataUrl = 'https://lottie.host/2fef3d6f-9896-4fa3-aa56-cb6b056f6992/SjzefAZ6OC.json';


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