@extends('layouts.guest')

@section('title', 'PautangPro — Your Loan, Our Priority')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Caveat:wght@600&display=swap" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
<style>
    :root {
        --pp-slate: #0B101A;
        --pp-crimson: #10b981;
    }

    /* --- Navbar --- */
    .navbar-landing {
        background: rgba(11, 16, 26, 0.85);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        z-index: 1030;
    }
    .navbar-landing .nav-link {
        color: #fff;
        position: relative;
        padding-bottom: 4px;
        transition: color .2s ease;
    }
    .navbar-landing .nav-link::after {
        content: '';
        position: absolute;
        left: 0; bottom: 0;
        width: 0;
        height: 2px;
        background: var(--pp-crimson);
        transition: width .25s ease;
    }
    .navbar-landing .nav-link:hover,
    .navbar-landing .nav-link.active { color: #fff; }
    .navbar-landing .nav-link.active::after,
    .navbar-landing .nav-link:hover::after { width: 100%; }

    @media (min-width: 768px) {
        .nav-center-links {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            margin: 0 !important;
        }
    }
    header, .navbar, nav {
        position: fixed !important;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1000; /* Sinisiguradong nasa ibabaw ito ng lahat ng content */
        background: rgba(15, 23, 42, 0.75) !important; /* Glassmorphism background */
        backdrop-filter: blur(12px) !important; /* Glass blur effect */
        -webkit-backdrop-filter: blur(12px) !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    /* --- Buttons --- */
    .btn-crimson-pill {
        background: var(--pp-crimson);
        border: none;
        color: #fff;
        border-radius: 999px;
        transition: filter .2s ease, transform .2s ease;
    }
    .btn-crimson-pill:hover, .btn-crimson-pill:focus {
        filter: brightness(1.1);
        transform: translateY(-1px);
        color: #fff;
    }
    .btn-outline-white-pill {
        background: transparent;
        border: 1px solid rgba(255,255,255,.6);
        color: #fff;
        border-radius: 999px;
        transition: background .2s ease;
    }
    .btn-outline-white-pill:hover, .btn-outline-white-pill:focus {
        background: rgba(255,255,255,.1);
        color: #fff;
    }

    /* --- Hero --- */
    .hero-section {
        min-height: 95vh;
        display: flex;
        align-items: center;
        color: #fff;
    }
    .hero-slate-bg {
    background-image: 
        linear-gradient(90deg, rgba(15, 23, 42, .55) 0%, rgba(15, 23, 42, .18) 60%, rgba(15, 23, 42, .05) 100%), 
        url('{{ asset('images/landing-hero.png') }}');
    background-size: 100% 100%;
    background-position: center;
    background-repeat: no-repeat;
}
    .hero-eyebrow {
        font-size: 1.35rem;
        font-weight: 600;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #e2e6f0;
    }
    .hero-title-teal {
        font-size: clamp(2.75rem, 6.5vw, 4.25rem);
        font-weight: 800;
        line-height: 1.05;
        letter-spacing: -.01em;
        text-transform: uppercase;
        background: linear-gradient(135deg, #ffffff 30%, #34d399 100%);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .hero-tagline-white {
        font-size: 1.4rem;
        font-weight: 600;
        color: #f2f3f7;
    }
    .btn-teal-pill {
        background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
        border: none;
        color: #fff;
        border-radius: 999px;
        transition: filter .2s ease, transform .2s ease;
    }
    .btn-teal-pill:hover, .btn-teal-pill:focus {
        filter: brightness(1.08);
        transform: translateY(-1px);
        color: #fff;
    }
    .hero-cursive-note {
        position: absolute;
        top: 12%;
        right: 4%;
        font-family: 'Caveat', cursive;
        font-size: 1.9rem;
        color: #fbdfe4;
        line-height: 1.25;
        text-align: right;
        border-bottom: 2px solid var(--pp-crimson);
        padding-bottom: .5rem;
        max-width: 260px;
    }

   .hero-section {
    min-height: 100vh !important;
    height: 100vh !important;
    width: 100% !important;
    display: flex;
    align-items: center;
    position: relative;
    overflow: hidden;
    background-color: #0d131f; /* Ito ang gagamiting kulay para hindi puti kung sakaling magkaroon man ng lag */
}

.hero-slate-bg {
    background-image: 
        linear-gradient(90deg, rgba(15, 23, 42, .55) 0%, rgba(15, 23, 42, .18) 60%, rgba(15, 23, 42, .05) 100%), 
        url('{{ asset('images/landing-hero.png') }}');
    background-size: cover !important; /* Tatakpan nito ang 100% ng screen nang walang puwang */
    background-position: center center !important;
    background-repeat: no-repeat !important;
    background-attachment: scroll !important; /* Iniiwasan nito ang white gaps sa mobile at browser resize */
}

    /* --- About feature cards --- */
    .feature-icon-crimson {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: var(--pp-crimson);
        color: #fff;
        margin-bottom: .75rem;
    }

    .service-card {
        border: none;
        border-radius: 12px;
        transition: transform .2s ease;
    }
    .service-card:hover { transform: translateY(-4px); }

    .contact-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(16, 24, 43, .08);
        color: #10182b;
        margin-right: .75rem;
        
    }

    .btn-emerald-pill {
    background: linear-gradient(135deg, #059669, #10b981);
    color: #ffffff !important;
    border-radius: 50px;
    border: none;
    transition: all 0.3s ease-in-out;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
}

.btn-emerald-pill:hover {
    background: linear-gradient(135deg, #047857, #059669);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
    color: #ffffff !important;
}

.btn-login-outline {
    background: transparent;
    color: #ffffff !important;
    border: 1px solid rgba(255, 255, 255, 0.4);
    border-radius: 50px;
    transition: all 0.3s ease-in-out;
}

.btn-login-outline:hover {
    background: rgba(255, 255, 255, 0.15);
    border-color: #34d399;
    color: #34d399 !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(52, 211, 153, 0.2);
}

.landing-section {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 80px 20px 40px 20px;
    box-sizing: border-box;
}

.bg-dark-slate {
    background-color: #0f172a !important;
    color: #ffffff !important;
}

.bg-dark-emerald {
    background-color: #064e3b !important;
    color: #ffffff !important;
}

/* About Section Background */
#about {
    min-height: 100vh;
    background-image: 
        linear-gradient(90deg, rgba(15, 23, 42, 0.85) 0%, rgba(15, 23, 42, 0.5) 50%, rgba(15, 23, 42, 0.2) 100%),
        url('{{ asset('images/about.png') }}'); /* Siguraduhing na-copy ang about.png sa public/images/ */
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    display: flex;
    align-items: center;
    position: relative;
    color: #ffffff;
}

/* Glassmorphic Cards para sa Features */
.about-glass-card {
    background: rgba(15, 23, 42, 0.45) !important;
    backdrop-filter: blur(16px) !important;
    -webkit-backdrop-filter: blur(16px) !important;
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    border-radius: 16px !important;
    padding: 24px !important;
    height: 100%;
    transition: transform 0.3s ease, border-color 0.3s ease;
}

.about-glass-card:hover {
    transform: translateY(-5px);
    border-color: rgba(16, 185, 129, 0.4) !important;
}

/* Rounded Cyan/Emerald Icon Circles */
.about-icon-circle {
    width: 54px;
    height: 54px;
    background: linear-gradient(135deg, #06b6d4, #10b981);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 16px;
    color: #ffffff;
    box-shadow: 0 4px 14px rgba(16, 185, 129, 0.3);
}

/* Pill badge sa itaas ng title */
.about-badge {
    display: inline-block;
    padding: 6px 16px;
    border-radius: 50px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    background: rgba(255, 255, 255, 0.05);
    font-size: 0.75rem;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: #cbd5e1;
    margin-bottom: 20px;
}

/* Services Section Background */
#services {
    min-height: 100vh;
    background-image: 
        linear-gradient(180deg, rgba(15, 23, 42, 0.75) 0%, rgba(15, 23, 42, 0.85) 100%),
        url('{{ asset('images/service.png') }}');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    display: flex;
    align-items: center;
    position: relative;
    color: #ffffff;
}

/* Service Pill Badge */
.services-badge {
    display: inline-block;
    padding: 6px 16px;
    border-radius: 50px;
    border: 1px solid rgba(16, 185, 129, 0.3);
    background: rgba(16, 185, 129, 0.1);
    font-size: 0.75rem;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: #10b981;
    font-weight: 600;
}

/* Glassmorphism Service Cards */
.service-glass-card {
    background: rgba(15, 23, 42, 0.5) !important;
    backdrop-filter: blur(16px) !important;
    -webkit-backdrop-filter: blur(16px) !important;
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    border-radius: 20px !important;
    padding: 32px 24px !important;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.service-glass-card:hover {
    transform: translateY(-8px);
    border-color: rgba(16, 185, 129, 0.5) !important;
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3);
}

/* Circular Gradient Icons */
.service-icon-circle {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #0ea5e9, #10b981);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-weight: bold;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
}

/* Circle Action Arrow Button */
.service-arrow-btn {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    border: 1px solid rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #10b981;
    transition: all 0.3s ease;
    margin-top: 20px;
}

.service-glass-card:hover .service-arrow-btn {
    background: #10b981;
    color: #ffffff;
    border-color: #10b981;
}

/* Contact Section Background */
#contact {
    min-height: 100vh;
    background-image: 
        linear-gradient(180deg, rgba(15, 23, 42, 0.8) 0%, rgba(15, 23, 42, 0.9) 100%),
        url('{{ asset('images/contact.png') }}');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    display: flex;
    align-items: center;
    position: relative;
    color: #ffffff;
}

/* Glassmorphic Contact Cards */
.contact-glass-card {
    background: rgba(15, 23, 42, 0.5) !important;
    backdrop-filter: blur(16px) !important;
    -webkit-backdrop-filter: blur(16px) !important;
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    border-radius: 20px !important;
    padding: 28px 24px !important;
    transition: all 0.3s ease;
}

.contact-glass-card:hover {
    transform: translateY(-4px);
    border-color: rgba(16, 185, 129, 0.5) !important;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
}

/* Icon Circles */
.contact-icon-circle {
    width: 65px;
    height: 65px;
    min-width: 65px;
    background: linear-gradient(135deg, #0ea5e9, #10b981);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
}
</style>
@endpush

@section('content')
{{-- Header / Navigation --}}
<nav class="navbar navbar-expand-md navbar-landing sticky-top py-3">
    <div class="container-fluid px-4 px-md-5 position-relative d-flex align-items-center justify-content-between">
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
        <img src="{{ asset('images/logo.png') }}" alt="PautangPro" height="44" class="me-2">
            <span>
                <span class="d-block" style="font-size:1.4rem; font-weight:800; line-height:1; background: linear-gradient(135deg, #ffffff 0%, #a7f3d0 45%, #34d399 75%, #10b981 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    PautangPro
                </span>
                    <span class="d-block" style="font-size:.7rem; color:#9aa1b5;">Your Loan, Our Priority</span>
            </span>
        </a>
        <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#landingNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="landingNav">
            <ul class="navbar-nav nav-center-links align-items-md-center gap-md-4 mb-3 mb-md-0">
                <li class="nav-item"><a class="nav-link" href="#hero">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
            </ul>
            <div class="d-flex gap-2 ms-md-auto">
                <a class="btn btn-login-outline btn-sm px-3" href="{{ route('login') }}">Login</a>
                <a class="btn btn-emerald-pill btn-sm px-3" href="{{ route('register') }}">Sign Up</a>
            </div>
        </div>
    </div>
</nav>

{{-- Hero --}}
<section id="hero" class="hero-section hero-slate-bg">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <p class="hero-eyebrow mb-2" data-aos="fade-down" data-aos-duration="600">Welcome To</p>
                <h1 class="hero-title-teal mb-2" data-aos="fade-up" data-aos-delay="100">PautangPro</h1>
                <p class="hero-tagline-white mb-4" data-aos="fade-up" data-aos-delay="200">Your Loan, Our Priority</p>
                <p class="mb-4" style="max-width: 480px; color: #c7cbd6;" data-aos="fade-up" data-aos-delay="300">
                    A simple and secure loan management system that helps you manage loan applications, track payments, and organize financial records with ease.
                </p>
                <div class="d-flex gap-3 flex-wrap" data-aos="fade-up" data-aos-delay="400">
                    <a href="{{ route('register') }}" class="btn btn-teal-pill btn-lg px-4">Get Started</a>
                    <a href="#about" class="btn btn-outline-white-pill btn-lg px-4">Learn more</a>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block position-relative" style="min-height: 320px;" data-aos="fade-left" data-aos-delay="500">
                <div class="hero-cursive-note" style="color: #a7f3d0;">
                    Better Loan Management<br>for a Brighter Future
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ABOUT SECTION -->
<section id="about">
    <div class="container py-5">
        <div class="row align-items-center g-5">

            <!-- Kaliwang Bahagi: Text & Headlines -->
            <div class="col-lg-6" data-aos="fade-right" data-aos-duration="800">
                <span class="about-badge" data-aos="fade-down" data-aos-delay="100">ABOUT US</span>

                <h1 class="display-4 fw-bold mb-2" data-aos="fade-up" data-aos-delay="200">
                    About <span style="background: linear-gradient(135deg, #ffffff 40%, #10b981 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">PautangPro</span>
                </h1>

                <h4 class="text-light fw-semibold mb-4" style="color: #e2e8f0 !important;" data-aos="fade-up" data-aos-delay="300">
                    Your Loan, Our Priority
                </h4>

                <p class="text-light opacity-75 mb-3" style="line-height: 1.7;" data-aos="fade-up" data-aos-delay="400">
                    PautangPro is a secure loan management platform built for students, employees, and everyday borrowers. We make loan applications, payment tracking, and record keeping simple and hassle-free so you can focus on what matters most.
                </p>

                <p class="text-light opacity-75 mb-4" style="line-height: 1.7;" data-aos="fade-up" data-aos-delay="500">
                    Our goal is to provide a transparent, reliable, and user-friendly experience, ensuring that your financial journey is organized, secure, and always within your control.
                </p>

                <div class="mt-4 pt-2" data-aos="fade-up" data-aos-delay="600">
                    <div style="width: 40px; height: 3px; background: #10b981; margin-bottom: 15px;"></div>
                    <p class="fst-italic fw-normal" style="font-family: 'hero-cursive-note', cursive; color: #6ee7b7; font-size: 1.8rem; line-height: 1.3;">
                        Better Loan Management <br>
                        for a Brighter Future
                    </p>
                </div>
            </div>

            <!-- Kanang Bahagi: 2x2 Feature Cards -->
            <div class="col-lg-6">
                <div class="row g-4">

                    <!-- Card 1: Secure & Reliable -->
                    <div class="col-md-6" data-aos="zoom-in" data-aos-delay="200">
                        <div class="about-glass-card">
                            <div class="about-icon-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                </svg>
                            </div>
                            <h5 class="fw-bold mb-2">Secure & Reliable</h5>
                            <p class="small text-light opacity-75 mb-0">Your data is protected with advanced security and privacy measures.</p>
                        </div>
                    </div>

                    <!-- Card 2: Easy Loan Tracking -->
                    <div class="col-md-6" data-aos="zoom-in" data-aos-delay="300">
                        <div class="about-glass-card">
                            <div class="about-icon-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><circle cx="10" cy="13" r="2"/><path d="M10 11v2h2"/>
                                </svg>
                            </div>
                            <h5 class="fw-bold mb-2">Easy Loan Tracking</h5>
                            <p class="small text-light opacity-75 mb-0">Monitor your applications, payments, and due dates in real-time.</p>
                        </div>
                    </div>

                    <!-- Card 3: Organized Records -->
                    <div class="col-md-6" data-aos="zoom-in" data-aos-delay="400">
                        <div class="about-glass-card">
                            <div class="about-icon-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                            </div>
                            <h5 class="fw-bold mb-2">Organized Records</h5>
                            <p class="small text-light opacity-75 mb-0">Keep all your financial records in one place, well-organized and accessible.</p>
                        </div>
                    </div>

                    <!-- Card 4: User Friendly -->
                    <div class="col-md-6" data-aos="zoom-in" data-aos-delay="500">
                        <div class="about-glass-card">
                            <div class="about-icon-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                </svg>
                            </div>
                            <h5 class="fw-bold mb-2">User Friendly</h5>
                            <p class="small text-light opacity-75 mb-0">A simple and intuitive interface for both borrowers and administrators.</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

<!-- SERVICES SECTION -->
<section id="services">
    <div class="container py-5">

        <!-- Header -->
        <div class="text-center mb-5">
            <span class="services-badge mb-3" data-aos="fade-down" data-aos-duration="600">OUR SERVICES</span>
            <h1 class="display-4 fw-bold text-white mb-3" data-aos="fade-up" data-aos-delay="100">
                What You <span style="background: linear-gradient(135deg, #10b981, #06b6d4); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Can Do</span>
            </h1>
            <p class="text-light opacity-75 mx-auto" style="max-width: 600px; font-size: 1.05rem;" data-aos="fade-up" data-aos-delay="200">
                Flexible, secure, and easy loan services designed to help you manage your financial needs anytime, anywhere.
            </p>
        </div>

        <!-- 3 Cards Row -->
        <div class="row g-4 justify-content-center">

            <!-- Service 1: Apply for a Loan -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="service-glass-card">
                    <div>
                        <div class="service-icon-circle mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>
                            </svg>
                        </div>
                        <h4 class="fw-bold text-white mb-3">Apply for a Loan</h4>
                        <p class="text-light opacity-75 small mb-0" style="line-height: 1.6;">
                            Choose a loan type, request the amount and term you need, and see your estimated monthly payment before you apply.
                        </p>
                    </div>
                    <div class="service-arrow-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Service 2: Pay via GCash -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="service-glass-card">
                    <div>
                        <div class="service-icon-circle mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 8.5A5.5 5.5 0 1 0 14 15.5H11V12h3" />
                                <path d="M18 9a4.5 4.5 0 0 1 0 6" />
                                <path d="M20.5 7a7.5 7.5 0 0 1 0 10" />
                            </svg>
                        </div>
                        <h4 class="fw-bold text-white mb-3">Pay via GCash</h4>
                        <p class="text-light opacity-75 small mb-0" style="line-height: 1.6;">
                            Submit your reference number and receipt after paying, and get notified the moment your payment is verified.
                        </p>
                    </div>
                    <div class="service-arrow-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Service 3: Track Everything -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                <div class="service-glass-card">
                    <div>
                        <div class="service-icon-circle mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                            </svg>
                        </div>
                        <h4 class="fw-bold text-white mb-3">Track Everything</h4>
                        <p class="text-light opacity-75 small mb-0" style="line-height: 1.6;">
                            Follow your outstanding balance, upcoming due dates, and full payment history in one dashboard.
                        </p>
                    </div>
                    <div class="service-arrow-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                        </svg>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- CONTACT SECTION -->
<section id="contact">
    <div class="container py-5">

        <!-- Section Header -->
        <div class="text-center mb-5">
            <span class="services-badge mb-3" data-aos="fade-down" data-aos-duration="600">GET IN TOUCH</span>
            <h1 class="display-4 fw-bold text-white mb-3" data-aos="fade-up" data-aos-delay="100">
                We're Here to <span style="background: linear-gradient(135deg, #10b981, #06b6d4); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Help</span>
            </h1>
            <p class="text-light opacity-75 mx-auto" style="max-width: 600px; font-size: 1.05rem;" data-aos="fade-up" data-aos-delay="200">
                Have questions, need assistance, or want to learn more about our services?<br>
                Feel free to reach out to us. Our team is ready to assist you.
            </p>
        </div>

        <!-- Top Row: Email & Phone -->
        <div class="row g-4 justify-content-center mb-4">

            <!-- Email Us Card -->
            <div class="col-lg-5 col-md-6" data-aos="fade-right" data-aos-delay="300">
                <div class="contact-glass-card d-flex align-items-center gap-4">
                    <div class="contact-icon-circle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
                        </svg>
                    </div>
                    <div>
                        <h5 class="fw-bold text-white mb-1">Email Us</h5>
                        <p class="text-white fw-semibold mb-1" style="font-size: 0.95rem;">support@pautangpro.example</p>
                        <p class="text-light opacity-75 small mb-0">We'll get back to you as soon as possible.</p>
                    </div>
                </div>
            </div>

            <!-- Call Us Card -->
            <div class="col-lg-5 col-md-6" data-aos="fade-left" data-aos-delay="400">
                <div class="contact-glass-card d-flex align-items-center gap-4">
                    <div class="contact-icon-circle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                    </div>
                    <div>
                        <h5 class="fw-bold text-white mb-1">Call Us</h5>
                        <p class="text-white fw-semibold mb-1" style="font-size: 0.95rem;">(+63) 900-000-0000</p>
                        <p class="text-light opacity-75 small mb-0">Our support team is available 24/7.</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Bottom Row: Visit Us -->
        <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="500">
            <div class="col-lg-10">
                <div class="contact-glass-card p-4">
                    <div class="row align-items-center">
                        
                        <!-- Left Side: Address -->
                        <div class="col-md-7 d-flex align-items-center gap-4 mb-3 mb-md-0">
                            <div class="contact-icon-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                                </svg>
                            </div>
                            <div>
                                <h5 class="fw-bold text-white mb-1">Visit Us</h5>
                                <p class="text-white fw-semibold mb-0" style="font-size: 0.95rem;">123 Business Center, Makati City,</p>
                                <p class="text-light opacity-75 small mb-0">Metro Manila, Philippines</p>
                            </div>
                        </div>

                        <!-- Right Side: Divider & Tagline -->
                        <div class="col-md-5 border-start border-secondary ps-md-4 d-none d-md-block">
                            <p class="text-light opacity-75 small mb-2">We're always here to support you.</p>
                            <div style="width: 35px; height: 3px; background: #10b981; border-radius: 2px;"></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<footer class="py-4 text-center text-white" style="background:#0B101A;">
    <small>&copy; {{ date('Y') }} PautangPro. All rights reserved.</small>
</footer>

@push('scripts')
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init({
            duration: 800,
            once: true
        });
    });
</script>
<script>
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.navbar-landing .nav-link[href^="#"]');

    const observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                const id = entry.target.getAttribute('id');
                navLinks.forEach(function (link) {
                    link.classList.toggle('active', link.getAttribute('href') === '#' + id);
                });
            }
        });
    }, { rootMargin: '-40% 0px -55% 0px', threshold: 0 });

    sections.forEach(function (section) {
        observer.observe(section);
    });
</script>
@endpush
@endsection