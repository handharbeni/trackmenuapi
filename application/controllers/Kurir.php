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

		$this->statusMessage = array(
			1 => 'New Order',
			2 => 'Accepted order by Admin',
			3 => 'Assign order by Admin to Courier',
			4 => 'Accepted by kurir',
			5 => 'Order Active (Processing order)',
			6 => 'Order done by Customer or Cancel order by Customer / Admin'
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
						case 'order':
								$kurir = $authToken;
								// $myorder = $this->db->get_where('m_order' , array(
								// 			'id_kurir' => $kurir['id']
								// 		));

								$myorder = $this->db->from('m_order')
								->where( array('id_kurir' => $kurir['id']))
								->order_by("tanggal_waktu DESC")
								->get();

								$result = null;

								foreach($myorder->result() as $row)
								{
									$user = $this->db->get_where('m_user' , array(
											'id' => $row->id_user
										))->result()[0];

									$result[] = array(
											'id_order' => $row->id,
											'user' => array(
													'id_user' => $user->id,
													'nama' => $user->nama,
													'email' => $user->email,
													'alamat' => $user->alamat,
													'location' => $user->location
												),
											'id_kurir' => $row->id_kurir,
											'alamat' => $row->alamat,
											'latitude' => $row->latitude,
											'longitude' => $row->longitude,
											'tanggal_waktu' => $row->tanggal_waktu,
											'status' => array(
													'key' => $row->status,
													'value' => $this->statusMessage[$row->status]
												),
											'keterangan' => $row->keterangan,
											'delivery_fee' => $row->delivery_fee,
											'sha' => generate_key()
										);
								}

								$num = $myorder->num_rows();

								$response = array(
										'return' => ($num != 0) ? true : false,
										($num != 0) ? 'data' : 'error_message' => 
										($num != 0) ? $result : 'Tidak ada orderan yang tersedia!'
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
										'status' => 4,
										'sha' => generate_key()
									);

								$this->db->set($data);
								$this->db->where( array('id' => $this->post('id_order')));
								$this->db->update('m_order');

								$myorder = $this->db->get_where('m_order' , array(
										'id_kurir' => $kurir['id'],
										'status' => 4
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