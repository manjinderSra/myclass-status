<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $status }} | My Class Status</title>
    <link rel="icon" href="{{ asset('landing/img/fav.png') }}">
    <style>
        :root {
            --primary: #39a85c;
            --primary-light: #5fcf80;
            --ink: #17252f;
            --muted: #64717a;
            --surface: #ffffff;
            --background: #f5faf7;
            --line: #e2ece5;
        }

        * { box-sizing: border-box; }

        body {
            min-height: 100vh;
            margin: 0;
            color: var(--ink);
            background:
                radial-gradient(circle at 12% 16%, rgba(95, 207, 128, .18), transparent 25rem),
                radial-gradient(circle at 88% 84%, rgba(95, 207, 128, .12), transparent 23rem),
                var(--background);
            font-family: "Segoe UI", Arial, sans-serif;
        }

        .shell {
            display: flex;
            width: min(100% - 32px, 1080px);
            min-height: 100vh;
            margin: 0 auto;
            flex-direction: column;
        }

        header {
            display: flex;
            padding: 24px 0;
            align-items: center;
            justify-content: space-between;
        }

        .logo img {
            display: block;
            width: auto;
            height: 48px;
        }

        .help-link {
            color: var(--muted);
            font-size: 14px;
            font-weight: 650;
            text-decoration: none;
        }

        .help-link:hover { color: var(--primary); }

        main {
            display: grid;
            margin: auto 0;
            padding: 48px 0 80px;
            align-items: center;
            grid-template-columns: 1fr minmax(320px, .78fr);
            gap: 80px;
        }

        .status-label {
            display: inline-flex;
            padding: 8px 14px;
            align-items: center;
            gap: 8px;
            color: var(--primary);
            border: 1px solid rgba(57, 168, 92, .2);
            border-radius: 999px;
            background: rgba(255, 255, 255, .78);
            font-size: 13px;
            font-weight: 750;
            letter-spacing: .05em;
            text-transform: uppercase;
        }

        .status-label::before {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--primary-light);
            content: "";
        }

        h1 {
            margin: 20px 0 0;
            font-size: clamp(40px, 6vw, 68px);
            line-height: 1.04;
            letter-spacing: -.045em;
        }

        .message {
            max-width: 620px;
            margin: 22px 0 0;
            color: var(--muted);
            font-size: 18px;
            line-height: 1.7;
        }

        .actions {
            display: flex;
            margin-top: 32px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .button {
            display: inline-flex;
            min-height: 50px;
            padding: 0 22px;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--line);
            border-radius: 12px;
            color: var(--ink);
            background: var(--surface);
            box-shadow: 0 7px 20px rgba(23, 37, 47, .06);
            font-size: 15px;
            font-weight: 700;
            text-decoration: none;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(23, 37, 47, .1);
        }

        .button-primary {
            color: #ffffff;
            border-color: var(--primary);
            background: var(--primary);
        }

        .visual {
            position: relative;
            display: flex;
            min-height: 360px;
            align-items: center;
            justify-content: center;
        }

        .status-number {
            position: absolute;
            color: rgba(95, 207, 128, .14);
            font-size: clamp(170px, 22vw, 280px);
            font-weight: 900;
            letter-spacing: -.08em;
            line-height: 1;
            user-select: none;
        }

        .notice-card {
            position: relative;
            width: min(310px, 86vw);
            padding: 40px 32px;
            border: 1px solid rgba(255, 255, 255, .9);
            border-radius: 24px;
            background: rgba(255, 255, 255, .9);
            box-shadow: 0 28px 70px rgba(35, 73, 48, .16);
            text-align: center;
            backdrop-filter: blur(10px);
        }

        .icon {
            display: grid;
            width: 76px;
            height: 76px;
            margin: 0 auto 20px;
            place-items: center;
            border-radius: 22px;
            color: #ffffff;
            background: linear-gradient(145deg, var(--primary-light), var(--primary));
            box-shadow: 0 14px 30px rgba(57, 168, 92, .25);
            font-size: 34px;
            font-weight: 800;
        }

        .notice-card strong {
            display: block;
            font-size: 18px;
        }

        .notice-card span {
            display: block;
            margin-top: 8px;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.5;
        }

        footer {
            padding: 0 0 24px;
            color: #89949b;
            font-size: 13px;
            text-align: center;
        }

        @media (max-width: 780px) {
            main {
                grid-template-columns: 1fr;
                gap: 28px;
                text-align: center;
            }

            .visual {
                min-height: 310px;
                grid-row: 1;
            }

            .message { margin-right: auto; margin-left: auto; }
            .actions { justify-content: center; }
        }

        @media (max-width: 480px) {
            .logo img { height: 40px; }
            .help-link { display: none; }
            .actions { flex-direction: column; }
            .button { width: 100%; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { transition: none !important; }
        }
    </style>
</head>
<body>
    <div class="shell">
        <header>
            <a class="logo" href="{{ url('/') }}" aria-label="My Class Status home">
                <img src="{{ asset('landing/img/Group (2).png') }}" alt="My Class Status">
            </a>
            <a class="help-link" href="{{ url('/in/contact') }}">Need help?</a>
        </header>

        <main>
            <section aria-labelledby="error-title">
                <div class="status-label">Error {{ $status }}</div>
                <h1 id="error-title">{{ $title }}</h1>
                <p class="message">{{ $message }}</p>
                <div class="actions">
                    <a class="button button-primary" href="{{ url('/') }}">Back to homepage</a>
                    @if (!empty($showBackButton))
                        <a class="button" href="{{ url()->previous() }}">Previous page</a>
                    @endif
                </div>
            </section>

            <div class="visual" aria-hidden="true">
                <div class="status-number">{{ $status }}</div>
                <div class="notice-card">
                    <div class="icon">{{ $icon ?? '!' }}</div>
                    <strong>{{ $cardTitle ?? 'Something needs attention' }}</strong>
                    <span>{{ $cardMessage ?? 'Please use one of the options on this page to continue.' }}</span>
                </div>
            </div>
        </main>

        <footer>&copy; {{ date('Y') }} My Class Status</footer>
    </div>
</body>
</html>
