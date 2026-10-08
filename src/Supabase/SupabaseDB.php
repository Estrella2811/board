<?php
// src/Supabase/SupabaseDB.php
// ---------------------------------------------------------
// Handles all Supabase database CRUD operations via PostgREST.
// Extends SupabaseClient for HTTP requests.
//
// PostgREST filter operators:
//   eq, neq, gt, lt, gte, lte, like, is, in
//   e.g. ['id' => 'eq.5', 'status' => 'eq.active']
// ---------------------------------------------------------

require_once __DIR__ . '/SupabaseClient.php';

class SupabaseDB extends SupabaseClient {
    private string $table;

    public function __construct(string $baseUrl, string $apiKey, string $table, ?string $authToken = null) {
        parent::__construct($baseUrl, $apiKey, $authToken);
        $this->table = $table;
    }

    // Switch the active table on the fly (chainable)
    public function from(string $table): static {
        $this->table = $table;
        return $this;
    }

    // SELECT rows — optionally filter with PostgREST operators
    // e.g. select('*', ['user_id' => 'eq.123'])
    public function select(string $columns = '*', array $filters = []): array {
        $query = 'select=' . urlencode($columns);

        foreach ($filters as $key => $value) {
            $query .= '&' . urlencode($key) . '=' . urlencode($value);
        }

        return $this->request('GET', '/rest/v1/' . $this->table . '?' . $query);
    }

    // INSERT a single row or multiple rows
    public function insert(array $data, bool $returnRecord = true): array {
        $headers = $returnRecord ? ['Prefer: return=representation'] : [];
        return $this->request('POST', '/rest/v1/' . $this->table, $data, $headers);
    }

    // UPDATE rows matching a filter
    // e.g. update(['id' => 'eq.5'], ['title' => 'New Title'])
    public function update(array $filter, array $data, bool $returnRecord = true): array {
        $query   = $this->buildFilterQuery($filter);
        $headers = $returnRecord ? ['Prefer: return=representation'] : [];
        return $this->request('PATCH', '/rest/v1/' . $this->table . '?' . $query, $data, $headers);
    }

    // DELETE rows matching a filter
    // e.g. delete(['id' => 'eq.5'])
    public function delete(array $filter): array {
        $query = $this->buildFilterQuery($filter);
        return $this->request('DELETE', '/rest/v1/' . $this->table . '?' . $query);
    }

    // UPSERT — insert or update on conflict
    public function upsert(array $data): array {
        $headers = ['Prefer: return=representation,resolution=merge-duplicates'];
        return $this->request('POST', '/rest/v1/' . $this->table, $data, $headers);
    }

    // Build query string from filter array
    private function buildFilterQuery(array $filters): string {
        $parts = [];
        foreach ($filters as $key => $value) {
            $parts[] = urlencode($key) . '=' . urlencode($value);
        }
        return implode('&', $parts);
    }
}
