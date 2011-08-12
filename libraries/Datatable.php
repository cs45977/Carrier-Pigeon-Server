<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Generates HTML and JS needed for DataTables
 *
 * 
 */
class Datatable {

    /**
     * Instance of CodeIgniter
     * @var CI_Controller
     */
    private $ci_instance;

    /**
     * HTML DOM ID of the table
     * @var string
     */
    public $table_id = "data_table";

    /**
     * CSS class of the HTML table
     * @var string
     */
    public $table_class = "display";


    /**
     * Cell Spacing of the table
     * @var int
     */
    public $table_cellspacing = 0;


    /**
     * Cell Padding of the table
     * @var int
     */
    public $table_cellpadding = 4;

    /**
     *  border of the table
     * @var int
     */
    public $table_border = 1;

    
    /**
     * Multidimensional array containing the table columns
     * @var array
     */
    public $header = array();


     /**
     * Multidimensional array containing the dats
     * @var array
     */
    public $data = array();


    public $tableBodyHTMl;


    /**
     * Multidimensional array containing the table columns
     * @var array
     */
    public $footer = array();

    public function  __construct() {
        $this->ci_instance =& get_instance();
        log_message('DEBUG', __CLASS__." Class Initialized");
    }

    /**
     * Turns a database result into a DataTable compatible JSON string
     * @param array $data
     * @return string
     */
    public static function format_data($data = array()) {

        if(is_array($data) && count($data) >= 1) {
            foreach($data as $key => $value) {
                if(is_array($value)) {
                    $data[$key] = array_values($value);
                }
            }
        }

        $return = new stdClass();
        $return->aaData = $data;

        return json_encode($return);
    }

    /**
     * Add a new column to the DataTable
     * @param string $title
     * @param array $options
     * @return void
     */
    public function addColumn($title = "", $options = array()) {
        $this->header[$title] = $options;
        return;
    }



    /**
     * Add Data to the DataTable

     * @param array $data
     * @return void
     */
    public function addData($data) {
        $this->data = $data;
        return;
    }

   
        /**
     * Load a view, pass it data and then return it as a string
     * @param string $template
     * @param mixed $data
     * @return string
     */
    private function parseTempate($template, $data) {
        ob_start();
        $this->ci_instance->load->view("data_table/".$template, $data);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    /**
     * Get all of the PUBLIC member varibles of an object
     * @param Datatable $obj
     * @return array
     */
    private function get_object_vars($obj) {
        $ref = new ReflectionObject($obj);
        $pros = $ref->getProperties(ReflectionProperty::IS_PUBLIC);
        $result = array();
        if(count($pros) >= 1) {
            foreach ($pros as $pro) {
                false && $pro = new ReflectionProperty();
                $result[$pro->getName()] = $pro->getValue($obj);
            }
        }
        return $result;
    }

    /**
     * Copies all of the header data of the table to the footer
     * @return void
     */
    public function setFooter() {
        $this->footer = $this->header;
        return;
    }

    /**
     * Returns the generated HTML for the DataTable
     * @return string
     */
    public function getHtmlTable() {
        //log_message('DATA', serialize($this->get_object_vars($this)));
        return $this->parseTempate('table', $this->get_object_vars($this));
    }

    
    public function getContactsTable(){
        $this->addColumn($this->ci_instance->lang->line('Form_Label_Username'),  array('width' => '15', 'class'=>'', 'sortable' => true));
        $this->addColumn($this->ci_instance->lang->line('Form_Label_Full_Name'), array('width' => '15', 'class'=>'', 'sortable' => true));
        $this->addColumn($this->ci_instance->lang->line('Form_Label_Professional_Designation'), array('width' => '15', 'class'=>'', 'sortable' => true));
        $this->addColumn($this->ci_instance->lang->line('Form_Label_Title'), array('width' => '15', 'class'=>'', 'sortable' => true));
        $this->addColumn($this->ci_instance->lang->line('Form_Label_Choose_Employer'), array('width' => '15', 'class'=>'', 'sortable' => true));
        $this->addColumn($this->ci_instance->lang->line('Form_Label_Title'), array('width' => '15', 'class'=>'', 'sortable' => true));
        /***
         * Not visiable but need to set the titles anyway
         */
        $this->addColumn('ID', array('width' => '0', 'class'=>'', 'sortable' => true));
        $this->addColumn('Group ID', array('width' => '0', 'class'=>'', 'sortable' => true));
        $this->addColumn($this->ci_instance->lang->line('Form_Label_Group'), array('width' => '0','class'=>'', 'sortable' => true));


    }
    




}
