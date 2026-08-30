<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Page Not Found | My Class Status</title>
    <link rel="icon" href="{{ asset('landing/img/fav.png') }}">
    <style>
        :root {
            --primary: #5fcf80;
            --primary-dark: #39a85c;
            --ink: #17252f;
            --muted: #64717a;
            --surface: #ffffff;
            --line: #e5eee8;
            --background: #f5faf7;
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;
            color: var(--ink);
            background:
                radial-gradient(circle at 12% 18%, rgba(95, 207, 128, .16), transparent 24rem),
                radial-gradient(circle at 88% 82%, rgba(95, 207, 128, .12), transparent 22rem),
                var(--background);
            font-family: "Segoe UI", Arial, sans-serif;
        }

        .page-shell {
            position: relative;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            isolation: isolate;
        }

        .site-header {
            display: flex;
            width: min(1160px, calc(100% - 40px));
            margin: 0 auto;
            padding: 24px 0;
            align-items: center;
            justify-content: space-between;
        }

        .logo img {
            display: block;
            width: auto;
            height: 48px;
        }

        .header-link {
            color: var(--muted);
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: color .2s ease;
        }

        .header-link:hover {
            color: var(--primary-dark);
        }

        .error-content {
            display: grid;
            width: min(1080px, calc(100% - 40px));
            margin: auto;
            padding: 48px 0 80px;
            align-items: center;
            grid-template-columns: minmax(0, 1fr) minmax(360px, .9fr);
            gap: 80px;
        }

        .eyebrow {
            display: inline-flex;
            margin-bottom: 20px;
            padding: 8px 14px;
            align-items: center;
            gap: 8px;
            color: var(--primary-dark);
            border: 1px solid rgba(57, 168, 92, .2);
            border-radius: 999px;
            background: rgba(255, 255, 255, .78);
            font-size: 13px;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .eyebrow::before {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--primary);
            content: "";
        }

        h1 {
            max-width: 650px;
            margin: 0;
            font-size: clamp(42px, 6vw, 72px);
            line-height: 1.03;
            letter-spacing: -.045em;
        }

        .description {
            max-width: 580px;
            margin: 24px 0 0;
            color: var(--muted);
            font-size: 18px;
            line-height: 1.75;
        }

        .actions {
            display: flex;
            margin-top: 34px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .button {
            display: inline-flex;
            min-height: 50px;
            padding: 0 22px;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border: 1px solid var(--line);
            border-radius: 12px;
            color: var(--ink);
            background: var(--surface);
            box-shadow: 0 7px 20px rgba(23, 37, 47, .06);
            font-size: 15px;
            font-weight: 700;
            text-decoration: none;
            transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
        }

        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(23, 37, 47, .1);
        }

        .button-primary {
            color: #ffffff;
            border-color: var(--primary-dark);
            background: var(--primary-dark);
        }

        .button-primary:hover {
            background: #2f914e;
        }

        .visual {
            position: relative;
            min-height: 410px;
        }

        .number {
            position: absolute;
            top: 50%;
            left: 50%;
            z-index: -1;
            color: rgba(95, 207, 128, .13);
            font-size: clamp(180px, 24vw, 300px);
            font-weight: 900;
            letter-spacing: -.1em;
            line-height: 1;
            transform: translate(-52%, -52%);
            user-select: none;
        }

        .classroom-card {
            position: absolute;
            top: 50%;
            left: 50%;
            width: min(370px, 88vw);
            padding: 18px;
            border: 1px solid rgba(255, 255, 255, .8);
            border-radius: 24px;
            background: rgba(255, 255, 255, .9);
            box-shadow: 0 28px 70px rgba(35, 73, 48, .16);
            transform: translate(-50%, -50%) rotate(2deg);
            backdrop-filter: blur(10px);
        }

        .browser-bar {
            display: flex;
            height: 24px;
            align-items: center;
            gap: 6px;
        }

        .browser-bar span {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #d8e5dc;
        }

        .browser-bar span:first-child {
            background: #ff9f8f;
        }

        .browser-bar span:nth-child(2) {
            background: #ffd478;
        }

        .browser-bar span:nth-child(3) {
            background: var(--primary);
        }

        .board {
            display: flex;
            min-height: 245px;
            padding: 34px;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            color: #ffffff;
            background: linear-gradient(145deg, #315c49, #203e33);
            box-shadow: inset 0 0 0 5px rgba(255, 255, 255, .08);
            text-align: center;
        }

        .board-mark {
            margin-bottom: 8px;
            color: #d9f8e2;
            font-family: Georgia, serif;
            font-size: 68px;
            font-weight: 700;
            line-height: 1;
        }

        .board p {
            margin: 0;
            color: rgba(255, 255, 255, .78);
            font-size: 15px;
            line-height: 1.5;
        }

        .shape {
            position: absolute;
            border-radius: 18px;
            background: var(--primary);
            box-shadow: 0 12px 30px rgba(57, 168, 92, .2);
        }

        .shape-one {
            top: 28px;
            right: 2px;
            width: 58px;
            height: 58px;
            transform: rotate(18deg);
        }

        .shape-two {
            bottom: 42px;
            left: 3px;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffd478;
        }

        .site-footer {
            padding: 0 20px 24px;
            color: #89949b;
            font-size: 13px;
            text-align: center;
        }

        @media (max-width: 820px) {
            .error-content {
                padding-top: 30px;
                grid-template-columns: 1fr;
                gap: 28px;
                text-align: center;
            }

            .description {
                margin-right: auto;
                margin-left: auto;
            }

            .actions {
                justify-content: center;
            }

            .visual {
                min-height: 360px;
                grid-row: 1;
            }
        }

        @media (max-width: 480px) {
            .site-header,
            .error-content {
                width: min(100% - 28px, 1080px);
            }

            .logo img {
                height: 40px;
            }

            .header-link {
                display: none;
            }

            .visual {
                min-height: 315px;
            }

            .classroom-card {
                padding: 12px;
            }

            .board {
                min-height: 210px;
                padding: 24px;
            }

            .actions {
                flex-direction: column;
            }

            .button {
                width: 100%;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                transition: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="page-shell">
        <header class="site-header">
            <a class="logo" href="{{ route('landing.index') }}" aria-label="My Class Status home">
                <img src="{{ asset('landing/img/Group (2).png') }}" alt="My Class Status">
            </a>
            <a class="header-link" href="{{ route('landing.contact') }}">Need help?</a>
        </header>

        <main class="error-content">
            <section aria-labelledby="error-title">
                <div class="eyebrow">Error 404</div>
                <h1 id="error-title">Looks like this page missed class.</h1>
                <p class="description">
                    The page you requested could not be found. It may have moved, been removed, or the address may be incorrect.
                </p>
                <div class="actions">
                    <a class="button button-primary" href="{{ route('landing.index') }}">
                        <span aria-hidden="true">&#8592;</span>
                        Back to homepage
                    </a>
                    <a class="button" href="{{ url()->previous() }}">Go to previous page</a>
                </div>
            </section>

            <div class="visual" aria-hidden="true">
                <div class="number">404</div>
                <div class="shape shape-one"></div>
                <div class="shape shape-two"></div>
                <div class="classroom-card">
                    <div class="browser-bar"><span></span><span></span><span></span></div>
                    <div class="board">
                        <div>
                            <div class="board-mark">404</div>
                            <p>Nothing is written on this board yet.</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <footer class="site-footer">&copy; {{ date('Y') }} My Class Status</footer>
    </div>
</body>
</html>
