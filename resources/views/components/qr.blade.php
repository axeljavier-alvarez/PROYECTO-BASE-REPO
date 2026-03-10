@props([
    'data', 
    'size' => 200, 
    'filename' => 'qrcode',
    'download' => false,
])

@php
    $pngBinary = \App\Services\QrGenerator::generatePng($data, $size);
    $base64Image = 'data:image/png;base64,' . base64_encode($pngBinary);
    $id = 'qr-' . md5($data);
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-col items-center gap-4']) }}>
    <div id="{{ $id }}">
        <img src="{{ $base64Image }}" alt="QR Code" width="{{ $size }}" height="{{ $size }}">
    </div>

    @if($download) 
        <flux:button
            variant="primary"
            color="blue"
            icon="document-arrow-down" 
            onclick="downloadQR('{{ $base64Image }}', '{{ $filename }}')"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
        >
            Descargar QR
        </flux:button>
    @endif
</div>

@script
    <script>
        function downloadQR(base64Data, filename) {
            const link = document.createElement("a");
            link.href = base64Data;
            link.download = filename + ".png";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
@endscript