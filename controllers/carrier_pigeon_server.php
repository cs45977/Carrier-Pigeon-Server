<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Carrier_pigeon_server extends Public_Controller
{
	
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
    }
    
    
    /*
     * By default we only need an admin and 
     * an API if a user is not an admin they really should
     * not see any results here
     */
    public function index(){
        if($this->ion_auth->is_admin()){
            redirect('/admin/carrier_pigeon_server/');
            die();
        }
        redirect('/open-source/carrier-pigeon/');
           die();
    }
    
}