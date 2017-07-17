<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Admin extends REST_Controller {

	public function __construct()
	{
		parent::__construct();
		$errorMsg = parent::setErrorMessage();
		foreach($errorMsg as $key => $value)
		{
			$x = "msg".$key;
			$this->$x = $value;
		}

		$this->status = array(
			1 => 'New Order',
			2 => 'available order on admin',
			3 => 'Send to courier',
			4 => 'Accepted by kurir',
			5 => 'Order Active',
			6 => 'Order done'
		);
	}

	public function index_get($action = '')
	{
		$token = $this->get('token');
		$authToken = authToken('admin' , $token);

		if ( $token)
		{
			if ( $authToken)
			{
				if ( ! empty($action))
				{
					switch( trimLower($action))
					{
						// dummy
						case 'menu':
							$query = $this->db->get('m_menu');

							$response = array(
									'return' => ($query->num_rows() > 0) ? true: false,
									($query->num_rows() > 0) ? 'data' : 'error_message' => ($query->num_rows() > 0) 
									? $query->result() : 'Data menu kosong'
								);
						break;

						default:
							$response = array(
									'return' => false,
									'error_message' => $this->msgErrorParameter
								);
						break;
					}
				}
				else
				{
					$response = array(
							'return' => true,
							'data' => $authToken
						);
				}
			}
			else
			{
				$response = array(
					'return' => false,
					'error_message' => $this->msgWrongToken
				);
			}
		}
		else
		{
			$response = array(
					'return' => false,
					'error_message' => $this->msgErrorToken
				);
		}

		$this->response($response);
	}

	public function index_post($action = '')
	{
		$token = $this->post('token');
		$authToken = authToken('admin' , $token);

		switch(trimLower($action))
		{
			case 'login':
				$postdata = array(
						'username' => $this->post('username'),
						'password' => $this->post('password')
					);

				if ( ! $postdata['username'] || ! $postdata['password'])
				{
					$response = array(
							'return' => false,
							'error_message' => $this->msgNullField
						);
				}
				else
				{
					$authLogin = $this->db
					->get_where('m_admin' , 
						array('username' => $postdata['username'] , 'password' => md5($postdata['password'])));

					if ( $authLogin->num_rows() > 0)
					{
						$response = array(
							'return' => true,
							'message' => 'Berhasil login'
						);
					}
					else
					{
						$response = array(
							'return' => false,
							'error_message' => $this->msgWrongUserPwd
						);
					}
				}
			break;

			case 'kurir':
				if ( ! $token)
				{
					$response = array(
							'return' => false,
							'error_message' => $this->msgErrorToken
						);
				}
				else
				{
					if ( ! $authToken)
					{
						$response = array(
								'return' => false,
								'error_message' => $this->msgWrongToken
							);
					}
					else
					{
						$postdata = array(
								'method' => $this->post('method'),
								'id_order' => $this->post('id_order'),
								'id_kurir' => $this->post('id_kurir'),
								'nama' => $this->post('nama'),
								'username' => $this->post('username'),
								'password' => $this->post('password')
							);

						if ( ! $postdata['method'])
						{
							$response = array(
									'return' => false,
									'error_message' => $this->msgNullField
								);
						}
						else
						{
							switch( trimLower($postdata['method']))
							{
								// tambah kurir
								case 'add_kurir':
									if ( ! $postdata['nama'] || ! $postdata['username'] || ! $postdata['password'])
									{
										$response = array(
												'return' => false,
												'error_message' => $this->msgNullField
											);
									}
									else
									{
										$dataKurir = $this->db->get_where('m_kurir' , 
											array('username' => $postdata['username']));

										$data = array(
												'nama' => $postdata['nama'],
												'username' => $postdata['username'],
												'password' => md5($postdata['password']),
												'key' => generate_key(),
												'tanggal' => date('Y-m-d')
											);

										$num = $dataKurir->num_rows();

										if ( $num == 0)
										{
											$this->db->insert('m_kurir' , $data);

											$kurir = $this->db->get_where('m_kurir' , array(
													'username' => $postdata['username']
												))->result()[0];

											$this->db->insert('t_tracking' , array(
													'id_kurir' => $kurir->id
												));
										}

										$response = array(
												'return' => ($num == 0) ? true : false,
												($num == 0) ? 'message' : 'error_message' => 
												($num == 0 ) ? 'Data kurir berhasil ditambahkan': $this->msgUsernameExist
											);
									}
								break;

								case 'send_order':
									if ( ! $postdata['id_order'] || ! $postdata['id_kurir'])
									{
										$response = array(
											'return' => false,
											'error_message' => $this->msgNullField
										);
									}
									else
									{
										$dataUpdate = array(
												'id_kurir' => $postdata['id_kurir'],
												'status' => 3
											);

										$this->db->set($dataUpdate);
										$this->db->where( 
											array('id' => $postdata['id_order']));
										$this->db->update('m_order');

										$query = $this->db->get_where('m_order' , array(
												'id' => $postdata['id_order']
											));

										$row = $query->num_rows();

										$response = array(
												'return' => ( $row > 0) ? true : false,
												 ( $row > 0) ? 'data' : 'error_message' 
												 =>  ( $row > 0) ? $query->result()[0] : 'ID Order tidak ditemukan!'
											);
									}
								break;

								default:
									$response = array(
											'return' => false,
											'error_message' => $this->msgErrorParameter
										);
								break;
							}
						}
					}
				}
			break;

			default:
				$response = array(
					'return' => false,
					'error_message' => $this->msgErrorParameter
				);
			break;
		}

		$this->response($response);
	}
}

/* End of file Admin.php */
/* Location: ./application/controllers/Admin.php */