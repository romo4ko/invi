<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Приглашение</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            color: #FFFFFF;
            background-color: {{ $invite['event']['color'] ?? '#08473F' }};
            text-shadow: 3px 4px 19px rgba(0, 0, 0, 0.4);
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: stretch; /* Растягиваем дочерние элементы по высоте */
            position: relative;
        }

        .invitation-container {
            display: flex;
            flex-direction: column;
            width: 100%;
            min-height: 100vh;
            padding: 20px 0;
            box-sizing: border-box;
            background-image: url('{{ asset('storage/' . $invite['event']['image']) }}');
            background-size: 150%;
            background-position: center;
            background-repeat: no-repeat;
        }

        .invitation-card {
            position: relative;
            z-index: 10;
            max-width: 800px;
            width: 90%;
            margin: auto;
            padding: 3rem;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(5px);
            background-color: rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column;
        }

        .greeting {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: clamp(1.8rem, 4vw, 2.2rem);
            color: #fff;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .content {
            font-size: 1rem;
            line-height: 1.7;
            margin-bottom: 2rem;
            text-align: center;
            flex-grow: 1;
        }

        .details {
            margin: 2rem 0;
            padding: 1.2rem;
            border-left: 4px solid {{ $invite['event']['color'] ?? '#08473F' }};
        }

        .detail-item {
            margin-bottom: 0.8rem;
            display: flex;
            align-items: center;
        }

        .detail-icon {
            margin-right: 10px;
            color: #d4af37;
            font-size: 1.1rem;
        }

        .response-section {
            margin-top: auto; /* Прижимаем к низу */
        }

        .caption {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            text-align: right;
            margin-top: 1.5rem;
            color: #fff;
            font-style: italic;
        }

        .response-buttons {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1.5rem;
            flex-wrap: wrap;
        }

        .btn-accept {
            background-color: #28a745;
            border-color: #28a745;
            min-width: 120px;
        }

        .btn-decline {
            background-color: #dc3545;
            border-color: #dc3545;
            min-width: 120px;
        }

        .btn-cancel {
            background-color: #6c757d;
            border-color: #6c757d;
            min-width: 120px;
        }

        .btn-accept:hover,
        .btn-accept:active,
        .btn-accept:focus {
            background-color: #28a745 !important;
            border-color: #28a745 !important;
            opacity: 0.6 !important;
        }

        .btn-decline:hover,
        .btn-decline:active,
        .btn-decline:focus {
            background-color: #dc3545 !important;
            border-color: #dc3545 !important;
            opacity: 0.6 !important;
        }

        .btn-cancel:hover,
        .btn-cancel:active,
        .btn-cancel:focus {
            background-color: #6c757d !important;
            border-color: #6c757d !important;
            opacity: 0.6 !important;
        }

        .response-message {
            text-align: center;
            margin-top: 1rem;
            font-weight: bold;
            padding: 0 10px;
        }

        @media (max-width: 576px) {
            .invitation-card {
                padding: 1.5rem;
                width: 90%;
            }

            .details {
                padding: 1rem;
                margin: 1.5rem 0;
            }

            .response-buttons {
                flex-direction: column;
                align-items: center;
                gap: 0.8rem;
            }

            .btn-accept,
            .btn-decline,
            .btn-cancel {
                width: 100%;
                max-width: 220px;
            }
        }

        @media (min-width: 576px) {
            .invitation-container {
                background-size: 100%;
                background-position: center;
                background-repeat: no-repeat;
            }
        }

        @media (min-width: 1080px) {
            .invitation-container {
                background-size: 60%;
                background-position: center;
                background-repeat: no-repeat;
            }
        }

        .btn-accept,
        .btn-decline,
        .btn-cancel {
            padding: 0.5rem 1rem; /* Уменьшен padding */
            font-size: 0.9rem; /* Уменьшен размер шрифта */
            min-width: 100px; /* Уменьшен минимальный размер */
        }
    </style>
</head>
<body>

<div class="invitation-container">
    <div class="invitation-card">
        <div class="card-content">
            <div class="greeting">
                @if($invite['guest']['gender'] == 'female')
                    Уважаемая
                @elseif($invite['guest']['gender'] == 'male')
                    Уважаемый
                @else
                    Уважаемые
                @endif
                {{ $invite['guest']['name'] }} {{ $invite['guest']['patronymic'] }}
            </div>

            <div class="content">
                {!! $invite['event']['content'] !!}
            </div>

            <div class="caption">
                {{ $invite['event']['caption'] }}
            </div>

            <div class="details">
                <div class="detail-item">
                    <span class="detail-icon">📅</span>
                    <span>{{ \Carbon\Carbon::parse($invite['event']['datetime'])->translatedFormat('j F H:i') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-icon">📍</span>
                    <span>{{ $invite['event']['location'] }}</span>
                </div>
            </div>

            <div class="response-section">
                @if(!isset($invite['approval']))
                    <div class="response-buttons">
                        <button class="btn btn-accept btn-lg text-white" onclick="sendResponse(true)">Планирую прийти</button>
                        <button class="btn btn-decline btn-lg text-white" onclick="sendResponse(false)">К сожалению, не смогу</button>
                    </div>
                @elseif($invite['approval'] === true)
                    <div class="response-message text-success">
                        Вы подтвердили участие! <br/>Жду вас.
                    </div>
                    <div class="response-buttons">
                        <button class="btn btn-cancel btn-lg text-white" onclick="sendResponse(null)">Отменить ответ</button>
                    </div>
                @elseif($invite['approval'] === false)
                    <div class="response-message text-danger">
                        Вы отказались от участия. <br/>Очень жаль.
                    </div>
                    <div class="response-buttons">
                        <button class="btn btn-cancel btn-lg text-white" onclick="sendResponse(null)">Изменить решение</button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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
                alert('Произошла ошибка. Пожалуйста, попробуйте ещё раз.');
            });
    }
</script>

</body>
</html>
