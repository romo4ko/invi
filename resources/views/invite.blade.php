<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Приглашение</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --accent: {{ $invite['event']['color'] ?? '#9f7a4c' }};
            --accent-soft: color-mix(in srgb, var(--accent) 36%, white);
            --paper: rgba(12, 15, 20, 0.7);
            --paper-strong: rgba(8, 10, 14, 0.88);
            --line: rgba(255, 255, 255, 0.14);
            --text-main: rgba(255, 248, 239, 0.96);
            --text-muted: rgba(255, 244, 231, 0.72);
            --success: #7ed6a2;
            --danger: #ff9c9c;
            --shadow: 0 30px 80px rgba(0, 0, 0, 0.45);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Manrope', sans-serif;
            color: var(--text-main);
            background:
                radial-gradient(circle at top left, color-mix(in srgb, var(--accent) 40%, transparent) 0%, transparent 34%),
                radial-gradient(circle at bottom right, rgba(255, 255, 255, 0.12) 0%, transparent 28%),
                linear-gradient(135deg, #0c1015 0%, #111925 42%, #1b2230 100%);
        }

        a {
            color: inherit;
            text-decoration: underline;
            text-decoration-color: currentColor;
            text-underline-offset: 0.16em;
        }

        a:hover,
        a:focus-visible {
            color: inherit;
        }

        .scene {
            position: relative;
            min-height: 100vh;
            overflow: hidden;
            isolation: isolate;
        }

        .scene::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(5, 8, 12, 0.3), rgba(5, 8, 12, 0.82)),
                url('{{ asset('storage/' . $invite['event']['image']) }}') center/cover no-repeat;
            transform: scale(1.05);
            filter: saturate(1.08) contrast(1.05) brightness(0.65);
            z-index: -3;
        }

        .scene::after {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 20% 20%, rgba(255, 255, 255, 0.14), transparent 20%),
                radial-gradient(circle at 80% 30%, color-mix(in srgb, var(--accent) 40%, transparent), transparent 24%),
                linear-gradient(180deg, rgba(7, 9, 12, 0.15), rgba(7, 9, 12, 0.5));
            z-index: -2;
        }

        .layout {
            width: min(1180px, calc(100% - 32px));
            margin: 0 auto;
            min-height: 100vh;
            display: grid;
            grid-template-columns: minmax(0, 0.95fr) minmax(320px, 1.15fr);
            gap: 32px;
            align-items: center;
            padding: 40px 0;
        }

        .hero-copy {
            align-self: stretch;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 32px 0;
            min-width: 0;
        }

        .hero-title {
            margin: 0 0 18px;
            max-width: 560px;
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2.9rem, 5.2vw, 5.4rem);
            line-height: 0.98;
            letter-spacing: -0.04em;
            text-wrap: balance;
            overflow-wrap: anywhere;
        }

        .hero-subtitle {
            max-width: 440px;
            font-size: 1.02rem;
            line-height: 1.75;
            color: var(--text-muted);
        }

        .invitation-card {
            position: relative;
            overflow: hidden;
            border-radius: 32px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.09), rgba(255, 255, 255, 0.04)),
                var(--paper);
            backdrop-filter: blur(22px);
            box-shadow: var(--shadow);
        }

        .invitation-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at top right, color-mix(in srgb, var(--accent) 26%, transparent) 0%, transparent 34%),
                linear-gradient(135deg, rgba(255, 255, 255, 0.08), transparent 36%);
            pointer-events: none;
        }

        .card-inner {
            position: relative;
            padding: clamp(28px, 5vw, 52px);
        }

        .card-topline {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 28px;
            color: var(--text-muted);
            font-size: 0.82rem;
            letter-spacing: 0.26em;
            text-transform: uppercase;
        }

        .crest {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            display: grid;
            place-items: center;
            border: 1px solid rgba(255, 255, 255, 0.14);
            background: linear-gradient(135deg, color-mix(in srgb, var(--accent) 30%, rgba(255, 255, 255, 0.12)), rgba(255, 255, 255, 0.04));
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.18);
        }

        .crest::before {
            content: "✦";
            font-size: 1.15rem;
            color: var(--text-main);
        }

        .greeting {
            margin: 0;
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2.2rem, 5vw, 4rem);
            line-height: 0.98;
            letter-spacing: -0.03em;
            text-wrap: balance;
        }

        .guest-intro {
            margin: 18px 0 0;
            max-width: 540px;
            color: var(--text-muted);
            font-size: 0.98rem;
            line-height: 1.8;
        }

        .content {
            margin: 34px 0;
            padding: 28px 0;
            border-top: 1px solid var(--line);
            border-bottom: 1px solid var(--line);
            font-size: 1.02rem;
            line-height: 1.9;
            color: rgba(255, 247, 238, 0.9);
        }

        .content :first-child {
            margin-top: 0;
        }

        .content :last-child {
            margin-bottom: 0;
        }

        .content p + p {
            margin-top: 1.1em;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 32px;
        }

        .detail-card {
            padding: 18px 18px 20px;
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.05);
        }

        .detail-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
            color: var(--text-muted);
            font-size: 0.76rem;
            letter-spacing: 0.2em;
            text-transform: uppercase;
        }

        .detail-label svg {
            width: 15px;
            height: 15px;
            color: var(--accent-soft);
        }

        .detail-value {
            font-size: 1rem;
            line-height: 1.7;
            color: var(--text-main);
        }

        .caption {
            margin: 0 0 30px;
            padding-left: 18px;
            border-left: 2px solid color-mix(in srgb, var(--accent) 74%, white);
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(1.35rem, 2vw, 1.75rem);
            line-height: 1.35;
            color: rgba(255, 245, 235, 0.94);
        }

        .response-section {
            padding: 24px;
            border-radius: 28px;
            background: var(--paper-strong);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .response-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 18px;
        }

        .response-title {
            margin: 0;
            font-size: 0.92rem;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: var(--text-muted);
        }

        .response-note {
            margin: 0;
            font-size: 0.95rem;
            line-height: 1.7;
            color: rgba(255, 247, 238, 0.78);
        }

        .response-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 18px;
        }

        .action-button {
            appearance: none;
            border: 0;
            border-radius: 999px;
            padding: 16px 24px;
            min-width: 196px;
            font: inherit;
            font-weight: 700;
            letter-spacing: 0.01em;
            transition: transform 0.25s ease, box-shadow 0.25s ease, background-color 0.25s ease, opacity 0.25s ease;
        }

        .action-button:hover {
            transform: translateY(-1px);
        }

        .action-button:focus-visible {
            outline: 2px solid rgba(255, 255, 255, 0.8);
            outline-offset: 3px;
        }

        .action-button.primary {
            background: linear-gradient(135deg, color-mix(in srgb, var(--accent) 60%, white), var(--accent));
            color: #111;
            box-shadow: 0 14px 34px color-mix(in srgb, var(--accent) 24%, transparent);
        }

        .action-button.secondary {
            background: rgba(255, 255, 255, 0.08);
            color: var(--text-main);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .action-button.ghost {
            background: rgba(255, 255, 255, 0.04);
            color: var(--text-muted);
            border: 1px solid rgba(255, 255, 255, 0.1);
            min-width: 180px;
        }

        .response-message {
            margin: 0;
            font-size: 1rem;
            line-height: 1.75;
        }

        .response-message.success {
            color: var(--success);
        }

        .response-message.danger {
            color: var(--danger);
        }

        @media (max-width: 980px) {
            .layout {
                grid-template-columns: 1fr;
                gap: 24px;
                padding: 24px 0;
            }

            .hero-copy {
                padding: 4px 6px;
            }
        }

        @media (min-width: 981px) {
            .layout {
                grid-template-columns: minmax(0, 0.88fr) minmax(380px, 1.12fr);
            }
        }

        @media (max-width: 720px) {
            .layout {
                width: min(100% - 20px, 640px);
            }

            .hero-title {
                max-width: none;
            }

            .details-grid {
                grid-template-columns: 1fr;
            }

            .card-topline,
            .response-head {
                flex-direction: column;
                align-items: flex-start;
            }

            .response-buttons {
                flex-direction: column;
            }

            .action-button {
                width: 100%;
                min-width: 0;
            }
        }
    </style>
</head>
<body>
<div class="scene">
    <div class="layout">
        <section class="hero-copy">
            <div>
                <h1 class="hero-title">Вечер, на который очень хочется позвать именно тебя</h1>
                <p class="hero-subtitle">
                    Всё просто: классный повод встретиться, провести время вместе и создать хорошие воспоминания.
                </p>
            </div>
        </section>

        <section class="invitation-card">
            <div class="card-inner">
                <div class="card-topline">
                    <span>Приглашение</span>
                    <div class="crest" aria-hidden="true"></div>
                </div>

                <h2 class="greeting">
                    {{ $invite['guest']['name'] }}@if(!empty($invite['guest']['patronymic'])) {{ $invite['guest']['patronymic'] }}@endif
                </h2>

                <p class="guest-intro">
                    Очень хочется увидеть тебя среди гостей. Ниже всё, что нужно: детали события и быстрый ответ в один клик.
                </p>

                <div class="content">
                    {!! $invite['event']['content'] !!}
                </div>

                <div class="details-grid">
                    <div class="detail-card">
                        <div class="detail-label">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M8 2v4"></path>
                                <path d="M16 2v4"></path>
                                <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                                <path d="M3 10h18"></path>
                            </svg>
                            Когда
                        </div>
                        <div class="detail-value">{{ \Carbon\Carbon::parse($invite['event']['datetime'])->translatedFormat('j F H:i') }}</div>
                    </div>

                    <div class="detail-card">
                        <div class="detail-label">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M12 21s7-4.35 7-11a7 7 0 1 0-14 0c0 6.65 7 11 7 11Z"></path>
                                <circle cx="12" cy="10" r="2.5"></circle>
                            </svg>
                            Где
                        </div>
                        <div class="detail-value">{!! $invite['event']['location'] !!}</div>
                    </div>
                </div>

                <p class="caption">{{ $invite['event']['caption'] }}</p>

                <div class="response-section">
                    <div class="response-head">
                        <h3 class="response-title">Сможешь прийти?</h3>
                        <p class="response-note">Дай знать, получится ли у тебя быть.</p>
                    </div>

                    @if(!isset($invite['approval']))
                        <div class="response-buttons">
                            <button class="action-button primary" onclick="sendResponse(true)">Планирую прийти</button>
                            <button class="action-button secondary" onclick="sendResponse(false)">Не получится</button>
                        </div>
                    @elseif($invite['approval'] === true)
                        <p class="response-message success">Супер, ты в списке. Очень жду тебя.</p>
                        <div class="response-buttons">
                            <button class="action-button ghost" onclick="sendResponse(null)">Передумать</button>
                        </div>
                    @elseif($invite['approval'] === false)
                        <p class="response-message danger">Жаль, но если планы поменяются, ответ можно обновить.</p>
                        <div class="response-buttons">
                            <button class="action-button ghost" onclick="sendResponse(null)">Изменить ответ</button>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>
</div>

<script>
    function sendResponse(response) {
        fetch('/approve/{{ $invite['id'] }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                approval: response
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Что-то пошло не так. Попробуй ещё раз.');
            });
    }
</script>
</body>
</html>
