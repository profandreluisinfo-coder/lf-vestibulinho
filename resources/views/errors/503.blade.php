<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manutenção em Andamento</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #7e22ce 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem 0;
            position: relative;
        }

        /* Partículas animadas de fundo */
        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            animation: float 15s infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) translateX(0);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh) translateX(100px);
                opacity: 0;
            }
        }

        .container {
            text-align: center;
            padding: 3rem;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 90%;
            position: relative;
            z-index: 10;
            animation: slideIn 0.8s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .icon-container {
            margin-bottom: 2rem;
            position: relative;
        }

        .gear {
            width: 80px;
            height: 80px;
            margin: 0 auto;
            position: relative;
            animation: rotate 4s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .gear svg {
            width: 100%;
            height: 100%;
            fill: #fff;
            filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.5));
        }

        h1 {
            font-size: 2.8rem;
            color: #fff;
            margin-bottom: 1rem;
            font-weight: 700;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.02);
            }
        }

        .subtitle {
            font-size: 1.3rem;
            color: rgba(255, 255, 255, 0.95);
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .info-box {
            background: rgba(255, 255, 255, 0.15);
            padding: 1.5rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.25);
        }

        .info-box p {
            color: #fff;
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .info-box p:last-child {
            margin-bottom: 0;
        }

        .progress-bar {
            width: 100%;
            height: 6px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            overflow: hidden;
            margin-top: 1rem;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #60a5fa, #a78bfa, #60a5fa);
            background-size: 200% 100%;
            animation: loading 2s linear infinite;
            border-radius: 10px;
        }

        @keyframes loading {
            0% {
                background-position: 200% 0;
            }
            100% {
                background-position: -200% 0;
            }
        }

        .contact-info {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .contact-info a {
            color: #fff;
            text-decoration: none;
            font-size: 1rem;
            padding: 0.8rem 1.5rem;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 25px;
            display: inline-block;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .contact-info a:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .status {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            color: #fff;
        }

        .status-dot {
            width: 10px;
            height: 10px;
            background: #fbbf24;
            border-radius: 50%;
            animation: blink 1.5s ease-in-out infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        @media (max-width: 768px) {
            .container {
                padding: 2rem 1.5rem;
            }
            
            h1 {
                font-size: 2rem;
            }
            
            .subtitle {
                font-size: 1.1rem;
            }
            
            .gear {
                width: 60px;
                height: 60px;
            }
        }
    </style>
</head>
<body>
    <div class="particles" id="particles"></div>
    
    <div class="container">
        <div class="icon-container">
            <div class="gear">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 15.5C10.0670034,15.5 8.5,13.9329966 8.5,12 8.5,10.0670034 10.0670034,8.5 12,8.5 13.9329966,8.5 15.5,10.0670034 15.5,12 15.5,13.9329966 13.9329966,15.5 12,15.5 Z M19.4284371,12.9819452 L21.4999989,12.9819452C21.4999989,13.6396646 21.4363077,14.2825252 21.3143958,14.9044118L19.2640748,14.5177617C19.1527426,15.2707095 18.9320121,15.9926788 18.6177617,16.6669415L20.2941176,17.8731984C19.8899696,18.5951677 19.4041627,19.2640748 18.8497158,19.8673267L17.1827411,18.6897152C16.6069061,19.1867572 15.9561739,19.5981982 15.2486631,19.9054054L15.6353132,21.9557264C15.0134267,22.0776383 14.370566,22.1413296 13.7128466,22.1413296L13.7128466,20.0697678C13.0041627,20.0697678 12.3137783,19.963071 11.6606061,19.7647059L11.064489,21.7555556C10.4273559,21.6053299 9.81235697,21.3942277 9.22508651,21.1283938L9.82120364,19.1375441C9.20895522,18.8428452 8.63583815,18.4772727 8.11367893,18.0526316L6.76607252,19.5405405C6.22162563,19.0434985 5.7237836,18.4890516 5.27944118,17.8873874L6.63797702,16.2969982C6.27240443,15.7566742 5.96519723,15.1704839 5.72479675,14.5481982L3.73394648,15.1443153C3.58372085,14.5071823 3.48009174,13.8501148 3.43255132,13.177617L5.39647059,12.7727273C5.35952669,12.5188976 5.34055132,12.2609497 5.34055132,12C5.34055132,11.7390503 5.35952669,11.4811024 5.39647059,11.2272727L3.43255132,10.822383C3.48009174,10.1498852 3.58372085,9.49281774 3.73394648,8.85568466L5.72479675,9.45180179C5.96519723,8.82951606 6.27240443,8.24332578 6.63797702,7.70300179L5.27944118,6.11261261C5.7237836,5.51094838 6.22162563,4.95650149 6.76607252,4.45945946L8.11367893,5.94736842C8.63583815,5.52272727 9.20895522,5.15715468 9.82120364,4.86245586L9.22508651,2.87160621C9.81235697,2.60577229 10.4273559,2.39467011 11.064489,2.24444444L11.6606061,4.23529412C12.3137783,4.03692897 13.0041627,3.93023166 13.7128466,3.93023166L13.7128466,1.85867036C14.370566,1.85867036 15.0134267,1.92236166 15.6353132,2.04427359L15.2486631,4.09459459C15.9561739,4.40180179 16.6069061,4.81324279 17.1827411,5.31028482L18.8497158,4.13267327C19.4041627,4.73592521 19.8899696,5.40483226 20.2941176,6.12680162L18.6177617,7.33305853C18.9320121,8.00732117 19.1527426,8.72929053 19.2640748,9.48223834L21.3143958,9.09558819C21.4363077,9.71747477 21.4999989,10.3603354 21.4999989,11.0180548L19.4284371,11.0180548C19.4284371,10.3603354 19.3647458,9.71747477 19.2428339,9.09558819L17.1925129,9.48223834C17.0799989,8.72752289 16.8592685,8.00461644 16.5438362,7.32849821L18.2201921,6.1222413C17.8172259,5.40194959 17.3302372,4.73415698 16.7758085,4.13090504L15.1100179,5.30852517C14.533008,4.81011623 13.8823175,4.39867524 13.1747192,4.09145504L13.561007,2.04113404C12.9391205,1.91922211 12.29626,1.85553081 11.638572,1.85553081L11.638572,3.92709211C10.9298881,3.92709211 10.2395037,4.03378942 9.58633151,4.23215457L8.99021439,2.24130492C8.35308131,2.3915306 7.7380824,2.60263278 7.15081193,2.86846671L7.74692906,4.85931636C7.13551878,5.15292725 6.5623199,5.51850802 6.04016068,5.94288738L4.69255427,4.45527842C4.14725551,4.95231227 3.6494134,5.50719641 3.20507099,6.10843339L4.56360683,7.69882257C4.19803424,8.23914656 3.89082704,8.82533684 3.65042656,9.4476226L1.65957629,8.85150546C1.50935066,9.48863854 1.40572155,10.1456882 1.35818113,10.8181681L3.32210041,11.2230578C3.28515651,11.4768876 3.26618113,11.7348354 3.26618113,11.9957851 3.26618113,12.2567348 3.28515651,12.5146827 3.32210041,12.7685124L1.35818113,13.173402C1.40572155,13.845882 1.50935066,14.5029316 1.65957629,15.1400647L3.65042656,14.5439475C3.89082704,15.1662333 4.19803424,15.7524236 4.56360683,16.2927476L3.20507099,17.883137C3.6494134,18.4847736 4.14725551,19.0392579 4.69255427,19.5362917L6.04016068,18.0483828C6.5623199,18.4730222 7.13551878,18.8385929 7.74692906,19.1332918L7.15081193,21.1241055C7.7380824,21.3899394 8.35308131,21.6010416 8.99021439,21.7512673L9.58633151,19.7604176C10.2395037,19.9587827 10.9298881,20.0654801 11.638572,20.0654801L11.638572,22.137041C12.29626,22.137041 12.9391205,22.0733497 13.561007,21.9514378L13.1747192,19.9010171C13.8823175,19.5937969 14.533008,19.1823559 15.1100179,18.6839469L16.7758085,19.8615584C17.3302372,19.2583065 17.8172259,18.5905139 18.2201921,17.8702222L16.5438362,16.6639653C16.8592685,15.9896471 17.0799989,15.2680406 17.1925129,14.5150928L19.2428339,14.901743C19.3647458,14.2798564 19.4284371,13.6369958 19.4284371,12.9792764"/>
                </svg>
            </div>
        </div>

        <h1>Estamos Melhorando!</h1>
        
        <p class="subtitle">
            Nosso sistema está passando por uma atualização importante para oferecer uma experiência ainda melhor.
        </p>

        <div class="info-box">
            <p class="status">
                <span class="status-dot"></span>
                Manutenção em Andamento
            </p>
            <div class="progress-bar">
                <div class="progress-fill"></div>
            </div>
        </div>

        <div class="info-box">
            <p><strong>🕐 Previsão de retorno:</strong> Em breve</p>
            <p><strong>⚡ Motivo:</strong> Melhorias e atualizações do sistema</p>
        </div>

        <div class="contact-info">
            <p style="color: rgba(255, 255, 255, 0.9); margin-bottom: 1rem;">
                Precisa de ajuda urgente?
            </p>
            <a href="mailto:emdrleandrofranceschini@educacaosumare.com.br">
                📧 Contate o Suporte
            </a>
        </div>
    </div>

    <script>
        // Gerar partículas animadas
        const particlesContainer = document.getElementById('particles');
        const particleCount = 50;

        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 15 + 's';
            particle.style.animationDuration = (Math.random() * 10 + 10) + 's';
            particlesContainer.appendChild(particle);
        }
    </script>
</body>
</html>