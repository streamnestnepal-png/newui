<?php
defined('BASEPATH') or exit('No direct script access allowed');

$config['supabase_url'] = rtrim((string) getenv('SUPABASE_URL'), '/');
$config['supabase_service_key'] = (string) getenv('SUPABASE_SERVICE_ROLE_KEY');
