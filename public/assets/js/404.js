// Caminho para o arquivo JSON da animação Lottie
var animationDataUrl = 'https://lottie.host/452bc374-aa79-4bba-a2c8-7b5b94281d56/fvpJL4vvCS.json';


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