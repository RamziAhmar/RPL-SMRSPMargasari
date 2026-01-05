<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PredictionClient
{
    /**
     * Singleton instance
     */
    private static ?PredictionClient $instance = null;

    /**
     * Base URL API prediksi
     */
    private string $baseUrl;

    /**
     * Constructor PRIVATE
     */
    private function __construct()
    {
        $this->baseUrl = config('services.prediction_api.url', 'http://127.0.0.1:8001');
    }

    /**
     * Akses instance Singleton
     */
    public static function getInstance(): PredictionClient
    {
        if (self::$instance === null) {
            self::$instance = new PredictionClient();
        }

        return self::$instance;
    }

    /**
     * Kirim data ke API Random Forest
     */
    public function predict(array $payload): array
    {
        $response = Http::post("{$this->baseUrl}/predict", $payload);

        if (! $response->ok()) {
            return [];
        }

        return $response->json();
    }
}