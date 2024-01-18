<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CI_Controller
{

	/**
	 * Reference to the CI singleton
	 *
	 * @var	object
	 */
	private static $instance;

	/**
	 * CI_Loader
	 *
	 * @var	CI_Loader
	 */
	public $load;

	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		self::$instance = &$this;

		// Assign all the class objects that were instantiated by the
		// bootstrap file (CodeIgniter.php) to local class variables
		// so that CI can run as one big super object.
		foreach (is_loaded() as $var => $class) {
			$this->$var = &load_class($class);
		}

		$this->load = &load_class('Loader', 'core');
		$this->load->initialize();
		log_message('info', 'Controller Class Initialized');
	}

	// --------------------------------------------------------------------

	/**
	 * Get the CI singleton
	 *
	 * @static
	 * @return	object
	 */
	public static function &get_instance()
	{
		return self::$instance;
	}
	public function isValidClient($subdomain = null)
	{
		//load landloard db
		$this->load->database('landlord');

		//default status
		$status = false;

		//query untuk cek tenant
		$tenant = $this->db->query("SELECT a.id FROM tenants a
		JOIN clients b ON a.client_id = b.id WHERE a.sub = '$subdomain'")->num_rows();

		//jika tenant ada
		if ($tenant > 0) {
			//maka status true jika tidak tetap default status
			$status = true;
		}

		//kirim nilai status
		return $status;
	}

	function hasSubdomain($host)
	{
		$hostParts = explode('.', $host);

		if (count($hostParts) > 2) {
			$data = array('status' => true, 'subdomain' => $hostParts[0]);
		} else {
			$data = array('status' => false, 'subdomain' => '');
		}

		return $data;
	}

	public function tenantConfig($subdomain = null)
	{
		$this->load->database('landlord');
		$tenant = $this->db->query("SELECT c.host, c.user, c.pass, c.port, a.db, b.status
									FROM tenants a
									JOIN clients b ON a.client_id = b.id
									JOIN servers c ON a.server_id = c.id
									WHERE a.sub = '$subdomain'")->row_array();

		return json_encode($tenant);
	}

	public function sysLang($val = null)
	{
		$lang = 'bahasa';

		if (isset($val)) {
			$lang = $val;
		}

		$this->lang->load('common', $lang);
	}

	public function createUrl(String $subdomain = null)
	{
		$domain = base_url();

		// Insert the string after "http://"
		$url = str_replace('http://', 'http://' . $subdomain . '.', $domain);

		// Output the modified string
		return $url;
	}

	public function redirectTo(String $url = null, String $uri = null)
	{
		redirect($url . $uri);
	}
}
