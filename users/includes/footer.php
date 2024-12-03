<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <title>User | Footer</title>
  <style>
    /* General Footer Styling */
    .footer {
      display: flex;
      flex-direction: column;
      align-items: center;
      background-color: whitesmoke;
      color: orange;
      padding: 10px;
      font-size: 14px;
    }

    .footer-icons {
      display: flex;
      gap: 20px;
    }

    .icon-btn {
      border: solid lightgray;
      border-radius: 10px;
      padding: 10px;
      background-color: rgb(53, 97, 255);
      color: white;
      font-family: 'Monsterrat', sans-serif;
      font-weight: bolder;
      cursor: pointer;
    }

    .icon-btn:hover {
      background-color: darkblue;
      border: solid blue;
    }

    .icon {
      width: 40px;
      height: 40px;
    }

    .footer-contact {
      text-align: center;
      margin-top: 10px;
    }

    .footer-copyright {
      margin-top: 10px;
      font-size: 12px;
      text-align: center;
    }

    /* Modal Styling */
    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.7);
      justify-content: center;
      align-items: center;
    }

    .modal-dialog.custom-width {
      max-width: 100%; /* Set the modal width to 90% of the viewport */
      width:auto ;
    }

    .modal-content {
      background: #fff;
      padding: 1px;
      border-radius: 10px;
      position: relative;
    }

    .iframe-content {
      width: 100%;
      height: 80vh; /* Set height to occupy most of the viewport */
      border: none;
    }

    .close-btn {
      position: absolute;
      top: 10px;
      right: 10px;
      background: none;
      border: none;
      font-size: 20px;
      cursor: pointer;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .modal-dialog.custom-width {
        max-width: 100%; /* Full width on smaller screens */
      }
    }
  </style>
</head>
<body>
  <footer class="footer">
    <div class="footer-icons">
      <button class="icon-btn" id="slide-btn">
        <i class="bi bi-gear-fill"></i> User Manual
      </button>
      <button class="icon-btn" id="privacy-btn">
        <i class="bi bi-house-gear-fill"></i> Privacy Policy
      </button>
      <button class="icon-btn" id="terms-btn">
        <i class="bi bi-house-lock-fill"></i> Terms and Conditions
      </button>
    </div>
    <div class="footer-contact">
      <p><strong>For more concerns, contact Security Admin:</strong></p>
      <p>Contact Number: <a href="tel:09123456789">0912-345-6789</a></p>
    </div>
    <marquee direction="left">
      <div class="footer-copyright">
        &copy; 2024 CTU DANAO - VEHICLE PARKING MANAGEMENT SYSTEM. <br>All Rights Reserved.
      </div>
    </marquee>
  </footer>

  <!-- Slide Modal -->
  <div id="slide-modal" class="modal modal-dialog-centered" style="width: 1000px;">
    <div class="modal-dialog custom-width">
      <div class="modal-content">
        <iframe src="slide.php" class="iframe-content"></iframe>
        <button class="close-btn" id="close-slide"><i class="bi bi-x-circle-fill"></i></button>
      </div>
    </div>
  </div>

  <!-- Privacy Policy Modal -->
  <div id="privacy-modal" class="modal modal-dialog-centered">
    <div class="modal-dialog custom-width">
      <div class="modal-content">
        <iframe src="policy.php" class="iframe-content"></iframe>
        <button class="close-btn" id="close-privacy"><i class="bi bi-x-circle-fill"></i></button>
      </div>
    </div>
  </div>

  <!-- Terms and Conditions Modal -->
  <div id="terms-modal" class="modal modal-dialog-centered">
    <div class="modal-dialog custom-width">
      <div class="modal-content">
        <iframe src="terms.php" class="iframe-content"></iframe>
        <button class="close-btn" id="close-terms"><i class="bi bi-x-circle-fill"></i></button>
      </div>
    </div>
  </div>

  <script>
    const privacyModal = document.getElementById('privacy-modal');
    const termsModal = document.getElementById('terms-modal');
    const slideModal = document.getElementById('slide-modal');

    const privacyBtn = document.getElementById('privacy-btn');
    const termsBtn = document.getElementById('terms-btn');
    const slideBtn = document.getElementById('slide-btn');

    const closePrivacy = document.getElementById('close-privacy');
    const closeTerms = document.getElementById('close-terms');
    const closeSlide = document.getElementById('close-slide');

    slideBtn.addEventListener('click', () => {
      slideModal.style.display = 'flex';
    });

    privacyBtn.addEventListener('click', () => {
      privacyModal.style.display = 'flex';
    });

    termsBtn.addEventListener('click', () => {
      termsModal.style.display = 'flex';
    });

    closeSlide.addEventListener('click', () => {
      slideModal.style.display = 'none';
    });

    closePrivacy.addEventListener('click', () => {
      privacyModal.style.display = 'none';
    });

    closeTerms.addEventListener('click', () => {
      termsModal.style.display = 'none';
    });

    window.addEventListener('click', (e) => {
      if (e.target === privacyModal) {
        privacyModal.style.display = 'none';
      } else if (e.target === termsModal) {
        termsModal.style.display = 'none';
      } else if (e.target === slideModal) {
        slideModal.style.display = 'none';
      }
    });
  </script>
</body>
</html>
