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

if ( ! function_exists('encryptFile'))
{
	function encryptFile($string)
	{
		$key1 = substr( md5(uniqid(rand(), true)),0,10);
		$key2 = generate_string('3');
		$key3 = strrev(strtotime( date('Y-m-d H:i:s')));
		$key4 = strrev(substr(md5($string) , 0, rand(7,15)));

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
	function authToken($type , $token , $viewpwd = '')
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
								'no_hp' => $row->no_hp,
								'token' => $row->key,
								'terdaftar' => $row->tanggal_buat,
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
						$tmpdata = null;

						if ( $row->id_outlet != 0)
						{
							$outlet = $CI->db->get_where('m_outlet' , array('id' => $row->id_outlet))->result()[0];
							$resto = $CI->db->get_where('m_resto' , array('id' => $outlet->id_resto))->result()[0];

							if ( $viewpwd)
							{
								$data = array(
									'id' => $row->id,
									'outlet' => array(
											'id_outlet' => $outlet->id,
											'restaurant' => array(
													'id_restaurant' => $resto->id,
													'nama_restaurant' => $resto->resto
												),
											'nama_outlet' => $outlet->outlet,
											'alamat' => $outlet->alamat,
											'latitude' => $outlet->lat,
											'longitude' => $outlet->long,
											'tanggal_waktu' => $outlet->tanggal_waktu,
											'sha' => $outlet->sha
										),
									'username' => $row->username,
									'password' => $row->password,
									'token' => $row->key,
									'tanggal' => $row->tanggal,
								);
							}
							else
							{
								$data = array(
									'id' => $row->id,
									'outlet' => array(
											'id_outlet' => $outlet->id,
											'restaurant' => array(
													'id_restaurant' => $resto->id,
													'nama_restaurant' => $resto->resto
												),
											'nama_outlet' => $outlet->outlet,
											'alamat' => $outlet->alamat,
											'latitude' => $outlet->lat,
											'longitude' => $outlet->long,
											'tanggal_waktu' => $outlet->tanggal_waktu,
											'sha' => $outlet->sha
										),
									'username' => $row->username,
									'token' => $row->key,
									'tanggal' => $row->tanggal,
								);
							}
							
							$tmpdata[] = $data;
						}
						else
						{
							if ( $viewpwd)
							{
								$data = array(
									'id' => $row->id,
									'username' => $row->username,
									'password' => $row->password,
									'token' => $row->key,
									'tanggal' => $row->tanggal,
								);
							}
							else
							{
								$data = array(
									'id' => $row->id,
									'username' => $row->username,
									'token' => $row->key,
									'tanggal' => $row->tanggal,
								);
							}

							$tmpdata[] = $data;
						}

						array_push($data, $tmpdata);
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
						if ( $viewpwd)
						{
							$data[] = array(
								'id' => $row->id,
								'nama' => $row->nama,
								'username' => $row->username,
								'password' => $row->password,
								'foto_profil' => $row->foto_profil,
								'no_hp' => $row->no_hp,
								'no_plat' => $row->no_plat,
								'token' => $row->key,
								'tanggal' => $row->tanggal,
							);
						}
						else
						{
							$data[] = array(
								'id' => $row->id,
								'nama' => $row->nama,
								'username' => $row->username,
								'foto_profil' => $row->foto_profil,
								'no_hp' => $row->no_hp,
								'no_plat' => $row->no_plat,
								'token' => $row->key,
								'tanggal' => $row->tanggal,
							);
						}
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

if(!function_exists('checkMime')) 
{
    function checkMime($filename) {

        $mime_types = array(

            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        $ext = strtolower(array_pop(explode('.',$filename)));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        }
        elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        }
        else {
            return 'application/octet-stream';
        }
    }
}

if ( ! function_exists('statusMessages'))
{
	function statusMessages()
	{
		return array(
				1 => 'Pesanan baru', // user
				2 => 'Pesanan sudah dterima oleh Admin', // admin / oulet
				3 => 'Pesanan akan diambil oleh Kurir', // kurir
				4 => 'Pesanan diterima oleh Kurir', // kurir
				5 => 'Pesanan sedang diantar oleh Kurir', // kurir
				6 => 'Pesanan selesai', // selesai
				7 => 'Pesanan telah dihapus' // user / admin
			);
	}
}