<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payment extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('supabase_sync');
    }

    public function create_checkout()
    {
        if (strtoupper($this->input->method()) !== 'POST') {
            show_404();
        }
        if (!$this->session->userdata('email')) {
            $this->session->set_userdata('login_redirect', base_url('subscriptions/all'));
            $this->session->set_flashdata('purchase_notice', 'Please log in before buying a product.');
            redirect('welcome/login');
            return;
        }

        $this->config->load('paybridge');
        $api_key = $this->config->item('paybridgenp_api_key');
        $product = trim((string) $this->input->post('product', true));
        $package = trim((string) $this->input->post('package', true));
        $price = trim((string) $this->input->post('package_price', true));
        $email = trim((string) $this->input->post('delivery_email', true));
        $customer_name = trim((string) $this->input->post('customer_name', true));
        $phone_country = trim((string) $this->input->post('phone_country', true));
        $phone_number = trim((string) $this->input->post('phone_number', true));
        $user_id = trim((string) $this->input->post('user_id', true));
        $server_id = trim((string) $this->input->post('server_id', true));
        $requires_user_id = $product === 'Mobile Legends';
        $amount = $this->price_to_paisa($price);
        $valid_phone = ctype_digit($phone_number)
            && (($phone_country === '+977' && strlen($phone_number) === 10)
                || ($phone_country !== '+977' && strlen($phone_number) >= 7 && strlen($phone_number) <= 15));
        if (!$api_key || !$product || !$package || !$amount || ($requires_user_id && !$user_id) || !$customer_name || !$phone_country || !$valid_phone || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            show_error('Please complete the customer name, email, phone, and package details before checkout.', 400);
        }

        $checkout_id = 'CHK-'.strtoupper(bin2hex(random_bytes(8)));
        $checkout = [
            'checkout_id' => $checkout_id,
            'email' => strtolower($email),
            'customer_name' => $customer_name,
            'phone' => $phone_country.' '.$phone_number,
            'user_id' => $user_id,
            'server_id' => $server_id,
            'product' => $product,
            'package' => $package,
            'amount' => $amount,
            'status' => 'pending',
            'created_at' => time(),
        ];
        $this->db->insert('checkout_sessions', $checkout);
        $checkout['id'] = $this->db->insert_id();
        $this->supabase_sync->upsert('checkout_sessions', $checkout, 'checkout_id');
        $public_url = rtrim($this->config->item('paybridgenp_public_url'), '/');
        $payload = json_encode([
            'amount' => $amount,
            'currency' => 'NPR',
            'returnUrl' => $public_url.'/gameina/payment/complete?checkout_id='.rawurlencode($checkout_id),
            'cancelUrl' => $public_url.'/gameina/cart',
            'description' => $product.' - '.$package,
            'metadata' => ['checkout_id' => $checkout_id],
            'customer' => ['name' => $customer_name, 'email' => $email, 'phone' => $phone_country.' '.$phone_number],
        ]);
        $request = curl_init('https://api.paybridgenp.com/v1/checkout');
        curl_setopt_array($request, [CURLOPT_POST => true, CURLOPT_POSTFIELDS => $payload, CURLOPT_HTTPHEADER => ['Authorization: Bearer '.$api_key, 'Content-Type: application/json', 'Idempotency-Key: '.$checkout_id], CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 30]);
        $response_body = curl_exec($request);
        $status = curl_getinfo($request, CURLINFO_HTTP_CODE);
        curl_close($request);
        $response = json_decode((string) $response_body, true);
        if ($status < 200 || $status >= 300 || empty($response['checkout_url'])) {
            log_message('error', 'PayBridge checkout creation failed: '.(string) $response_body);
            show_error('Unable to start payment checkout. Please try again.', 502);
        }
        redirect($response['checkout_url']);
    }

    private function sendOrderConfirmation($order_id, $email, $customer_name, $product, $package, $amount, $phone, $user_id, $server_id)
    {
        $this->load->library('email');
        $this->email->initialize([
            'protocol' => 'smtp',
            'smtp_host' => getenv('GAMEINA_SMTP_HOST') ?: 'smtp.gmail.com',
            'smtp_user' => getenv('GAMEINA_SMTP_USER'),
            'smtp_pass' => getenv('GAMEINA_SMTP_PASS'),
            'smtp_port' => (int) (getenv('GAMEINA_SMTP_PORT') ?: 587),
            'smtp_crypto' => getenv('GAMEINA_SMTP_CRYPTO') ?: 'tls',
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'newline' => "\r\n",
        ]);
        $this->email->from(getenv('GAMEINA_SMTP_USER'), 'StreamNest');
        $this->email->to($email);
        $this->email->subject('Your StreamNest order was successful - '.$order_id);
        $extra = $user_id || $server_id ? '<br><strong>User ID:</strong> '.htmlspecialchars($user_id, ENT_QUOTES, 'UTF-8').'<br><strong>Server ID:</strong> '.htmlspecialchars($server_id, ENT_QUOTES, 'UTF-8') : '';
        $this->email->message('<h2>Your order was successful!</h2><p>Thank you, '.htmlspecialchars($customer_name, ENT_QUOTES, 'UTF-8').'. We received your order through StreamNest.</p><p><strong>Product:</strong> '.htmlspecialchars($product, ENT_QUOTES, 'UTF-8').'<br><strong>Package:</strong> '.htmlspecialchars($package, ENT_QUOTES, 'UTF-8').'<br><strong>Order ID:</strong> '.htmlspecialchars($order_id, ENT_QUOTES, 'UTF-8').'<br><strong>Amount:</strong> NPR '.number_format($amount / 100, 2).'<br><strong>Phone:</strong> '.htmlspecialchars($phone, ENT_QUOTES, 'UTF-8').$extra.'</p><p>Please keep this email as your order confirmation.</p>');
        if (!$this->email->send()) {
            log_message('error', 'Order confirmation email failed for '.$order_id.': '.$this->email->print_debugger(['headers']));
        }
    }

    private function notifyTelegram($order, $payment_id = '')
    {
        $this->config->load('telegram');
        $token = $this->config->item('telegram_bot_token');
        $chat_id = $this->config->item('telegram_admin_chat_id');
        if (!$token || !$chat_id || !function_exists('curl_init')) {
            log_message('error', 'Telegram order notification skipped: bot configuration is missing.');
            return;
        }

        $escape = static function ($value) {
            $value = trim((string) $value);
            return htmlspecialchars($value !== '' ? $value : '-', ENT_QUOTES, 'UTF-8');
        };
        $line = "\n━━━━━━━━━━━━━━━━━━\n";
        $message = '<b>💎 STREAM NEPAL</b>' . "\n"
            .'<i>Secure Payment • New Order</i>' . $line
            .'<b>✅ PAYMENT VERIFIED</b>' . "\n\n"
            .'<b>🎉 NEW ORDER RECEIVED</b>' . "\n\n"
            .'<b>🧾 ORDER DETAILS</b>' . "\n"
            .'Order ID: <code>'.$escape($order['order_id']).'</code>' . "\n"
            .'👤 Customer: '.$escape($order['customer_name']) . "\n"
            .'📧 Email: '.$escape($order['email']) . "\n"
            .'📱 Phone: '.$escape($order['phone']) . $line
            .'<b>🛍 PRODUCT INFORMATION</b>' . "\n"
            .'🎬 Product: '.$escape($order['product']) . "\n"
            .'📦 Package: '.$escape($order['package']) . "\n"
            .'💰 Amount: <b>NPR '.number_format((int) $order['amount'] / 100, 2).'</b>' . "\n"
            .'💳 Payment ID: <code>'.$escape($payment_id ?: ($order['payment_id'] ?? '')).'</code>' . $line
            .'<b>💳 PAYMENT STATUS</b>' . "\n"
            .'🟢 Payment: <b>PAID</b>' . "\n"
            .'⚙️ Order: <b>PROCESSING</b>' . "\n"
            .'🕒 Paid at: '.$escape(date('Y-m-d H:i:s')) . $line
            .'<b>🔗 SYSTEM INFORMATION</b>' . "\n"
            .'👤 User ID: '.$escape($order['user_id'] ?? '') . "\n"
            .'🖥️ Server ID: '.$escape($order['server_id'] ?? '') . $line
            .'🔐 <b>DELIVERY / CREDENTIALS</b>' . "\n"
            .'Send credentials using:' . "\n"
            .'<code>/credentials '.$escape($order['order_id']).' ID PASSWORD</code>' . "\n\n"
            .'<i>⚡ Payment verified • Order ready for processing</i>';
        $keyboard = ['inline_keyboard' => [[['text' => '🎁 Give Product', 'callback_data' => 'give_product:'.$order['order_id']]]]];
        $action = curl_init('https://api.telegram.org/bot'.$token.'/sendChatAction');
        curl_setopt_array($action, [CURLOPT_POST => true, CURLOPT_POSTFIELDS => http_build_query(['chat_id' => $chat_id, 'action' => 'typing']), CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 5]);
        curl_exec($action);
        curl_close($action);
        $request = curl_init('https://api.telegram.org/bot'.$token.'/sendMessage');
        curl_setopt_array($request, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query(['chat_id' => $chat_id, 'text' => $message, 'parse_mode' => 'HTML', 'disable_web_page_preview' => 'true', 'reply_markup' => json_encode($keyboard)]),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
        ]);
        $response = curl_exec($request);
        $status = curl_getinfo($request, CURLINFO_HTTP_CODE);
        curl_close($request);
        if ($status < 200 || $status >= 300) {
            log_message('error', 'Telegram order notification failed: '.(string) $response);
            return;
        }
        $telegram_result = json_decode((string) $response, true);
        $message_id = $telegram_result['result']['message_id'] ?? '';
        if ($message_id) {
            $this->db->where('order_id', $order['order_id'])->update('orders', ['telegram_chat_id' => (string) $chat_id, 'telegram_message_id' => (string) $message_id]);
        }
    }

    public function telegram_webhook()
    {
        $this->config->load('telegram');
        $secret = $this->config->item('telegram_webhook_secret');
        if (!$secret || !hash_equals($secret, (string) $this->input->get('secret', true))) {
            show_404();
        }
        $update = json_decode($this->input->raw_input_stream, true);
        $message = $update['message'] ?? ($update['callback_query']['message'] ?? []);
        if ((string) ($message['chat']['id'] ?? '') !== (string) $this->config->item('telegram_admin_chat_id')) {
            show_404();
        }
        $callback = $update['callback_query'] ?? null;
        if ($callback) {
            $callback_data = (string) ($callback['data'] ?? '');
            $callback_chat_id = (string) ($callback['message']['chat']['id'] ?? '');
            $callback_message_id = (string) ($callback['message']['message_id'] ?? '');
            if (strpos($callback_data, 'give_product:') === 0) {
                $order_id = substr($callback_data, strlen('give_product:'));
                $order = $this->db->get_where('orders', ['order_id' => $order_id, 'status' => 'paid'])->row_array();
                if ($order) {
                    $this->db->insert('telegram_delivery_drafts', ['order_id' => $order_id, 'chat_id' => $callback_chat_id, 'draft_message' => '', 'status' => 'waiting_input', 'created_at' => time()]);
                    $this->sendTelegramMessage("🎁 <b>GIVE PRODUCT</b>\n\nSend any product information now. You can include login details, links, instructions, or a message for the customer.\n\n<i>Your next message will be shown as a preview before sending.</i>");
                    $this->answerTelegramCallback($callback['id'], 'Send the product information now.');
                } else {
                    $this->answerTelegramCallback($callback['id'], 'Paid order not found.');
                }
            } elseif (strpos($callback_data, 'send_product:') === 0) {
                $order_id = substr($callback_data, strlen('send_product:'));
                $draft = $this->db->order_by('id', 'DESC')->get_where('telegram_delivery_drafts', ['order_id' => $order_id, 'chat_id' => $callback_chat_id, 'status' => 'preview'])->row_array();
                $order = $this->db->get_where('orders', ['order_id' => $order_id, 'status' => 'paid'])->row_array();
                if ($draft && $order && $this->sendProductEmail($order, $draft['draft_message'])) {
                    $this->db->where('order_id', $order_id)->update('orders', ['credentials_sent_at' => time(), 'status' => 'fulfilled']);
                        $order['credentials_sent_at'] = time();
                        $order['status'] = 'fulfilled';
                        $this->supabase_sync->upsert('orders', $order, 'order_id');
                        $order['credentials_sent_at'] = time();
                        $order['status'] = 'fulfilled';
                        $this->supabase_sync->upsert('orders', $order, 'order_id');
                    $this->db->where('id', $draft['id'])->update('telegram_delivery_drafts', ['status' => 'sent']);
                    $completed = "━━━━━━━━━━━━━━━━━━\n📦 <b>ORDER COMPLETED</b>\n\n🟢 Payment: <b>PAID</b>\n✅ Order: <b>DELIVERED</b>\n📧 Customer: <b>NOTIFIED</b>\n🕒 Delivered: <code>".date('Y-m-d H:i:s')."</code>\n━━━━━━━━━━━━━━━━━━";
                    $this->editTelegramMessage($callback_chat_id, $callback_message_id, $completed);
                    $this->editTelegramMessage($order['telegram_chat_id'], $order['telegram_message_id'], "<b>💎 STREAM NEPAL</b>\n━━━━━━━━━━━━━━━━━━\n✅ <b>ORDER COMPLETED</b>\n\n🧾 Order ID: <code>".htmlspecialchars($order_id, ENT_QUOTES, 'UTF-8')."</code>\n🛍 Product: ".htmlspecialchars($order['product'], ENT_QUOTES, 'UTF-8')."\n💳 Payment: <b>PAID</b>\n📦 Delivery: <b>COMPLETED</b>\n📧 Customer: <b>NOTIFIED</b>\n━━━━━━━━━━━━━━━━━━");
                    $this->answerTelegramCallback($callback['id'], 'Sent to customer email.');
                } else {
                    $this->answerTelegramCallback($callback['id'], 'Email failed or preview expired.');
                }
            } elseif (strpos($callback_data, 'cancel_product:') === 0) {
                $order_id = substr($callback_data, strlen('cancel_product:'));
                $this->db->where(['order_id' => $order_id, 'chat_id' => $callback_chat_id, 'status' => 'preview'])->update('telegram_delivery_drafts', ['status' => 'cancelled']);
                $this->answerTelegramCallback($callback['id'], 'Delivery cancelled.');
            }
            $this->output->set_content_type('application/json')->set_output(json_encode(['ok' => true]));
            return;
        }
        $text = trim((string) ($message['text'] ?? ''));
        if ($text && strpos($text, '/') !== 0) {
            $draft = $this->db->order_by('id', 'DESC')->get_where('telegram_delivery_drafts', ['chat_id' => (string) $message['chat']['id'], 'status' => 'waiting_input'])->row_array();
            if ($draft) {
                $order = $this->db->get_where('orders', ['order_id' => $draft['order_id'], 'status' => 'paid'])->row_array();
                if ($order) {
                    $this->db->where('id', $draft['id'])->update('telegram_delivery_drafts', ['draft_message' => $text, 'status' => 'preview']);
                    $preview = "<b>📨 EMAIL PREVIEW</b>\n━━━━━━━━━━━━━━━━━━\n<b>To:</b> ".htmlspecialchars($order['email'], ENT_QUOTES, 'UTF-8')."\n<b>Product:</b> ".htmlspecialchars($order['product'].' - '.$order['package'], ENT_QUOTES, 'UTF-8')."\n<b>Order ID:</b> <code>".htmlspecialchars($order['order_id'], ENT_QUOTES, 'UTF-8')."</code>\n━━━━━━━━━━━━━━━━━━\n".nl2br(htmlspecialchars($text, ENT_QUOTES, 'UTF-8'))."\n━━━━━━━━━━━━━━━━━━\n<i>Review the message before sending.</i>";
                    $this->sendTelegramMessage($preview, ['inline_keyboard' => [[['text' => '✅ SEND TO CUSTOMER', 'callback_data' => 'send_product:'.$order['order_id']], ['text' => '❌ CANCEL', 'callback_data' => 'cancel_product:'.$order['order_id']]]]]);
                }
            }
        }
        $reply_text = (string) ($message['reply_to_message']['text'] ?? '');
        if (preg_match('/Order ID:\s*<code>(ORD-[A-Z0-9]+)<\/code>/i', $reply_text, $reply_order)) {
            $reply_order_id = $reply_order[1];
        } elseif (preg_match('/Order ID:\s*(ORD-[A-Z0-9]+)/i', $reply_text, $reply_order)) {
            $reply_order_id = $reply_order[1];
        } else {
            $reply_order_id = '';
        }
        if (preg_match('/^\/deliver\s+(?:(ORD-[A-Z0-9]+)\s+)?(.+)$/s', $text, $delivery_matches)) {
            $order_id = $delivery_matches[1] ?: $reply_order_id;
            $delivery_message = trim($delivery_matches[2]);
            $order = $order_id ? $this->db->get_where('orders', ['order_id' => $order_id, 'status' => 'paid'])->row_array() : null;
            if (!$order) {
                $this->sendTelegramMessage('<b>⚠️ Paid order not found.</b> Reply to the order message or include a valid Order ID.');
            } else {
                $this->db->where(['order_id' => $order_id, 'chat_id' => (string) $message['chat']['id'], 'status' => 'waiting_input'])->delete('telegram_delivery_drafts');
                $this->db->insert('telegram_delivery_drafts', ['order_id' => $order_id, 'chat_id' => (string) $message['chat']['id'], 'draft_message' => $delivery_message, 'status' => 'preview', 'created_at' => time()]);
                $preview = "<b>📨 EMAIL PREVIEW</b>\n━━━━━━━━━━━━━━━━━━\n<b>To:</b> ".htmlspecialchars($order['email'], ENT_QUOTES, 'UTF-8')."\n<b>Product:</b> ".htmlspecialchars($order['product'].' - '.$order['package'], ENT_QUOTES, 'UTF-8')."\n<b>Order ID:</b> <code>".htmlspecialchars($order['order_id'], ENT_QUOTES, 'UTF-8')."</code>\n━━━━━━━━━━━━━━━━━━\n".nl2br(htmlspecialchars($delivery_message, ENT_QUOTES, 'UTF-8'))."\n━━━━━━━━━━━━━━━━━━\n<i>Review the message before sending.</i>";
                $this->sendTelegramMessage($preview, ['inline_keyboard' => [[['text' => '✅ SEND TO CUSTOMER', 'callback_data' => 'send_product:'.$order['order_id']], ['text' => '❌ CANCEL', 'callback_data' => 'cancel_product:'.$order['order_id']]]]]);
            }
        }
        if (preg_match('/^\/credentials\s+(ORD-[A-Z0-9]+)\s+(.+?)\s+(.+)$/s', $text, $matches)) {
            $order_id = $matches[1];
            $login_id = trim($matches[2]);
            $password = trim($matches[3]);
            $order = $this->db->get_where('orders', ['order_id' => $order_id])->row_array();
            if ($order) {
                if ($this->sendCredentialEmail($order, $login_id, $password)) {
                    $this->db->where('order_id', $order_id)->update('orders', ['credentials_sent_at' => time(), 'status' => 'fulfilled']);
                    $this->sendTelegramMessage("━━━━━━━━━━━━━━━━━━\n📦 <b>ORDER DELIVERED</b>\n\n🟢 Status: <b>COMPLETED</b>\n🔐 Credentials: <b>SENT</b>\n🕒 Delivered at: <code>".date('Y-m-d H:i:s')."</code>\n\n━━━━━━━━━━━━━━━━━━\n✅ <b>CUSTOMER NOTIFIED BY EMAIL</b>\n━━━━━━━━━━━━━━━━━━");
                } else {
                    $this->sendTelegramMessage("⚠️ <b>Email delivery failed</b> for <code>".htmlspecialchars($order_id, ENT_QUOTES, 'UTF-8')."</code>. Order remains PROCESSING; please check SMTP settings and try again.");
                }
            } else {
                $this->sendTelegramMessage('<b>⚠️ Order not found:</b> <code>'.htmlspecialchars($order_id, ENT_QUOTES, 'UTF-8').'</code>');
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode(['ok' => true]));
    }

    private function sendTelegramMessage($message, $reply_markup = null)
    {
        $this->config->load('telegram');
        $request = curl_init('https://api.telegram.org/bot'.$this->config->item('telegram_bot_token').'/sendMessage');
        $fields = ['chat_id' => $this->config->item('telegram_admin_chat_id'), 'text' => $message, 'parse_mode' => 'HTML', 'disable_web_page_preview' => 'true'];
        if ($reply_markup) {
            $fields['reply_markup'] = json_encode($reply_markup);
        }
        curl_setopt_array($request, [CURLOPT_POST => true, CURLOPT_POSTFIELDS => http_build_query($fields), CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 10]);
        curl_exec($request);
        curl_close($request);
    }

    private function answerTelegramCallback($callback_id, $text)
    {
        $this->config->load('telegram');
        $request = curl_init('https://api.telegram.org/bot'.$this->config->item('telegram_bot_token').'/answerCallbackQuery');
        curl_setopt_array($request, [CURLOPT_POST => true, CURLOPT_POSTFIELDS => http_build_query(['callback_query_id' => $callback_id, 'text' => $text, 'show_alert' => 'false']), CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 10]);
        curl_exec($request);
        curl_close($request);
    }

    private function editTelegramMessage($chat_id, $message_id, $text)
    {
        if (!$chat_id || !$message_id) {
            return;
        }
        $this->config->load('telegram');
        $request = curl_init('https://api.telegram.org/bot'.$this->config->item('telegram_bot_token').'/editMessageText');
        curl_setopt_array($request, [CURLOPT_POST => true, CURLOPT_POSTFIELDS => http_build_query(['chat_id' => $chat_id, 'message_id' => $message_id, 'text' => $text, 'parse_mode' => 'HTML', 'disable_web_page_preview' => 'true']), CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 10]);
        curl_exec($request);
        curl_close($request);
    }

    private function sendCredentialEmail($order, $login_id, $password)
    {
        $this->load->library('email');
        $this->email->initialize(['protocol' => 'smtp', 'smtp_host' => getenv('GAMEINA_SMTP_HOST') ?: 'smtp.gmail.com', 'smtp_user' => getenv('GAMEINA_SMTP_USER'), 'smtp_pass' => getenv('GAMEINA_SMTP_PASS'), 'smtp_port' => (int) (getenv('GAMEINA_SMTP_PORT') ?: 587), 'smtp_crypto' => getenv('GAMEINA_SMTP_CRYPTO') ?: 'tls', 'mailtype' => 'html', 'charset' => 'utf-8', 'newline' => "\r\n"]);
        $this->email->from(getenv('GAMEINA_SMTP_USER'), 'StreamNest');
        $this->email->to($order['email']);
        $this->email->subject('Your '.$order['product'].' credentials - '.$order['order_id']);
        $this->email->message('<h2>Your order is ready!</h2><p>Here are your credentials for <strong>'.htmlspecialchars($order['product'].' - '.$order['package'], ENT_QUOTES, 'UTF-8').'</strong>.</p><p><strong>Login ID:</strong> '.htmlspecialchars($login_id, ENT_QUOTES, 'UTF-8').'<br><strong>Password:</strong> '.htmlspecialchars($password, ENT_QUOTES, 'UTF-8').'<br><strong>Order ID:</strong> '.htmlspecialchars($order['order_id'], ENT_QUOTES, 'UTF-8').'</p><p>Keep these details private.</p>');
        if (!$this->email->send()) {
            log_message('error', 'Credential email failed for '.$order['order_id'].': '.$this->email->print_debugger(['headers']));
            return false;
        }
        return true;
    }

    private function sendProductEmail($order, $delivery_message)
    {
        $this->load->library('email');
        $this->email->initialize(['protocol' => 'smtp', 'smtp_host' => getenv('GAMEINA_SMTP_HOST') ?: 'smtp.gmail.com', 'smtp_user' => getenv('GAMEINA_SMTP_USER'), 'smtp_pass' => getenv('GAMEINA_SMTP_PASS'), 'smtp_port' => (int) (getenv('GAMEINA_SMTP_PORT') ?: 587), 'smtp_crypto' => getenv('GAMEINA_SMTP_CRYPTO') ?: 'tls', 'mailtype' => 'html', 'charset' => 'utf-8', 'newline' => "\r\n"]);
        $this->email->from(getenv('GAMEINA_SMTP_USER'), 'StreamNest');
        $this->email->to($order['email']);
        $this->email->subject('Your '.$order['product'].' delivery - '.$order['order_id']);
        $this->email->message('<h2>Your product is ready!</h2><p>Thank you for your purchase of <strong>'.htmlspecialchars($order['product'].' - '.$order['package'], ENT_QUOTES, 'UTF-8').'</strong>.</p><p><strong>Order ID:</strong> '.htmlspecialchars($order['order_id'], ENT_QUOTES, 'UTF-8').'<br><strong>Payment status:</strong> Paid<br><strong>Order status:</strong> Completed</p><hr><p>'.nl2br(htmlspecialchars($delivery_message, ENT_QUOTES, 'UTF-8')).'</p><p>Keep your delivery details private.</p>');
        if (!$this->email->send()) {
            log_message('error', 'Product delivery email failed for '.$order['order_id'].': '.$this->email->print_debugger(['headers']));
            return false;
        }
        return true;
    }

    public function complete()
    {
        $checkout_id = trim((string) $this->input->get('checkout_id', true));
        $payment_id = trim((string) $this->input->get('payment_id', true));
        if (!$checkout_id && $payment_id) {
            $payment = $this->retrievePaybridgePayment($payment_id);
            $checkout_id = $payment['metadata']['checkout_id'] ?? '';
            if ($checkout_id && $this->verifyPaybridgePayment($payment_id, $checkout_id)) {
                $this->finalizePayment($checkout_id, $payment_id);
            }
        }
        $order = $checkout_id ? $this->db->get_where('orders', ['checkout_id' => $checkout_id])->row_array() : null;
        $message = $order && $order['status'] === 'paid'
            ? 'Your order was successful.'
            : 'Please wait a moment while we process your order.';
        $this->load->view('payment/complete', ['message' => $message, 'order' => $order, 'checkout_id' => $checkout_id]);
    }

    private function retrievePaybridgePayment($payment_id)
    {
        $this->config->load('paybridge');
        $request = curl_init(sprintf($this->config->item('paybridgenp_verify_url'), rawurlencode($payment_id)));
        curl_setopt_array($request, [CURLOPT_HTTPHEADER => ['Authorization: Bearer '.$this->config->item('paybridgenp_api_key'), 'Accept: application/json'], CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 15]);
        $body = curl_exec($request);
        $status = curl_getinfo($request, CURLINFO_HTTP_CODE);
        curl_close($request);
        $payment = json_decode((string) $body, true);
        return $status >= 200 && $status < 300 && is_array($payment) ? $payment : [];
    }

    public function payment_status()
    {
        $checkout_id = trim((string) $this->input->get('checkout_id', true));
        $order = $checkout_id ? $this->db->get_where('orders', ['checkout_id' => $checkout_id])->row_array() : null;
        $this->output->set_content_type('application/json')->set_output(json_encode([
            'paid' => $order && $order['status'] === 'paid',
            'order_id' => $order['order_id'] ?? null,
            'status' => $order['status'] ?? 'pending',
        ]));
    }

    private function price_to_paisa($price)
    {
        $normalized = str_replace([',', 'NPR', 'Rs', 'रु', ' '], '', (string) $price);
        if (!is_numeric($normalized)) {
            return 0;
        }

        return (int) round((float) $normalized * 100);
    }

    public function paybridge_webhook()
    {
        if (strtoupper($this->input->method()) !== 'POST') {
            $this->output
                ->set_status_header(405)
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'POST requests only']));
            return;
        }

        $raw_payload = $this->input->raw_input_stream;
        $this->config->load('paybridge');
        $signing_secret = $this->config->item('paybridge_webhook_secret');
        $signature = $this->input->get_request_header('X-PayBridgeNP-Signature', true);
        if (!$signature) {
            $signature = $this->input->get_request_header('X-Webhook-Signature', true);
        }

        if (!$signature && function_exists('getallheaders')) {
            foreach (getallheaders() as $header_name => $header_value) {
                if (stripos($header_name, 'signature') !== false) {
                    $signature = $header_value;
                    break;
                }
            }
        }

        if (!$signature) {
            $headers = function_exists('getallheaders') ? getallheaders() : [];
            log_message('error', 'PayBridge signature header not found. Headers: '.implode(', ', array_keys($headers)));
        }

        if ($signing_secret && $signing_secret !== 'PASTE_YOUR_SIGNING_SECRET_HERE') {
            $signature_parts = [];
            foreach (explode(',', trim((string) $signature)) as $part) {
                $part = explode('=', trim($part), 2);
                if (count($part) === 2) {
                    $signature_parts[$part[0]] = $part[1];
                }
            }
            $timestamp = $signature_parts['t'] ?? null;
            $provided_signature = $signature_parts['v1'] ?? null;
            if (!$timestamp || !$provided_signature || !ctype_digit($timestamp) || abs(time() - (int) $timestamp) > 300) {
                $this->output
                    ->set_status_header(400)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Malformed or expired webhook signature']));
                return;
            }

            $expected_signature = hash_hmac('sha256', $timestamp.'.'.$raw_payload, $signing_secret);
            if (!hash_equals($expected_signature, $provided_signature)) {
                $this->output
                    ->set_status_header(400)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => 'Invalid webhook signature']));
                return;
            }
        }

        $payload = json_decode($raw_payload, true);
        if (!is_array($payload)) {
            $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Invalid JSON payload']));
            return;
        }

        log_message('info', 'PayBridge webhook received: '.json_encode($payload));

        $event = strtolower((string) ($payload['event'] ?? $payload['type'] ?? ''));
        $payment_status = strtolower((string) ($payload['status'] ?? $payload['payment_status'] ?? $payload['data']['status'] ?? ''));
        $payment_id = (string) ($payload['payment_id'] ?? $payload['transaction_id'] ?? $payload['data']['payment_id'] ?? $payload['data']['id'] ?? $payload['id'] ?? '');
        $checkout_id = (string) ($payload['metadata']['checkout_id'] ?? $payload['data']['metadata']['checkout_id'] ?? $payload['checkout_id'] ?? '');
        $payment_succeeded = in_array($event, ['payment.success', 'payment.succeeded', 'payment.completed', 'payment.paid', 'invoice.paid', 'invoice.payment_succeeded', 'success', 'paid', 'completed'], true)
            || in_array($payment_status, ['success', 'succeeded', 'paid', 'completed'], true);
        if ($payment_succeeded && $payment_id && $checkout_id) {
            $verified = $this->verifyPaybridgePayment($payment_id, $checkout_id);
            if ($verified) {
                $this->finalizePayment($checkout_id, $payment_id);
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'received' => true,
                'event' => $payload['event'] ?? $payload['type'] ?? null,
                'payment_id' => $payload['payment_id'] ?? $payload['id'] ?? null,
            ]));
    }

    private function verifyPaybridgePayment($payment_id, $checkout_id)
    {
        $this->config->load('paybridge');
        $verify_url = sprintf($this->config->item('paybridgenp_verify_url'), rawurlencode($payment_id));
        $request = curl_init($verify_url);
        curl_setopt_array($request, [CURLOPT_HTTPHEADER => ['Authorization: Bearer '.$this->config->item('paybridgenp_api_key'), 'Accept: application/json'], CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 15]);
        $body = curl_exec($request);
        $status = curl_getinfo($request, CURLINFO_HTTP_CODE);
        curl_close($request);
        $result = json_decode((string) $body, true);
        $verified_status = strtolower((string) ($result['status'] ?? $result['payment_status'] ?? $result['data']['status'] ?? ''));
        $verified_id = (string) ($result['payment_id'] ?? $result['transaction_id'] ?? $result['id'] ?? $result['data']['payment_id'] ?? $result['data']['id'] ?? '');
        $verified_checkout = (string) ($result['metadata']['checkout_id'] ?? $result['data']['metadata']['checkout_id'] ?? $result['checkout_id'] ?? '');
        $session = $this->db->get_where('checkout_sessions', ['checkout_id' => $checkout_id])->row_array();
        $verified_amount = (int) ($result['amount'] ?? $result['data']['amount'] ?? 0);
        return $status >= 200 && $status < 300 && $session
            && in_array($verified_status, ['success', 'succeeded', 'paid', 'completed'], true)
            && hash_equals((string) $payment_id, $verified_id)
            && hash_equals((string) $checkout_id, $verified_checkout)
            && $verified_amount === (int) $session['amount'];
    }

    private function finalizePayment($checkout_id, $payment_id)
    {
        $this->db->trans_start();
        $session = $this->db->query('SELECT * FROM checkout_sessions WHERE checkout_id = ? FOR UPDATE', [$checkout_id])->row_array();
        if (!$session || $session['status'] !== 'pending') {
            $this->db->trans_complete();
            return;
        }
        $order_id = 'ORD-'.strtoupper(bin2hex(random_bytes(6)));
        $this->db->insert('orders', ['order_id' => $order_id, 'checkout_id' => $checkout_id, 'payment_id' => $payment_id, 'email' => $session['email'], 'customer_name' => $session['customer_name'], 'phone' => $session['phone'], 'user_id' => $session['user_id'], 'server_id' => $session['server_id'], 'product' => $session['product'], 'package' => $session['package'], 'amount' => $session['amount'], 'status' => 'paid', 'confirmation_sent_at' => 0, 'credentials_sent_at' => 0, 'created_at' => time()]);
        $this->db->where('checkout_id', $checkout_id)->update('checkout_sessions', ['status' => 'paid', 'payment_id' => $payment_id]);
            $session['status'] = 'paid';
            $session['payment_id'] = $payment_id;
            $this->supabase_sync->upsert('checkout_sessions', $session, 'checkout_id');
        $order = $this->db->get_where('orders', ['order_id' => $order_id])->row_array();
        $this->db->trans_complete();
        if ($order) {
            $this->supabase_sync->upsert('orders', $order, 'order_id');
            $this->sendOrderConfirmation($order['order_id'], $order['email'], $order['customer_name'], $order['product'], $order['package'], (int) $order['amount'], $order['phone'], $order['user_id'], $order['server_id']);
            $this->notifyTelegram($order, $payment_id);
            $this->db->where('order_id', $order_id)->update('orders', ['confirmation_sent_at' => time()]);
                $order['confirmation_sent_at'] = time();
                $this->supabase_sync->upsert('orders', $order, 'order_id');
        }
    }
}