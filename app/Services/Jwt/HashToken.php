<?php

namespace App\Services\Jwt;

use Exception;

class HashToken
{
    private string $pathPrivateKey;
    private string $pathPublicKey;

    public function __construct() {
        $this->pathPrivateKey = 'file://' . storage_path('keys') . '/private_key.pem';
        $this->pathPublicKey = 'file://' . storage_path('keys') . '/public_key.pem';
    }

    public function createShortHash(string $sid, ?int $horasExpiracion = null): string  {

        $privateKey = openssl_pkey_get_private($this->pathPrivateKey, config("jwt.secret"));

        $tiempoVida = $horasExpiracion ?? intval(config("jwt.expired_token"));

        $expires = time() + ($tiempoVida * 3600);

        if (!$privateKey) {
            throw new \Exception("No se pudo cargar la clave privada");
        }
        
        $message = "{$sid}|{$expires}";
        
        $signature = '';

        
        if (!openssl_sign($message, $signature, $privateKey, OPENSSL_ALGO_SHA256)) {
            throw new \Error("No se pudo firmar el JWT");
        }
            
        $base64Signature = $this->base64UrlEncode($signature);
        
        // Retornamos sid|expires|firma
        return "{$sid}.{$expires}.{$base64Signature}";
    }

    public function verifyShortHash(string $hash): array  {

        list($sid, $expires, $signatureB64) = explode('.', $hash);

        $currentTimestamp = time();

        if ($currentTimestamp > $expires) {
            throw new \Exception("El código QR ha expirado.");
        }

        $publicKey = openssl_pkey_get_public(file_get_contents($this->pathPublicKey));

        if (!$publicKey) {
            throw new \Exception("No se pudo cargar la clave pública");
        }

        $message = "{$sid}|{$expires}";

        $signature = $this->base64UrlDecode($signatureB64);

        $isValid = openssl_verify($message, $signature, $publicKey, OPENSSL_ALGO_SHA256);

        if ($isValid !== 1) {
            throw new \Exception('Firma de token inválida', 401);
        }

        return ['sid' => $sid];
    }

    private function base64UrlEncode(string $data): string {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    private function base64UrlDecode(string $data): string {
        return base64_decode(str_replace(['-', '_'], ['+', '/'], $data));
    }
}