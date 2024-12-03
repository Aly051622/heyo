<div id="slide-section">
    <style>
        #slide-section .section {
            margin: 20px 0;
            padding: 10px;
        }
        #slide-section .title {
            text-align: center;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        #slide-section .slider-container {
            overflow: hidden;
            position: relative;
            width: 100%;
            height: auto;
        }
        #slide-section .slider {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }
        #slide-section .slider img {
            width: 100%;
            object-fit: cover;
            pointer-events: none;
        }
        /* Hover to pause */
        #slide-section .slider-container:hover .slider {
            animation-play-state: paused;
        }
        /* Landscape Slides */
        #slide-section .slider img.landscape {
            height: 225px;
            width: 100vw;
        }
        /* Portrait Sections (Sections 4-7) */
        #slide-section .portrait-section {
            display: flex;
            gap: 10px;
            justify-content: space-between;
        }
        #slide-section .portrait-container {
            flex: 1;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }
        #slide-section .portrait-container img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            transition: transform 0.5s ease-in-out;
        }
        
        #slide-section .portrait-container img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            transition: transform 0.5s ease-in-out;
            cursor: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="blue" class="bi bi-collection-play-fill" viewBox="0 0 16 16"><path d="M2.5 3.5a.5.5 0 0 1 0-1h11a.5.5 0 0 1 0 1zm2-2a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zM0 13a1.5 1.5 0 0 0 1.5 1.5h13A1.5 1.5 0 0 0 16 13V6a1.5 1.5 0 0 0-1.5-1.5h-13A1.5 1.5 0 0 0 0 6zm6.258-6.437a.5.5 0 0 1 .507.013l4 2.5a.5.5 0 0 1 0 .848l-4 2.5A.5.5 0 0 1 6 12V7a.5.5 0 0 1 .258-.437"/></svg>') 16 16, auto;
        }
     
        #slide-section .portrait-container {
            position: relative;
            height: 300px;
            overflow: hidden;
        }

        #slide-section .portrait-container:hover img {
            transform: scale(1.05);
        }
        #slide-section .hover-yellow:hover { background-color: #FFFACD; }
        #slide-section .hover-orange:hover { background-color: #FFDAB9; }
        #slide-section .hover-skyblue:hover { background-color: #ADD8E6; }
        #slide-section .hover-lightred:hover { background-color: #FFC0CB; }
        /* Responsive */
        @media (max-width: 768px) {
            #slide-section .portrait-section {
                flex-wrap: wrap;
            }
            #slide-section .portrait-container {
                flex-basis: 100%;
            }
        }
    </style>

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
        <div class="title">PROPOSED PARKING AREAS</div>
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
</div>

<script>
    (function() {
        // Automatic Sliding Functionality for Sections 1-3
        const sliders = document.querySelectorAll('#slide-section .slider');
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
        const portraitContainers = document.querySelectorAll('#slide-section .portrait-container');
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
    })();
</script>