<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Maintenance Mode | {{ $settings->site_name ?? config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

    <style>
        :root {
            --navy: #07111f;
            --gold: #ffc107;
            --muted: #627084;
            --line: #dbe3ee;
            --panel: #ffffff;
            --page: #f5f7fb;
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            display: grid;
            place-items: center;
            padding: 32px 18px;
            background: var(--page);
            color: var(--navy);
            font-family: "DM Sans", Arial, sans-serif;
        }

        .maintenance-card {
            width: min(680px, 100%);
            padding: clamp(28px, 5vw, 48px);
            border: 1px solid var(--line);
            border-radius: 8px;
            background: var(--panel);
            box-shadow: 0 24px 70px rgba(7, 17, 31, 0.12);
            text-align: center;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 28px;
            font-family: "Playfair Display", Georgia, serif;
            font-size: 28px;
            font-weight: 700;
        }

        .brand-mark {
            width: 42px;
            height: 42px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background: var(--gold);
            color: var(--navy);
            font-family: Arial, sans-serif;
            font-size: 20px;
            font-weight: 800;
        }

        .eyebrow {
            margin: 0 0 12px;
            color: #0d6efd;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 0;
            text-transform: uppercase;
        }

        h1 {
            margin: 0;
            font-family: "Playfair Display", Georgia, serif;
            font-size: clamp(34px, 6vw, 56px);
            line-height: 1.05;
        }

        .message {
            max-width: 520px;
            margin: 18px auto 0;
            color: var(--muted);
            font-size: 18px;
            line-height: 1.7;
        }

        .contact {
            margin-top: 30px;
            padding-top: 24px;
            border-top: 1px solid var(--line);
            color: var(--muted);
            font-size: 15px;
        }

        .contact a {
            color: var(--navy);
            font-weight: 700;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <section class="maintenance-card" aria-labelledby="maintenance-title">
        <div class="brand">
            <span class="brand-mark">DF</span>
            <span>{{ $settings->site_name ?? 'Destination Fareways' }}</span>
        </div>

        <p class="eyebrow">Scheduled maintenance</p>
        <h1 id="maintenance-title">We will be back shortly.</h1>

        <p class="message">
            Our website is temporarily unavailable while we make updates. Please check back soon.
        </p>

        @if(!empty($settings->primary_email) || !empty($settings->primary_phone))
            <div class="contact">
                For urgent travel support,
                @if(!empty($settings->primary_phone))
                    call <a href="tel:{{ preg_replace('/[^0-9+]/', '', $settings->primary_phone) }}">{{ $settings->primary_phone }}</a>
                @endif
                @if(!empty($settings->primary_email))
                    {{ !empty($settings->primary_phone) ? 'or' : '' }}
                    email <a href="mailto:{{ $settings->primary_email }}">{{ $settings->primary_email }}</a>
                @endif
                .
            </div>
        @endif
    </section>
</body>
</html>
