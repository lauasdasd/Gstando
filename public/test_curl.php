<?php

// URL de prueba de la API del BCRA
$url = "https://api.bcra.gob.ar/centraldedeudores/v1.0/Deudas/27253691560";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
$error = curl_error($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($error) {
    echo "<h1>Error de cURL:</h1><pre>";
    echo $error;
    echo "</pre>";
} else {
    echo "<h1>Respuesta de la API BCRA:</h1><pre>";
    echo htmlspecialchars($response);
    echo "</pre>";
    echo "<br><b>Código HTTP:</b> " . $http_code;
}