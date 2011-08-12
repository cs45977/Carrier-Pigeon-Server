<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 *
 * The keys module enables users to perform various CRUD actions 
 * on the Keys Table.
 *
 * @author 		@author Christian Serna - Carrier Pigeon Dev Team
 * @package 	Carrier Pigeon Server
 * @subpackage 	Carrier Pigeon Server Module
 * @category 	Modules
 * @license 	Apache License v2.0
 */
class Keys_m extends MY_Model {

protected $rest_keys_table;
protected $record_time;

public function __construct()
{
    parent::__construct();
    $this->load->config('carrier_pigeon_server/rest.php');
    $this->rest_keys_table = $this->config->item('rest_keys_table');
    $this->record_time = mktime();

}


    /**
     * Get all api keys  and profile data
     *
     * @author Christian Serna - Carrier Pigeon Dev Team
     * @access public
     * @return array
     */
    public function get_all()
    {
        $sql = <<<SQL
        SELECT 
            pigeon_keys.id as keys_id, pigeon_keys.users_id, profiles.first_name, profiles.last_name, users.username, pigeon_keys.key, pigeon_keys.level, pigeon_keys.date_created
        FROM 
            profiles , users, {$this->rest_keys_table} pigeon_keys
        WHERE
            pigeon_keys.users_id = users.id
        AND
            profiles.user_id = users.id
SQL;

       return $this->db->query($sql)->result_array();

    }

    
    /**
     * insert api keys  
     *
     * @author Christian Serna - Carrier Pigeon Dev Team
     * @access public
     * @return 
     */
    public function insert()
    {
        $sql = <<<SQL
        insert into 
            {$this->rest_keys_table}
        (users_id, key, level, ignore_limits, date_created)
            values
        (?,?,?,?)
           
SQL;

       return $this->db->query($sql,array($user_id,$key,$level,$ignore_limits,$this->record_time));

    }

    
    
    /**
	 * check_login
	 *
	 * @return bool
	 * @author Mathew(PyroCMS) modded by Serna for Pigeon
	 **/
	public function check_login($identity, $password)
	{
		if (empty($identity) || empty($password) || !$this->identity_check($identity))
		{
			return FALSE;
		}

		$this->db->select('users_id, password, group_id')
			->where('users_id', $identity);

		if (isset($this->ion_auth->_extra_where))
		{
			$this->db->where($this->ion_auth->_extra_where);
		}

		$query = $this->db->where('active', 1)
					   ->limit(1)
					   ->get($this->tables['users']);

		$result = $query->row();

		if ($query->num_rows() == 1)
		{
			$password = $this->hash_password_db($identity, $password);

			if ($result->password === $password)
			{
				return TRUE;
			}
		}

		return FALSE;
	}

}