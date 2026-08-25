<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Topup extends CI_Controller
{
    private $products = [
        'mobile_legends' => 'Mobile Legends',
        'free_fire' => 'Free Fire',
        'pubg' => 'PUBG',
    ];

    private $packages = [
        'Mobile Legends' => [
            ['86 Diamonds', '78 + 8 Bonus', 'Rs 230'],
            ['172 Diamonds', '156 + 16 Bonus', 'Rs 460'],
            ['257 Diamonds', '234 + 23 Bonus', 'Rs 680'],
            ['706 Diamonds', '625 + 81 Bonus', 'Rs 1820'],
            ['2195 Diamonds', '1860 + 335 Bonus', 'Rs 5465'],
            ['3688 Diamonds', '3099 + 589 Bonus', 'Rs 9100'],
            ['Weekly Diamond Pass', '', 'Rs 290'],
        ],
        'Free Fire' => [
            ['25 Diamonds', '', 'रु 35.00'],
            ['50 Diamonds', '', 'रु 60.00'],
            ['115 Diamonds', '', 'रु 105.00'],
            ['240 Diamonds', '', 'रु 210.00'],
            ['355 Diamonds', '', 'रु 315.00'],
            ['480 Diamonds', '', 'रु 420.00'],
            ['610 Diamonds', '', 'रु 520.00'],
            ['850 Diamonds', '', 'रु 730.00'],
            ['1090 Diamonds', '', 'रु 920.00'],
            ['1240 Diamonds', '', 'रु 1,050.00'],
            ['2530 Diamonds', '', 'रु 2,080.00'],
            ['5060 Diamonds', '', 'रु 4,160.00'],
            ['1x Weekly Lite', '', 'रु 75.00'],
            ['1x Weekly Membership', '', 'रु 210.00'],
            ['1x Monthly Membership', '', 'रु 1,000.00'],
            ['1x Weekly + Weekly Lite', '', 'रु 285.00'],
            ['2x Weekly Membership', '', 'रु 420.00'],
            ['3x Weekly Membership', '', 'रु 630.00'],
            ['4x Weekly Membership', '', 'रु 840.00'],
            ['5x Weekly Membership', '', 'रु 1,040.00'],
            ['1x Monthly + Weekly Membership', '', 'रु 1,200.00'],
            ['1x Monthly + Weekly + Weekly Lite', '', 'रु 1,280.00'],
            ['All in one up to 30 Level', '', 'रु 620.00'],
            ['Level 6 Package', '', 'रु 60.00'],
            ['Level 10 Package', '', 'रु 110.00'],
            ['Level 15 Package', '', 'रु 110.00'],
            ['Level 20 Package', '', 'रु 110.00'],
            ['Level 25 Package', '', 'रु 110.00'],
            ['Level 30 Package', '', 'रु 150.00'],
        ],
        'PUBG' => [
            ['60 UC', '', 'Rs 180'],
            ['325 UC', '', 'Rs 750'],
            ['660 UC', '', 'Rs 1,500'],
            ['1800 UC', '', 'Rs 3,700'],
            ['3850 UC', '', 'Rs 7,400'],
            ['8100 UC', '', 'Rs 14,700'],
            ['16200 UC', '', 'Rs 29,000'],
            ['Prime Pass', '', 'Rs 205'],
            ['Prime Plus', '', 'Rs 2,050'],
            ['Prime Combo', '', 'Rs 2,150'],
        ],
    ];

    public function index($product)
    {
        if (!isset($this->products[$product])) {
            show_404();
        }

        $this->load->view('templates/nav');
        $product_name = $this->products[$product];
        $this->load->view('topup/index', [
            'product' => $product_name,
            'packages' => $this->packages[$product_name] ?? [],
        ]);
        $this->load->view('templates/footer');
    }
}