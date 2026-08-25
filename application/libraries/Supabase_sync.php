<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Supabase_sync
{
    private $url;
    private $key;

    public function __construct()
    {
        $this->url = rtrim((string) getenv('SUPABASE_URL'), '/');
        $this->key = (string) getenv('SUPABASE_SERVICE_ROLE_KEY');
    }

    public function enabled()
    {
        return $this->url !== '' && $this->key !== '' && function_exists('curl_init');
    }

    public function upsert($table, array $row, $conflict_column)
    {
        if (!$this->enabled()) {
            return false;
        }

        $request = curl_init($this->url.'/rest/v1/'.rawurlencode($table).'?on_conflict='.rawurlencode($conflict_column));
        curl_setopt_array($request, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($row),
            CURLOPT_HTTPHEADER => [
                'apikey: '.$this->key,
                'Authorization: Bearer '.$this->key,
                'Content-Type: application/json',
                'Prefer: resolution=merge-duplicates,return=minimal',
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
        ]);
        $response = curl_exec($request);
        $status = curl_getinfo($request, CURLINFO_HTTP_CODE);
        curl_close($request);
        if ($status < 200 || $status >= 300) {
            log_message('error', 'Supabase sync failed for '.$table.': '.(string) $response);
            return false;
        }
        return true;
    }
}
