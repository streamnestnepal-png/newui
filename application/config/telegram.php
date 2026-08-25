<?php
defined('BASEPATH') or exit('No direct script access allowed');

$config['telegram_bot_token'] = getenv('GAMEINA_TELEGRAM_BOT_TOKEN') ?: '';
$config['telegram_admin_chat_id'] = getenv('GAMEINA_TELEGRAM_ADMIN_CHAT_ID') ?: '';
$config['telegram_webhook_secret'] = getenv('GAMEINA_TELEGRAM_WEBHOOK_SECRET') ?: '';
