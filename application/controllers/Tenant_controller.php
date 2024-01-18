<?php
class Tenant_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $host = $_SERVER['HTTP_HOST'];

        $checkSubdomain = $this->hasSubdomain($host);
        $checkSubdomain = json_encode($checkSubdomain);

        $checkSubdomain = json_decode($checkSubdomain, true);

        if (!$checkSubdomain['status']) {
            redirect('welcome');
            exit;
        }

        $subdomain = $checkSubdomain['subdomain'];

        $this->baseUrl = $this->createUrl($subdomain);

        //cek subdomain
        if (!$this->isValidClient($subdomain)) {
            //jika subdomain tidak ada/tidak terdaftar
            show_404();
            exit;
        }

        //ambil data koneksi tenant
        $tenant = $this->tenantConfig($subdomain);
        $tenant = json_decode($tenant, true);

        //tutup koneksi landlord
        $this->db->close();

        if ($tenant['status'] == 0) {
            echo 'Aplikasi Anda Tidak Aktif';
            exit;
        }

        //buat koneksi baru untuk akses db tenant
        $db_config = array(
            'dsn'      => '',
            'hostname' => $tenant['host'] . ':' . $tenant['port'],
            'username' => $tenant['user'],
            'password' => $tenant['pass'],
            'database' => $tenant['db'],
            'dbdriver' => 'mysqli'
        );

        //load database tenant
        $this->load->database($db_config);

        //load models
        $this->load->model(array('setting_model', 'user_model'));

        $getLang    = $this->setting_model->get(array('name' => 'SETTING_LANGUAGE'));
        $val        = $getLang->value;

        $this->sysLang($val);
    }

    public function index()
    {
        $data['users'] = $this->user_model->get();

        $this->load->view('users/view', $data);
    }
}
