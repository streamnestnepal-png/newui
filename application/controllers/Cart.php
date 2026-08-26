<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cart extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('email');
    }

    public function index()
    {
        $this->load->view('templates/nav');
        $this->load->view('cart/index');
        $this->load->view('templates/footer');
    }

    public function sync_abandoned()
    {
        if (strtoupper($this->input->method()) !== 'POST') {
            show_404();
        }

        $email = strtolower(trim((string) $this->session->userdata('email')));
        $items = json_decode($this->input->raw_input_stream, true);
        if (!$email || !is_array($items) || !$items) {
            $this->output->set_status_header(204);
            return;
        }

        $this->db->where('email', $email)->delete('abandoned_carts');
        $this->db->insert('abandoned_carts', [
            'email' => $email,
            'items_json' => json_encode(array_slice($items, 0, 20)),
            'updated_at' => time(),
            'last_reminded_at' => 0,
            'reminders_today' => 0,
            'reminder_day' => date('Y-m-d'),
        ]);
        $this->output->set_content_type('application/json')->set_output(json_encode(['saved' => true]));
    }

    public function remind_abandoned()
    {
        if (php_sapi_name() !== 'cli' && $this->input->get('key', true) !== getenv('GAMEINA_REMINDER_KEY')) {
            show_404();
        }

        $smtp_user = getenv('GAMEINA_SMTP_USER');
        $smtp_pass = getenv('GAMEINA_SMTP_PASS');
        if (!$smtp_user || !$smtp_pass) {
            log_message('error', 'Cart reminder skipped: GAMEINA_SMTP_USER and GAMEINA_SMTP_PASS are required.');
            return;
        }

        $today = date('Y-m-d');
        $rows = $this->db->where('updated_at <', time() - 3600)->get('abandoned_carts')->result_array();
        foreach ($rows as $cart) {
            $sent_today = $cart['reminder_day'] === $today ? (int) $cart['reminders_today'] : 0;
            if ($sent_today >= 2 || ($cart['last_reminded_at'] && time() - $cart['last_reminded_at'] < 3600 * 8)) {
                continue;
            }

            $items = json_decode($cart['items_json'], true);
            $item_text = implode('<br>', array_map(function ($item) {
                return htmlspecialchars(($item['product'] ?? 'Product').' - '.($item['package'] ?? ''), ENT_QUOTES, 'UTF-8');
            }, is_array($items) ? $items : []));
            $this->email->initialize([
                'protocol' => 'smtp', 'smtp_host' => getenv('GAMEINA_SMTP_HOST') ?: 'smtp.gmail.com',
                'smtp_user' => $smtp_user, 'smtp_pass' => $smtp_pass,
                'smtp_port' => (int) (getenv('GAMEINA_SMTP_PORT') ?: 587),
                'smtp_crypto' => getenv('GAMEINA_SMTP_CRYPTO') ?: 'tls', 'mailtype' => 'html',
                'charset' => 'utf-8', 'newline' => "\r\n",
            ]);
            $this->email->from($smtp_user, 'StreamNest');
            $this->email->to($cart['email']);
            $this->email->subject('Your StreamNest cart is waiting');
            $this->email->message('<h2>Your cart is waiting for you</h2><p>You left these items in your StreamNest cart:</p><p>'.$item_text.'</p><p><a href="'.base_url('cart').'">Return to your cart</a> and complete your order.</p>');
            if ($this->email->send()) {
                $this->db->where('id', $cart['id'])->update('abandoned_carts', [
                    'last_reminded_at' => time(), 'reminders_today' => $sent_today + 1, 'reminder_day' => $today,
                ]);
            }
        }
    }
}