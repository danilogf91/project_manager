<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ExchangeRate extends Controller
{
    public function getExchangeRate()
    {
        $apiKey = 'ee31dfc9f710c693adbc043c9d185448';

        // URL de la API
        $url = "http://apilayer.net/api/live";

        try {
            // Crea una instancia del cliente Guzzle HTTP
            $client = new Client();

            // Realiza la solicitud GET a la API
            $response = $client->get($url, [
                'query' => [
                    'access_key' => $apiKey,
                    'currencies' => 'USD',
                    'source' => 'EUR',
                    'format' => 1,
                ],
            ]);

            // Decodifica la respuesta JSON
            $data = json_decode($response->getBody(), true);

            // Obtiene la tasa de cambio de USD a EUR
            $exchangeRateUSDToEUR = $data['quotes']['EURUSD'];

            // Calcula la tasa de cambio de EUR a USD (inversa)
            $exchangeRateEURToUSD = 1 / $exchangeRateUSDToEUR;

            // Puedes usar $exchangeRateEURToUSD en tu aplicación
            return $exchangeRateEURToUSD;
        } catch (\Exception $e) {
            // Maneja errores aquí
            return "Error al obtener la tasa de cambio: " . $e->getMessage();
        }
    }
}
