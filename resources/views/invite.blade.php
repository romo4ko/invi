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
            color: #333;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }

        .invitation-card {
            position: relative;
            z-index: 10;
            max-width: 800px;
            width: 90%;
            padding: 3rem;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(5px);
        }

        .greeting {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 2.2rem;
            color: #2c3e50;
            margin-bottom: 2rem;
            text-align: center;
        }

        .content {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 2.5rem;
            text-align: center;
        }

        .details {
            margin: 2.5rem 0;
            padding: 1.5rem;
            background-color: rgba(255, 255, 255, 0.7);
            border-left: 4px solid #d4af37;
        }

        .detail-item {
            margin-bottom: 0.8rem;
            display: flex;
            align-items: center;
        }

        .detail-icon {
            margin-right: 10px;
            color: #d4af37;
            font-size: 1.2rem;
        }

        .caption {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            text-align: right;
            margin-top: 2rem;
            color: #2c3e50;
            font-style: italic;
        }

        .response-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 2rem;
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

        .response-message {
            text-align: center;
            margin-top: 1rem;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .invitation-card {
                padding: 2rem;
            }

            .greeting {
                font-size: 1.8rem;
            }

            .response-buttons {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body style="background-image: url('{{ asset('storage/' . $invite['event']['image']) }}')">

<div class="invitation-card">
    <div class="greeting">
        @if($invite['guest']['gender'] == 'female')
            Уважаемая
        @else
            Уважаемый
        @endif
        {{ $invite['guest']['name'] }} {{ $invite['guest']['patronymic'] }}
    </div>

    <div class="content">
        {!! $invite['event']['content'] !!}
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
                <button class="btn btn-accept btn-lg text-white" onclick="sendResponse(true)">С радостью приду!</button>
                <button class="btn btn-decline btn-lg text-white" onclick="sendResponse(false)">К сожалению, не смогу</button>
            </div>
        @elseif($invite['approval'] === true)
            <div class="response-message text-success">
                Вы подтвердили участие! Ждём вас!
            </div>
            <div class="response-buttons">
                <button class="btn btn-cancel btn-lg text-white" onclick="sendResponse(null)">Отменить ответ</button>
            </div>
        @elseif($invite['approval'] === false)
            <div class="response-message text-danger">
                Вы отказались от участия. Будем скучать!
            </div>
            <div class="response-buttons">
                <button class="btn btn-cancel btn-lg text-white" onclick="sendResponse(null)">Изменить решение</button>
            </div>
        @endif
    </div>

    <div class="caption">
        {{ $invite['event']['caption'] }}
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
