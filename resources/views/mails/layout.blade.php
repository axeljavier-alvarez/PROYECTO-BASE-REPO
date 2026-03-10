<x-mail::message :title="$title">
# Registro en Mega Feria Empleo | MuniGuate
 
Guarda este código QR para que puedas postularte a plazas durante la Mega Feria Empleo, que ser llevara acabo el 27 de febrero 2026 en el parqueo de la municipalidad de Guatemala.
 
<img src="{{ $message->embedData($qrCode, 'qrcode.png', 'image/png') }}" width="200">
 
Gracias,<br>
Municipalidad de Guatemala
</x-mail::message>