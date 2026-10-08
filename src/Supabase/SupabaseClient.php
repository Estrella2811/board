<?php
// src/Supabase/SupabaseClient.php
// ---------------------------------------------------------
// Base class that handles all HTTP requests to Supabase.
// SupabaseAuth and SupabaseDB both extend this class.
// ---------------------------------------------------------

class SupabaseClient {
    protected string $baseUrl;
    protected string $apiKey;
    protected ?string $authToken;

    public function __construct(string $baseUrl, string $apiKey, ?string $authToken = null) {
        $this->baseUrl   = rtrim($baseUrl, '/');
        $this->apiKey    = $apiKey;
        $this->authToken = $authToken;
    }

    public function setAuthToken(string $token): void {
        $this->authToken = $token;
    }

    public function getAuthToken(): ?string {
        return $this->authToken;
    }

    protected function request(string $method, string $endpoint, ?array $data = null, array $extraHeaders = []): array {
        $url = $this->baseUrl . $endpoint;

        $headers = array_merge([
            'Content-Type: application/json',
            'apikey: '       . $this->apiKey,
            'Authorization: Bearer ' . ($this->authToken ?? $this->apiKey),
        ], $extraHeaders);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,     $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  $method);

        if ($data !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response  = curl_exec($ch);
        $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            return ['status' => 0, 'error' => $curlError, 'data' => null];
        }

        return [
            'status' => $httpCode,
            'data'   => json_decode($response, true),
        ];
    }
}
