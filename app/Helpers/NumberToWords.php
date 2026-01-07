<?php

// Función principal para convertir un número a palabras en español
function numeroALetras($numero) {
    $unidades = ['', 'un', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete', 'ocho', 'nueve'];
    $decenas = ['', 'diez', 'veinte', 'treinta', 'cuarenta', 'cincuenta', 'sesenta', 'setenta', 'ochenta', 'noventa'];
    $centenas = ['', 'ciento', 'doscientos', 'trescientos', 'cuatrocientos', 'quinientos', 'seiscientos', 'setecientos', 'ochocientos', 'novecientos'];

    // Lógica para convertir la parte entera
    $convertirEntero = function($n) use ($unidades, $decenas, $centenas, &$convertirEntero) {
        $palabra = '';
        if ($n < 10) {
            $palabra = $unidades[$n];
        } elseif ($n < 20) {
            $palabra = ['diez', 'once', 'doce', 'trece', 'catorce', 'quince', 'dieciséis', 'diecisiete', 'dieciocho', 'diecinueve'][$n - 10];
        } elseif ($n < 30) {
            $palabra = ($n == 20) ? 'veinte' : 'veinti' . $unidades[$n % 10];
        } elseif ($n < 100) {
            $palabra = $decenas[(int)($n / 10)];
            if ($n % 10 != 0) {
                $palabra .= ' y ' . $unidades[$n % 10];
            }
        } elseif ($n < 1000) {
            if ($n == 100) {
                $palabra = 'cien';
            } else {
                $palabra = $centenas[(int)($n / 100)];
                if ($n % 100 != 0) {
                    // La llamada recursiva debe ser a través de la variable
                    $palabra .= ' ' . $convertirEntero($n % 100);
                }
            }
        }
        return $palabra;
    };
    
    $numero = (string)$numero;
    $partes = explode('.', $numero);
    $entero = (int)$partes[0];
    $decimal = isset($partes[1]) ? (int)$partes[1] : 0;
    
    $numeroEnLetras = '';
    $millones = (int)($entero / 1000000);
    $miles = (int)(($entero % 1000000) / 1000);
    $unidadesMiles = (int)($entero % 1000);

    if ($millones > 0) {
        $numeroEnLetras .= $convertirEntero($millones) . ' ' . ($millones == 1 ? 'millón' : 'millones');
    }
    if ($miles > 0) {
        if (!empty($numeroEnLetras)) $numeroEnLetras .= ' ';
        $numeroEnLetras .= ($miles == 1 && $entero > 1000 ? 'un' : $convertirEntero($miles)) . ' mil';
    }
    if ($unidadesMiles > 0) {
        if (!empty($numeroEnLetras)) $numeroEnLetras .= ' ';
        $numeroEnLetras .= $convertirEntero($unidadesMiles);
    }
    
    // Parte decimal
    $numeroEnLetras .= ' con ' . ($decimal < 10 ? '0' . $decimal : $decimal) . '/100';

    return trim($numeroEnLetras);
}