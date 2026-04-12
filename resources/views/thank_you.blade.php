<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thank You | MODU SHADE LLC</title>
  <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&family=Montserrat:wght@500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #d0fc00;
      --dark-bg: #202020;
      --black: #000000;
      --white: #ffffff;
      --font-montserrat: 'Montserrat', sans-serif;
       --font-inter: 'Inter', sans-serif;
    }
    body {
      font-family: var(--font-inter);
      background-color: var(--dark-bg);
      color: var(--white);
      margin: 0;
      padding: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      overflow: hidden;
    }
    .thank-you-card {
      background: rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 30px;
      padding: 60px 40px;
      text-align: center;
      max-width: 500px;
      width: 90%;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
      animation: fadeIn 0.8s ease-out;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .icon-wrapper {
      width: 80px;
      height: 80px;
      background: var(--primary);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 30px;
      box-shadow: 0 0 30px rgba(208, 252, 0, 0.3);
    }
    h1 {
      font-family: var(--font-montserrat);
      font-size: 36px;
      font-weight: 700;
      margin-bottom: 16px;
      color: var(--primary);
    }
    p {
      font-size: 18px;
      line-height: 1.6;
      color: rgba(255, 255, 255, 0.8);
      margin-bottom: 40px;
    }
    .back-btn {
      display: inline-block;
      background-color: var(--primary);
      color: var(--black);
      padding: 16px 40px;
      border-radius: 15px;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.3s ease;
      font-family: var(--font-montserrat);
    }
    .back-btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
      filter: brightness(1.1);
    }
    .bg-video {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      opacity: 0.2;
      z-index: -1;
    }
  </style>
</head>
<body>
  <!-- Background video -->
  <video autoPlay loop muted playsInline src="https://storage.googleapis.com/msgsndr/co4WnoqQGUmBzPYPO98e/media/69849f43e169f01b003f3e91.mp4" class="bg-video"></video>

  <div class="thank-you-card">
    <div class="icon-wrapper">
      <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
    </div>
    <h1>Thank You!</h1>
    <p>We have successfully received your request. Our specialist will contact you shortly to discuss your project.</p>
    <a href="{{ route('home') }}" class="back-btn">Back to Home</a>
  </div>
</body>
</html>
