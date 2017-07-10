<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Kurir extends REST_Controller {

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
		$authToken = authToken('kurir' , $token);

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

						case 'order':
							$this->status = $this->get('status');

							if ( ! $this->status)
							{
								$response = array(
										'return' => false,
										'error_message' => $this->msgErrorParameter
									);
							}
							else
							{
								$kurir = $authToken;
								switch( trimLower($this->status))
								{
									case 'self':
										$myorder = $this->db->get_where('m_order' , array(
													'id_kurir' => $kurir['id'],
													'status' => 3
												));

										$result = $myorder->result();
										$num = $myorder->num_rows();

										$response = array(
												'return' => ($num != 0) ? true : false,
												($num != 0) ? 'data' : 'error_message' => 
												($num != 0) ? $result : 'Tidak ada orderan yang aktif!'
											);
									break;

									case 'all':
										$myorder = $this->db->get_where('m_order' , array(
													'id_kurir' => $kurir['id'],
												));

										$result = $myorder->result();
										$num = $myorder->num_rows();

										$response = array(
												'return' => ($num != 0) ? true : false,
												($num != 0) ? 'data' : 'error_message' => 
												($num != 0) ? $result : 'Orderan masih kosong!'
											);
									break;

									default:
										$response = array(
												'return' => false,
												'error_message' => $this->msgWrongMethod
											);
									break;
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
		$authToken = authToken('kurir' , $token);

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
					->get_where('m_kurir' , 
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

			case 'order':
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
						$kurir = $authToken;
						if ( ! $this->post('id_order'))
						{
							$response = array(
									'return' => false,
									'error_message' => $this->msgNullField
								);
						}
						else
						{
							$allorder = $this->db
							->where( array('id' => $this->post('id_order')))
							->get('m_order');

							if ( $allorder->num_rows() == 0)
							{
								$response = array(
										'return' => false,
										'error_message' => 'Data order tidak ditemukan!'
									);
							}
							else
							{
								$data = array(
										'id_kurir' => $kurir['id'],
										'status' => 3
									);

								$this->db->set($data);
								$this->db->where( array('id' => $this->post('id_order')));
								$this->db->update('m_order');

								$myorder = $this->db->get_where('m_order' , array(
										'id_kurir' => $kurir['id'],
										'status' => 3
									))->result();

								$response = array(
										'return' => true,
										'message' => 'Berhasil mengambil orderan!',
										'orders' => $myorder
									);
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

/* End of file Kurir.php */
/* Location: ./application/controllers/Kurir.php */