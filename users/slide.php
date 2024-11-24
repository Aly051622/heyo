<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background: whitesmoke;
            height: 100vh;
        }
        .section {
            margin: 20px 0;
            padding: 10px;
        }
        .title {
            text-align: center;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        .slider-container {
            overflow: hidden;
            position: relative;
            width: 100%;
            height: auto;
        }
        .slider {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }
        .slider img {
            width: 100%;
            object-fit: cover;
            pointer-events: none;
        }
        /* Hover to pause */
        .slider-container:hover .slider {
            animation-play-state: paused;
        }
        /* Landscape Slides */
        .slider img.landscape {
            height: 225px;
            width: 100vw;
        }
        /* Portrait Sections (Sections 4-7) */
        .portrait-section {
            display: flex;
            gap: 10px;
            justify-content: space-between;
        }
        .portrait-container {
            flex: 1;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }
        .portrait-container img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            transition: transform 0.5s ease-in-out;
        }

        .portrait-container img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            transition: transform 0.5s ease-in-out;
            cursor: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="blue" class="bi bi-collection-play-fill" viewBox="0 0 16 16"><path d="M2.5 3.5a.5.5 0 0 1 0-1h11a.5.5 0 0 1 0 1zm2-2a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zM0 13a1.5 1.5 0 0 0 1.5 1.5h13A1.5 1.5 0 0 0 16 13V6a1.5 1.5 0 0 0-1.5-1.5h-13A1.5 1.5 0 0 0 0 6zm6.258-6.437a.5.5 0 0 1 .507.013l4 2.5a.5.5 0 0 1 0 .848l-4 2.5A.5.5 0 0 1 6 12V7a.5.5 0 0 1 .258-.437"/></svg>') 16 16, auto;
        }
     
        .portrait-container {
            position: relative;
            height: 300px;
            overflow: hidden;
        }

        .portrait-container:hover img {
            transform: scale(1.05);
        }
        .hover-yellow:hover { background-color: #FFFACD; }
        .hover-orange:hover { background-color: #FFDAB9; }
        .hover-skyblue:hover { background-color: #ADD8E6; }
        .hover-lightred:hover { background-color: #FFC0CB; }
        /* Responsive */
        @media (max-width: 768px) {
            .portrait-section {
                flex-wrap: wrap;
            }
            .portrait-container {
                flex-basis: 100%;
            }
        }
    </style>
</head>
<body>
    <?php include_once('includes/sidebar.php'); ?>
    <?php include_once('includes/header.php'); ?>
    <?php include_once('includes/userheader.php'); ?>
    <?php if ($licenseStatusMessage): ?>
                            <div class="notification"><?php echo $licenseStatusMessage; ?></div>
                        <?php endif; ?>
        <!-- Content -->
        <div class="content">
            <!-- Animated -->
            <div class="animated fadeIn">
                <!-- Widgets  -->
                <div class="row">
                    <div class="col-lg-1">
                            <div class="card-body " id="notificationCard">
                                <h2>Welcome! <?php echo $firstName; $lastName;?> <?php echo $lastName; ?></h2>
                        </div>
                    </div>
                </div>
                <!-- /Widgets -->
            </div>
            <!-- .animated -->
        </div>

        <!-- Notification card with disappearing effect -->
        <?php if ($regValidityStatus == 0): ?>
            <div  class="notification" style="margin-left: 25em; position: absolute;">
                <?php echo $licenseStatusMessage; ?>
            </div>
        <?php endif; ?>

    <div class="carousel-container"style="margin-top: -70px;">
        <div class="carousel">
            <img src="images/tem.png" alt="Slide 1">
            <img src="images/temp.png" alt="Slide 2">
            <img src="images/tempo.png" alt="Slide 3">
            <img src="images/tempor.png" alt="Slide 4">
            <img src="images/tempora.png" alt="Slide 5">
        </div>
        <!-- Progress bar with slide number -->
        <div class="progress-bar">
            <div class="slide-number">1 / 5</div> 
        </div>
    </div>

   <!-- <div class="scrollable-images">
    <h2> PROPOSED PARKING AREAS</h2>
    <p> This is the proposed open parking space to be approved by Dr. Rosemary Almacen-CTU Danao Campus Director. The source of this parking areas is the </p>
    <img src="images/allArea.png" alt="Image 6">

    <hr class="section-divider"> 

    <h4> Area A</h4>
    <p> This is the proposed open parking space to be approved by Dr. Rosemary Almacen-CTU Danao Campus Director. The source of this parking areas is the </p>
    <img src="images/areaA.png" alt="Image 7">
    <hr class="section-divider"> 

    <h4> Area B</h4>
    <p> This is the proposed open parking space to be approved by Dr. Rosemary Almacen-CTU Danao Campus Director. The source of this parking areas is the </p>
    <img src="images/areaB.png" alt="Image 8">

    <hr class="section-divider"> 

    <h4> Area C</h4>
    <p> This is the proposed open parking space to be approved by Dr. Rosemary Almacen-CTU Danao Campus Director. The source of this parking areas is the </p>
    <img src="images/areaC.png" alt="Image 9">

    <hr class="section-divider"> 

    <h4> Area D</h4>
    <p> This is the proposed open parking space to be approved by Dr. Rosemary Almacen-CTU Danao Campus Director. The source of this parking areas is the </p>
    <img src="images/areaD.png" alt="Image 10">
</div> -->

<!-- Section 1 -->
<div class="section">
    <div class="title">CTU DANAO PARKING SYSTEM OVERVIEW</div>
    <div class="slider-container">
        <div class="slider" id="slider1">
            <img src="images/allArea.png" alt="Slide 1" class="landscape">
            <img src="images/areaA.png" alt="Slide 2" class="landscape">
            <img src="images/areaB.png" alt="Slide 3" class="landscape">
            <img src="images/areaC.png" alt="Slide 4" class="landscape">
            <img src="images/areaD.png" alt="Slide 5" class="landscape">
            <img src="images/clienthc.png" alt="Slide 6" class="landscape">
        </div>
    </div>
</div>

<!-- Section 2 -->
<div class="section">
    <div class="title">AUDIENCE AND SCOPE</div>
    <div class="slider-container">
        <div class="slider" id="slider2">
            <img src="images/1.png" alt="Slide 1" class="landscape">
            <img src="images/2.png" alt="Slide 2" class="landscape">
            <img src="images/3.png" alt="Slide 3" class="landscape">
            <img src="images/4.png" alt="Slide 4" class="landscape">
            <img src="images/5.png" alt="Slide 5" class="landscape">
            <img src="images/6.png" alt="Slide 6" class="landscape">
        </div>
    </div>
</div>

<!-- Section 3 -->
<div class="section">
    <div class="title">PROPOSE PARKING AREAS</div>
    <div class="slider-container">
        <div class="slider" id="slider3">
            <img src="images/6.png" alt="Slide 1" class="landscape">
            <img src="images/5.png" alt="Slide 2" class="landscape">
            <img src="images/4.png" alt="Slide 3" class="landscape">
            <img src="images/3.png" alt="Slide 4" class="landscape">
            <img src="images/2.png" alt="Slide 5" class="landscape">
            <img src="images/1.png" alt="Slide 6" class="landscape">
        </div>
    </div>
</div>

<!-- Sections 4-7 -->
<div class="section">
    <div class="title">ADMIN AND CLIENT FEATURES</div>
    <div class="portrait-section">
        <div class="portrait-container hover-yellow">
            <img src="images/admin1.png" alt="Section 4">
            <img src="images/admin2.png" alt="Section 4">
            <img src="images/admin3.png" alt="Section 4">
            <img src="images/admin4.png" alt="Section 4">
        </div>
        <div class="portrait-container hover-orange">
            <img src="images/admin5.png" alt="Section 5">
            <img src="images/admin6.png" alt="Section 5">
            <img src="images/admin7.png" alt="Section 5">
            <img src="images/admin8.png" alt="Section 5">

        </div>
        <div class="portrait-container hover-skyblue">
            <img src="images/user1.png" alt="Section 6">
            <img src="images/user2.png" alt="Section 6">
            <img src="images/user3.png" alt="Section 6">
            <img src="images/user4.png" alt="Section 6">
        </div>
        <div class="portrait-container hover-lightred">
            <img src="images/user5.png" alt="Section 7">
            <img src="images/user6.png" alt="Section 7">
            <img src="images/user7.png" alt="Section 7">
            <img src="images/user8.png" alt="Section 7">
        </div>
    </div>
</div>


    <div class="clearfix"></div>
<!-- Footer -->
<?php include_once('includes/footer.php'); ?>
</div>

<!-- /#right-panel -->

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../admin/assets/js/main.js"></script>
<script src="../admin/assets/js/main.js"></script>

<script>
const carousel = document.querySelector('.carousel');
const carouselContainer = document.querySelector('.carousel-container');
const images = document.querySelectorAll('.carousel img');
const progressBar = document.querySelector('.progress-bar');
const slideNumber = document.querySelector('.slide-number');
const intervalTime = 5000; 
let index = 0;
let intervalId; 

function startSlideshow() {
    intervalId = setInterval(() => {
        index++;
        if (index >= images.length) {
            index = 0; 
        }

        carousel.style.transform = `translateX(-${index * 100}%)`;

        const progress = ((index + 1) / images.length) * 100;
        progressBar.style.width = `${progress}%`;

        slideNumber.textContent = `${index + 1} / ${images.length}`;
    }, intervalTime);
}

function pauseSlideshow() {
    clearInterval(intervalId);
}

startSlideshow();

carouselContainer.addEventListener('mouseenter', pauseSlideshow);
carouselContainer.addEventListener('mouseleave', startSlideshow);

// Hide and remove the notification card after 10 seconds
setTimeout(function() {
    var notificationCard = document.getElementById('notificationCard');
    if (notificationCard) {
        notificationCard.style.transition = 'opacity 0.5s'; // Add transition effect
        notificationCard.style.opacity = '0'; // Fade out the card

        // After the fade-out effect, remove the element from the DOM
        setTimeout(function() {
            notificationCard.remove(); // Remove the card element
        }, 500); // Wait for the fade-out effect before removing
    }
}, 10000); // 10 seconds in milliseconds

// Automatic Sliding Functionality for Sections 1-3
const sliders = document.querySelectorAll('.slider');
    sliders.forEach(slider => {
        let idx = 0;
        setInterval(() => {
            if (document.querySelector(':hover') !== slider) {
                idx++;
                const images = slider.querySelectorAll('img');
                slider.style.transform = `translateX(-${(idx % images.length) * 100}%)`;
            }
        }, 7000); // Adjust duration here for all sliders
    });

    // Sliding on Hover for Portrait Sections (4-7)
    const portraitContainers = document.querySelectorAll('.portrait-container');
    portraitContainers.forEach(container => {
        const images = container.querySelectorAll('img');
        let idx = 0;
        let interval;

        // Function to start sliding
        const startSliding = () => {
            interval = setInterval(() => {
                idx = (idx + 1) % images.length;
                images.forEach((img, i) => {
                    img.style.transform = `translateY(${i === idx ? '0' : '-100%'})`;
                });
            }, 2000); // Adjust duration for each slide
        };

        // Function to reset to the first image
        const resetToFirstImage = () => {
            clearInterval(interval);
            idx = 0;
            images.forEach((img, i) => {
                img.style.transform = `translateY(${i === 0 ? '0' : '-100%'})`;
            });
        };

        // Hover events
        container.addEventListener('mouseenter', startSliding);
        container.addEventListener('mouseleave', resetToFirstImage);

        // Initial reset to ensure correct state
        resetToFirstImage();
    });
</script>

</body>
</html>
