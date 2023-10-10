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
                    'currencies' => 'EUR',
                    'source' => 'USD',
                    'format' => 1,
                ],
            ]);

            // Decodifica la respuesta JSON
            $data = json_decode($response->getBody(), true);

            // Obtiene la tasa de cambio de EUR a USD
            $exchangeRate = $data['quotes']['USDEUR'];

            // Puedes usar $exchangeRate en tu aplicación
            return $exchangeRate;
        } catch (\Exception $e) {
            // Maneja errores aquí
            return "Error al obtener la tasa de cambio: " . $e->getMessage();
        }
    }
}
