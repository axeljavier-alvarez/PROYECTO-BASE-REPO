<?php

namespace App\Services;

use BaconQrCode\Encoder\Encoder;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class QrGenerator
{
    /**
     * Genera un SVG con margen usando la configuración nativa
     */
    public static function generateSvg(string $content, int $size = 200, int $margin = 4): string {
        // El tercer parámetro de RendererStyle es el margen (Quiet Zone)
        $renderer = new ImageRenderer(
            new RendererStyle($size, $margin),
            new SvgImageBackEnd()
        );

        $writer = new Writer($renderer);
        return $writer->writeString($content);
    }

    /**
     * Genera un PNG calculando manualmente el padding blanco
     */
    public static function generatePng(string $content, int $size = 200, int $marginModules = 4): string {
        $qrCode = Encoder::encode($content, \BaconQrCode\Common\ErrorCorrectionLevel::L());
        $matrix = $qrCode->getMatrix();
        $matrixWidth = $matrix->getWidth();

        // 1. Calculamos el tamaño total incluyendo el margen
        // El margen se añade a ambos lados (izquierda/derecha y arriba/abajo)
        $totalModules = $matrixWidth + ($marginModules * 2);
        
        $moduleSize = (int)($size / $totalModules);
        $finalCanvasSize = $moduleSize * $totalModules;

        $image = imagecreate($finalCanvasSize, $finalCanvasSize);
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);

        // Rellenar fondo de blanco
        imagefill($image, 0, 0, $white);

        // 2. Dibujar la matriz desplazada por el margen
        for ($y = 0; $y < $matrixWidth; $y++) {
            for ($x = 0; $x < $matrixWidth; $x++) {
                if ($matrix->get($x, $y)) {
                    // Calculamos la posición sumando el offset del margen
                    $posX = ($x + $marginModules) * $moduleSize;
                    $posY = ($y + $marginModules) * $moduleSize;

                    imagefilledrectangle(
                        $image, 
                        $posX, 
                        $posY, 
                        $posX + $moduleSize - 1, 
                        $posY + $moduleSize - 1, 
                        $black
                    );
                }
            }
        }

        ob_start();
        imagepng($image);
        $imageData = ob_get_contents();
        ob_end_clean();

        return $imageData;
    }
}