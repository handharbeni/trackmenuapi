<?php

if ( ! function_exists('trimLower'))
{
	function trimLower($string)
	{
		$string = trim($string);
		$string = strtolower($string);

		return $string;
	}
}

if ( ! function_exists('generate_string'))
{
	function generate_string($length) {
	    $possible = "0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRESTUVWXYZ"; // allowed chars in the password
	     if ($length == "" OR !is_numeric($length)){
	      $length = 8;
	     }

	     $i = 0; 
	     $password = ""; 
	     while ($i < $length) { 
	      $char = substr($possible, rand(0, strlen($possible)-1), 1);
	      if (!strstr($password, $char)) { 
	       $password .= $char;
	       $i++;
	       }
	      }
	     return $password;
	}
}

if ( ! function_exists('random_string'))
{
	function random_string($length) 
	{
	    $key = '';
	    $keys = array_merge(range(0, 9), range('a', 'z'));

	    for ($i = 0; $i < $length; $i++) {
	        $key .= $keys[array_rand($keys)];
	    }

	    return $key;
	}
}

if ( ! function_exists('generate_key'))
{
	function generate_key()
	{
		
		$key1 = substr( md5(uniqid(rand(), true)),0,10);
		$key2 = generate_string('3');
		$key3 = strrev(strtotime( date('Y-m-d H:i:s')));
		$key4 = strrev(random_string('5'));
		return $key1.'-'.$key2.'-'.$key3.'-'.$key4;
	}
}

if ( ! function_exists('validEmail'))
{
	function validEmail($string)
    {
        $string = $this->trimLower($string);
        $chars = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}\$/i";
        if (strpos($string, '@') === false && strpos($string, '.') === false) {
            return false;
        }
        if (!preg_match($chars, $string)) {
            return false;
        }
        return $string;
    }
}

if ( ! function_exists('authToken'))
{
	function authToken($type , $token)
	{
		$CI =& get_instance();
		switch($type)
		{
			case 'users':
				$query = $CI->db
				->get_where('m_user' , array('key' => $token));

				if ( $query->num_rows() > 0)
				{
					$data = array();

					foreach($query->result() as $row)
					{
						$data[] = array(
								'id' => $row->id,
								'nama' => $row->nama,
								'email' => $row->email,
								'token' => $row->key,
								'tanggal' => $row->tanggal_buat,
								'alamat' => $row->alamat,
								'location' => $row->location
							);
					}

					return $data[0];	
				}

				return false;
			break;

			case 'admin':
				$query = $CI->db
				->get_where('m_admin' , array('key' => $token));

				if ( $query->num_rows() > 0)
				{
					$data = array();

					foreach($query->result() as $row)
					{
						$data[] = array(
								'id' => $row->id,
								'nama' => $row->nama,
								'username' => $row->username,
								'token' => $row->key,
								'tanggal' => $row->tanggal,
							);
					}

					return $data[0];	
				}

				return false;
			break;

			case 'kurir':
				$query = $CI->db
				->get_where('m_kurir' , array('key' => $token));

				if ( $query->num_rows() > 0)
				{
					$data = array();

					foreach($query->result() as $row)
					{
						$data[] = array(
								'id' => $row->id,
								'nama' => $row->nama,
								'username' => $row->username,
								'token' => $row->key,
								'tanggal' => $row->tanggal,
							);
					}

					return $data[0];	
				}

				return false;
			break;
		}
	}
}

if ( ! function_exists('generate_id'))
{
	function generate_id()
	{
		$CI =& get_instance();

		$count = $CI->db->get('m_order')->num_rows() + 1;

		$isRow = $CI->db->get_where('m_order' , 
			array('id' => $count))->num_rows();

		if ( $isRow == 0)
		{
			return $count;
		}
		else
		{
			return $count + rand(1,5);
		}
	}
}