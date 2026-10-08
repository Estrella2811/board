<?php
// src/Supabase/SupabaseAuth.php
// ---------------------------------------------------------
// Handles all Supabase authentication operations.
// Extends SupabaseClient for HTTP requests.
// ---------------------------------------------------------

require_once __DIR__ . '/SupabaseClient.php';

class SupabaseAuth extends SupabaseClient {

    // Register a new user
    public function signUp(string $email, string $password): array {
        return $this->request('POST', '/auth/v1/signup', [
            'email'    => $email,
            'password' => $password,
        ]);
    }

    // Sign in and auto-store the access token
    public function signIn(string $email, string $password): array {
        $result = $this->request('POST', '/auth/v1/token?grant_type=password', [
            'email'    => $email,
            'password' => $password,
        ]);

        if (!empty($result['data']['access_token'])) {
            $this->setAuthToken($result['data']['access_token']);
        }

        return $result;
    }

    // Sign out the current user
    public function signOut(): array {
        return $this->request('POST', '/auth/v1/logout');
    }

    // Get the currently authenticated user
    public function getUser(): array {
        return $this->request('GET', '/auth/v1/user');
    }

    // Refresh an expired access token
    public function refreshToken(string $refreshToken): array {
        $result = $this->request('POST', '/auth/v1/token?grant_type=refresh_token', [
            'refresh_token' => $refreshToken,
        ]);

        if (!empty($result['data']['access_token'])) {
            $this->setAuthToken($result['data']['access_token']);
        }

        return $result;
    }

    // Send a password reset email
    public function resetPasswordEmail(string $email): array {
        return $this->request('POST', '/auth/v1/recover', [
            'email' => $email,
        ]);
    }
}
