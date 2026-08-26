<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('email');
        $this->load->library('supabase_sync');
        try {
            if (getenv('MYSQLHOST') || getenv('DB_HOST')) {
                $this->load->database();
                $this->ensureDatabaseSchema();
            }
        } catch (Throwable $e) {
            // Database not available - app will run without database features
            log_message('error', 'Database connection failed: ' . $e->getMessage());
        }
    }

    private function ensureDatabaseSchema()
    {
        if (!isset($this->db) || !isset($this->db->conn_id) || !is_object($this->db->conn_id)) {
            return;
        }

        $table = $this->db->query("SHOW TABLES LIKE 'user'");
        if ($table && $table->num_rows() > 0) {
            $this->db->query("ALTER TABLE `user` MODIFY `id` int(64) NOT NULL AUTO_INCREMENT");
            return;
        }

        $schema_path = FCPATH.'database/gameina.sql';
        if (!is_readable($schema_path)) {
            log_message('error', 'Database schema file is not readable: '.$schema_path);
            return;
        }

        $schema = file_get_contents($schema_path);
        if (!$schema || !$this->db->conn_id->multi_query($schema)) {
            log_message('error', 'Database schema initialization failed: '.$this->db->conn_id->error);
            return;
        }

        while ($this->db->conn_id->more_results() && $this->db->conn_id->next_result()) {
        }
    }

    public function index()
    {
        // Check if database is available
        if (!isset($this->db) || !is_object($this->db)) {
            // Database not available - show homepage only
            $this->load->view('templates/nav');
            $this->load->view('index');
            $this->load->view('templates/footer');
            return;
        }

        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email', [
            'required' => 'Harap isi bidang email!',
            'valid_email' => 'Email tidak valid!',
        ]);
        $this->form_validation->set_rules('password', 'Password', 'trim|required', [
            'required' => 'Harap isi bidang password!',
        ]);
        if ($this->form_validation->run() == false) {

            $this->load->view('templates/nav');
            $this->load->view('index');
            $this->load->view('templates/footer');
        } else {
            //validasi sukses
            $this->authenticateUser();
        }
    }

    public function login()
    {
        $this->load->view('auth/login');
    }

    public function google_login()
    {
        if (strtoupper($this->input->method()) !== 'POST') {
            show_404();
        }

        $credential = trim((string) $this->input->post('credential', true));
        $flow = $this->input->post('flow', true) === 'signup' ? 'signup' : 'login';
        $is_ajax = $this->input->get_request_header('X-Requested-With') === 'XMLHttpRequest';
        $this->config->load('google');
        $client_id = $this->config->item('google_client_id');

        if (!$credential || !$client_id || !function_exists('curl_init')) {
            $this->googleLoginFailure('Google login is not configured.', $is_ajax, 400);
            return;
        }

        $request = curl_init('https://oauth2.googleapis.com/tokeninfo?id_token='.rawurlencode($credential));
        curl_setopt_array($request, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
        ]);
        $response_body = curl_exec($request);
        $status = curl_getinfo($request, CURLINFO_HTTP_CODE);
        curl_close($request);
        $claims = json_decode((string) $response_body, true);

        if ($status !== 200 || !is_array($claims)
            || !hash_equals((string) $client_id, (string) ($claims['aud'] ?? ''))
            || ($claims['email_verified'] ?? '') !== 'true'
            || !filter_var($claims['email'] ?? '', FILTER_VALIDATE_EMAIL)) {
            $this->googleLoginFailure('Google account verification failed.', $is_ajax, 401);
            return;
        }

        $email = strtolower(trim($claims['email']));
        $user = $this->db->get_where('user', ['email' => $email])->row_array();
        if ($flow === 'login' && !$user) {
            $this->googleLoginFailure('This Google email is not registered. Please sign up first.', $is_ajax, 401);
            return;
        }
        if ($flow === 'signup' && $user) {
            $this->googleLoginFailure('This Google email is already registered. Please log in instead.', $is_ajax, 409);
            return;
        }
        if (!$user) {
            $google_user = [
                'nama' => htmlspecialchars($claims['name'] ?? $email, ENT_QUOTES, 'UTF-8'),
                'email' => $email,
                'image' => 'default.jpg',
                'password' => password_hash(bin2hex(random_bytes(32)), PASSWORD_DEFAULT),
                'is_active' => 1,
                'date_created' => time(),
                'inacash' => 0,
            ];
            $this->db->insert('user', $google_user);
            $google_user['id'] = $this->db->insert_id();
            $this->syncUserToSupabase($google_user);
            $this->session->set_flashdata('google-signup-success', 'Your Google account was created successfully. Please log in.');
            redirect(base_url('welcome/login'));
            return;
        } elseif ((int) $user['is_active'] !== 1) {
            $this->googleLoginFailure('This account is not active yet.', $is_ajax, 403);
            return;
        }

        $this->session->set_userdata('email', $email);
        $login_redirect = $this->session->userdata('login_redirect');
        $this->session->unset_userdata('login_redirect');
        $redirect = $login_redirect ?: base_url('welcome');
        if (!$is_ajax) {
            redirect($redirect);
            return;
        }
        $this->output->set_content_type('application/json')->set_output(json_encode([
            'success' => true,
            'redirect' => $redirect,
        ]));
    }

    private function googleLoginFailure($message, $is_ajax, $status)
    {
        if (!$is_ajax) {
            $this->session->set_flashdata('google-login-error', $message);
            redirect(base_url('welcome/login'));
            return;
        }
        $this->output->set_status_header($status)->set_content_type('application/json')->set_output(json_encode([
            'success' => false,
            'message' => $message,
        ]));
    }

    public function admin()
    {
        // Check if database is available
        if (!isset($this->db) || !is_object($this->db)) {
            $data['message'] = 'Admin login requires database connection. Please try again later.';
            $this->load->view('admin/login', $data);
            return;
        }

        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email', [
            'required' => 'Harap isi bidang email!',
            'valid_email' => 'Email tidak valid!',
        ]);
        $this->form_validation->set_rules('password', 'Password', 'trim|required', [
            'required' => 'Harap isi bidang password!',
        ]);
        if ($this->form_validation->run() == false) {

            $this->load->view('admin/login');
        } else {
            //validasi sukses
            $this->adminlogin();
        }
    }

    public function publisher()
    {
        // Check if database is available
        if (!isset($this->db) || !is_object($this->db)) {
            $data['message'] = 'Publisher login requires database connection. Please try again later.';
            $this->load->view('publisher/login', $data);
            return;
        }

        $this->form_validation->set_rules('email2', 'Email', 'trim|required|valid_email', [
            'required' => 'Harap isi bidang email!',
            'valid_email' => 'Email tidak valid!',
        ]);
        $this->form_validation->set_rules('password2', 'Password', 'trim|required', [
            'required' => 'Harap isi bidang password!',
        ]);
        if ($this->form_validation->run() == false) {

            $this->load->view('publisher/login');
        } else {
            //validasi sukses
            $this->publisherlogin();
        }
    }

    private function publisherlogin()
    {
        $email = $this->input->post('email2');
        $password = $this->input->post('password2');

        $user = $this->db->get_where('publisher', ['email_publisher' => $email])->row_array();

        if ($user) {
            //cek password
            if (password_verify($password, $user['password'])) {
                $data = [

                    'email' => $user['email_publisher'],
                    'nama' => $user['nama_publisher'],

                ];
                $this->session->set_userdata($data);
                redirect(base_url('publisher/index'));
            } else {

                $this->session->set_flashdata('fail-pass', 'Gagal!');
                redirect(base_url('welcome/publisher'));
            }
        } else {

            $this->session->set_flashdata('fail-login', 'Gagal!');
            redirect(base_url('welcome/publisher'));
        }
    }

    private function adminlogin()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $user = $this->db->get_where('admin', ['email_admin' => $email])->row_array();

        if ($user) {
            //cek password
            if (password_verify($password, $user['password'])) {
                $data = [

                    'email' => $user['email_admin'],
                    'nama' => $user['nama_admin'],

                ];
                $this->session->set_userdata($data);
                redirect(base_url('admin'));
            } else {

                $this->session->set_flashdata('fail-pass', 'Gagal!');
                redirect(base_url('welcome/admin'));
            }
        } else {

            $this->session->set_flashdata('fail-login', 'Gagal!');
            redirect(base_url('welcome/admin'));
        }
    }

    private function authenticateUser()
    {
        $email = strtolower(trim($this->input->post('email', true)));
        $password = $this->input->post('password');

        $user = $this->db->get_where('user', ['email' => $email])->row_array();

        if ($user) {
            //user ada
            if ($user['is_active'] == 1) {
                //cek password
                if (password_verify($password, $user['password'])) {
                    $data = [
                        'email' => $user['email'],
                    ];

                    $this->session->set_userdata($data);
                    $login_redirect = $this->session->userdata('login_redirect');
                    $this->session->unset_userdata('login_redirect');
                    redirect($login_redirect ?: base_url('welcome'));
                } else {
                    $this->session->set_flashdata('fail-pass', 'Gagal!');
                    redirect(base_url('welcome/login'));
                }
            } else {
                $this->session->set_flashdata('fail-login', 'Your email is not verified yet.');
                redirect(base_url('welcome/login'));
            }
        } else {
            $this->session->set_flashdata('fail-login', 'Gagal!');
            redirect(base_url('welcome/login'));
        }
    }

    public function registration()
    {
        // Check if database is available
        if (!isset($this->db) || !is_object($this->db)) {
            $data['message'] = 'Database connection required for registration. Please try again later.';
            $this->load->view('auth/registration', $data);
            return;
        }

        $this->form_validation->set_rules('nama', 'Full name', 'required|trim|min_length[5]', [
            'required' => 'Please enter your full name.',
            'min_length' => 'Full name must be at least 5 characters.',
        ]);
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|callback_registration_email_available', [
            'required' => 'Please enter your email address.',
            'valid_email' => 'Please enter a valid email address.',
            'registration_email_available' => 'This email address is already registered.',
        ]);
        $this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[6]', [
            'required' => 'Please enter a password.',
            'matches' => 'Passwords do not match.',
            'min_length' => 'Password must be at least 6 characters.',
        ]);
        $this->form_validation->set_rules('retype_password', 'Retype password', 'required|trim|matches[password]', [
            'required' => 'Please retype your password.',
            'matches' => 'The passwords do not match.',
        ]);

        if ($this->form_validation->run() == false) {
            $this->load->view('auth/registration');
        } else {
            $email = strtolower(trim($this->input->post('email', true)));
            $existing_user = $this->db->get_where('user', ['email' => $email])->row_array();

            if ($existing_user) {
                $this->db->delete('user_token', ['email' => $email]);
                $this->db->delete('user', ['email' => $email]);
            }

            $data = [
                'nama' => htmlspecialchars($this->input->post('nama', true)),
                'email' => htmlspecialchars($email),
                'image' => 'default.jpg',
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'is_active' => 0,
                'date_created' => time(),
                'inacash' => 0,
            ];

            //siapkan token
            $token = bin2hex(random_bytes(32));
            $user_token = [
                'email' => $email,
                'token' => $token,
                'date_created' => time(),
            ];

            $this->db->insert('user', $data);
            $data['id'] = $this->db->insert_id();
            $this->db->insert('user_token', $user_token);
            $this->syncUserToSupabase($data);

            if ($this->_sendEmail($token, 'verify')) {
                redirect(base_url('welcome/verification_pending?email=' . rawurlencode($email)));
            } else {
                $this->session->set_flashdata('email-fail', 'Account created, but the verification email could not be sent. Please contact support or try again later.');
            }
            redirect(base_url('welcome/registration'));
        }
    }

    public function verification_pending()
    {
        $email = strtolower(trim($this->input->get('email', true)));
        $this->load->view('auth/verification_pending', ['email' => $email]);
    }

    public function verification_status()
    {
        $email = strtolower(trim($this->input->get('email', true)));
        $user = $this->db->select('is_active')->get_where('user', ['email' => $email])->row_array();

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['verified' => $user && (int) $user['is_active'] === 1]));
    }

    public function registration_email_available($email)
    {
        $user = $this->db->get_where('user', ['email' => strtolower(trim($email))])->row_array();

        return !$user || (int) $user['is_active'] !== 1;
    }

    private function syncUserToSupabase($user)
    {
        $this->supabase_sync->upsert('users', [
            'id' => (int) $user['id'],
            'name' => $user['nama'],
            'email' => strtolower($user['email']),
            'image' => $user['image'] ?? 'default.jpg',
            'is_active' => (int) $user['is_active'] === 1,
            'date_created' => (int) $user['date_created'],
            'inacash' => (int) ($user['inacash'] ?? 0),
        ], 'id');
    }

    private function _sendEmail($token, $type)
    {
        $smtp_user = trim((string) getenv('GAMEINA_SMTP_USER'));
        $smtp_pass = preg_replace('/\s+/', '', (string) getenv('GAMEINA_SMTP_PASS'));
        $resend_api_key = trim((string) getenv('RESEND_API_KEY'));
        if ((!$smtp_user || !$smtp_pass) && !$resend_api_key) {
            log_message('error', 'Verification email skipped: GAMEINA_SMTP_USER and GAMEINA_SMTP_PASS are required.');
            error_log('Verification email skipped: configure SMTP credentials or RESEND_API_KEY.');
            return false;
        }

        $data = array(
            'name' => $this->input->post('nama', true),
            'link' => base_url('welcome/verify') . '?' . http_build_query([
                'email' => $this->input->post('email', true),
                'token' => $token,
            ]),
        );

        try {
            $body = $this->load->view('templates/email-template.php', $data, true);

            $gmail_refresh_token = trim((string) getenv('GMAIL_REFRESH_TOKEN'));
            if ($gmail_refresh_token) {
                return $this->_sendEmailWithGmailApi($gmail_refresh_token, $body);
            }

            if ($resend_api_key) {
                return $this->_sendEmailWithResend($resend_api_key, $body, $data['name']);
            }

            $smtp_crypto = strtolower(trim((string) getenv('GAMEINA_SMTP_CRYPTO')));
            if (!in_array($smtp_crypto, ['tls', 'ssl'], true)) {
                $smtp_crypto = 'tls';
            }

            $config = [
                'protocol' => 'smtp',
                'smtp_host' => trim((string) (getenv('GAMEINA_SMTP_HOST') ?: 'smtp.gmail.com')),
                'smtp_user' => $smtp_user,
                'smtp_pass' => $smtp_pass,
                'smtp_port' => (int) (getenv('GAMEINA_SMTP_PORT') ?: 587),
                'smtp_crypto' => $smtp_crypto,
                'smtp_timeout' => 15,
                'mailtype' => 'html',
                'charset' => 'utf-8',
                'newline' => "\r\n",
            ];

            $this->email->initialize($config);

            $this->email->from($smtp_user, 'StreamNest');
            $this->email->to($this->input->post('email'));

            if ($type == 'verify') {
                $this->email->subject('Verify your StreamNest account');
                // $this->email->message('Click untuk verifikasi :
                // <a href="' . base_url() . 'welcome/verify?email=' . $this->input->post('email') . '& token' . urlencode($token) . '">activate</a>');
                $this->email->message($body);
            } else {
                $this->email->subject('Email Verification');
            }

            if ($this->email->send()) {
                return true;
            }

            $debug = $this->email->print_debugger(['headers']);
            log_message('error', 'Verification email failed: ' . $debug);
            error_log('Verification email failed: ' . strip_tags($debug));
        } catch (Throwable $exception) {
            log_message('error', 'Verification email exception: ' . $exception->getMessage());
            error_log('Verification email exception: ' . $exception->getMessage());
        }

        return false;
    }

    private function _sendEmailWithGmailApi($refresh_token, $body)
    {
        $client_id = trim((string) getenv('GMAIL_CLIENT_ID'));
        $client_secret = trim((string) getenv('GMAIL_CLIENT_SECRET'));
        $from = trim((string) (getenv('GMAIL_FROM') ?: getenv('GAMEINA_SMTP_USER')));
        $recipient = trim((string) $this->input->post('email'));

        if (!$client_id || !$client_secret || !$from || !$recipient) {
            error_log('Verification email via Gmail API skipped: OAuth variables are incomplete.');
            return false;
        }

        $token = $this->_postJsonForm('https://oauth2.googleapis.com/token', [
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'refresh_token' => $refresh_token,
            'grant_type' => 'refresh_token',
        ]);
        if (empty($token['access_token'])) {
            error_log('Verification email via Gmail API token exchange failed: '.substr(json_encode($token), 0, 500));
            return false;
        }

        $mime = "From: StreamNest <{$from}>\r\n"
            ."To: {$recipient}\r\n"
            .'Subject: Verify your StreamNest account' ."\r\n"
            .'MIME-Version: 1.0' ."\r\n"
            .'Content-Type: text/html; charset=UTF-8' ."\r\n\r\n"
            .$body;
        $raw = rtrim(strtr(base64_encode($mime), '+/', '-_'), '=');

        $response = $this->_postJson('https://gmail.googleapis.com/gmail/v1/users/me/messages/send', [
            'raw' => $raw,
        ], [
            'Authorization: Bearer '.$token['access_token'],
        ]);
        if (!empty($response['id'])) {
            return true;
        }

        error_log('Verification email via Gmail API failed: '.substr(json_encode($response), 0, 500));
        return false;
    }

    private function _postJsonForm($url, $fields)
    {
        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($fields),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
            CURLOPT_TIMEOUT => 15,
        ]);
        $response = curl_exec($curl);
        curl_close($curl);

        $decoded = json_decode((string) $response, true);
        return is_array($decoded) ? $decoded : [];
    }

    private function _postJson($url, $payload, $headers = [])
    {
        $curl = curl_init($url);
        $headers[] = 'Content-Type: application/json';
        curl_setopt_array($curl, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => 15,
        ]);
        $response = curl_exec($curl);
        curl_close($curl);

        $decoded = json_decode((string) $response, true);
        return is_array($decoded) ? $decoded : [];
    }

    private function _sendEmailWithResend($api_key, $body, $name)
    {
        $from = trim((string) (getenv('RESEND_FROM') ?: getenv('GAMEINA_SMTP_USER')));
        $from = strpos($from, '<') === false ? 'StreamNest <'.$from.'>' : $from;
        $payload = json_encode([
            'from' => $from,
            'to' => [$this->input->post('email')],
            'subject' => 'Verify your StreamNest account',
            'html' => $body,
        ]);
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Authorization: Bearer {$api_key}\r\nContent-Type: application/json\r\nContent-Length: ".strlen($payload)."\r\n",
                'content' => $payload,
                'ignore_errors' => true,
                'timeout' => 15,
            ],
        ]);

        $response = @file_get_contents('https://api.resend.com/emails', false, $context);
        $status = (int) ($http_response_header[0] ?? 0);
        if ($response !== false && $status >= 200 && $status < 300) {
            return true;
        }

        error_log('Verification email via Resend failed: HTTP '.$status.' '.substr((string) $response, 0, 500));
        return false;
    }

    private function _sendEmailPublisher($token, $type)
    {
        $config = [
            'protocol' => 'smtp',
            'smtp_host' => getenv('GAMEINA_SMTP_HOST') ?: 'smtp.gmail.com',
            'smtp_user' => getenv('GAMEINA_SMTP_USER'),
            'smtp_pass' => getenv('GAMEINA_SMTP_PASS'),
            'smtp_port' => (int) (getenv('GAMEINA_SMTP_PORT') ?: 587),
            'smtp_crypto' => getenv('GAMEINA_SMTP_CRYPTO') ?: 'tls',
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'newline' => "\r\n",
        ];

        $this->email->initialize($config);

        $data = array(
            'link' => ' ' . base_url() . 'welcome/verifypub?email_publisher=' . $this->input->post('email_publisher') . '& token' . urlencode($token) . '"',
        );

        $this->email->from(getenv('GAMEINA_SMTP_USER'), 'StreamNest');
        $this->email->to($this->input->post('email_publisher'));

        if ($type == 'verifypub') {
            $this->email->subject('Verifikasi Akun');
            // $this->email->message('Click untuk verifikasi :
            // <a href="' . base_url() . 'welcome/verify?email=' . $this->input->post('email') . '& token' . urlencode($token) . '">activate</a>');
            $body = $this->load->view('templates/email-template.php', $data, true);
            $this->email->message($body);
        } else {
            $this->email->subject('Email Verification');
        }

        if ($this->email->send()) {
            return true;
        } else {
            echo $this->email->print_debugger();
            die();
        }
    }

    public function publisheregist()
    {
        $this->form_validation->set_rules('nama_publisher', 'Nama', 'required|trim|min_length[5]', [
            'required' => 'Harap isi kolom username.',
            'min_length' => 'Username terlalu pendek.',
        ]);
        $this->form_validation->set_rules('nama_perusahaan', 'Perusahaan', 'required|trim|min_length[4]', [
            'required' => 'Harap isi kolom Perusahaan.',
            'min_length' => 'Nama terlalu pendek.',
        ]);
        $this->form_validation->set_rules('email_publisher', 'Email', 'required|trim|valid_email|is_unique[publisher.email_publisher]', [
            'is_unique' => 'Email ini telah digunakan!',
            'required' => 'Harap isi kolom email.',
            'valid_email' => 'Masukan email yang valid.',
        ]);
        $this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[6]|matches[rpassword]', [
            'required' => 'Harap isi kolom Password.',
            'matches' => 'Password tidak sama!',
            'min_length' => 'Password terlalu pendek',
        ]);
        $this->form_validation->set_rules('rpassword', 'Password', 'required|trim|matches[password]');

        if ($this->form_validation->run() == false) {
            $this->load->view('publisher/login');
        } else {
            $email = $this->input->post('email_publisher', true);
            $data = [
                'nama_publisher' => htmlspecialchars($this->input->post('nama_publisher', true)),
                'nama_perusahaan' => htmlspecialchars($this->input->post('nama_perusahaan', true)),
                'email_publisher' => htmlspecialchars($email),
                'image' => 'default.jpg',
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'is_active' => 1,
                'date_created' => time(),

            ];

            //siapkan token
            $token = base64_encode(random_bytes(32));
            $user_token = [
                'email' => $email,
                'token' => $token,
                'date_created' => time(),
            ];

            $this->db->insert('publisher', $data);
            $this->db->insert('user_token', $user_token);


            $this->session->set_flashdata('success-reg', 'Berhasil!');
            redirect(base_url('welcome/publisher'));
        }
    }

    public function verify()
    {
        $email = strtolower(trim($this->input->get('email', true)));
        $token = trim(rawurldecode($this->input->get('token', true)));

        $user = $this->db->get_where('user', ['email' => $email])->row_array();
        if ($user) {
            $user_token = $this->db->get_where('user_token', [
                'email' => $email,
                'token' => $token,
            ])->row_array();
            if ($user_token) {
                if (time() - $user_token['date_created'] < (60 * 60 * 24)) {
                    $this->db->set('is_active', 1);
                    $this->db->where('email', $email);
                    $this->db->update('user');

                    $this->db->delete('user_token', ['email' => $email]);
                    $this->session->set_flashdata('success-verify', 'Email verified successfully.');
                    redirect(base_url('welcome/login'));
                } else {
                    $this->db->delete('user', ['email' => $email]);
                    $this->db->delete('user_token', ['email' => $email]);

                    $this->session->set_flashdata('fail-token-expired', 'gagal');
                    redirect(base_url('welcome'));
                }
            } else {
                $this->session->set_flashdata('fail-token', 'gagal');
                redirect(base_url('welcome'));
            }
        } else {
            $this->session->set_flashdata('fail-verify', 'gagal');
            redirect(base_url('welcome'));
        }
    }

    public function verifypub()
    {
        $email = $this->input->get('email_publisher');
        $token = $this->input->get('token');

        $user = $this->db->get_where('publisher', ['email_publisher' => $email])->row_array();
        if ($user) {
            $user_token = $this->db->get_where('user_token', ['token => $token'])->row_array();
            if ($user_token) {
                if (time() - $user_token['date_created'] < (60 * 60 * 24)) {
                    $this->db->set('is_active', 1);
                    $this->db->where('email_publisher', $email);
                    $this->db->update('publisher');

                    $this->db->delete('user_token', ['email' => $email]);
                    $this->session->set_flashdata('success-verify', 'Berhasil!');
                    redirect(base_url('welcome/publisher'));
                } else {
                    $this->db->delete('publisher', ['email_publisher' => $email]);
                    $this->db->delete('user_token', ['email' => $email]);

                    $this->session->set_flashdata('fail-token-expired', 'gagal');
                    redirect(base_url('welcome'));
                }
            } else {
                $this->session->set_flashdata('fail-token', 'gagal');
                redirect(base_url('welcome'));
            }
        } else {
            $this->session->set_flashdata('fail-verify', 'gagal');
            redirect(base_url('welcome'));
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('email');
        $this->session->set_flashdata('success-logout', 'Berhasil!');
        redirect(base_url('welcome'));
    }

    public function email()
    {
        $this->load->view('templates/email-template');
    }
}
