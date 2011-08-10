<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Carrier_Pigeon_Server extends Module {

	public $version = '0.01 Alpha';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Carrier Pigeon Server'
				
			),
			'description' => array(
				
				'en' => 'The Carrier Pigeon Server module is a powerful module that Contains the API to handle Carrier Pigeon Requests.',
				
			),
			'frontend' => TRUE,
			'backend' => TRUE,
            'menu' => 'utilities'
		);
	}

    
	public function install(){
        
        $this->load->config('carrier_pigeon_server/rest.php');
        
        $this->dbforge->drop_table($this->config->item('rest_keys_table'));
        $this->dbforge->drop_table($this->config->item('rest_limits_table'));
        $this->dbforge->drop_table($this->config->item('rest_logs_table'));
        $createTS= mktime();
        $install_sql1 = <<<SQL1
        
            CREATE TABLE `{$this->config->item('rest_keys_table')}` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `users_id` int(11) NOT NULL,
              `key` varchar(40) NOT NULL,
              `level` int(2) NOT NULL,
              `ignore_limits` tinyint(1) NOT NULL DEFAULT '0',
              `date_created` int(11) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SQL1;
        $install_sql2 = <<<SQL2
           

            CREATE TABLE `{$this->config->item('rest_limits_table')}` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `uri` varchar(255) NOT NULL,
              `count` int(10) NOT NULL,
              `hour_started` int(11) NOT NULL,
              `api_key` varchar(40) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SQL2;
        
$install_sql3 = <<<SQL3
            
            CREATE TABLE `{$this->config->item('rest_logs_table')}` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `uri` varchar(255) NOT NULL,
              `method` varchar(6) NOT NULL,
              `params` text NOT NULL,
              `api_key` varchar(40) NOT NULL,
              `ip_address` varchar(15) NOT NULL,
              `time` int(11) NOT NULL,
              `authorized` tinyint(1) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SQL3;
            
$install_sql4 = <<<SQL4
            
            INSERT INTO
                {$this->config->item('rest_keys_table')}
            (`users_id`, `key`, `level`,`ignore_limits`,`date_created`)
                values
            ({$this->user->id},'{$this->config->item('admin_API_Key')}',1,0,{$createTS});
SQL4;
        
        if ( ($this->db->query($install_sql1))&& ( $this->db->query($install_sql2))&&( $this->db->query($install_sql3)) &&( $this->db->query($install_sql4)))
		{
			return true;
		}else {
            return false;
        }
        
    }
    
     public function uninstall(){
         $this->load->config('carrier_pigeon_server/rest.php');
        
        if(
            ($this->dbforge->drop_table($this->config->item('rest_keys_table')))  &&
            ($this->dbforge->drop_table($this->config->item('rest_limits_table'))) &&
            ($this->dbforge->drop_table($this->config->item('rest_logs_table')))     
          ){
			return true;
		}else {
            return false;
        }

        
    }
    
     public function upgrade($old_version){
         $this->load->config('carrier_pigeon_server/rest.php');
        return true;
    }
    

	public function help()
	{
		// Return a string containing help info
		// You could include a file and return it here.
		return "<h4>Overview</h4>
		<p>The Carrier Pigeon Server module is a powerful module that lets users  publish communicates across multipe channels with one transaction.</p>
		";
	}
}
/* End of file details.php */
