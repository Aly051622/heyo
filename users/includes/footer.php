<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <title>User | Footer</title>
  <link rel="stylesheet" href="styles.css">
</head>
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
  background: none;
  border: none;
  cursor: pointer;
}

.icon {
  width: 40px;
  height: 40px;
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

.modal-content {
  background: #fff;
  padding: 15px;
  border-radius: 10px;
  width: 90%;
  max-width: 500px;
  position: relative;
}

.iframe-content {
  width: 100%;
  height: 400px;
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
@media (max-width: 480px) {
  .icon {
    width: 30px;
    height: 30px;
  }

  .modal-content {
    width: 90%;
    max-width: 300px;
  }
}

@media (max-width: 768px) {
  .icon {
    width: 35px;
    height: 35px;
  }
}

    </style>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <title>User | Footer</title>
  <link rel="stylesheet" href="styles.css">
</head>
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
                cursor: pointer;
                font-family: 'Monsterrat', sans-serif;
                font-weight: bolder;
                cursor: pointer;
}

.icon:hover {
  background-color: darkblue;
  border: solid blue;
}

.icon {
  width: 40px;
  height: 40px;
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

.modal-content {
  background: #fff;
  padding: 15px;
  border-radius: 10px;
  width: 90%;
  max-width: 500px;
  position: relative;
}

.iframe-content {
  width: 100%;
  height: 400px;
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
@media (max-width: 480px) {
  .icon {
    width: 30px;
    height: 30px;
  }

  .modal-content {
    width: 90%;
    max-width: 300px;
  }
}

@media (max-width: 768px) {
  .icon {
    width: 35px;
    height: 35px;
  }
}

    </style>
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
      <marquee direction="left"><div class="footer-copyright">
        &copy; 2024 CTU DANAO - VEHICLE PARKING MANAGEMENT SYSTEM. <br>All Rights Reserved.
      </div></marquee>
    </footer>

    <!-- Slide Modal -->
  <div id="slide-modal" class="modal">
    <div class="modal-content">
      <iframe src="slide.php" class="iframe-content"></iframe>
      <button class="close-btn" id="close-slide">&times;</button>
    </div>
  </div>
  <!-- Privacy Policy Modal -->
  <div id="privacy-modal" class="modal">
    <div class="modal-content">
      <iframe src="policy.php" class="iframe-content"></iframe>
      <button class="close-btn" id="close-privacy">&times;</button>
    </div>
  </div>

  <!-- Terms and Conditions Modal -->
  <div id="terms-modal" class="modal">
    <div class="modal-content">
      <iframe src="terms.php" class="iframe-content"></iframe>
      <button class="close-btn" id="close-terms">&times;</button>
    </div>
  </div>

  <script>
    // Select Elements
const privacyModal = document.getElementById('privacy-modal');
const termsModal = document.getElementById('terms-modal');
const slideModal = document.getElementById('slide-modal');

const privacyBtn = document.getElementById('privacy-btn');
const termsBtn = document.getElementById('terms-btn');
const slideBtn = document.getElementById('slide-btn');

const closePrivacy = document.getElementById('close-privacy');
const closeTerms = document.getElementById('close-terms');
const closeSlide = document.getElementById('close-slide');

// button ni
slideBtn.addEventListener('click', () => {
  slideModal.style.display = 'flex';
});

privacyBtn.addEventListener('click', () => {
  privacyModal.style.display = 'flex';
});

termsBtn.addEventListener('click', () => {
  termsModal.style.display = 'flex';
});

//for close ni
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
