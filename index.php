<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2019, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2019, British Columbia Institute of Technology (https://bcit.ca/)
 * @license	https://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */

/*
 *---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 *---------------------------------------------------------------
 *
 * You can load different configurations depending on your
 * current environment. Setting the environment also influences
 * things like logging and error reporting.
 *
 * This can be set to anything, but default usage is:
 *
 *     development
 *     testing
 *     production
 *
 * NOTE: If you change these, also change the error_reporting() code below
 */
	define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'development');

	if (function_exists('mysqli_report'))
	{
		mysqli_report(MYSQLI_REPORT_OFF);
	}

	set_error_handler(static function ($severity, $message, $file) {
		if ($severity === E_WARNING
			&& strpos($message, 'mysqli::real_connect()') !== false
			&& basename($file) === 'mysqli_driver.php')
		{
			return true;
		}

		return false;
	});

/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but testing and live will hide them.
 */
switch (ENVIRONMENT)
{
	case 'development':
		error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
		ini_set('display_errors', 1);
	break;

	case 'testing':
	case 'production':
		ini_set('display_errors', 0);
		if (version_compare(PHP_VERSION, '5.3', '>='))
		{
			error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
		}
		else
		{
			error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
		}
	break;

	default:
		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
		echo 'The application environment is not set correctly.';
		exit(1); // EXIT_ERROR
}

ob_start(static function ($output) {
	return strtr($output, array(
		'Selamat datang!' => 'Welcome!',
		'Selamat Datang,' => 'Welcome,',
		'Selamat datang publisher.' => 'Welcome, publisher.',
		'Selamat Pembelian Anda Telah Berhasil!' => 'Your purchase was successful!',
		'Selamat tinggal, Sampai jumpa lagi!' => 'Goodbye, see you again!',
		'Selamat tinggal!' => 'Goodbye!',
		'Beranda' => 'Home',
		'Halaman Utama' => 'Home Page',
		'Toko' => 'Store',
		'Kategori' => 'Categories',
		'Paling Populer' => 'Most Popular',
		'Game yang tersedia.' => 'Available Games.',
		'Login event' => 'Login Event',
		'Masuk sebagai admin' => 'Sign in as admin',
		'Masuk sebagai publisher' => 'Sign in as publisher',
		'Mistery' => 'Mystery',
		'Pilih disini' => 'Choose here',
		'Masukan username mu disini ..' => 'Enter your username here ..',
		'Masukan password mu disini ..' => 'Enter your password here ..',
		'Masukan email mu disini ..' => 'Enter your email here ..',
		'Nama lengkap.' => 'Full name.',
		'Saya setuju dan ingin melanjutkan.' => 'I agree and want to continue.',
		'Dengan daftar anda menyetujui' => 'By registering, you agree to our',
		'Dengan login anda menyetujui' => 'By logging in, you agree to our',
		'privasi dan persyaratan ketentuan hukum kami' => 'privacy policy and legal terms',
		'baca selengkapnya' => 'read more',
		'disini.' => 'here.',
		'Ingat saya.' => 'Remember me.',
		'Daftar Sekarang!' => 'Register Now!',
		'Login Sekarang!' => 'Login Now!',
		'Mainkan Game Gratis!' => 'Play Free Games!',
		'Mainkan banyak game gratis disini. Gratis, seru dan menarik.' => 'Play many free games here. Free, fun, and exciting.',
		'Nikmati kemudahan bermain game dimana saja dengan StreamNest.' => 'Enjoy the convenience of playing games anywhere with StreamNest.',
		'Mainkan Game Populer dan' => 'Play Popular and',
		'Menarik!' => 'Exciting Games!',
		'Mainkan!' => 'Play!',
		'Mainkan Game' => 'Play Game',
		'Game tentang ninja laki lakis' => 'A game about a ninja boy',
		'Game tentang burung' => 'A game about birds',
		'Permainan Sport Terseru dan populer telah hadir!' => 'The most exciting sports game is here!',
		'Bermain dan beri makan omnom! si alien lucu .' => 'Play and feed Om Nom, the cute alien.',
		'Permainan Balapan Menegangkan ! seru dan menarik!' => 'An exciting and thrilling racing game!',
		'Mainkan game billiar terpopuler di sini ! mainkan gratis!' => 'Play the most popular billiards game here, for free!',
		'Game perang terseru dan terkeren! yuk main sekarang dude!' => 'The coolest and most exciting war game. Play now!',
		'Tendang MONYET yang lucu dan pikakeheulen jiga kamu :)' => 'Kick the cute monkey and have fun :)',
		'Mystery box oriented game yang bikin kamu pusing dan menantang!' => 'A challenging mystery box game that will make you think!',
		'Mainkan olahraga basket dengan mudah disini!' => 'Play basketball easily here!',
		'Rasakan sensasi parkour yang seru dan menegangkan disini!' => 'Experience exciting and thrilling parkour here!',
		'Mainkan game thunderbirds yang seru,menarik dan gege' => 'Play the exciting and entertaining Thunderbirds game.',
		'Mainkan game golf bersama temanmu sekarang!' => 'Play golf with your friends now!',
		'Tabrakan dan ledakan mobil sekitarmu hahahahhaha!' => 'Crash and blow up the cars around you!',
		'Kalahkan para zombie dan bertahan hidup agar kamu selamat!' => 'Defeat the zombies and survive!',
		'Kalahkan tank lain , kuasai mereka dan menangkan pertandingan!' => 'Defeat the other tanks and win the match!',
		'Kalahkan musuh dan bertahan hidup untuk menang!' => 'Defeat your enemies and survive to win!',
		'Loncat loncat fucekkkk' => 'Jump your way through the challenge!',
		'Mainkan simulasi operasi gigi sekarang!' => 'Play the dental surgery simulation now!',
		'Mainkan game keren puzzle dan mengasah otak!' => 'Play a cool puzzle game that exercises your brain!',
		'Mainkan game bersama temanmu! selesaikan level bersama sama!' => 'Play with your friends and complete the levels together!',
		'Mau top-up? cek kategori dibawah ini!' => 'Want a top-up? Check the categories below!',
		'Tertarik bergabung bersama kami?' => 'Interested in joining us?',
		'Punya game? yuk promosikan dan publish sekarang!' => 'Have a game? Promote and publish it now!',
		'Tambah data game' => 'Add game data',
		'Tambah Data Store' => 'Add store data',
		'Tambah Data Free' => 'Add free game data',
		'Tambah Data Publisher' => 'Add publisher data',
		'Tambah Data User' => 'Add user data',
		'Simpan' => 'Save',
		'Ubah' => 'Edit',
		'Hapus' => 'Delete',
		'Detail' => 'Details',
		'Cari' => 'Search',
		'Tanggal' => 'Date',
		'Aksi' => 'Actions',
		'Harga' => 'Price',
		'Deskripsi' => 'Description',
		'Nama Game' => 'Game Name',
		'Jenis Game' => 'Game Genre',
		'Penerbit' => 'Publisher',
		'Pengguna' => 'User',
		'Upload' => 'Upload',
		'Keluar dari admin' => 'Log out of admin',
		'Keluar dari publisher' => 'Log out of publisher',
		'Riwayat Pembelian.' => 'Purchase History',
		'Kamu bisa memainkan game action!' => 'You can play action games!',
		'beli sekarang gamenya!' => 'buy the game now!',
		'mainkan game action!' => 'play action games!',
		'Verifikasi Akun' => 'Account Verification',
		'Verifikasi akun StreamNest' => 'Verify your StreamNest account',
		'Klik disini untuk verifikasi.' => 'Click here to verify.',
		'Tentang StreamNest.' => 'About StreamNest.',
		'Tentang Kami' => 'About Us',
		'StreamNest Adalah portal game pertama di indonesia' => 'StreamNest is Indonesia\'s first game portal',
		'yang menyediakan fasilitas untuk para' => 'that provides services for',
		'program ataupun games yang dibuat oleh anak bangsa!. mari bergabung dengan kami' => 'programs and games made by Indonesian developers. Join us',
		'untuk mendukung karya karya yang dibuat oleh millenial indonesia! jadikan hobby sebagai titik maju bangsa mulai dari sekarang!' => 'to support the work of Indonesian creators. Turn your hobby into progress for the nation!',
		'Mari bersama mendukung produk indonesia!' => 'Let us support Indonesian products together!',
		'senantiasa mendukung program ataupun games yang dibuat oleh anak bangsa!' => 'always support programs and games made by Indonesian creators!',
		'mari bergabung dengan kami' => 'join us',
		'dengan kami' => 'with us',
		'hukum kami' => 'legal terms',
		'untuk mendukung karya karya' => 'to support the work',
		'yang dibuat oleh millenial indonesia!' => 'created by Indonesian creators!',
		'jadikan hobby sebagai titik maju' => 'turn your hobby into progress',
		'bangsa mulai dari sekarang!' => 'the nation starting today!',
		'Mainkan game bergenre Aksi dan jadilah yang terkuat!' => 'Play action games and become the strongest!',
		'Mainkan game bergenre Arcade atau' => 'Play arcade games or',
		'Mainkan game bergenre Balapan dan jadilah pemenang!' => 'Play racing games and become a winner!',
		'Mainkan game bergenre Edukasi dan jadilah Pintar!' => 'Play educational games and become smarter!',
		'Mainkan game bergenre FPS atau' => 'Play FPS games or',
		'Mainkan game bergenre Misteri dan jadilah detektif!' => 'Play mystery games and become a detective!',
		'Mainkan game bergenre Multiplayer bersama temanmu!' => 'Play multiplayer games with your friends!',
		'Mainkan game bergenre Olahraga dan jadilah Atlit!' => 'Play sports games and become an athlete!',
		'Mainkan game bergenre Petualangan dan jadilah Explorer!' => 'Play adventure games and become an explorer!',
		'mainkan di page' => 'play on this page',
		'mainkan di' => 'play on',
		'bermain game dimana saja' => 'playing games anywhere',
		'game buatan developer indonesia' => 'games made by Indonesian developers',
		'Game' => 'Game',
		'Klik Disini Untuk Kode Game' => 'Click Here for the Game Code',
		'Data Game Telah Dihapus!' => 'Game data deleted!',
		'Data Publisher Telah Dihapus!' => 'Publisher data deleted!',
		'Data User Telah Dihapus!' => 'User data deleted!',
		'Data Pembayaran Telah Dihapus!' => 'Payment data deleted!',
		'Data Game Telah Dirubah!' => 'Game data updated!',
		'Data Publisher Telah Dirubah!' => 'Publisher data updated!',
		'Selamat data telah dihapus!' => 'The data was deleted successfully!',
		'Selamat data telah Dirubah!' => 'The data was updated successfully!',
		'Selamat data telah dirubah!' => 'The data was updated successfully!',
		'Selamat data telah ditambah!' => 'The data was added successfully!',
		'Selamat data berubah!' => 'The data was updated successfully!',
		'Keluarr' => 'Log out'
	));
});

/*
 *---------------------------------------------------------------
 * SYSTEM DIRECTORY NAME
 *---------------------------------------------------------------
 *
 * This variable must contain the name of your "system" directory.
 * Set the path if it is not in the same directory as this file.
 */
	$system_path = __DIR__.DIRECTORY_SEPARATOR.'system';

/*
 *---------------------------------------------------------------
 * APPLICATION DIRECTORY NAME
 *---------------------------------------------------------------
 *
 * If you want this front controller to use a different "application"
 * directory than the default one you can set its name here. The directory
 * can also be renamed or relocated anywhere on your server. If you do,
 * use an absolute (full) server path.
 * For more info please see the user guide:
 *
 * https://codeigniter.com/user_guide/general/managing_apps.html
 *
 * NO TRAILING SLASH!
 */
	$application_folder = __DIR__.DIRECTORY_SEPARATOR.'application';

/*
 *---------------------------------------------------------------
 * VIEW DIRECTORY NAME
 *---------------------------------------------------------------
 *
 * If you want to move the view directory out of the application
 * directory, set the path to it here. The directory can be renamed
 * and relocated anywhere on your server. If blank, it will default
 * to the standard location inside your application directory.
 * If you do move this, use an absolute (full) server path.
 *
 * NO TRAILING SLASH!
 */
	$view_folder = '';


/*
 * --------------------------------------------------------------------
 * DEFAULT CONTROLLER
 * --------------------------------------------------------------------
 *
 * Normally you will set your default controller in the routes.php file.
 * You can, however, force a custom routing by hard-coding a
 * specific controller class/function here. For most applications, you
 * WILL NOT set your routing here, but it's an option for those
 * special instances where you might want to override the standard
 * routing in a specific front controller that shares a common CI installation.
 *
 * IMPORTANT: If you set the routing here, NO OTHER controller will be
 * callable. In essence, this preference limits your application to ONE
 * specific controller. Leave the function name blank if you need
 * to call functions dynamically via the URI.
 *
 * Un-comment the $routing array below to use this feature
 */
	// The directory name, relative to the "controllers" directory.  Leave blank
	// if your controller is not in a sub-directory within the "controllers" one
	// $routing['directory'] = '';

	// The controller class file name.  Example:  mycontroller
	// $routing['controller'] = '';

	// The controller function you wish to be called.
	// $routing['function']	= '';


/*
 * -------------------------------------------------------------------
 *  CUSTOM CONFIG VALUES
 * -------------------------------------------------------------------
 *
 * The $assign_to_config array below will be passed dynamically to the
 * config class when initialized. This allows you to set custom config
 * items or override any default config values found in the config.php file.
 * This can be handy as it permits you to share one application between
 * multiple front controller files, with each file containing different
 * config values.
 *
 * Un-comment the $assign_to_config array below to use this feature
 */
	// $assign_to_config['name_of_config_item'] = 'value of config item';



// --------------------------------------------------------------------
// END OF USER CONFIGURABLE SETTINGS.  DO NOT EDIT BELOW THIS LINE
// --------------------------------------------------------------------

/*
 * ---------------------------------------------------------------
 *  Resolve the system path for increased reliability
 * ---------------------------------------------------------------
 */

	// Set the current directory correctly for CLI requests
	if (defined('STDIN'))
	{
		chdir(dirname(__FILE__));
	}

	if (($_temp = realpath($system_path)) !== FALSE)
	{
		$system_path = $_temp.DIRECTORY_SEPARATOR;
	}
	else
	{
		// Ensure there's a trailing slash
		$system_path = strtr(
			rtrim($system_path, '/\\'),
			'/\\',
			DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
		).DIRECTORY_SEPARATOR;
	}

	// Is the system path correct?
	if ( ! is_dir($system_path))
	{
		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
		echo 'Your system folder path does not appear to be set correctly. Please open the following file and correct this: '.pathinfo(__FILE__, PATHINFO_BASENAME);
		exit(3); // EXIT_CONFIG
	}

/*
 * -------------------------------------------------------------------
 *  Now that we know the path, set the main path constants
 * -------------------------------------------------------------------
 */
	// The name of THIS file
	define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

	// Path to the system directory
	define('BASEPATH', $system_path);

	// Path to the front controller (this file) directory
	define('FCPATH', dirname(__FILE__).DIRECTORY_SEPARATOR);

	// Name of the "system" directory
	define('SYSDIR', basename(BASEPATH));

	// The path to the "application" directory
	if (is_dir($application_folder))
	{
		if (($_temp = realpath($application_folder)) !== FALSE)
		{
			$application_folder = $_temp;
		}
		else
		{
			$application_folder = strtr(
				rtrim($application_folder, '/\\'),
				'/\\',
				DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
			);
		}
	}
	elseif (is_dir(BASEPATH.$application_folder.DIRECTORY_SEPARATOR))
	{
		$application_folder = BASEPATH.strtr(
			trim($application_folder, '/\\'),
			'/\\',
			DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
		);
	}
	else
	{
		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
		echo 'Your application folder path does not appear to be set correctly. Please open the following file and correct this: '.SELF;
		exit(3); // EXIT_CONFIG
	}

	define('APPPATH', $application_folder.DIRECTORY_SEPARATOR);

	// The path to the "views" directory
	if ( ! isset($view_folder[0]) && is_dir(APPPATH.'views'.DIRECTORY_SEPARATOR))
	{
		$view_folder = APPPATH.'views';
	}
	elseif (is_dir($view_folder))
	{
		if (($_temp = realpath($view_folder)) !== FALSE)
		{
			$view_folder = $_temp;
		}
		else
		{
			$view_folder = strtr(
				rtrim($view_folder, '/\\'),
				'/\\',
				DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
			);
		}
	}
	elseif (is_dir(APPPATH.$view_folder.DIRECTORY_SEPARATOR))
	{
		$view_folder = APPPATH.strtr(
			trim($view_folder, '/\\'),
			'/\\',
			DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
		);
	}
	else
	{
		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
		echo 'Your view folder path does not appear to be set correctly. Please open the following file and correct this: '.SELF;
		exit(3); // EXIT_CONFIG
	}

	define('VIEWPATH', $view_folder.DIRECTORY_SEPARATOR);

/*
 * --------------------------------------------------------------------
 * LOAD THE BOOTSTRAP FILE
 * --------------------------------------------------------------------
 *
 * And away we go...
 */
require_once BASEPATH.'core/CodeIgniter.php';
