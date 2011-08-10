<?php defined('BASEPATH') OR exit('No direct script access allowed');

 /*
  * autoload cant work here :(
  * 
  * 
  */

/*
 * ---------------------------------------------------------------
 *  Resolve the addons path for increased reliability
 * ---------------------------------------------------------------
 */
	if (function_exists('realpath') AND @realpath($addon_folder) !== FALSE)
	{
		$addon_folder = realpath($addon_folder).'/';
	}
	
	// ensure there's a trailing slash
	$addon_folder = rtrim($addon_folder, '/').'/';

	// Is the sytsem path correct?
	if ( ! is_dir($addon_folder))
	{
		exit("Your Addons folder path does not appear to be set correctly. Please open the following file and correct this: ".pathinfo(__FILE__, PATHINFO_BASENAME));
	}
    define('ADDONPATHMOD', $addon_folder.'/');
    
    require_once ADDONPATHMOD.'modules/carrier_pigeon_server/libraries/REST_Controller.php';
    require_once ADDONPATHMOD.'modules/carrier_pigeon_server/config/rest.php';


/**
 * Keys Controller
 *
 * This is a basic Key Management REST controller to make and delete keys.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Phil Sturgeon
 * @link		http://philsturgeon.co.uk/code/
*/

// This can be removed if you use __autoload() in config.php


class Key extends REST_Controller
{
	protected $methods = array(
		'index_put' => array('level' => 10, 'limit' => 10),
		'index_delete' => array('level' => 10),
		'level_post' => array('level' => 10),
		'regenerate_post' => array('level' => 10),
	);
    
    protected $CI;
    public function __construct() {
		parent::__construct();
        $this->load->model('keys_m','keys');
    }

    
    
    
    
    
	/**
	 * Key Add Key
	 * @access	public
	 * @return	void
	 */
	public function add_post()
    {
        if( (strlen($this->post('username'))>1) &&(strlen($this->post('password')))>1)
        {
            // Build a new key
            $key = self::_generate_key();
            $userName = $this->post('username');
            $password = $this->post('password');
            $add_users_id = $this->post('add_id');
            
            if($this->keys->check_login($userName,$password)){
                // If no key level provided, give them a rubbish one
                $level = $this->post('level') ? $this->post('level') : 1;
                $ignore_limits = $this->post('ignore_limits') ? $this->post('ignore_limits') : 1;

                if(!$this->_user_exists($add_users_id)){
                    die('87');

            // Insert the new key
                    if (self::_insert_key($key, array('username'=>$userName,'password'=>md5($password.config_item('rest_valid_logins_salt')),'level' => $level, 'ignore_limits' => $ignore_limits)))
                    {
                        $this->response(array('status' => 1, 'key' => $key), 201); // 201 = Created
                    }

                    else
                    {
                        $this->response(array('status' => 0, 'error' => 'Could not save the key.'), 500); // 500 = Internal Server Error
                    }
                } else {
                    $this->response(array('status' => 0, 'error' => 'Could not save the key user already exits.'), 500);
                }
            } else{
                $this->response(array('status' => 0, 'error' => 'Invalid Credintials.'), 500);
            }
        } else{
             $this->response(array('status' => 0, 'error' => 'Could not save the key. Username and Password required'), 500);
        }
    }

	// --------------------------------------------------------------------

	/**
	 * Key Delete
	 *
	 * Remove a key from the database to stop it working.
	 *
	 * @access	public
	 * @return	void
	 */
	public function removekey_delete()
    {
		$key = $this->delete('key');

		// Does this key even exist?
		if ( ! self::_key_exists($key))
		{
			// NOOOOOOOOO!
			$this->response(array('status' => 0, 'error' => 'Invalid API Key.'), 400);
		}

		// Kill it
		self::_delete_key($key);

		// Tell em we killed it
		$this->response(array('status' => 1, 'success' => 'API Key was deleted.'), 200);
    }

	// --------------------------------------------------------------------

	/**
	 * Update Key
	 *
	 * Change the level
	 *
	 * @access	public
	 * @return	void
	 */
	public function level_post()
    {
		$key = $this->post('key');
		$new_level = $this->post('level');

		// Does this key even exist?
		if ( ! self::_key_exists($key))
		{
			// NOOOOOOOOO!
			$this->response(array('error' => 'Invalid API Key.'), 400);
		}

		// Update the key level
		if (self::_update_key($key, array('level' => $new_level)))
		{
			$this->response(array('status' => 1, 'success' => 'API Key was updated.'), 200); // 200 = OK
		}

		else
		{
			$this->response(array('status' => 0, 'error' => 'Could not update the key level.'), 500); // 500 = Internal Server Error
		}
    }

	// --------------------------------------------------------------------

	/**
	 * Update Key
	 *
	 * Change the level
	 *
	 * @access	public
	 * @return	void
	 */
	public function suspend_post()
    {
		$key = $this->post('key');

		// Does this key even exist?
		if ( ! self::_key_exists($key))
		{
			// NOOOOOOOOO!
			$this->response(array('error' => 'Invalid API Key.'), 400);
		}

		// Update the key level
		if (self::_update_key($key, array('level' => 0)))
		{
			$this->response(array('status' => 1, 'success' => 'Key was suspended.'), 200); // 200 = OK
		}

		else
		{
			$this->response(array('status' => 0, 'error' => 'Could not suspend the user.'), 500); // 500 = Internal Server Error
		}
    }

	// --------------------------------------------------------------------

	/**
	 * Regenerate Key
	 *
	 * Remove a key from the database to stop it working.
	 *
	 * @access	public
	 * @return	void
	 */
	public function regenerate_post()
    {
		$old_key = $this->post('key');
		$key_details = self::_get_key($old_key);

		// The key wasnt found
		if ( ! $key_details)
		{
			// NOOOOOOOOO!
			$this->response(array('status' => 0, 'error' => 'Invalid API Key.'), 400);
		}

		// Build a new key
		$new_key = self::_generate_key();

        
		// Insert the new key
		if (self::_insert_key($new_key, array('level' => $key_details->level, 'ignore_limits' => $key_details->ignore_limits)))
		{
			// Suspend old key
			self::_update_key($old_key, array('level' => 0));

			$this->response(array('status' => 1, 'key' => $new_key), 201); // 201 = Created
		}

		else
		{
			$this->response(array('status' => 0, 'error' => 'Could not save the key.'), 500); // 500 = Internal Server Error
		}
    }

	// --------------------------------------------------------------------

	/* Helper Methods */
	
	private function _generate_key()
	{
		$this->load->helper('security');
		
		do
		{
			$salt = do_hash(time().mt_rand());
			$new_key = substr($salt, 0, config_item('rest_key_length'));
		}

		// Already in the DB? Fail. Try again
		while (self::_key_exists($new_key));

		return $new_key;
	}

	// --------------------------------------------------------------------

	/* Private Data Methods */

	private function _get_key($key)
	{
		return $this->rest->db->where('key', $key)->get(config_item('rest_keys_table'))->row();
	}

	// --------------------------------------------------------------------

	private function _key_exists($key)
	{
		return $this->rest->db->where('key', $key)->count_all_results(config_item('rest_keys_table')) > 0;
	}
    
    // --------------------------------------------------------------------

	private function _user_exists($usersID)
	{
		return $this->rest->db->where('users_id', $usersID)->count_all_results(config_item('rest_keys_table')) > 0;
	}

	// --------------------------------------------------------------------

	private function _insert_key($key, $data)
	{
		$data['key'] = $key;
		$data['date_created'] = function_exists('now') ? now() : time();

		return $this->rest->db->set($data)->insert(config_item('rest_keys_table'));
	}

	// --------------------------------------------------------------------

	private function _update_key($key, $data)
	{
		return $this->rest->db->where('key', $key)->update(config_item('rest_keys_table'), $data);
	}

	// --------------------------------------------------------------------

	private function _delete_key($key)
	{
		return $this->rest->db->where('key', $key)->delete(config_item('rest_keys_table'));
	}
}