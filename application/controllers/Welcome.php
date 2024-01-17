<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$host = $_SERVER['HTTP_HOST'];

		$checkSubdomain = $this->hasSubdomain($host);
		$checkSubdomain = json_encode($checkSubdomain);

		$checkSubdomain = json_decode($checkSubdomain, true);

		if ($checkSubdomain['status']) {
			redirect('manage/tenant');
			exit;
		}

		$this->load->model('setting_model');

		$this->load->database('landlord');

		$getLang = $this->setting_model->get(array('name' => 'SETTING_LANGUAGE'));
		$val = $getLang->value;

		$this->sysLang($val);
	}

	public function index()
	{
		$this->load->view('welcome_message');
	}
}
