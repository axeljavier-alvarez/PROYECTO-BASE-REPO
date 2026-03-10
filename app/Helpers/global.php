<?php

use App\Models\Bitacora;
use App\Services\Captcha;
use App\Services\Jwt\HashToken;
use Illuminate\Support\Facades\Auth;

    if(!function_exists('getNestedValue')) {
        function getNestedValue($array, $key) {
            $keys = explode('.', $key);
            foreach ($keys as $innerKey) {
                if (isset($array[$innerKey])) {
                    $array = $array[$innerKey];
                } else {
                    return null;
                }
            }
            return $array;
        }
    }

    if(!function_exists('hasChanged')) {
        function hasChanged($target, $source): bool {

            if ($target === $source) {
                return false;
            }

            if (!is_array($target) || !is_array($source)) {
                return $target !== $source;
            }

            if (count($target) !== count($source)) {
                return true;
            }

            foreach ($target as $key => $value) {

                if (!array_key_exists($key, $source) || hasChanged($value, $source[$key])) {
                    return true;
                }
            }

            return false;
        }
    }


    if(!function_exists('hashToken')) {
        function hashToken() {
            return new HashToken();
        }
    }

    if(!function_exists('writeBitacora')) {
        function writeBitacora(int $solicitante_id, string $estado) {
            Bitacora::create([
                'solicitante_id' => $solicitante_id,
                'empresa_id' => Auth::user()->empresa_id ?? null,
                'user_id' => Auth::id() ?? null,
                'estado' => $estado,
            ]);
        }
    }

    if(!function_exists('captchaGenerate')) {
        function captchaGenerate(string $text) {
            $captcha = new Captcha();
            return $captcha->generate($text);
        }
    }