<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo mensaje de contacto</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #44AD49;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #44AD49;
            margin: 0;
            font-size: 24px;
        }
        .content {
            margin-bottom: 30px;
        }
        .field {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border-left: 4px solid #44AD49;
        }
        .field-label {
            font-weight: bold;
            color: #44AD49;
            margin-bottom: 5px;
            display: block;
        }
        .field-value {
            color: #333;
            font-size: 16px;
        }
        .message-content {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            font-style: italic;
        }
        .footer {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }
        .brand {
            color: #44AD49;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📬 Nuevo Mensaje de Contacto</h1>
            <p>Has recibido un nuevo mensaje desde el formulario de contacto de CMYM.cl</p>
        </div>

        <div class="content">
            <div class="field">
                <span class="field-label">👤 Nombre:</span>
                <div class="field-value">{{ $contact->nombre }}</div>
            </div>

            <div class="field">
                <span class="field-label">📧 Correo Electrónico:</span>
                <div class="field-value">{{ $contact->correo }}</div>
            </div>

            @if($contact->telefono)
            <div class="field">
                <span class="field-label">📱 Teléfono:</span>
                <div class="field-value">{{ $contact->telefono }}</div>
            </div>
            @endif

            @if($contact->direccion)
            <div class="field">
                <span class="field-label">📍 Dirección:</span>
                <div class="field-value">{{ $contact->direccion }}</div>
            </div>
            @endif

            <div class="field">
                <span class="field-label">💬 Mensaje:</span>
                <div class="message-content">
                    {{ $contact->mensaje }}
                </div>
            </div>

            <div class="field">
                <span class="field-label">📅 Fecha de envío:</span>
                <div class="field-value">{{ $contact->created_at->format('d/m/Y H:i:s') }}</div>
            </div>
        </div>

        <div class="footer">
            <p>Este mensaje fue enviado desde el formulario de contacto de <span class="brand">CMYM.cl</span></p>
            <p>Puedes responder directamente a este correo para contactar al remitente.</p>
        </div>
    </div>
</body>
</html>
