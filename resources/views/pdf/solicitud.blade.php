<!DOCTYPE html>
<html>
<head>
    <style>
        .qr-code {
            text-align: center;
            margin-top: 20px;
        }
        .qr-code svg {
            width: 150px;
            height: 150px;
        }
        .info { font-family: sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div class="info">
        <h1>Certificado de Autenticidad</h1>
        <p>Documento perteneciente a:</p>
    </div>

    <div class="qr-code">
        <img src="data:image/png;base64,{{ $qrCode }}" alt="Código QR">
        <p style="font-size: 10px;">Escanea para validar la autenticidad</p>
    </div>
</body>
</html>