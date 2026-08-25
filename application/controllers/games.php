<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Games extends CI_Controller
{
    private $database_available = false;

    public function __construct()
    {
        parent::__construct();
        try {
            $this->load->database();
            $this->database_available = true;
        } catch (Throwable $exception) {
            $this->database_available = false;
        }
        $this->load->model('m_games');
        $this->load->helper('url');
    }

    private function gamesFor($method)
    {
        if ($this->database_available) {
            return $this->m_games->{$method}()->result();
        }

        return [
            (object) [
                'gambar_game' => 'netflix.jpg',
                'nama_game' => 'Netflix',
                'deskripsi_game' => 'Stream your favorite movies and shows anytime.',
                'link_game' => 'netflix.html',
            ],
            (object) [
                'gambar_game' => 'spotify.jpg',
                'nama_game' => 'Spotify',
                'deskripsi_game' => 'Enjoy your favorite music and podcasts anywhere.',
                'link_game' => 'spotify.html',
            ],
            (object) [
                'gambar_game' => 'youtube.jpg',
                'nama_game' => 'YouTube Premium',
                'deskripsi_game' => 'Watch videos without ads and enjoy premium features.',
                'link_game' => 'youtube.html',
            ],
            (object) [
                'gambar_game' => 'discord.jpg',
                'nama_game' => 'Discord',
                'deskripsi_game' => 'Connect, chat, and enjoy premium Discord features.',
                'link_game' => 'discord.html',
            ],
        ];
    }

    public function index()
    {
        $data['user'] = $this->gamesFor('tampil_data');
        $this->load->view('templates/navgame');
        $this->load->view('games/index', $data);
        $this->load->view('templates/footer');

    }

    public function fps()
    {
        $data['user'] = $this->gamesFor('fps');
        $this->load->view('templates/navgame');
        $this->load->view('games/fps', $data);
        $this->load->view('templates/footer');
    }

    public function balapan()
    {
        $data['user'] = $this->gamesFor('balapan');
        $this->load->view('templates/navgame');
        $this->load->view('games/balapan', $data);
        $this->load->view('templates/footer');
    }

    public function misteri()
    {
        $data['user'] = $this->gamesFor('misteri');
        $this->load->view('templates/navgame');
        $this->load->view('games/misteri', $data);
        $this->load->view('templates/footer');
    }

    public function edukasi()
    {
        $data['user'] = $this->gamesFor('edukasi');
        $this->load->view('templates/navgame');
        $this->load->view('games/edukasi', $data);
        $this->load->view('templates/footer');
    }

    public function olahraga()
    {
        $data['user'] = $this->gamesFor('olahraga');
        $this->load->view('templates/navgame');
        $this->load->view('games/olahraga', $data);
        $this->load->view('templates/footer');
    }

    public function petualangan()
    {
        $data['user'] = $this->gamesFor('petualangan');
        $this->load->view('templates/navgame');
        $this->load->view('games/petualangan', $data);
        $this->load->view('templates/footer');
    }

    public function arcade()
    {
        $data['user'] = $this->gamesFor('arcade');
        $this->load->view('templates/navgame');
        $this->load->view('games/arcade', $data);
        $this->load->view('templates/footer');
    }

    public function aksi()
    {
        $data['user'] = $this->gamesFor('aksi');
        $this->load->view('templates/navgame');
        $this->load->view('games/aksi', $data);
        $this->load->view('templates/footer');
    }

    public function multiplayer()
    {
        $data['user'] = $this->gamesFor('multiplayer');
        $this->load->view('templates/navgame');
        $this->load->view('games/multiplayer', $data);
        $this->load->view('templates/footer');
    }

    public function action()
    {
        $data['user'] = $this->m_games->action()->result();
        $this->load->view('user/action', $data);

    }

}