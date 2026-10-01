<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Court Reserve | Batangas Badminton Center</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v=1.1">
    
    <style>
        body, html { margin: 0; padding: 0; overflow-x: hidden; scroll-behavior: smooth; }
        
        .landing-bg {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.4) 100%), url('{{ asset('images/court-bg.jpg') }}');
            background-size: cover; background-position: center; z-index: -1;
        }

        .navbar {
            position: fixed; top: 0; width: 100%; z-index: 1000; box-sizing: border-box;
        }

        /* Override style.css hero-section */
        .hero-section {
            background: none !important;
            min-height: 100vh;
            display: flex; align-items: center; padding-top: 80px;
        }
        
        .hero-content h1 { font-size: 64px; margin-bottom: 5px; line-height: 1.1; }
        .hero-content .text-blue { color: #0033cc; font-weight: 800; }
        .hero-content .text-gray { color: #555; font-weight: 800; }
        .hero-content .description { margin-top: 15px; font-size: 20px; font-weight: 500; }
        
        /* About Us Section */
        .about-section {
            padding: 80px 50px;
            display: flex; justify-content: center;
        }
        .about-card-container {
            background: white; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            width: 100%; max-width: 1100px; padding: 0; position: relative;
        }
        .about-badge {
            background: #0022cc; color: white; padding: 20px 40px; font-size: 28px; font-weight: 500;
            border-radius: 20px 20px 0 0; text-align: center; text-transform: uppercase; letter-spacing: 1px;
        }
        .about-grid {
            display: grid; grid-template-columns: repeat(3, 1fr); padding: 50px 40px; gap: 30px;
        }
        .about-col {
            text-align: left; padding: 0 20px; border-right: 1px solid #eaeaea;
        }
        .about-col:last-child { border-right: none; }
        
        .about-icon-header { display: flex; align-items: center; gap: 15px; margin-bottom: 20px; }
        .about-icon-header i { font-size: 40px; color: #4b8df8; }
        .about-icon-header h3 { font-size: 32px; color: #0033cc; margin: 0; font-weight: normal; }
        
        .about-col p { color: #334155; line-height: 1.7; font-size: 16px; font-weight: 500; margin: 0; }

        /* Services Section */
        .services-section {
            padding: 80px 50px; text-align: center;
        }
        .services-header h2 { font-size: 54px; color: #002288; margin-bottom: 10px; font-weight: 800; }
        .services-header p { color: #334155; font-size: 18px; max-width: 700px; margin: 0 auto 50px; line-height: 1.6; font-weight: 500; }
        
        .services-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; max-width: 1100px; margin: 0 auto; }
        .service-card { background: white; border-radius: 24px; padding: 40px 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .service-card:hover { transform: translateY(-10px); box-shadow: 0 15px 40px rgba(0,0,0,0.12); }
        .service-card h3 { color: #001e4d; font-size: 26px; margin-bottom: 25px; font-weight: 500; }
        .service-card img { width: 100%; height: 180px; object-fit: cover; border-radius: 12px; margin-bottom: 25px; }
        .service-card p { color: #334155; line-height: 1.6; font-size: 16px; font-weight: 500; margin: 0; }

        /* Excellence Section */
        .excellence-section {
            padding: 80px 50px; max-width: 1100px; margin: 0 auto; position: relative;
        }
        .excellence-border {
            border-left: 5px solid #0033cc; padding-left: 30px;
        }
        .excellence-border h2 { font-size: 64px; color: #002288; line-height: 1.1; margin-bottom: 25px; font-weight: normal; }
        .excellence-border p { font-size: 18px; color: #64748b; max-width: 500px; line-height: 1.6; font-weight: 500; }

        /* Find Us Section */
        .findus-section {
            background: white; border-radius: 60px 60px 0 0; padding: 80px 50px 0 50px; margin-top: 40px; box-shadow: 0 -10px 40px rgba(0,0,0,0.03);
        }
        .findus-container {
            display: flex; max-width: 1100px; margin: 0 auto; gap: 60px; align-items: center;
        }
        .findus-left { flex: 1; display: flex; flex-direction: column; }
        .findus-left h2 { font-size: 42px; color: #002288; margin: 0 0 20px 0; font-weight: 600; }
        .findus-map-img { width: 100%; max-width: 500px; border-radius: 10px; align-self: flex-end; }
        
        .findus-right { flex: 1; padding-left: 20px; }
        .findus-right h2 { font-size: 42px; color: #002288; margin-bottom: 15px; line-height: 1.1; font-weight: 600; }
        .findus-right p { font-size: 16px; color: #64748b; margin-bottom: 30px; font-weight: 500; }
        .btn-map { background: #1d4ed8; color: white; border: none; padding: 14px 32px; border-radius: 8px; font-size: 16px; cursor: pointer; text-decoration: none; display: inline-block; font-weight: 600; transition: 0.3s; }
        .btn-map:hover { background: #1e3a8a; }

        /* Footer */
        footer {
            background: white; padding: 80px 50px 30px;
        }
        .footer-grid {
            display: grid; grid-template-columns: 1.5fr 1fr 1fr; gap: 40px; max-width: 1100px; margin: 0 auto; border-bottom: 1px solid #e2e8f0; padding-bottom: 40px;
        }
        .footer-col img { height: 50px; margin-bottom: 20px; }
        .footer-col > p { color: #334155; line-height: 1.6; font-size: 15px; font-weight: 500; max-width: 90%; margin: 0; }
        .footer-col h3 { color: #1e3a8a; font-size: 22px; margin: 0 0 25px 0; font-weight: 500; }
        
        .contact-item { display: flex; align-items: center; gap: 15px; margin-bottom: 20px; color: #334155; font-weight: 500; font-size: 15px; }
        .contact-item i { background: #1e3a8a; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 14px; flex-shrink: 0; }
        
        .social-item { display: flex; align-items: center; gap: 15px; color: #334155; font-weight: 500; font-size: 15px; text-decoration: none; }
        .social-item i { color: #1e3a8a; font-size: 32px; }
        
        .footer-bottom { display: flex; justify-content: center; gap: 30px; max-width: 1100px; margin: 0 auto; padding-top: 30px; color: #64748b; font-size: 14px; font-weight: 500; }
        .footer-bottom a { color: #64748b; text-decoration: none; }

        /* Large Screen Text Adjustments */
        @media (min-width: 1400px) {
            .hero-content h1 { font-size: 80px; }
            .hero-content .subtitle { font-size: 26px; }
            .hero-content .description { font-size: 24px; }
            .about-col p { font-size: 18px; }
            .about-icon-header h3 { font-size: 38px; }
            .services-header h2 { font-size: 64px; }
            .services-header p { font-size: 20px; max-width: 900px; }
            .service-card h3 { font-size: 30px; }
            .service-card p { font-size: 18px; }
            .excellence-border h2 { font-size: 72px; }
            .excellence-border p { font-size: 22px; max-width: 700px; }
            .findus-left h2, .findus-right h2 { font-size: 52px; }
            .findus-right p { font-size: 18px; }
            .btn-primary { font-size: 20px; padding: 15px 40px; }
        }

        /* Mobile Adjustments */
        @media (max-width: 992px) {
            .about-grid, .services-grid, .findus-container, .footer-grid { grid-template-columns: 1fr; }
            .about-col { border-right: none; border-bottom: 1px solid #eaeaea; padding: 0 0 30px 0; margin-bottom: 30px; }
            .about-col:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
            .findus-left h2 { display: none; } /* Hide left title on mobile to not duplicate */
            .findus-map-img { align-self: flex-start; max-width: 100%; }
            .findus-right { padding-left: 0; }
            .hero-content h1 { font-size: 48px; }
            .excellence-border h2 { font-size: 42px; }
        }
        
        @media (max-width: 768px) {
            .navbar { padding: 15px 20px; }
            .hero-section { padding: 100px 20px 40px; min-height: auto; }
            .about-section, .services-section, .excellence-section { padding: 40px 20px; }
            .about-badge { font-size: 22px; padding: 15px 20px; }
            .about-grid { padding: 30px 20px; }
            .findus-section { padding: 40px 20px 0; border-radius: 30px 30px 0 0; }
            footer { padding: 40px 20px 20px; }
            .footer-bottom { flex-direction: column; text-align: center; gap: 15px; }
        }
    </style>
</head>
<body>
    
    <div class="landing-bg"></div>

    <header class="navbar">
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="Batangas Badminton Logo" height="50">
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="#home" class="active">Home</a></li>
                <li><a href="#about">About Us</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#contact">Contact Us</a></li>
            </ul>
        </nav>
        <a href="{{ url('/login') }}" style="text-decoration: none;">
            <button class="btn-signin">Sign In</button>
        </a>
    </header>

    <main>
        <!-- Hero Section -->
        <section id="home" class="hero-section">
            <div class="hero-content">
                <p class="subtitle">Welcome to</p>
                <h1><span class="text-blue">Batangas Badminton</span><br><span class="text-gray">Court Reserve</span></h1>
                <p class="description">Book your badminton or pickleball court in just a few clicks.</p>
                <a href="{{ url('/login') }}" style="text-decoration: none;">
                    <button class="btn-primary" style="margin-top: 15px;">Reserve Now &rarr;</button>
                </a>
            </div>
        </section>

        <!-- About Us Section -->
        <section id="about" class="about-section">
            <div class="about-card-container">
                <div class="about-badge">ABOUT US</div>
                <div class="about-grid">
                    <div class="about-col">
                        <div class="about-icon-header">
                            <i class="fa-solid fa-bullseye"></i>
                            <h3>Mission</h3>
                        </div>
                        <p>Our goal is to make booking a badminton court at Batangas Badminton Center simple and stress-free. We also aim to bring people together through the love of badminton, promoting health to help them stay active and connected within the community.</p>
                    </div>
                    <div class="about-col">
                        <div class="about-icon-header">
                            <i class="fa-solid fa-eye"></i>
                            <h3>Vision</h3>
                        </div>
                        <p>We aim to nurture a vibrant badminton community in Batangas Badminton Center where players of all ages and skill levels connect, grow, and thrive through the shared love of the sport.</p>
                    </div>
                    <div class="about-col">
                        <div class="about-icon-header">
                            <i class="fa-solid fa-chart-line"></i>
                            <h3>Goal</h3>
                        </div>
                        <p>The reservation system makes court booking easier and faster, reducing time and effort for players and staff. It supports skill development by giving players more chances to train and improve. By automating the process, it reduces booking errors and improves overall time management. Also, it provides a secure, user-friendly, and efficient platform designed to give badminton players a smooth and hassle-free experience.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section id="services" class="services-section">
            <div class="services-header">
                <h2>What we have</h2>
                <p>Our purpose is to provide a space that encourages growth, teamwork, and perseverance, helping individuals reach their personal goals in both fitness and athletic performance.</p>
            </div>
            
            <div class="services-grid">
                <div class="service-card">
                    <h3>Tactics Training</h3>
                    <img src="{{ asset('images/tactics.png') }}" alt="Tactics Training">
                    <p>Master game-winning, positioning and opponent analysis with structured group 1-on-1 sessions. Optimize your court awareness and decision-making</p>
                </div>
                <div class="service-card">
                    <h3>Mental Training</h3>
                    <img src="{{ asset('images/mental.png') }}" alt="Mental Training">
                    <p>Build resilience, focus, and competitive grit. Master pressure management and self-confidence through targeted exercises and psychology workshops</p>
                </div>
                <div class="service-card">
                    <h3>Skill Training</h3>
                    <img src="{{ asset('images/skill.png') }}" alt="Skill Training">
                    <p>Refine mechanics, footwork, and strokes with technical precision. Improve consistency, power, and agility for tournament-level play.</p>
                </div>
            </div>
        </section>

        <!-- Excellence Section -->
        <section class="excellence-section">
            <div class="excellence-border">
                <h2>Service Excellence,<br>Building Community.<br>Since 2003</h2>
                <p>Batangas Badminton Center has been dedicated safe, organized, and well-mainted facilities for training, recreation, and competition</p>
            </div>
        </section>

        <!-- Find Us Section -->
        <section id="contact" class="findus-section">
            <div class="findus-container">
                <div class="findus-left">
                    <h2>FIND US</h2>
                    <!-- Embed interactive Google Map with Red Pin -->
                    <iframe src="https://maps.google.com/maps?q=Batangas%20Badminton%20And%20Fitness%20Gym%20Center,%20Batangas&t=&z=16&ie=UTF8&iwloc=&output=embed" width="100%" height="300" style="border:0; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="findus-map-img"></iframe>
                </div>
                <div class="findus-right">
                    <h2>Batangas City<br>Location</h2>
                    <p>Q355+PC7, D. Silang, Batangas, 4200 Batangas</p>
                    <a href="https://maps.google.com" target="_blank" class="btn-map">View on Google Maps</a>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer>
            <div class="footer-grid">
                <div class="footer-col">
                    <img src="{{ asset('images/logo.png') }}" alt="Batangas Badminton Logo">
                    <p>Batangas Badminton Center and Fitness Gym Center is the best website that provides a reliable and efficient court reservation system in Batangas City.</p>
                </div>
                <div class="footer-col">
                    <h3>Get in touch</h3>
                    <div class="contact-item">
                        <i class="fa-solid fa-envelope"></i>
                        <span>courtreservebatangas@gmail.com</span>
                    </div>
                    <div class="contact-item">
                        <i class="fa-solid fa-phone"></i>
                        <span>09123456789</span>
                    </div>
                    <div class="contact-item">
                        <i class="fa-solid fa-location-dot"></i>
                        <span>Q355+PC7, D. Silang, Batangas, 4200 Batangas</span>
                    </div>
                </div>
                <div class="footer-col">
                    <h3>Follow Us</h3>
                    <a href="#" class="social-item">
                        <i class="fa-brands fa-facebook"></i>
                        <span>Batangas Badminton Center and Fitness Gym Center</span>
                    </a>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div>&copy; 2026 Court Reserve. All rights reserved.</div>
                <a href="#">Terms & Conditions</a>
            </div>
        </footer>
    </main>
    
    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Scroll spy to highlight active nav link
        const sections = document.querySelectorAll('section');
        const navLinks = document.querySelectorAll('.nav-links a');

        const observerOptions = {
            root: null,
            rootMargin: '-50% 0px -50% 0px', // Trigger when section crosses the middle of the viewport
            threshold: 0
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    navLinks.forEach(link => {
                        link.classList.remove('active');
                        if (link.getAttribute('href') === '#' + entry.target.id) {
                            link.classList.add('active');
                        }
                    });
                }
            });
        }, observerOptions);

        sections.forEach(section => {
            if (section.id) {
                observer.observe(section);
            }
        });
    </script>
</body>
</html>