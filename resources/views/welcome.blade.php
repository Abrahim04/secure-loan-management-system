@extends('layouts.guest')

@section('title', 'PautangPro — Your Loan, Our Priority')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Caveat:wght@600&display=swap" rel="stylesheet">
<style>
    :root {
        --pp-slate: #0B101A;
        --pp-crimson: #E63956;
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
            linear-gradient(90deg, rgba(15,23,42,.55) 0%, rgba(15,23,42,.18) 60%, rgba(15,23,42,.05) 100%),
            url('{{ asset('images/landing-hero.jpg') }}');
        background-size: cover;
        background-position: center;
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
</style>
@endpush

@section('content')
{{-- Header / Navigation --}}
<nav class="navbar navbar-expand-md navbar-landing sticky-top py-3">
    <div class="container-fluid px-4 px-md-5 position-relative d-flex align-items-center justify-content-between">
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <img src="{{ asset('images/logo.png') }}" alt="PautangPro" height="44" class="me-2">
            <span>
                <span class="d-block" style="font-size:1.4rem; font-weight:800; line-height:1;">
                    <span style="color:#fff;">Pautang</span><span style="color:#34d399;">Pro</span>
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
                <p class="hero-eyebrow mb-2">Welcome To</p>
                <h1 class="hero-title-teal mb-2">PautangPro</h1>
                <p class="hero-tagline-white mb-4">Your Loan, Our Priority</p>
                <p class="mb-4" style="max-width: 480px; color: #c7cbd6;">
                    A simple and secure loan management system that helps you manage loan applications, track payments, and organize financial records with ease.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('register') }}" class="btn btn-teal-pill btn-lg px-4">Get Started</a>
                    <a href="#about" class="btn btn-outline-white-pill btn-lg px-4">Learn more</a>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block position-relative" style="min-height: 320px;">
                <div class="hero-cursive-note" style="color: #a7f3d0;">
                    Better Loan Management<br>for a Brighter Future
                </div>
            </div>
        </div>
    </div>
</section>

{{-- About --}}
<section id="about" class="py-5 bg-white">
    <div class="container py-4">
        <div class="row align-items-center g-4">
            <div class="col-md-6">
                <h2 class="fw-bold mb-3">About PautangPro</h2>
                <p class="text-muted">
                    PautangPro is a secure loan management platform built for straightforward borrowing and lending.
                    Borrowers can apply for loans, follow their payment schedule, and pay conveniently through GCash.
                    Every account is protected with Email OTP multi-factor authentication, and every action is tracked
                    through detailed audit logs for full transparency.
                </p>
            </div>
            <div class="col-md-6">
                <div class="row g-4">
                    <div class="col-6">
                        <div class="feature-icon-crimson">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="10" width="16" height="10" rx="2"></rect><path d="M8 10V7a4 4 0 0 1 8 0v3"></path></svg>
                        </div>
                        <h6 class="fw-bold mb-1">Secure &amp; Reliable</h6>
                        <p class="small text-muted mb-0">Your data is protected with advanced security and privacy features.</p>
                    </div>
                    <div class="col-6">
                        <div class="feature-icon-crimson">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2h9l5 5v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z"></path><path d="M14 2v6h6"></path><line x1="8" y1="13" x2="16" y2="13"></line><line x1="8" y1="17" x2="16" y2="17"></line></svg>
                        </div>
                        <h6 class="fw-bold mb-1">Easy Loan Tracking</h6>
                        <p class="small text-muted mb-0">Monitor applications, payments, and due dates in real time.</p>
                    </div>
                    <div class="col-6">
                        <div class="feature-icon-crimson">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
                        </div>
                        <h6 class="fw-bold mb-1">Organized Records</h6>
                        <p class="small text-muted mb-0">Keep all your financial records in one place, well organized and accessible.</p>
                    </div>
                    <div class="col-6">
                        <div class="feature-icon-crimson">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                        </div>
                        <h6 class="fw-bold mb-1">User Friendly</h6>
                        <p class="small text-muted mb-0">Simple and intuitive interface for both borrowers and administrators.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Services --}}
<section id="services" class="py-5" style="background:#f4f6f9;">
    <div class="container py-4">
        <h2 class="fw-bold text-center mb-5">What You Can Do</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card service-card shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold">Apply for a Loan</h5>
                        <p class="text-muted mb-0">Choose a loan type, request the amount and term you need, and see your estimated monthly payment before you apply.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card service-card shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold">Pay via GCash</h5>
                        <p class="text-muted mb-0">Submit your reference number and receipt after paying, and get notified the moment your payment is verified.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card service-card shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold">Track Everything</h5>
                        <p class="text-muted mb-0">Follow your outstanding balance, upcoming due dates, and full payment history in one dashboard.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Contact --}}
<section id="contact" class="py-5 bg-white">
    <div class="container py-4 text-center">
        <h2 class="fw-bold mb-3">Get in Touch</h2>
        <p class="text-muted mb-4">Have questions? We're here to help.</p>

        {{-- Placeholder contact details — replace with your real support channels. --}}
        <div class="d-flex flex-column flex-sm-row justify-content-center align-items-center gap-4">
            <div class="d-flex align-items-center">
                <span class="contact-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"></rect><path d="m2 7 10 6 10-6"></path></svg>
                </span>
                <span>support@pautangpro.example</span>
            </div>
            <div class="d-flex align-items-center">
                <span class="contact-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                </span>
                <span>(+63) 900-000-0000</span>
            </div>
        </div>
    </div>
</section>

<footer class="py-4 text-center text-white" style="background:#0B101A;">
    <small>&copy; {{ date('Y') }} PautangPro. All rights reserved.</small>
</footer>

@push('scripts')
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