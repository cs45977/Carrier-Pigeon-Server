<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * The PyroCMS Admin COntrolloer for 
 * Carrier_Pigeon SERVER
 */


class Admin extends Admin_Controller
{
    public function __construct()
	{
		parent::__construct();
        $this->load->library('carrier_pigeon_server/Datatable');
        $this->load->model('keys_m','keys');
		$this->load->library('form_validation');
        $this->lang->load('carrier_pigeon_server');
	}

    /* $this->load->library('datatable');
     * List All Members
     */
    public function index(){
        
        $usersList = $this->keys->get_all();
        $this->datatable->addData($usersList);
      
        $this->getContactsTable("usersList");
        

        $data['dt_html'] = $this->datatable->getHtmlTable();

      // Load the view
        $data['message'] = "TODO:  Build out Carrier_Pigeon Admin!";
		$this->template
			->title($this->module_details['name'].' fox')
            ->append_metadata(js('data_tables/jquery.dataTables.js','carrier_pigeon_server')) 
            ->append_metadata(js('data_tables/table_headers_all.js','carrier_pigeon_server')) 
            ->append_metadata(css('table.css','carrier_pigeon_server')) 
            ->build('admin/index',$data);
    }
    
    
    
    /*
     * Get the standard Contacts Table Out line
     */
    protected function getContactsTable($tableName)
    {
        
       
        $this->load->library('datatable');
        $this->datatable->table_id = $tableName;
        
       $this->datatable->addColumn('Keys ID', array('width' => '0', 'class'=>'', 'sortable' => false));
       $this->datatable->addColumn('User ID', array('width' => '0', 'class'=>'',  'sortable' => false));
        
        
        $this->datatable->addColumn(lang('carrier_pigeion_server.First_Name'),  array('width' => '15', 'class'=>'', 'sortable' => true));
        $this->datatable->addColumn(lang('carrier_pigeion_server.Last_Name'), array('width' => '15', 'class'=>'', 'sortable' => true));
        $this->datatable->addColumn(lang('carrier_pigeion_server.User_Name'), array('width' => '15', 'class'=>'', 'sortable' => true));
        $this->datatable->addColumn(lang('carrier_pigeion_server.API_Key'), array('width' => '15', 'class'=>'', 'sortable' => true));
        $this->datatable->addColumn(lang('carrier_pigeion_server.Level'), array('width' => '15', 'class'=>'', 'sortable' => true));
        $this->datatable->addColumn(lang('carrier_pigeion_server.Date_Created'), array('width' => '15', 'class'=>'', 'sortable' => true));
        
        
    }
    
    
}

