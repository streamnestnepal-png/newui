<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Store extends CI_Controller
{
    public function netflix()
    {
        $products = [
            ['Netflix 1 Day Account', 'Nepal', 10],
            ['Netflix 1 Month 4K UHD - 5 Screens', 'Nepal', 1000],
            ['Netflix 1 Month Shared Account', 'Nepal', 400],
            ['Netflix 1 Week Account', 'Nepal', 200],
        ];

        $this->load->view('templates/navgame');
        $this->load->view('subscriptions/netflix', ['products' => $products]);
        $this->load->view('templates/footer');
    }

    public function spotify()
    {
        $products = [
            ['Spotify Premium 3 Month TRIAL (ONLY FOR NEW ACCOUNTS)', 'GLOBAL', 290],
            ['Spotify Premium 1 Month Subscription', 'GLOBAL', 300],
        ];

        $this->load->view('templates/navgame');
        $this->load->view('subscriptions/spotify', ['products' => $products]);
        $this->load->view('templates/footer');
    }

    public function youtube()
    {
        $products = [
            ['YouTube Premium 1 Month', 'Nepal', 190],
            ['YouTube Premium 3 Months', 'Nepal', 450],
        ];

        $this->load->view('templates/navgame');
        $this->load->view('subscriptions/youtube', ['products' => $products]);
        $this->load->view('templates/footer');
    }

    public function chatgpt()
    {
        $products = [
            ['ChatGPT Go 1 Month', 'Nepal', 860],
            ['ChatGPT Plus 1 Month', 'Nepal', 1850],
        ];

        $this->load->view('templates/navgame');
        $this->load->view('subscriptions/chatgpt', ['products' => $products]);
        $this->load->view('templates/footer');
    }

    public function canva()
    {
        $products = [
            ['Canva Education 1 Year', 'Nepal', 180],
            ['Canva Pro 1 Year', 'Nepal', 500],
            ['Canva Pro 1 Month', 'Nepal', 160],
            ['Canva Pro 6 Months', 'Nepal', 400],
        ];

        $this->load->view('templates/navgame');
        $this->load->view('subscriptions/canva', ['products' => $products]);
        $this->load->view('templates/footer');
    }

    public function adobe()
    {
        $products = [
            ['Adobe Creative Cloud Pro (PC) 1 Month - Adobe Key', 'GLOBAL', 1800],
            ['Adobe Creative Cloud Pro (PC) 3 Months - Adobe Key', 'GLOBAL', 9500],
        ];

        $this->load->view('templates/navgame');
        $this->load->view('subscriptions/adobe', ['products' => $products]);
        $this->load->view('templates/footer');
    }

    public function vpn()
    {
        $products = [
            ['NordVPN Basic VPN Service PC, Android, Mac, iOS 10 Devices 3 Months', 'GLOBAL', 672, 'vpn/nord.webp'],
            ['Express VPN PC, Mac 1 Device 12 Months', 'GLOBAL', 578, 'vpn/express.avif'],
            ['NordVPN Plus VPN Service PC, Android, Mac, iOS 10 Devices 1 Year', 'GLOBAL', 4613, 'vpn/nord.webp'],
            ['HMA! Pro VPN 1 Month', 'GLOBAL', 121, 'vpn/hma.webp'],
            ['Surfshark VPN Trial Unlimited Devices 2 Months', 'GLOBAL', 610, 'vpn/surfshark.avif'],
            ['NordVPN Standard VPN Service PC, Android, Mac, iOS 10 Devices 2 Years', 'GLOBAL', 6003, 'vpn/nord.webp'],
            ['Mullvad VPN 6 Months', 'GLOBAL', 4971, 'vpn/mullvad.avif'],
            ['NordVPN Basic VPN Service PC, Android, Mac, iOS 10 Devices 2 Years', 'GLOBAL', 4502, 'vpn/nord.webp'],
            ['Express VPN PC, Mac 1 Device 6 Months', 'GLOBAL', 393, 'vpn/express.avif'],
            ['Avast SecureLine VPN 10 Devices 2 Years', 'GLOBAL', 1727, 'vpn/avast.webp'],
            ['NordVPN Basic VPN Service PC, Android, Mac, iOS 10 Devices 6 Months', 'GLOBAL', 2058, 'vpn/nord.webp'],
            ['Bitdefender Premium VPN PC, Android, Mac, iOS 1 Year', 'GLOBAL', 2158, 'vpn/bitdefender.avif'],
        ];

        $this->load->view('templates/navgame');
        $this->load->view('subscriptions/vpn', ['products' => $products]);
        $this->load->view('templates/footer');
    }

    public function discord()
    {
        $products = [
            ['Discord Nitro Trial 3 Months', 'GLOBAL', 158],
            ['Discord Nitro Trial 1 Month', 'GLOBAL', 130],
            ['Discord Server Boost 28x 1 Month', 'GLOBAL', 966],
            ['Discord Server Boost 20x 3 Months', 'GLOBAL', 2018],
            ['Discord Nitro 1 Month', 'GLOBAL', 928],
            ['Discord Nitro 3 Months', 'GLOBAL', 1688],
            ['Discord Nitro Basic 1 Month', 'GLOBAL', 602],
            ['Discord Nitro 1 Year', 'GLOBAL', 13501],
            ['Discord Server Boost 14x 1 Week', 'GLOBAL', 441],
            ['Discord Server Boost 50x 1 Month', 'GLOBAL', 1724],
            ['Discord Nitro Gift Card 5 USD', 'GLOBAL', 896],
            ['Discord Nitro Gift Card 20 USD', 'GLOBAL', 3359],
            ['Discord Nitro Gift Card 10 USD', 'GLOBAL', 1716],
            ['Discord Nitro Basic 1 Year', 'GLOBAL', 1686],
        ];

        $this->load->view('templates/navgame');
        $this->load->view('subscriptions/discord', ['products' => $products]);
        $this->load->view('templates/footer');
    }

    public function telegram()
    {
        $products = [
            ['Telegram Top-Up 1000 Stars', 'GLOBAL', 3020],
            ['Telegram Top-Up 500 Stars', 'GLOBAL', 1764],
            ['Telegram Top-Up 2500 Stars', 'GLOBAL', 7423],
            ['Telegram Top-Up 250 Stars', 'GLOBAL', 996],
            ['Telegram Top-Up 100 Stars', 'GLOBAL', 465],
            ['Telegram Top-Up 50 Stars', 'GLOBAL', 305],
            ['Telegram Premium 3 Months', 'GLOBAL', 3003],
            ['Telegram Top-Up 5000 Stars', 'GLOBAL', 13788],
            ['Telegram Top-Up 10000 Stars', 'GLOBAL', 26606],
            ['Telegram Premium 12 Months', 'GLOBAL', 6786],
            ['Telegram Premium 6 Months', 'GLOBAL', 3955],
            ['Telegram Members 50 Members', 'GLOBAL', 955],
            ['Telegram Members 100 Members', 'GLOBAL', 1871],
            ['Telegram Members 250 Members', 'GLOBAL', 3573],
            ['Telegram Members 10000 Members', 'GLOBAL', 20079],
            ['Telegram Members 5000 Members', 'GLOBAL', 16735],
            ['Telegram Members 1000 Members', 'GLOBAL', 10400],
            ['Telegram Members 500 Members', 'GLOBAL', 7017],
        ];

        $this->load->view('templates/navgame');
        $this->load->view('subscriptions/telegram', ['products' => $products]);
        $this->load->view('templates/footer');
    }

    public function all_subscriptions()
    {
        $products = [
            ['Netflix 1 Day Account', 'Nepal', 10, 'media/netflix.jpg'],
            ['Netflix 1 Month 4K UHD - 5 Screens', 'Nepal', 1000, 'media/netflix.jpg'],
            ['Netflix 1 Month Shared Account', 'Nepal', 400, 'media/netflix.jpg'],
            ['Netflix 1 Week Account', 'Nepal', 200, 'media/netflix.jpg'],
            ['Spotify Premium 3 Month TRIAL (ONLY FOR NEW ACCOUNTS)', 'GLOBAL', 290, 'media/spotify.jpg'],
            ['Spotify Premium 1 Month Subscription', 'GLOBAL', 300, 'media/spotify.jpg'],
            ['YouTube Premium 1 Month', 'Nepal', 190, 'media/youtube.jpg'],
            ['YouTube Premium 3 Months', 'Nepal', 450, 'media/youtube.jpg'],
            ['ChatGPT Go 1 Month', 'Nepal', 860, 'media/chatgpt.png'],
            ['ChatGPT Plus 1 Month', 'Nepal', 1850, 'media/chatgpt.png'],
            ['Canva Education 1 Year', 'Nepal', 180, 'media/canva.avif'],
            ['Canva Pro 1 Year', 'Nepal', 500, 'media/canva.avif'],
            ['Canva Pro 1 Month', 'Nepal', 160, 'media/canva.avif'],
            ['Canva Pro 6 Months', 'Nepal', 400, 'media/canva.avif'],
            ['Adobe Creative Cloud Pro (PC) 1 Month - Adobe Key', 'GLOBAL', 1800, 'media/adobe.jpeg'],
            ['Adobe Creative Cloud Pro (PC) 3 Months - Adobe Key', 'GLOBAL', 9500, 'media/adobe.jpeg'],
            ['NordVPN Basic VPN Service PC, Android, Mac, iOS 10 Devices 3 Months', 'GLOBAL', 672, 'vpn/nord.webp'],
            ['Express VPN PC, Mac 1 Device 12 Months', 'GLOBAL', 578, 'vpn/express.avif'],
            ['NordVPN Plus VPN Service PC, Android, Mac, iOS 10 Devices 1 Year', 'GLOBAL', 4613, 'vpn/nord.webp'],
            ['HMA! Pro VPN 1 Month', 'GLOBAL', 121, 'vpn/hma.webp'],
            ['Surfshark VPN Trial Unlimited Devices 2 Months', 'GLOBAL', 610, 'vpn/surfshark.avif'],
            ['NordVPN Standard VPN Service PC, Android, Mac, iOS 10 Devices 2 Years', 'GLOBAL', 6003, 'vpn/nord.webp'],
            ['Mullvad VPN 6 Months', 'GLOBAL', 4971, 'vpn/mullvad.avif'],
            ['NordVPN Basic VPN Service PC, Android, Mac, iOS 10 Devices 2 Years', 'GLOBAL', 4502, 'vpn/nord.webp'],
            ['Express VPN PC, Mac 1 Device 6 Months', 'GLOBAL', 393, 'vpn/express.avif'],
            ['Avast SecureLine VPN 10 Devices 2 Years', 'GLOBAL', 1727, 'vpn/avast.webp'],
            ['NordVPN Basic VPN Service PC, Android, Mac, iOS 10 Devices 6 Months', 'GLOBAL', 2058, 'vpn/nord.webp'],
            ['Bitdefender Premium VPN PC, Android, Mac, iOS 1 Year', 'GLOBAL', 2158, 'vpn/bitdefender.avif'],
            ['Discord Nitro Trial 3 Months', 'GLOBAL', 158, 'media/discord.avif'],
            ['Discord Nitro Trial 1 Month', 'GLOBAL', 130, 'media/discord.avif'],
            ['Discord Server Boost 28x 1 Month', 'GLOBAL', 966, 'media/discord.avif'],
            ['Discord Server Boost 20x 3 Months', 'GLOBAL', 2018, 'media/discord.avif'],
            ['Discord Nitro 1 Month', 'GLOBAL', 928, 'media/discord.avif'],
            ['Discord Nitro 3 Months', 'GLOBAL', 1688, 'media/discord.avif'],
            ['Discord Nitro Basic 1 Month', 'GLOBAL', 602, 'media/discord.avif'],
            ['Discord Nitro 1 Year', 'GLOBAL', 13501, 'media/discord.avif'],
            ['Discord Server Boost 14x 1 Week', 'GLOBAL', 441, 'media/discord.avif'],
            ['Discord Server Boost 50x 1 Month', 'GLOBAL', 1724, 'media/discord.avif'],
            ['Discord Nitro Gift Card 5 USD', 'GLOBAL', 896, 'media/discord.avif'],
            ['Discord Nitro Gift Card 20 USD', 'GLOBAL', 3359, 'media/discord.avif'],
            ['Discord Nitro Gift Card 10 USD', 'GLOBAL', 1716, 'media/discord.avif'],
            ['Discord Nitro Basic 1 Year', 'GLOBAL', 1686, 'media/discord.avif'],
            ['Telegram Top-Up 1000 Stars', 'GLOBAL', 3020, 'media/telegram.avif'],
            ['Telegram Top-Up 500 Stars', 'GLOBAL', 1764, 'media/telegram.avif'],
            ['Telegram Top-Up 2500 Stars', 'GLOBAL', 7423, 'media/telegram.avif'],
            ['Telegram Top-Up 250 Stars', 'GLOBAL', 996, 'media/telegram.avif'],
            ['Telegram Top-Up 100 Stars', 'GLOBAL', 465, 'media/telegram.avif'],
            ['Telegram Top-Up 50 Stars', 'GLOBAL', 305, 'media/telegram.avif'],
            ['Telegram Premium 3 Months', 'GLOBAL', 3003, 'media/telegram.avif'],
            ['Telegram Top-Up 5000 Stars', 'GLOBAL', 13788, 'media/telegram.avif'],
            ['Telegram Top-Up 10000 Stars', 'GLOBAL', 26606, 'media/telegram.avif'],
            ['Telegram Premium 12 Months', 'GLOBAL', 6786, 'media/telegram.avif'],
            ['Telegram Premium 6 Months', 'GLOBAL', 3955, 'media/telegram.avif'],
            ['Telegram Members 50 Members', 'GLOBAL', 955, 'media/telegram.avif'],
            ['Telegram Members 100 Members', 'GLOBAL', 1871, 'media/telegram.avif'],
            ['Telegram Members 250 Members', 'GLOBAL', 3573, 'media/telegram.avif'],
            ['Telegram Members 10000 Members', 'GLOBAL', 20079, 'media/telegram.avif'],
            ['Telegram Members 5000 Members', 'GLOBAL', 16735, 'media/telegram.avif'],
            ['Telegram Members 1000 Members', 'GLOBAL', 10400, 'media/telegram.avif'],
            ['Telegram Members 500 Members', 'GLOBAL', 7017, 'media/telegram.avif'],
        ];

        $this->load->view('templates/navgame');
        $this->load->view('subscriptions/all', ['products' => $products]);
        $this->load->view('templates/footer');
    }

    public function checkout()
    {
        if (!$this->session->userdata('email')) {
            $this->session->set_userdata('login_redirect', current_url().($this->input->server('QUERY_STRING') ? '?'.$this->input->server('QUERY_STRING') : ''));
            $this->session->set_flashdata('purchase_notice', 'Please log in before buying a subscription.');
            redirect('welcome/login');
            return;
        }

        $product = trim((string) $this->input->get('product', true));
        $package = trim((string) $this->input->get('package', true));
        $price = trim((string) $this->input->get('price', true));

        if (!$product || !$package || !$price) {
            redirect(base_url('subscriptions/all'));
        }

        $this->load->view('templates/navgame');
        $this->load->view('subscriptions/checkout', [
            'product' => $product,
            'package' => $package,
            'price' => $price,
        ]);
        $this->load->view('templates/footer');
    }

    public function action()
    {
        $this->load->view('store/action');
    }
    // public function __construct()
    // {
    //     parent::__construct();
    //     $this->load->model('m_games');
    //     $this->load->helper('url');
    // }

    // public function index()
    // {
    //     $data['user'] = $this->m_games->tampil_data()->result();
    //     $this->load->view('templates/navgame');
    //     $this->load->view('games/index', $data);
    //     $this->load->view('templates/footer');
    // }

    // public function fps()
    // {
    //     $data['user'] = $this->m_games->fps()->result();
    //     $this->load->view('templates/navgame');
    //     $this->load->view('games/fps', $data);
    //     $this->load->view('templates/footer');
    // }

    // public function balapan()
    // {
    //     $data['user'] = $this->m_games->balapan()->result();
    //     $this->load->view('templates/navgame');
    //     $this->load->view('games/balapan', $data);
    //     $this->load->view('templates/footer');
    // }

    // public function misteri()
    // {
    //     $data['user'] = $this->m_games->misteri()->result();
    //     $this->load->view('templates/navgame');
    //     $this->load->view('games/misteri', $data);
    //     $this->load->view('templates/footer');
    // }

    // public function edukasi()
    // {
    //     $data['user'] = $this->m_games->edukasi()->result();
    //     $this->load->view('templates/navgame');
    //     $this->load->view('games/edukasi', $data);
    //     $this->load->view('templates/footer');
    // }

    // public function olahraga()
    // {
    //     $data['user'] = $this->m_games->olahraga()->result();
    //     $this->load->view('templates/navgame');
    //     $this->load->view('games/olahraga', $data);
    //     $this->load->view('templates/footer');
    // }

    // public function petualangan()
    // {
    //     $data['user'] = $this->m_games->petualangan()->result();
    //     $this->load->view('templates/navgame');
    //     $this->load->view('games/petualangan', $data);
    //     $this->load->view('templates/footer');
    // }

    // public function arcade()
    // {
    //     $data['user'] = $this->m_games->arcade()->result();
    //     $this->load->view('templates/navgame');
    //     $this->load->view('games/arcade', $data);
    //     $this->load->view('templates/footer');
    // }

    // public function aksi()
    // {
    //     $data['user'] = $this->m_games->aksi()->result();
    //     $this->load->view('templates/navgame');
    //     $this->load->view('games/aksi', $data);
    //     $this->load->view('templates/footer');
    // }

    // public function multiplayer()
    // {
    //     $data['user'] = $this->m_games->multiplayer()->result();
    //     $this->load->view('templates/navgame');
    //     $this->load->view('games/multiplayer', $data);
    //     $this->load->view('templates/footer');
    // }
}
