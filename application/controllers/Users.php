<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Users extends REST_Controller {

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
			2 => 'Available order on admin',
			3 => 'Accepted by kurir',
			4 => 'Pengiriman selesai',
			5 => 'Order selesai',
			6 => 'Canceled by admin or user'
		);
	}

	public function index_get($action = '')
	{
		$token = $this->get('token');
		$authToken = authToken('users' , $token);

		if ( $token)
		{
			if ( $authToken)
			{
				if ( ! empty($action))
				{
					switch( trimLower($action))
					{
						case 'menu':
							$query = $this->db->get('m_menu');

							$response = array(
									'return' => ($query->num_rows() > 0) ? true: false,
									($query->num_rows() > 0) ? 'data' : 'error_message' => ($query->num_rows() > 0) 
									? $query->result() : 'Data menu kosong'
								);
						break;
						
						case 'order':
							$dataUser = $authToken;
							// $dataUser['nama']
							$sql = "SELECT t_order.* , m_order.* FROM t_order, m_order";
							$sql.= " WHERE m_order.id_user = ".$dataUser['id'];
							$sql.= " AND m_order.id = t_order.id_order AND status = 3";
							$queryOrder = $this->db->query($sql);

							$dataOrder = array();

							foreach($queryOrder->result() as $row)
							{
								$menu = $this->db->get_where('m_menu' , 
									array('id' => $row->id_menu))
								->result();

								$kurir = $this->db->get_where('m_kurir' ,
									array('id' => $row->id_kurir))
								->result()[0];

								$x = explode(" " , $row->tanggal_waktu);

								$dataOrder[] = array(
										'id' => $row->id,
										'id_order' => $row->id_order,
										'id_user' => $dataUser['id'],
										'id_kurir' => $kurir->id,
										'nama_user' => $dataUser['nama'],
										'email' => $dataUser['email'],
										'nama_kurir' => $kurir->nama,
										'total_harga' => $row->total_harga,
										'tanggal' => $x[0],
										'jam' => $x[1],
										'status' => array('key' => $row->status , 'value' => $this->status[$row->status]),
										'items' => $menu,
									);
							}

							$response = array(
									'return' => true,
									'data' => $dataOrder
								);
						break;

						case 'tracking':
							$Id = $this->get('kurir_id');

							if ( ! $Id)
							{
								$response = array(
										'return' => false,
										'error_message' => $this->msgNullField
									);
							}
							else
							{
								$kurirTracking = $this->db
								->get_where('t_tracking' , 
									array('id_kurir' => $Id));

								if ( $kurirTracking->num_rows() > 0)
								{
									$queryKurir = $this->db->get_where('m_kurir' , array('id' => $Id));

									foreach($queryKurir->result() as $row)
									{
										$result = array(
												'id' => $kurirTracking->result()[0]->id,
												'id_kurir' => $Id,
												'nama_kurir' => $row->nama,
												'latitude' => $kurirTracking->result()[0]->latitude,
												'longitude' => $kurirTracking->result()[0]->longitude
											);
									}

									$response = array(
										'return' => true,
										'data' => $result
									);
								}
								else
								{
									$response = array(
										'return' => false,
										'error_message' => 'Data tracking tidak ditemukan!'
									);
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
		$authToken = authToken('users' , $token);

		switch(trimLower($action))
		{
			case 'login':
				$postdata = array(
						'email' => $this->post('email'),
						'password' => $this->post('password')
					);

				if ( ! $postdata['email'] || ! $postdata['password'])
				{
					$response = array(
							'return' => false,
							'error_message' => $this->msgNullField
						);
				}
				else
				{
					$authLogin = $this->db
					->get_where('m_user' , 
						array('email' => $postdata['email'] , 'password' => md5($postdata['password'])));

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
							'error_message' => $this->msgWrongEmailPwd
						);
					}
				}
			break;

			case 'daftar':
				$postdata = array(
						'nama' => $this->post('nama'),
						'email' => $this->post('email'),
						'password' => $this->post('password')
					);

				if ( ! $postdata['nama'] || ! $postdata['email'] || ! $postdata['password'])
				{
					$response = array(
							'return' => false,
							'error_message' => $this->msgNullField
						);
				}
				else
				{
					$validasi = $this->db
					->get_where('m_user' , 
						array('email' => $postdata['email']));

					if ( $validasi->num_rows() > 0)
					{
						$response = array(
								'return' => false,
								'error_message' => $this->msgEmailExist
							);
					}
					else
					{
						$data = array(
								'nama' => $postdata['nama'],
								'email' => $postdata['email'],
								'password' => md5($postdata['password']),
								'key' => generate_key(),
								'tanggal_buat' => date('Y-m-d H:i:s')
							);

						$this->db->insert('m_user' , $data);

						$response = array(
								'return' => true,
								'message' => 'Berhasil daftar'
							);
					}
				}
			break;

			case 'order':
				if ( ! $token )
				{
					$response = array(
							'return' => true,
							'error_message' => $this->msgErrorToken
						);
				}
				else
				{
					if ( $authToken)
					{
						$postdata = array(
							'method' => $this->post('method'),
							'id_order' => $this->post('id_order'),
							'id_menu' => $this->post('id_menu'),
							'jumlah' => $this->post('jumlah')
						);

						$this->isNullField = array(
								'return' => false,
								'error_message' => $this->msgNullField
							);
					
						$acceptedMethod = array('new_order' , 'done');

						if ( ! $postdata['method'] || ! in_array($postdata['method'] , $acceptedMethod))
						{
							$response = array(
								'return' => false,
								'error_message' => ( ! $postdata['method']) 
									? $this->msgNullField : $this->msgWrongMethod
							);
						}
						else
						{
							$user = $authToken;
							switch( trimLower($postdata['method']))
							{
								case 'new_order':
									if ( ! $postdata['id_menu'] || ! $postdata['jumlah'])
									{
										$response = $this->isNullField;
									}
									else
									{
										$generate_id = generate_id();
										$ternaryId = ( ! $postdata['id_order']) 
													? $generate_id : $postdata['id_order'];
										/* Master Order */
										$dataMaster = array(
												'id' => $ternaryId,
												'id_user' => $user['id'],
												'id_kurir' => 0,
												'tanggal_waktu' => date('Y-m-d H:i:s'),
												'status' => 1
											);

										$selectMaster = $this->db->get_where('m_order', array(
												'id' => $ternaryId
											));

										if ( $selectMaster->num_rows() == 0)
										{
											$this->db->insert('m_order' , $dataMaster);
										}
										/* Master Order */

										/* Tabel Menu */	
										$selectMenu = $this->db->get_where('m_menu' , array(
												'id' => $postdata['id_menu']
											))->result()[0];
										/* Tabel Menu */

										/* Tabel Order */	
										$dataOrder = array(
												'id_order' => $ternaryId,
												'id_menu' => $postdata['id_menu'],
												'jumlah' => $postdata['jumlah'],
												'harga' =>	$selectMenu->harga,
												'total_harga' => $selectMenu->harga * $postdata['jumlah']
											); 

										$this->db->insert('t_order' , $dataOrder);
										/* Tabel Order */

										$response = array(
												'return' => true,
												'message' => 'Berhasil input order!'
											);
									}
								break;

								case 'done':
									if ( ! $postdata['id_order'])
									{
										$respose = $this->isNullField;
									}
									else
									{
										$data = array(
												'status' => 5
											);

										$this->db->set($data);
										$this->db->where( 
											array('id' => $postdata['id_order'], 'id_user' => $user['id']));
										$this->db->update('m_order');

										$response = array(
												'return' => true,
												'message' => 'Status order berhasil diubah!'
											);
									}
								break;
							}
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

/* End of file Users.php */
/* Location: ./application/controllers/Users.php */