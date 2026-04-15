<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ \App\Models\Setting::get('seo_title', 'Privacy Policy - MODU SHADE LLC') }}</title>
    <meta name="description" content="{{ \App\Models\Setting::get('seo_description', '') }}">
    <meta name="keywords" content="{{ \App\Models\Setting::get('seo_keywords', '') }}">

    {!! \App\Models\Setting::get('header_scripts') !!}

    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&family=Montserrat:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #d0fc00;
            --dark-bg: #111;
            --white: #ffffff;
            --text-main: #f5f5f5;
            --text-muted: #a0a0a0;
            --font-inter: 'Inter', sans-serif;
            --font-montserrat: 'Montserrat', sans-serif;
        }

        body {
            font-family: var(--font-inter);
            background-color: var(--dark-bg);
            color: var(--text-main);
            line-height: 1.6;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        h1 {
            font-family: var(--font-montserrat);
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 2rem;
            color: var(--white);
        }

        h2 {
            font-family: var(--font-montserrat);
            font-size: 1.5rem;
            font-weight: 600;
            margin-top: 2rem;
            margin-bottom: 1rem;
            color: var(--primary);
        }

        p {
            margin-bottom: 1.5rem;
            color: var(--text-main);
            opacity: 0.9;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            color: var(--primary);
            text-decoration: none;
            margin-bottom: 2rem;
            font-weight: 500;
            transition: opacity 0.2s;
        }

        .back-link:hover {
            opacity: 0.8;
        }

        .footer {
            margin-top: 4rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 2rem;
            text-align: center;
            font-size: 0.9rem;
            color: var(--text-muted);
        }
    </style>
</head>
<body>
    {!! \App\Models\Setting::get('body_scripts') !!}
    <div class="container">
        <a href="{{ route('home') }}" class="back-link">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Home
        </a>

        <h1>Privacy Policy</h1>
        
        <p>Welcome to Modu Shade. This Privacy Policy outlines our practices regarding the collection, use, and disclosure of personal information through our website, <a href="https://modu-shade.com/" class="text-primary hover:underline">https://modu-shade.com/</a>. By utilizing our Site, you agree to the terms outlined in this policy.</p>

        <h2>Information Collection and Use</h2>
        <p>While engaging with our Site, we may request certain personally identifiable information, such as your name ("Personal Information"). We utilize this information solely for the purpose of enhancing the user experience and improving our services.</p>

        <h2>Log Data</h2>
        <p>As with many websites, we collect Log Data, including your computer's IP address, browser type, page visits, and other statistical information. This data assists us in analyzing and enhancing the functionality of our Site.</p>

        <h2>Cookies</h2>
        <p>Cookies, which are small data files, may be sent to your browser to provide a more personalized experience. You can configure your browser to reject cookies, but please note that this may impact certain features of our Site.</p>

        <h2>Security</h2>
        <p>The security of your Personal Information is of utmost importance to us. While we employ industry-standard measures to protect your data, it's essential to acknowledge that no method of transmission over the Internet or electronic storage is entirely secure.</p>

        <h2>Changes to This Privacy Policy</h2>
        <p>This Privacy Policy is effective as of 17th Jan 2025 and may be subject to periodic updates. It is your responsibility to review this policy regularly. Any modifications will be effective immediately upon posting. If there are significant changes, we will notify you via the email address you have provided or through a prominent notice on our website.</p>

        <h2>Contact Us</h2>
        <p>For any inquiries or concerns regarding this Privacy Policy, please contact us at info@modu-shade.com.</p>

        <div class="footer">
            <p>&copy; Copyright 2026 MODU SHADE LLC - All Rights Reserved</p>
            <p class="text-xs opacity-50 mt-2">This site is not a part of the Facebook website or Facebook Inc. Additionally, This site is NOT endorsed by Facebook in any way. FACEBOOK is a trademark of FACEBOOK, Inc.</p>
        </div>
    </div>
    {!! \App\Models\Setting::get('footer_scripts') !!}
</body>
</html>
