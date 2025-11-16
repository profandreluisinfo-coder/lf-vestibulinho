// Caminho para o arquivo JSON da animação Lottie
var animationDataUrl = 'https://lottie.host/74bd75bf-9a08-499b-aa47-e336b86623b2/Uvwj4hh3iD.json';


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