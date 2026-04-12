<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MODU SHADE LLC</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="At MODU SHADE LLC, we specialize in high-end custom window treatments and smart shading solutions that seamlessly blend design, functionality, and craftsmanship elevating living spaces with precision-crafted results.">
  <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&family=Montserrat:wght@500;600;700&family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      /* Theme Colors */
      --primary: #d0fc00;
      --primary-alt: #ebc400;
      --secondary: #14AB0A;
      --dark-bg: #202020;
      --darker-bg: #111;
      --black: #000000;
      --white: #ffffff;
      
      /* Typography */
      --text-main: #1c1c1c;
      --text-muted: #6a6a6a;
      --text-light-muted: #555;
      
      /* Form UI */
      --border-color: #ccc;
      --border-active: #a9b3c6;
      --btn-bg: #444;

      /* Fonts */
      --font-montserrat: 'Montserrat', sans-serif;
      --font-inter: 'Inter', sans-serif;
      --font-open: 'Open Sans', sans-serif;
    }

    body {
      font-family: var(--font-inter);
      background-color: var(--white);
      margin: 0;
      padding: 0;
    }

    /* Typography & Utilities */
    .font-mont { font-family: var(--font-montserrat); }
    .font-inter { font-family: var(--font-inter); }
    .font-open { font-family: var(--font-open); }
    .text-center { text-align: center; }
    .w-full { width: 100%; }

    /* Buttons */
    .feature-button {
      display: flex; align-items: center; gap: 12px; width: 100%; max-width: 100%;
      padding: 14px 18px; border-radius: 14px; font-family: var(--font-montserrat); font-size: 16px; font-weight: 600;
      color: var(--white); background: rgba(255, 255, 255, 0.06); backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.12); box-sizing: border-box; margin: 0;
    }
    .feature-button .check {
      width: 22px; height: 22px; min-width: 22px; border-radius: 50%; background: #22c55e;
      color: var(--white); font-size: 14px; font-weight: 700; display: flex; align-items: center; justify-content: center;
    }
    .buttonElevate {
      background-color: var(--black); color: var(--white); padding: 14px 35px; border-radius: 15px; border: none; font-size: 18px; font-weight: 600; cursor: pointer; font-family: var(--font-inter); transition: transform 0.2s, box-shadow 0.2s;
    }
    .buttonElevate:hover {
      transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.2);
    }

    /* Survey Form Component Classes */
    .form-wrapper { border-radius: 20px; box-shadow: 0px 10px 33px 3px rgba(255,255,255,0.29); max-width: 500px; width: 100%; margin: 0 auto; }
    .form-body { background-color: #FFFDFD; color: #000; border: 2px solid var(--white); border-radius: 20px 20px 0 0; max-width: 500px; width: 100%; padding: 60px 25px 40px; min-height: 480px; display: flex; flex-direction: column; justify-content: center; }
    .survey-q-title { font-weight: 500; font-size: 20px; font-family: var(--font-montserrat); margin-bottom: 16px; }
    .survey-sub-title { font-weight: 500; font-size: 16px; font-family: var(--font-montserrat); margin-bottom: 8px; }
    .survey-options-wrapper { display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; }
    .survey-input { width: 100%; padding: 10px 20px; border-radius: 5px; border: 1px solid var(--border-active); font-size: 14px; font-family: var(--font-inter); margin-bottom: 16px; box-sizing: border-box; }
    .survey-select { width: 100%; padding: 10px; border-radius: 5px; border: 1px solid var(--border-active); font-size: 14px; font-family: var(--font-inter); margin-bottom: 16px; }
    .survey-radio-group { display: flex; flex-direction: column; gap: 8px; }
    .survey-radio-label { display: flex; align-items: center; font-size: 16px; font-family: var(--font-inter); }
    .survey-radio { margin-right: 10px; }
    .survey-footer-text { text-align: center; font-size: 14px; color: var(--text-light-muted); }
    .survey-link { color: #188bf6; }
    .survey-nav-btn { background-color: var(--primary); color: var(--black); border: none; height: 50px; padding: 0 20px; border-radius: 0 0 20px 0; cursor: pointer; font-family: var(--font-montserrat); font-size: 16px; font-weight: 500; }
    .survey-footer-bar { background-color: var(--black); border-radius: 0 0 20px 20px; height: 50px; display: flex; align-items: center; justify-content: flex-end; box-shadow: 0px 10px 33px 3px rgba(255,255,255,0.29); }

    /* Image Gallery & Crew Grids */
    .gallery-card { position: relative; border-radius: 13px; overflow: hidden; min-height: 500px; background-color: var(--black); }
    .gallery-img { width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; }
    .gallery-caption-wrapper { position: absolute; bottom: 0; left: 0; right: 0; background-color: rgba(0,0,0,0.5); padding: 8px 12px; border-radius: 0 0 13px 13px; }
    .gallery-caption-title { color: var(--white); font-size: 18px; font-weight: 600; font-family: var(--font-open); margin: 0; }
    .crew-card { position: relative; border-radius: 18px; overflow: hidden; min-height: 280px; background-color: var(--black); }
    .crew-caption-wrapper { position: absolute; bottom: 0; left: 0; right: 0; background-color: rgba(0,0,0,0.5); padding: 10px 15px; }
    .crew-caption-title { color: var(--white); font-size: 18px; font-weight: 700; font-family: var(--font-montserrat); margin: 0; line-height: 1.3; }
    .crew-caption-sub { color: var(--primary); font-size: 14px; font-family: var(--font-montserrat); font-weight: 600; }

    /* Structural / Component Misc */
    .testimonial-text { font-size: 18px; font-style: italic; max-width: 700px; margin: 0 auto; line-height: 1.5; transition: opacity 0.4s ease; }
    .section-title { font-size: 24px; font-weight: 500; font-family: var(--font-montserrat); color: var(--text-main); margin-bottom: 24px; text-align: center; }
    .section-title-inter { font-size: 24px; font-weight: 500; font-family: var(--font-inter); color: var(--text-main); margin-bottom: 24px; text-align: center; }
    .hero-bg { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; opacity: 0.3; z-index: 0; }
    .hero-h1 { color: var(--white); font-family: var(--font-montserrat); font-size: 37.5px; font-weight: 700; line-height: 1.3; margin: 10px 0 0 0; }
    .hero-h2 { font-family: var(--font-montserrat); font-size: 37.5px; font-weight: 700; line-height: 1.3; margin: 0 0 10px 0; }
    .hero-p { color: var(--white); font-family: var(--font-open); font-size: 16px; line-height: 1.3; margin-bottom: 16px; }
  
    /* Remaining Extracted Inline Classes */
    .section-light { background-color: #fff; padding: 20px 0; }
    .hero-container { position: relative; z-index: 1; margin: 0 auto; }
    .hero-logo-box { margin-bottom: 24px; display: inline-block; background-color: var(--darker-bg); padding: 16px 20px; border-radius: 12px; }
    .hero-logo { width: 220px; height: auto; }
    .flex-1 { flex: 1; }
    .hero-subtext { color: rgba(255,255,255,0.85); font-family: var(--font-open); font-size: 14.5px; margin: 0; }
    .mt-20px { margin-top: 20px; }
    .progress-wrapper { width: 100%; background-color: #ddd; border-radius: 20px; margin-bottom: 20px; position: relative; height: 30px; }
    .progress-text-abs { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: var(--black); font-weight: 700; font-family: var(--font-montserrat); font-size: 14px; z-index: 1; }
    .progress-fill { height: 30px; background-color: orange; border-radius: 20px; transition: width 0.3s ease; }
    .survey-option-img { width: 100px; height: 80px; object-fit: cover; display: block; }
    .survey-success-title { text-align: center; color: var(--secondary); font-weight: 700; font-size: 18px; font-family: 'Poppins', sans-serif; margin-bottom: 8px; }
    .survey-success-sub { text-align: center; font-weight: 700; font-size: 16px; font-family: 'Poppins', sans-serif; margin-bottom: 16px; }
    .survey-thankyou-box { background-color: #fff; border-radius: 20px 20px 0 0; padding: 40px 20px; text-align: center; }
    .thankyou-title { color: var(--secondary); font-size: 20px; font-weight: 700; }
    .thankyou-text { font-size: 16px; color: #000; }
    .booking-bar { background: linear-gradient(109deg, #202020 39%, rgba(5,5,5,0.36) 100%); padding: 10px 20px; text-align: center; position: relative; z-index: 1; }
    .booking-text { color: var(--white); font-size: 16px; font-weight: 700; font-family: var(--font-inter); margin: 0; }
    .divider-brown { border-top: 1px solid #7a3e12; margin: 0; }
    .smart-video-wrap { position: relative; padding-bottom: 130%; background-color: #000; overflow: hidden; }
    .smart-video { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; border: none; }
    .smart-sound-badge { position: absolute; top: 16px; left: 16px; background: rgba(0,0,0,0.54); color: #fff; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 600; font-family: var(--font-inter); display: flex; align-items: center; gap: 8px; cursor: pointer; }
    .content-heading { font-size: 24px; font-weight: 500; font-family: var(--font-inter); color: var(--text-main); margin-bottom: 12px; }
    .content-body { font-size: 16px; font-family: var(--font-inter); line-height: 1.6; color: #000; }
    .mb-12px { margin-bottom: 12px; }
    .mb-16px { margin-bottom: 16px; }
    .badge-img { width: 70px; height: auto; object-fit: contain; }
    .badge-img-lg { width: 150px; height: auto; object-fit: contain; }
    .testimonial-stars { color: #f4c430; font-size: 18px; margin-bottom: 6px; }
    .testimonial-author { margin-top: 6px; font-size: 14px; color: #555; }
    .stat-card-wrap { font-family: var(--font-montserrat); padding: 10px 15px; text-align: center; }

    /* Mobile Responsiveness Overrides */
    @media (max-width: 768px) {
      .hero-h1, .hero-h2 {
        font-size: clamp(24px, 7vw, 32px);
      }
      .section-title, .section-title-inter {
        font-size: 22px;
      }
      .form-body {
        padding: 40px 15px 30px;
        min-height: 400px;
      }
      .hero-logo-box {
        padding: 10px 15px;
        margin-bottom: 16px;
      }
      .hero-logo {
        width: 180px;
      }
      .survey-q-title {
        font-size: 18px;
      }
    }
  </style>
</head>
<body>
  <main class="mx-auto w-[95%] md:w-[85%] lg:w-[82%] max-w-[1536px]">
    <!-- HeroSection -->
    <section class="hero-bg-wrapper relative bg-[#202020] overflow-hidden p-0">
      <!-- Background video -->
      <video autoPlay loop muted playsInline src="https://storage.googleapis.com/msgsndr/co4WnoqQGUmBzPYPO98e/media/69849f43e169f01b003f3e91.mp4" class="hero-bg"></video>

      <!-- Content -->
      <div class="hero-container pt-8 pb-20 md:pt-10 md:pb-32 pl-4 pr-4 md:pl-10 md:pr-10">
        <div class="flex flex-col md:flex-row gap-8 md:gap-16 items-center md:items-start">
          <!-- Left column -->
          <div class="w-full md:w-1/2 mb-10 md:mb-0 md:pr-8 flex flex-col justify-center">
            <div class="hero-logo-box">
              <img src="https://assets.cdn.filesafe.space/JZknYcB8Q1gRXfkLUZNS/media/4bc0f5d6-c2f1-41a5-b292-92c4faf5c474.png" alt="MODU SHADE" class="hero-logo" onerror="this.style.display='none'">
            </div>

            <h1 class="hero-h1">
              <span>Premium Blinds &amp; Shades for Homes across</span>
            </h1>
            <h1 class="hero-h2">
              <span style="color: #d0fc00;">New Jersey &amp; New York</span>
              <span style="color: #d0fc00;">📍</span>
            </h1>

            <p class="hero-p">
              Serving homeowners with tailored design, expert installation, and premium materials for maximum home style and comfort. Licensed &amp; insured. Minimum investment applies.
            </p>

            <div class="flex flex-col gap-2 mb-4">
              <div class="flex gap-2">
                <div class="feature-button flex-1">
                  <span class="check">✓</span>
                  <span>Free design consultation.</span>
                </div>
                <div class="feature-button flex-1">
                  <span class="check">✓</span>
                  <span>Installs completed in 1–2 weks.</span>
                </div>
              </div>
              <div class="flex gap-2">
                <div class="feature-button flex-1">
                  <span class="check">✓</span>
                  <span>1-Year warranty.</span>
                </div>
                <div class="feature-button flex-1">
                  <span class="check">✓</span>
                  <span>Family-owned, licensed &amp; insured.</span>
                </div>
              </div>
            </div>

            <div class="mt-20px">
              <p class="hero-subtext">
                Trusted by families across Cresskill • Licensed &amp; Insured • Family-Owned
              </p>
            </div>
          </div>

          <!-- Right column - Survey Form -->
          <div class="w-full md:w-1/2 md:pl-8 pt-6 md:pt-0 pb-10">
            <div class="form-wrapper">
              <div id="survey-body" class="form-body" style="border-radius: 20px 20px 0 0;">
                
                <!-- Progress bar -->
                <div class="progress-wrapper">
                  <div id="progress-text" class="progress-text-abs">Progress: 12%</div>
                  <div id="progress-bar-fill" class="progress-fill" style="width: 12%;"></div>
                </div>

                <!-- Error Message Container -->
                <div id="survey-error" style="display: none; background-color: #fee2e2; color: #b91c1c; padding: 10px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; text-align: center; border: 1px solid #fca5a5; font-family: 'Inter', sans-serif;"></div>

                <!-- Steps -->
                <div id="step-1">
                  <p style="font-weight: 500; font-size: 20px; font-family: 'Montserrat', sans-serif; margin-bottom: 16px;">Are you located within our service areas - NYC, Yonkers, Newark, Stamford, or nearby?</p>
                  <div class="survey-options-wrapper">
                    <div style="cursor: pointer; text-align: center;" onclick="selectOption('serviceArea', 'Yes'); nextStep();">
                      <div id="serviceArea-Yes" class="survey-option-box">
                        <img src="https://images.leadconnectorhq.com/image/f_webp/q_80/r_1200/u_https://firebasestorage.googleapis.com/v0/b/highlevel-backend.appspot.com/o/location%2FJZknYcB8Q1gRXfkLUZNS%2Fcustom-field-store%2F60697a16-ab81-4dc3-880f-8f46889cdffe.webp?alt=media&token=440f78e1-7499-4782-bc54-80ffa3c2840f" alt="Yes" class="survey-option-img" onerror="this.style.display='none'">
                        <div class="survey-option-label">Yes</div>
                      </div>
                    </div>
                    <div style="cursor: pointer; text-align: center;" onclick="selectOption('serviceArea', 'No'); nextStep();">
                      <div id="serviceArea-No" class="survey-option-box">
                        <img src="https://images.leadconnectorhq.com/image/f_webp/q_80/r_1200/u_https://firebasestorage.googleapis.com/v0/b/highlevel-backend.appspot.com/o/location%2FJZknYcB8Q1gRXfkLUZNS%2Fcustom-field-store%2F3f816785-460e-4d86-a61d-974d2bb5f95c.webp?alt=media&token=9c7da90a-eb43-41cc-ba19-6a1c5ce6b263" alt="No" class="survey-option-img" onerror="this.style.display='none'">
                        <div class="survey-option-label">No</div>
                      </div>
                    </div>
                  </div>
                  <p style="text-align: center; margin-top: 20px; font-size: 14px; color: #555;">
                    🔒 Safe | Secure | <a href="https://info.modu-shade.com/privacy-policy-425885" target="_blank" style="color: #188bf6;">Privacy Policy</a>
                  </p>
                </div>

                <div id="step-2" style="display: none;">
                  <p style="font-weight: 500; font-size: 20px; font-family: 'Montserrat', sans-serif; margin-bottom: 16px;">Which type of Blinds/Shades you are looking for?</p>
                  <select id="blindsType" class="survey-select">
                    <option value="">Select Blinds/Shades you are looking for</option>
                    <option value="Blackout Shades">Blackout Shades</option>
                    <option value="Curtains / Drapes">Curtains / Drapes</option>
                    <option value="Faux Wood Shutters">Faux Wood Shutters</option>
                    <option value="Honeycomb Shades">Honeycomb Shades</option>
                    <option value="Lutron Shades">Lutron Shades</option>
                    <option value="Motorized or Smart Glass">Motorized or Smart Glass</option>
                    <option value="Outdoor Shades">Outdoor Shades</option>
                    <option value="Roller Shades">Roller Shades</option>
                    <option value="Roman shades">Roman shades</option>
                    <option value="Skylights shades">Skylights shades</option>
                    <option value="Soft Shades">Soft Shades</option>
                    <option value="Vertical Blinds">Vertical Blinds</option>
                    <option value="Zebra / Dual Shades">Zebra / Dual Shades</option>
                    <option value="Zipper shades">Zipper shades</option>
                    <option value="Not Sure - Need Guidance">Not Sure - Need Guidance</option>
                  </select>
                  <p class="survey-footer-text">
                    🔒 Safe | Secure | <a href="https://info.modu-shade.com/privacy-policy-425885" target="_blank" class="survey-link">Privacy Policy</a>
                  </p>
                </div>

                <div id="step-3" style="display: none;">
                  <p class="survey-q-title">How many windows you are looking to cover?</p>
                  <div class="survey-radio-group">
                    <label class="survey-radio-label"><input type="radio" name="windowCount" value="1 or 2" class="survey-radio"> 1 or 2</label>
                    <label class="survey-radio-label"><input type="radio" name="windowCount" value="2 to 5" class="survey-radio"> 2 to 5</label>
                    <label class="survey-radio-label"><input type="radio" name="windowCount" value="5 to 10" class="survey-radio"> 5 to 10</label>
                    <label class="survey-radio-label"><input type="radio" name="windowCount" value="10 to 15" class="survey-radio"> 10 to 15</label>
                    <label class="survey-radio-label"><input type="radio" name="windowCount" value="15 and above" class="survey-radio"> 15 and above</label>
                  </div>
                  <p class="survey-footer-text">
                    🔒 Safe | Secure | <a href="https://info.modu-shade.com/privacy-policy-425885" target="_blank" class="survey-link">Privacy Policy</a>
                  </p>
                </div>

                <div id="step-4" style="display: none;">
                  <p class="survey-q-title">What is your estimated investment for this project?</p>
                  <div class="survey-radio-group">
                    <label class="survey-radio-label"><input type="radio" name="investment" value="$1,000 – $2,000" class="survey-radio"> $1,000 – $2,000</label>
                    <label class="survey-radio-label"><input type="radio" name="investment" value="$2,000 – $5,000" class="survey-radio"> $2,000 – $5,000</label>
                    <label class="survey-radio-label"><input type="radio" name="investment" value="$5,000 +" class="survey-radio"> $5,000 +</label>
                  </div>
                  <p class="survey-footer-text">
                    🔒 Safe | Secure | <a href="https://info.modu-shade.com/privacy-policy-425885" target="_blank" class="survey-link">Privacy Policy</a>
                  </p>
                </div>

                <div id="step-5" style="display: none;">
                  <p class="survey-q-title">Timeline for your project?</p>
                  <div class="survey-radio-group">
                    <label class="survey-radio-label"><input type="radio" name="timeline" value="As soon as possible" class="survey-radio"> As soon as possible</label>
                    <label class="survey-radio-label"><input type="radio" name="timeline" value="Within 1 Month" class="survey-radio"> Within 1 Month</label>
                    <label class="survey-radio-label"><input type="radio" name="timeline" value="Within 2 Months" class="survey-radio"> Within 2 Months</label>
                    <label class="survey-radio-label"><input type="radio" name="timeline" value="Just checking for ideas" class="survey-radio"> Just checking for ideas</label>
                  </div>
                  <p class="survey-footer-text">
                    🔒 Safe | Secure | <a href="https://info.modu-shade.com/privacy-policy-425885" target="_blank" class="survey-link">Privacy Policy</a>
                  </p>
                </div>

                <div id="step-6" style="display: none;">
                  <p class="survey-q-title">Any Special Message?</p>
                  <input type="text" id="message" placeholder="Any message for us / Project Description?" class="survey-input">
                  <p class="survey-footer-text">
                    🔒 Safe | Secure | <a href="https://info.modu-shade.com/privacy-policy-425885" target="_blank" class="survey-link">Privacy Policy</a>
                  </p>
                </div>

                <div id="step-7" style="display: none;">
                  <p class="survey-q-title">What is your postcode?</p>
                  <input type="text" id="postcode" placeholder="eg. 07626" style="width: 100%; padding: 10px 20px; border-radius: 5px; border: 1px solid #a9b3c6; font-size: 14px; font-family: 'Inter', sans-serif; margin-bottom: 8px; box-sizing: border-box;">
                  <p style="text-align: center; font-size: 14px; font-style: italic; font-weight: 700; color: #000; margin-bottom: 16px;">So we can check your postcodes eligibility</p>
                  <p class="survey-footer-text">
                    🔒 Safe | Secure | <a href="https://info.modu-shade.com/privacy-policy-425885" target="_blank" class="survey-link">Privacy Policy</a>
                  </p>
                </div>

                <div id="step-8" style="display: none;">
                  <p class="survey-q-title">What is your Full Name?</p>
                  <input type="text" id="fullName" placeholder="Eg. John Doe" class="survey-input">
                  <p class="survey-sub-title">What is your email address?</p>
                  <input type="email" id="email" placeholder="eg. name@company.com" class="survey-input">
                  <p class="survey-footer-text">
                    🔒 Safe | Secure | <a href="https://info.modu-shade.com/privacy-policy-425885" target="_blank" class="survey-link">Privacy Policy</a>
                  </p>
                </div>

                <div id="step-9" style="display: none;">
                  <p class="survey-success-title">Good News!</p>
                  <p class="survey-success-sub">We can help you. Where should we send you the details?</p>
                  <p class="survey-sub-title">What is your phone number?</p>
                  <input type="tel" id="phone" placeholder="Phone" class="survey-input">
                  <p class="survey-footer-text">
                    🔒 Safe | Secure | <a href="https://info.modu-shade.com/privacy-policy-425885" target="_blank" class="survey-link">Privacy Policy</a>
                  </p>
                </div>

              </div>

              <!-- Thank you message -->
              <div id="survey-thankyou" class="survey-thankyou-box" style="display: none;">
                <h2 class="thankyou-title">Thank You!</h2>
                <p class="thankyou-text">We will be in touch shortly.</p>
              </div>

              <!-- Footer with NEXT button -->
              <div id="survey-footer" class="survey-footer-bar">
                <button id="nextBtn" onclick="nextStep()" class="survey-nav-btn" style="display: flex; align-items: center; gap: 4px;">
                  NEXT
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" stroke="#000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9H15M15 9L10.5 4.5M15 9L10.5 13.5"></path>
                  </svg>
                </button>
                <button id="submitBtn" onclick="submitForm()" class="survey-nav-btn" style="display: none;">
                  SUBMIT
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Booking notice bar -->
      <div class="booking-bar">
        <h1 class="booking-text">
          Now booking a limited number of upcoming installation projects.
        </h1>
      </div>
    </section>



    

    <!-- TrustBadgesSection -->
    <section class="section-light">
      <div class="max-w-10xl mx-auto px-4">
        <div class="text-center mb-4">
          <h2 class="section-title">
            Trusted by Homeowners Across Cresskill
          </h2>
        </div>
        <div class="flex justify-center items-center gap-4 flex-wrap mb-4">
          <img src="https://assets.cdn.filesafe.space/JZknYcB8Q1gRXfkLUZNS/media/66ed15a8-2dcb-4169-95dc-2d3f8934b4cb.svg+xml" alt="Google Guaranteed" class="badge-img" onerror="this.style.display='none'">
          <img src="https://assets.cdn.filesafe.space/JZknYcB8Q1gRXfkLUZNS/media/989c84f1-9efe-4398-86a0-4ba629b8f106.webp" alt="Google Guaranteed Badge" class="badge-img-lg" onerror="this.style.display='none'">
          <img src="https://assets.cdn.filesafe.space/JZknYcB8Q1gRXfkLUZNS/media/26c8d1ef-3871-473b-b041-b0db33a2e617.svg+xml" alt="Accredited Business" class="badge-img-lg" onerror="this.style.display='none'">
        </div>
      </div>
    </section>

    <!-- CrewSection -->
    <section class="section-light">
      <div class="max-w-10xl mx-auto px-4">
        <h2 class="text-center mb-4 section-title">
          Our Dedicated Crew at Work
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
          <div class="crew-card">
            <img src="https://images.leadconnectorhq.com/image/f_webp/q_80/r_1200/u_https://assets.cdn.filesafe.space/JZknYcB8Q1gRXfkLUZNS/media/59bb1e89-e4bf-48f7-862e-6ebd95be4e18.jpg" alt="Precision Window Treatments" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; opacity: 0.5;" onerror="this.style.opacity='0.3'">
            <div class="crew-caption-wrapper">
              <h1 class="crew-caption-title">Precision Window Treatments</h1>
              <span class="crew-caption-sub">- Custom Fit for Your Space - Built to Last</span>
            </div>
          </div>
          <div class="crew-card">
            <img src="https://images.leadconnectorhq.com/image/f_webp/q_80/r_1200/u_https://assets.cdn.filesafe.space/JZknYcB8Q1gRXfkLUZNS/media/ef6f8738-ec3f-49db-a0df-470d62f3a62b.jpg" alt="Custom Shade &amp; Drapery Integration" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; opacity: 0.5;" onerror="this.style.opacity='0.3'">
            <div class="crew-caption-wrapper">
              <h1 class="crew-caption-title">Custom Shade &amp; Drapery Integration</h1>
              <span class="crew-caption-sub">- Seamless Finishes • Expert Craftsmanship</span>
            </div>
          </div>
          <div class="crew-card">
            <img src="https://images.leadconnectorhq.com/image/f_webp/q_80/r_1200/u_https://assets.cdn.filesafe.space/JZknYcB8Q1gRXfkLUZNS/media/5cf98936-c0cf-4e0a-ad08-77046f800114.jpg" alt="Precision Window Installations" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; opacity: 0.5;" onerror="this.style.opacity='0.3'">
            <div class="crew-caption-wrapper">
              <h1 class="crew-caption-title">Precision Window Installations</h1>
              <span class="crew-caption-sub">- Custom Designed for Everyday Living</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Divider -->
    <div class="divider-brown"></div>

    <!-- TestimonialsSection -->
    <section class="py-10 bg-white">
      <div class="max-w-10xl mx-auto px-4">
        <div class="text-center">
          <h2 class="text-2xl font-medium text-center mb-4 section-title">
            What Our Customers Say
          </h2>
          <div class="testimonial-wrap stat-card-wrap">
            <div class="stars testimonial-stars">★★★★★</div>
            <div id="testimonialText" class="testimonial-text">
              MODU SHADE LLC completely transformed our home. The window treatments look incredibly elegant, and the attention to detail was outstanding. You can truly see the craftsmanship in every finish.
            </div>
            <div id="testimonialName" class="testimonial-name testimonial-author">
              — Michael R., New Jersey
            </div>
          </div>
        </div>
        <div class="flex justify-center mt-6">
          <button class="buttonElevate">
            Get a Free Quote
          </button>
        </div>
      </div>
    </section>

    <!-- GallerySection -->
    <section class="section-light">
      <div class="max-w-10xl mx-auto px-4">
        <h2 class="text-center mb-4 section-title">
          See Our Work
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 mb-2">
          <div class="gallery-card">
            <img src="https://images.leadconnectorhq.com/image/f_webp/q_80/r_1200/u_https://assets.cdn.filesafe.space/JZknYcB8Q1gRXfkLUZNS/media/2581ebaa-a611-4790-a896-bc29c12796c1.jpg" alt="Roller Shades" class="gallery-img" onerror="this.style.opacity='0.3'">
            <div class="gallery-caption-wrapper"><h1 class="gallery-caption-title">Roller Shades</h1></div>
          </div>
          <div class="gallery-card">
            <img src="https://images.leadconnectorhq.com/image/f_webp/q_80/r_1200/u_https://assets.cdn.filesafe.space/JZknYcB8Q1gRXfkLUZNS/media/07e78b96-eb2d-4c3a-a3fa-c5d9e082863c.webp" alt="Roman Blind" class="gallery-img" onerror="this.style.opacity='0.3'">
            <div class="gallery-caption-wrapper"><h1 class="gallery-caption-title">Roman Blind</h1></div>
          </div>
          <div class="gallery-card">
            <img src="https://images.leadconnectorhq.com/image/f_webp/q_80/r_1200/u_https://assets.cdn.filesafe.space/JZknYcB8Q1gRXfkLUZNS/media/2add339e-bf87-4b70-89b6-02f1620d326f.jpg" alt="Honeycomb Shades" class="gallery-img" onerror="this.style.opacity='0.3'">
            <div class="gallery-caption-wrapper"><h1 class="gallery-caption-title">Honeycomb Shades</h1></div>
          </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 mb-2">
          <div class="gallery-card">
            <img src="https://images.leadconnectorhq.com/image/f_webp/q_80/r_1200/u_https://assets.cdn.filesafe.space/JZknYcB8Q1gRXfkLUZNS/media/4d733d39-0dcb-498b-8d07-e4d09eb677e1.webp" alt="Zebra Shades" class="gallery-img" onerror="this.style.opacity='0.3'">
            <div class="gallery-caption-wrapper"><h1 class="gallery-caption-title">Zebra Shades</h1></div>
          </div>
          <div class="gallery-card">
            <img src="https://images.leadconnectorhq.com/image/f_webp/q_80/r_1200/u_https://assets.cdn.filesafe.space/JZknYcB8Q1gRXfkLUZNS/media/46bafab3-8e1a-43f9-a24e-43002d02ef01.webp" alt="Fabric Blinds" class="gallery-img" onerror="this.style.opacity='0.3'">
            <div class="gallery-caption-wrapper"><h1 class="gallery-caption-title">Fabric Blinds</h1></div>
          </div>
          <div class="gallery-card">
            <img src="https://images.leadconnectorhq.com/image/f_webp/q_80/r_1200/u_https://assets.cdn.filesafe.space/JZknYcB8Q1gRXfkLUZNS/media/9199fae6-d9af-4815-bca3-33f6b7824516.webp" alt="Curtains / Drapes" class="gallery-img" onerror="this.style.opacity='0.3'">
            <div class="gallery-caption-wrapper"><h1 class="gallery-caption-title">Curtains / Drapes</h1></div>
          </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 mb-2">
          <div class="gallery-card">
            <img src="https://images.leadconnectorhq.com/image/f_webp/q_80/r_1200/u_https://assets.cdn.filesafe.space/JZknYcB8Q1gRXfkLUZNS/media/dde50e47-dcfc-4c76-8791-7e0136e8f567.jpg" alt="Drapery Shades" class="gallery-img" onerror="this.style.opacity='0.3'">
            <div class="gallery-caption-wrapper"><h1 class="gallery-caption-title">Drapery Shades</h1></div>
          </div>
          <div class="gallery-card">
            <img src="https://images.leadconnectorhq.com/image/f_webp/q_80/r_1200/u_https://assets.cdn.filesafe.space/JZknYcB8Q1gRXfkLUZNS/media/069af2b3-aed6-40a0-b866-a0b2cc82a944.webp" alt="Vertical Blinds" class="gallery-img" onerror="this.style.opacity='0.3'">
            <div class="gallery-caption-wrapper"><h1 class="gallery-caption-title">Vertical Blinds</h1></div>
          </div>
          <div class="gallery-card">
            <img src="https://images.leadconnectorhq.com/image/f_webp/q_80/r_1200/u_https://assets.cdn.filesafe.space/JZknYcB8Q1gRXfkLUZNS/media/f899bafa-7486-46d4-8d94-19eaec724ba4.jpg" alt="Sheer Shades" class="gallery-img" onerror="this.style.opacity='0.3'">
            <div class="gallery-caption-wrapper"><h1 class="gallery-caption-title">Sheer Shades</h1></div>
          </div>
        </div>
        <div class="flex justify-center mt-6">
          <button class="buttonElevate">
            Get a Free Quote
          </button>
        </div>
      </div>
    </section>

    <!-- Divider -->
    <div class="divider-brown"></div>

    <!-- SmartSolutionsSection -->
    <section class="section-light">
      <div class="max-w-10xl mx-auto px-4">
        <div class="flex flex-col md:flex-row gap-10 md:gap-16 items-center">
          <div class="w-full md:w-1/2">
            <div class="smart-video-wrap">
              <video autoPlay loop muted playsInline src="https://storage.googleapis.com/msgsndr/co4WnoqQGUmBzPYPO98e/media/69849f43e169f01b003f3e91.mp4" class="smart-video"></video>
              <div class="smart-sound-badge">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon><line x1="23" y1="9" x2="17" y2="15"></line><line x1="17" y1="9" x2="23" y2="15"></line></svg>
                Enable sound
              </div>
            </div>
          </div>
          <div class="w-full md:w-1/2 md:pr-12 md:pl-4">
            <h2 class="content-heading">
              Smart Solutions
            </h2>
            <div class="content-body">
              <p class="mb-12px">Lets talk about the top motorizations blinds and shades brands out-there and layout all the information that you possibly can gather to help you chose the right brand to your needs.</p>
              <p class="mb-12px">The fact is when you purchase blinds and shades you are dealing with the dealer, almost like buying a new car. There are so many brands and options and you can easily get lost with the overwhelming information.</p>
              <p class="mb-16px">So we are here to help!</p>
            </div>
            <button class="buttonElevate">
              Get a Free Quote
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- InvestmentSection -->
    <section class="section-light">
      <div class="max-w-10xl mx-auto px-4">
        <h2 class="text-center mb-6" style="font-size: 24px; font-weight: 500; font-family: 'Inter', sans-serif; color: #1c1c1c;">
          Why High-End Custom Window Treatments Are a Smart Investment
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div style="background-color: #fff; border: 1px solid rgba(255,255,255,0.46); border-radius: 15px; padding: 30px 20px; box-shadow: 2px 2px 4px 4px rgba(112,112,112,0.17);">
            <p style="font-size: 16px; font-weight: 500; font-family: 'Inter', sans-serif; color: #000; margin-bottom: 10px;">🏡 Increase Home Value</p>
            <p style="font-size: 16px; font-family: 'Inter', sans-serif; color: #000; line-height: 1.3; margin-bottom: 16px;">Custom window treatments and integrated shading systems elevate your home's value while enhancing aesthetics, comfort, and energy efficiency.</p>
            <button style="background-color: #202020; color: #fff; padding: 2px 8px; border: 1px solid #fff; border-radius: 20px; font-size: 14px; font-weight: 700; font-family: 'Inter', sans-serif; cursor: pointer;">Premium return on investment</button>
          </div>
          <div style="background-color: #fff; border: 1px solid rgba(255,255,255,0.46); border-radius: 15px; padding: 30px 20px; box-shadow: 2px 2px 4px 4px rgba(112,112,112,0.17);">
            <p style="font-size: 16px; font-weight: 500; font-family: 'Inter', sans-serif; color: #000; margin-bottom: 10px;">🪟 Superior Fit, Finish &amp; Function</p>
            <p style="font-size: 16px; font-family: 'Inter', sans-serif; color: #000; line-height: 1.3; margin-bottom: 16px;">Unlike off-the-shelf solutions, our custom shades, shutters, and drapery are precision-measured and expertly installed for a flawless fit and smooth operation.</p>
            <button style="background-color: #202020; color: #fff; padding: 2px 8px; border: 1px solid #fff; border-radius: 20px; font-size: 14px; font-weight: 700; font-family: 'Inter', sans-serif; cursor: pointer;">Every detail, purpose-built</button>
          </div>
          <div style="background-color: #fff; border: 1px solid rgba(255,255,255,0.46); border-radius: 15px; padding: 30px 20px; box-shadow: 2px 2px 4px 4px rgba(112,112,112,0.17);">
            <p style="font-size: 16px; font-weight: 500; font-family: 'Inter', sans-serif; color: #000; margin-bottom: 10px;">🎨 Personalized Design &amp; Materials</p>
            <p style="font-size: 16px; font-family: 'Inter', sans-serif; color: #000; line-height: 1.3; margin-bottom: 16px;">Select from luxury fabrics, finishes, control options, and automation tailored to your space, lifestyle, and design vision.</p>
            <button style="background-color: #202020; color: #fff; padding: 2px 8px; border: 1px solid #fff; border-radius: 20px; font-size: 14px; font-weight: 700; font-family: 'Inter', sans-serif; cursor: pointer;">Designed exclusively for you</button>
          </div>
        </div>
        <div class="flex justify-center mt-6">
          <button class="buttonElevate">
            Get a Free Quote
          </button>
        </div>
      </div>
    </section>

    <!-- FAQSection -->
    <section class="py-10" style="background-color: #fff;">
      <div class="max-w-10xl mx-auto px-4">
        <h2 class="text-center mb-6" style="font-size: 24px; font-weight: 500; font-family: 'Inter', sans-serif; color: #1c1c1c;">
          FAQs
        </h2>
        <div class="flex flex-col gap-3" id="faq-container">
          <!-- Populated by JS -->
        </div>
      </div>
    </section>

    <!-- ServiceAreasSection -->
    <section class="py-10" style="background-color: #fff;">
      <div class="max-w-10xl mx-auto px-4">
        <h2 class="text-center mb-6" style="font-size: 24px; font-weight: 500; font-family: 'Inter', sans-serif; color: #1c1c1c;">
          Proudly Serving Homeowners Across Cresskill County
        </h2>
        <div class="flex flex-col gap-2">
          <div class="flex flex-wrap justify-center gap-2">
            <button style="background-color: #f4f1ec; color: #3a2a12; padding: 8px 12px; border-radius: 25px; border: 1px solid #e8e1d8; font-size: 16px; font-weight: 400; font-family: 'Montserrat', sans-serif; cursor: pointer; min-width: 100px; text-align: center;">New York</button>
            <button style="background-color: #f4f1ec; color: #3a2a12; padding: 8px 12px; border-radius: 25px; border: 1px solid #e8e1d8; font-size: 16px; font-weight: 400; font-family: 'Montserrat', sans-serif; cursor: pointer; min-width: 100px; text-align: center;">Yorkville</button>
            <button style="background-color: #f4f1ec; color: #3a2a12; padding: 8px 12px; border-radius: 25px; border: 1px solid #e8e1d8; font-size: 16px; font-weight: 400; font-family: 'Montserrat', sans-serif; cursor: pointer; min-width: 100px; text-align: center;">Harlem</button>
            <button style="background-color: #f4f1ec; color: #3a2a12; padding: 8px 12px; border-radius: 25px; border: 1px solid #e8e1d8; font-size: 16px; font-weight: 400; font-family: 'Montserrat', sans-serif; cursor: pointer; min-width: 100px; text-align: center;">Cresskill</button>
            <button style="background-color: #f4f1ec; color: #3a2a12; padding: 8px 12px; border-radius: 25px; border: 1px solid #e8e1d8; font-size: 16px; font-weight: 400; font-family: 'Montserrat', sans-serif; cursor: pointer; min-width: 100px; text-align: center;">Tenafly</button>
          </div>
          <div class="flex flex-wrap justify-center gap-2">
            <button style="background-color: #f4f1ec; color: #3a2a12; padding: 8px 12px; border-radius: 25px; border: 1px solid #e8e1d8; font-size: 16px; font-weight: 400; font-family: 'Montserrat', sans-serif; cursor: pointer; min-width: 100px; text-align: center;">Demarest</button>
            <button style="background-color: #f4f1ec; color: #3a2a12; padding: 8px 12px; border-radius: 25px; border: 1px solid #e8e1d8; font-size: 16px; font-weight: 400; font-family: 'Montserrat', sans-serif; cursor: pointer; min-width: 100px; text-align: center;">Alpine</button>
            <button style="background-color: #f4f1ec; color: #3a2a12; padding: 8px 12px; border-radius: 25px; border: 1px solid #e8e1d8; font-size: 16px; font-weight: 400; font-family: 'Montserrat', sans-serif; cursor: pointer; min-width: 100px; text-align: center;">Brooklyn</button>
            <button style="background-color: #f4f1ec; color: #3a2a12; padding: 8px 12px; border-radius: 25px; border: 1px solid #e8e1d8; font-size: 16px; font-weight: 400; font-family: 'Montserrat', sans-serif; cursor: pointer; min-width: 100px; text-align: center;">Kensington</button>
            <button style="background-color: #f4f1ec; color: #3a2a12; padding: 8px 12px; border-radius: 25px; border: 1px solid #e8e1d8; font-size: 16px; font-weight: 400; font-family: 'Montserrat', sans-serif; cursor: pointer; min-width: 100px; text-align: center;">New Jersey</button>
          </div>
        </div>
      </div>
    </section>

    <!-- FooterSection -->
    <footer style="background-color: #000; color: #fff; padding: 20px 0;">
      <div class="max-w-10xl mx-auto px-4 text-center">
        <p style="font-size: 16px; font-weight: 300; font-family: 'Inter', sans-serif; color: #fff; margin: 0 0 4px 0;">
          © 2026 Modu Shade • Licensed &amp; Insured • Open Mon–Sat 8 AM–5 PM
        </p>
        <p style="font-size: 16px; font-weight: 300; font-family: 'Inter', sans-serif; color: #fff; margin: 0 0 8px 0;">
          Serving New York, New Jersey, and nearby areas.
        </p>
        <p style="margin: 0;">
          <a href="https://info.modu-shade.com/privacy-policy-425885" target="_blank" rel="noopener noreferrer" style="color: #fff; text-decoration: underline; font-size: 15px; font-family: 'Inter', sans-serif;">
            Privacy Policy
          </a>
        </p>
      </div>
    </footer>
  </main>

  <script>
    // --- Survey Form Logic ---
    let currentStep = 1;
    const progressMap = {1: 12, 2: 18, 3: 27, 4: 32, 5: 40, 6: 49, 7: 61, 8: 81, 9: 97};
    const surveyData = {};

    function selectOption(key, value) {
      surveyData[key] = value;
      // Visual feedback for Step 1
      if (key === 'serviceArea') {
        document.getElementById('serviceArea-Yes').style.border = value === 'Yes' ? '2px solid #000' : '1px solid #ccc';
        document.getElementById('serviceArea-No').style.border = value === 'No' ? '2px solid #000' : '1px solid #ccc';
      }
    }

    function nextStep() {
      if (!validateStep(currentStep)) return;

      if (currentStep < 9) {
        document.getElementById(`step-${currentStep}`).style.display = 'none';
        currentStep++;
        document.getElementById(`step-${currentStep}`).style.display = 'block';
        updateProgress();
        clearError();
        if (currentStep === 9) {
          document.getElementById('nextBtn').style.display = 'none';
          document.getElementById('submitBtn').style.display = 'flex';
          document.getElementById('submitBtn').style.alignItems = 'center';
        }
      }
    }

    function showError(message) {
      const errorEl = document.getElementById('survey-error');
      errorEl.innerText = message;
      errorEl.style.display = 'block';
      errorEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function clearError() {
      const errorEl = document.getElementById('survey-error');
      errorEl.style.display = 'none';
      errorEl.innerText = '';
      
      // Clear red borders
      const inputs = document.querySelectorAll('.survey-input, .survey-select');
      inputs.forEach(input => input.style.borderColor = '');
    }

    function validateStep(step) {
      clearError();
      let isValid = true;

      if (step === 1) {
        if (!surveyData.serviceArea) {
          showError('Please select if you are in our service area.');
          isValid = false;
        }
      } else if (step === 2) {
        const val = document.getElementById('blindsType').value;
        if (!val) {
          showError('Please select what you are looking for.');
          document.getElementById('blindsType').style.borderColor = '#b91c1c';
          isValid = false;
        }
      } else if (step === 3) {
        const checked = document.querySelector('input[name="windowCount"]:checked');
        if (!checked) {
          showError('Please select the number of windows.');
          isValid = false;
        }
      } else if (step === 4) {
        const checked = document.querySelector('input[name="investment"]:checked');
        if (!checked) {
          showError('Please select your estimated investment.');
          isValid = false;
        }
      } else if (step === 5) {
        const checked = document.querySelector('input[name="timeline"]:checked');
        if (!checked) {
          showError('Please select your project timeline.');
          isValid = false;
        }
      } else if (step === 7) {
        const val = document.getElementById('postcode').value.trim();
        if (!val) {
          showError('Please enter your postcode.');
          document.getElementById('postcode').style.borderColor = '#b91c1c';
          isValid = false;
        }
      } else if (step === 8) {
        const name = document.getElementById('fullName').value.trim();
        const email = document.getElementById('email').value.trim();
        if (!name) {
          showError('Please enter your full name.');
          document.getElementById('fullName').style.borderColor = '#b91c1c';
          isValid = false;
        } else if (!email || !email.includes('@')) {
          showError('Please enter a valid email address.');
          document.getElementById('email').style.borderColor = '#b91c1c';
          isValid = false;
        }
      } else if (step === 9) {
        const phone = document.getElementById('phone').value.trim();
        if (!phone || phone.length < 10) {
          showError('Please enter a valid phone number.');
          document.getElementById('phone').style.borderColor = '#b91c1c';
          isValid = false;
        }
      }

      return isValid;
    }

    function updateProgress() {
      const p = progressMap[currentStep];
      const bar = document.getElementById('progress-bar-fill');
      bar.style.width = p + '%';
      bar.style.backgroundColor = p >= 60 ? 'lightgreen' : 'orange';
      document.getElementById('progress-text').innerText = `Progress: ${p}%`;
    }

    function submitForm() {
      // Gather data
      surveyData.blindsType = document.getElementById('blindsType')?.value;
      surveyData.windowCount = document.querySelector('input[name="windowCount"]:checked')?.value;
      surveyData.investment = document.querySelector('input[name="investment"]:checked')?.value;
      surveyData.timeline = document.querySelector('input[name="timeline"]:checked')?.value;
      surveyData.message = document.getElementById('message')?.value;
      surveyData.postcode = document.getElementById('postcode')?.value;
      surveyData.fullName = document.getElementById('fullName')?.value;
      surveyData.email = document.getElementById('email')?.value;
      surveyData.phone = document.getElementById('phone')?.value;

      console.log('Survey submitting:', surveyData);
      
      const submitBtn = document.getElementById('submitBtn');
      submitBtn.disabled = true;
      submitBtn.innerText = 'SUBMITTING...';

      // Map data to controller expectations
      const payload = {
        full_name: surveyData.fullName,
        email: surveyData.email,
        phone: surveyData.phone,
        postal_code: surveyData.postcode,
        shades_needed: surveyData.blindsType,
        windows_count: surveyData.windowCount,
        timeline: surveyData.timeline,
        budget: surveyData.investment,
        message: surveyData.message
      };

      fetch('/submit-lead', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Accept': 'application/json'
        },
        body: JSON.stringify(payload)
      })
      .then(async response => {
        const isJson = response.headers.get('content-type')?.includes('application/json');
        const data = isJson ? await response.json() : null;

        if (!response.ok) {
          if (response.status === 422 && data && data.errors) {
            const firstError = Object.values(data.errors)[0][0];
            throw new Error(firstError);
          }
          throw new Error('Something went wrong. Please try again.');
        }
        return data;
      })
      .then(data => {
        console.log('Success:', data);
        window.location.href = '/thank-you';
      })
      .catch((error) => {
        console.error('Error:', error);
        showError(error.message);
        submitBtn.disabled = false;
        submitBtn.innerText = 'SUBMIT';
      });
    }

    // --- FAQ Logic ---
    const faqs = [
      {
        question: 'What types of motorized window treatments do you offer?',
        answer: 'We provide motorized curtains, blinds, shades, drapes, roller blinds, zebra blinds and many more options — all customizable to your space.'
      },
      {
        question: 'Where can custom window treatments be installed?',
        answer: 'Our custom solutions can be installed throughout your home, including living rooms, bedrooms, kitchens, bathrooms, offices, skylights, and outdoor spaces—anywhere light control, privacy, or design enhancement is needed.'
      },
      {
        question: 'Do you help with design and material selection?',
        answer: 'Yes. We guide you through fabric selection, finishes, shade types, control options, and automation to ensure your window treatments complement your home\'s style and lifestyle perfectly.'
      },
      {
        question: 'Are custom window treatments worth the investment?',
        answer: 'Absolutely. High-end custom window treatments enhance comfort, energy efficiency, and home value while delivering long-lasting performance and a tailored, luxury finish that mass-produced options can\'t match.'
      }
    ];

    let openIndex = null;
    const faqContainer = document.getElementById('faq-container');

    function renderFAQs() {
      faqContainer.innerHTML = '';
      faqs.forEach((faq, index) => {
        const isOpen = openIndex === index;
        const borderRad = isOpen ? '10px 10px 0 0' : '10px';
        const innerIcon = isOpen ? '⌃' : '⌄';
        
        const answerHtml = isOpen ? `
          <div style="background-color: #fff; padding: 15px 20px; border-radius: 0 0 10px 10px;">
            <p style="font-size: 15px; font-weight: 400; font-family: 'Inter', sans-serif; color: #000; margin: 0; line-height: 1.5;">
              ${faq.answer}
            </p>
          </div>
        ` : '';

        const itemHtml = `
          <div style="border: 1.5px solid #f5f5f5; border-radius: 10px; overflow: hidden;">
            <div class="flex items-center justify-between cursor-pointer" style="background-color: #f5f5f5; padding: 15px 20px; border-radius: ${borderRad};" onclick="toggleFaq(${index})">
              <h4 style="font-size: 16px; font-weight: 500; font-family: 'Inter', sans-serif; margin: 0; color: #000;">
                ${faq.question}
              </h4>
              <span style="font-size: 16px; color: #000; margin-left: 10px; flex-shrink: 0;">${innerIcon}</span>
            </div>
            ${answerHtml}
          </div>
        `;
        faqContainer.innerHTML += itemHtml;
      });
    }

    function toggleFaq(index) {
      openIndex = openIndex === index ? null : index;
      renderFAQs();
    }
    
    // Initialize FAQs
    renderFAQs();

    // Clear error on input interaction
    document.addEventListener('DOMContentLoaded', () => {
      const inputs = document.querySelectorAll('.survey-input, .survey-select, .survey-radio');
      inputs.forEach(input => {
        ['input', 'change', 'click'].forEach(evt => {
          input.addEventListener(evt, clearError);
        });
      });
    });

    // --- Testimonials Logic ---
    const testimonials = [
      {
        text: "MODU SHADE LLC completely transformed our home. The window treatments look incredibly elegant, and the attention to detail was outstanding. You can truly see the craftsmanship in every finish.",
        name: "— Michael R., New Jersey"
      },
      {
        text: "We wanted smart shading solutions that didn't compromise on style—and MODU SHADE LLC delivered beyond expectations. Everything works flawlessly, and the design fits our space perfectly.",
        name: "— Sophia L."
      },
      {
        text: "From consultation to installation, the MODU SHADE LLC team was professional, knowledgeable, and efficient. The final result elevated our living space and added real value to our home.",
        name: "— Daniel P."
      },
      {
        text: "Our custom window treatments look stunning and function beautifully. MODU SHADE LLC combines modern technology with a luxury finish better than anyone we've worked with.",
        name: "— Emily T."
      }
    ];

    let currentTestimonialIndex = 0;
    const textEl = document.getElementById('testimonialText');
    const nameEl = document.getElementById('testimonialName');

    setInterval(() => {
      textEl.style.opacity = 0;
      nameEl.style.opacity = 0;
      setTimeout(() => {
        currentTestimonialIndex = (currentTestimonialIndex + 1) % testimonials.length;
        textEl.innerText = testimonials[currentTestimonialIndex].text;
        nameEl.innerText = testimonials[currentTestimonialIndex].name;
        textEl.style.opacity = 1;
        nameEl.style.opacity = 1;
      }, 400); // 400ms fade transition
    }, 4000);
  </script>

  <!-- External Tracking Scripts -->
  <script type="module" async src="https://static.rocket.new/rocket-web.js?_cfg=https%3A%2F%2Fmodushade6320back.builtwithrocket.new&_be=https%3A%2F%2Fappanalytics.rocket.new&_v=0.1.18"></script>
  <script type="module" defer src="https://static.rocket.new/rocket-shot.js?v=0.0.2"></script>
</body>
</html>
