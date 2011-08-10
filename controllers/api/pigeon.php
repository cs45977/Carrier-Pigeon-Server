<?php defined('BASEPATH') OR exit('No direct script access allowed'); require APPPATH.'/core/REST_Controller.php';


class pigeon extends REST_Controller {

    private $currentUser;

	public function __construct() {
		parent::__construct();
        
        // Load the required classes
		
        /*$this->load->model('galleries_m');
		$this->load->model('gallery_images_m');
		$this->lang->load('galleries');
		$this->lang->load('gallery_images');
		$this->load->helper('html');*/
        
        
        $this->load->library('REST_Controller.php');
        
	
  	} // end __construct
    
     public function send_message_post(){
         
         die('index Post');
     }

    
     public function index_get(){
        
         
         
     }




}
