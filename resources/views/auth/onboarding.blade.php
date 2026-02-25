<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zenith 📦 Onboarding</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@300;400;600;700&family=Space+Grotesk:wght@300;400;500;600;700&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Jersey+15&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Jersey+15&family=Rubik+Vinyl&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

          body {
            color: white;
            font-family: 'Inter', sans-serif; /* Base font for body text */
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            position: relative;
        }

        /* Dynamic Background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(125deg,
                #FF0080,
                #FF8C00,
                #40E0D0,
                #6A5ACD,
                #FF0080
            );
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            opacity: 0.8;
            z-index: -2;
        }

        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: -1;
        }

        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        .background-effects {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }

        .particle {
            position: absolute;
            width: 3px;
            height: 3px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            filter: blur(1px);
            animation: float 8s infinite linear;
        }

        @keyframes float {
            0% {
                transform: translateY(100vh) scale(0);
                opacity: 0;
            }
            50% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh) scale(1);
                opacity: 0;
            }
        }

        .message {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            opacity: 0;
            visibility: hidden;
            transition: all 0.5s ease;
            text-align: center;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .message.active {
            opacity: 1;
            visibility: visible;
            animation: messageIn 5s ease-in-out forwards;
        }

        @keyframes messageIn {
            0% { opacity: 0; transform: translate(-50%, -30%); }
            10% { opacity: 1; transform: translate(-50%, -50%); }
            90% { opacity: 1; transform: translate(-50%, -50%); }
            100% { opacity: 0; transform: translate(-50%, -70%); }
        }

        h1, h2 {
            margin-bottom: 1rem;
            line-height: 1.4;
            text-align: center;
        }

        h1 {
             font-family: "Rubik Vinyl", serif;
              font-weight: 800;
              font-size: 3.2rem;
              font-style: normal;
            letter-spacing: -0.02em;
            background: linear-gradient(to right, #fff, #ffd700);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        h2 {
            font-family: 'Montserrat', sans-serif; /* Clean, professional font for subheadings */
            font-size: 1.8rem;
            font-weight: 300;
            letter-spacing: 0.01em;
            line-height: 1.6;
            text-align: center;
            opacity: 0.9;
        }


        .highlight {
            color: #ffd700;
            font-weight: 500;
        }
        .final-message {
             font-family: "Jersey 15", serif;
              font-weight: 800;
              font-style: normal;
            letter-spacing: -0.03em;
            background: linear-gradient(to right, #ffd700, #ff8c00);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: pulse 2s infinite;
            text-transform: uppercase;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .progress-bar {
            position: fixed;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            width: 200px;
            height: 4px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 2px;
            overflow: hidden;
        }

        .progress {
            width: 0%;
            height: 100%;
            background: linear-gradient(90deg, #ffd700, #ff8c00);
            border-radius: 2px;
            transition: width 0.5s linear;
        }

        .skip-button {
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            letter-spacing: 0.02em;
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }

        .skip-button:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="background-effects" id="particles"></div>

    <div class="content-container">
        <div class="message" id="welcome">
            <h1>Welcome, {{ Auth::user()->name }}! </h1>
            <h2>🎉Ahoy!!<br>Your journey to exceptional stock management begins here.</h2>
        </div>

        <div class="message" id="intro">
            <h1>Join the Elite</h1>
            <h2>Welcome to the <span class="highlight">Roman Stock Manager</span> family!</h2>
        </div>

        <div class="message" id="features">
            <h1>You are now a Champion !! </h1>
            <h2>😎<br>Experience smart automation and insights that transform your workflow.</h2>
        </div>

        <div class="message" id="support">
            <h1>We've Got Your Back</h1>
            <h2>24/7 support and continuous updates to keep you ahead.</h2>
        </div>

        <div class="message" id="start">
            <h1>Ready to Begin?</h1>
            <h2>🚀<br>Let's revolutionize ,<span class="highlight">You can`t Lose!!</span> </h2>
        </div>

        <div class="message" id="final">
            <h1 class="final-message">ROMAN STOCK MANAGER <br> Rsm</h1>
            <h2>Where Excellence Meets Innovation</h2>
        </div>
    </div>

    <div class="progress-bar">
        <div class="progress" id="progress"></div>
    </div>

    <button class="skip-button" onclick="skipToEnd()">Skip Intro</button>

    <script>
        // Create floating particles
        function createParticles() {
            const container = document.getElementById('particles');
            for (let i = 0; i < 50; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = `${Math.random() * 100}%`;
                particle.style.animationDelay = `${Math.random() * 8}s`;
                container.appendChild(particle);
            }
        }

        // Sequence management
        const messages = ['welcome', 'intro', 'features', 'support', 'start', 'final'];
        let currentIndex = 0;
        const duration = 5000; // 5 seconds per message

        function showMessage(index) {
            messages.forEach(id => {
                document.getElementById(id).classList.remove('active');
            });

            if (index < messages.length) {
                document.getElementById(messages[index]).classList.add('active');
                const progress = ((index + 1) / messages.length) * 100;
                document.getElementById('progress').style.width = `${progress}%`;
            }
        }

        function startSequence() {
            showMessage(0);

            const interval = setInterval(() => {
                currentIndex++;
                if (currentIndex >= messages.length) {
                    clearInterval(interval);
                    setTimeout(() => {
                        window.location.href = "{{ route('dashboard') }}";
                    }, duration);
                } else {
                    showMessage(currentIndex);
                }
            }, duration);
        }

        function skipToEnd() {
            currentIndex = messages.length - 1;
            showMessage(currentIndex);
            setTimeout(() => {
                window.location.href = "{{ route('dashboard') }}";
            }, 2000);
        }

        // Initialize
        createParticles();
        startSequence();
    </script>
</body>
</html>
