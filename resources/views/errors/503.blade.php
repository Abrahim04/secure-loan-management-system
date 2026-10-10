<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Notice - PautangPro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-emerald: #10b981;
            --dash-card-bg: rgba(30, 41, 59, 0.75);
            --dash-card-border: rgba(255, 255, 255, 0.12);
            --text-main: #f8fafc;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #0b101a 0%, #0f172a 100%);
            color: var(--text-main);
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
        }

        .maintenance-card {
            background: var(--dash-card-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--dash-card-border);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
            padding: 3rem 2.5rem;
            max-width: 520px;
            width: 100%;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .maintenance-icon {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem auto;
            font-size: 2rem;
        }

        .icon-maintenance {
            background: rgba(245, 158, 11, 0.15);
            border: 1px solid rgba(245, 158, 11, 0.3);
            color: #f59e0b;
        }

        .icon-shutdown {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #ef4444;
        }

        .brand-logo-img {
            height: 42px;
            width: auto;
            object-fit: contain;
            margin-bottom: 1.5rem;
        }

        .notice-text {
            color: #cbd5e1 !important;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .btn-pill-home {
            background: linear-gradient(135deg, #0284c7 0%, #10b981 100%);
            color: white;
            border: none;
            padding: 10px 28px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-pill-home:hover {
            opacity: 0.95;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
            color: white;
        }
    </style>
</head>
<body>

    @php
        use App\Models\SystemSetting;
        $systemState = SystemSetting::get('maintenance_mode', '1');
    @endphp

    <div class="container px-3">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="maintenance-card">
                    <!-- Brand Logo -->
                    <div class="d-flex justify-content-center align-items-center gap-2 mb-3">
                        <img src="{{ asset('images/logo.png') }}" alt="PautangPro Logo" class="brand-logo-img mb-0">
                        <div class="text-start">
                            <div class="fw-bold fs-5 lh-1 text-white">
                                Pautang<span style="color: var(--primary-emerald);">Pro</span>
                            </div>
                            <div class="text-muted small mt-1" style="font-size: 0.72rem; color: #94a3b8 !important;">Your Loan, Our Priority</div>
                        </div>
                    </div>

                    @if($systemState === '2')
                        <!-- EMERGENCY SHUTDOWN STATE -->
                        <div class="maintenance-icon icon-shutdown">
                            <i class="bi bi-shield-slash-fill"></i>
                        </div>
                        <h3 class="fw-bold text-white mb-2">System Offline</h3>
                        <p class="notice-text mb-4">
                            PautangPro services are currently offline due to an emergency system shutdown. Please contact the system administrator for further assistance.
                        </p>
                    @else
                        <!-- SCHEDULED MAINTENANCE STATE -->
                        <div class="maintenance-icon icon-maintenance">
                            <i class="bi bi-tools"></i>
                        </div>
                        <h3 class="fw-bold text-white mb-2">Under Maintenance</h3>
                        <p class="notice-text mb-4">
                            We are currently performing scheduled system maintenance and upgrades. Please check back shortly.
                        </p>
                    @endif

                  <div class="pt-2">
    <form action="{{ route('logout') }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn-pill-home border-0 cursor-pointer">
            Back to Home
        </button>
    </form>
</div>

                    <div class="mt-4" style="font-size: 0.75rem; color: #64748b;">
                        &copy; {{ date('Y') }} PautangPro. All rights reserved.
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>