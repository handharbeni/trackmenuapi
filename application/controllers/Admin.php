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

		// $this->statusMessage = array(
		// 	1 => 'New Order',
		// 	2 => 'Accepted order by Admin',
		// 	3 => 'Assign order by Admin to Courier',
		// 	4 => 'Accepted by kurir',
		// 	5 => 'Order Active (Processing order)',
		// 	6 => 'Order done by Customer or Cancel order by Customer / Admin',
		// 	7 => 'Order has been deleted!'
		// );

		$this->statusMessage = array(
				1 => 'Pesanan baru',
				2 => 'Pesanan sudah dterima oleh Admin',
				3 => 'Pesanan akan diisi oleh Kurir',
				4 => 'Pesanan diterima oleh Kurir',
				5 => 'Pesanan sedang diantar oleh Kurir',
				6 => 'Pesanan selesai',
				7 => 'Pesanan telah dihapus'
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
						case 'menu':
							$query = $this->db
							->from('m_menu')
							->order_by('id DESC')
							->get();

							$response = array(
									'return' => ($query->num_rows() > 0) ? true: false,
									($query->num_rows() > 0) ? 'data' : 'error_message' => ($query->num_rows() > 0) 
									? $query->result() : 'Data menu kosong'
								);
						break;

						case 'order':
							if ( ! $this->get('order_id'))
							{
								$queryOrder = $this->db->from('m_order')
											->order_by('tanggal_waktu DESC')
											->get();
							}
							else
							{
								$queryOrder = $this->db->from('m_order')
											->where( array('id' => $this->get('order_id')))
											->order_by('tanggal_waktu DESC')
											->get();
							}

							$dataOrder = array();

							foreach($queryOrder->result() as $row)
							{
								$total_belanja = 0;

								if ( $row->id_user != 0 || $row->id_user != null)
								{
									$user = $this->db->get_where('m_user' ,
										array('id' => $row->id_user))
									->result();

									foreach($user as $userdata)
									{
										$tmpuser = array(
												'id' => $userdata->id,
												'nama' => $userdata->nama,
												'email' => $userdata->email,
												'no_hp' => $userdata->no_hp,
												'alamat' => $userdata->alamat
											);
									}
								}

								if ( $row->id_kurir != 0 || $row->id_kurir != null)
								{
									$kurir = $this->db->get_where('m_kurir' ,
										array('id' => $row->id_kurir))
									->result();

									foreach($kurir as $kurirdata)
									{
										$tmpkurir = array(
												'id' => $kurirdata->id,
												'nama' => $kurirdata->nama,
												'foto_profil' => $kurirdata->foto_profil,
												'no_hp' => $kurirdata->no_hp,
												'no_plat' => $kurirdata->no_plat
											);
									}
								}

								if ( $row->id_outlet != 0 || $row->id_outlet != null)
								{
									$outlet = $this->db->get_where('m_outlet' ,
										array('id' => $row->id_outlet))
									->result();

									foreach($outlet as $outletdata)
									{
										$resto = $this->db->get_where('m_resto' ,
											array('id' => $outletdata->id_resto))
										->result()[0];

										$tmpoutlet = array(
												'id' => $outletdata->id,
												'resto' => array(
														'id_resto' => $resto->id,
														'nama_resto' => $resto->resto
													),
												'outlet' => $outletdata->outlet,
												'alamat' => $outletdata->alamat,
												'latitude' => $outletdata->lat,
												'longitude' => $outletdata->long,
												'tanggal_waktu' => $outletdata->tanggal_waktu,
												'sha' => $outletdata->sha
											);
									}
								}

								$items = $this->db->get_where('t_order' , 
									array('id_order' => $row->id));

								$tmpitems = null;

								foreach($items->result() as $menudata)
								{
									$menu = $this->db->get_where('m_menu' , 
										array('id' => $menudata->id_menu))
										->result()[0];

									$total_belanja += $menudata->total_harga;

									$tmpitems[] = array(
											'id' => $menudata->id,
											'id_order' => $menudata->id_order,
											'menu' => array(
													'id_menu' => $menu->id,
													'nama_menu' => $menu->nama,
													'gambar' => $menu->gambar,
													'sha' => $menu->sha
												),
											'jumlah' => $menudata->jumlah,
											'harga' => $menudata->harga,
											'total_harga' => $menudata->total_harga,
											'keterangan' => $menudata->keterangan
										);
								}

								$x = explode(" " , $row->tanggal_waktu);

								$dataOrder[] = array(
										'id_order' => $row->id,
										'user' => ($row->id_user != 0 ) ? $tmpuser : 'nothing',
										'outlet' => ($row->id_outlet != 0) ? $tmpoutlet : 'nothing',
										'kurir' => ( $row->id_kurir != 0 ) ? $tmpkurir : 'nothing',
										'alamat_kirim' => $row->alamat,
										'maps' => array(
												'latitude' => $row->latitude,
												'longitude' => $row->longitude
											),
										'total_belanja' => $total_belanja,
										'tanggal' => $x[0],
										'jam' => $x[1],
										'status' => array('key' => $row->status , 'value' => $this->statusMessage[$row->status]), 
										'sha' => $row->sha,
										'items' => $tmpitems
									);
							}

							$response = array(
									'return' => true,
									'data' => $dataOrder
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
							'message' => 'Berhasil login',
							'data' => $authLogin->result()[0]
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

								case 'delete_kurir':
									if ( ! $postdata['id_kurir'])
									{
										$response = array(
												'return' => false,
												'error_message' => $this->msgNullField
											);
									}
									else
									{
										$this->db->delete('m_kurir' , array('id' => $postdata['id_kurir']));

										$response = array(
												'return' => true,
												'message' => 'Berhasil menghapus kurir!'
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
												'status' => 3,
												'sha' => generate_key()
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

			case 'menu':
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
						$listMethod = array('add','update','delete');
						$method = $this->post('method');

						if ( ! in_array( trimLower($method) , $listMethod))
						{
							$response = array(
									'return' => false,
									'error_message' => $this->msgWrongMethod
								);
						}
						else
						{
							$postdata = array(
									'id_menu' => $this->post('id_menu'),
									'nama' => $this->post('nama'),
									'gambar' => $_FILES['gambar'],
									'harga' => $this->post('harga'),
									'kategori' => $this->post('kategori')
								);

							switch($method)
							{
								case 'add':
									if ( ! $postdata['nama'] || ! $postdata['gambar'] 
										|| ! $postdata['harga'] || ! $postdata['kategori'])
									{
										$response = array(
												'return' => false,
												'error_message' => $this->msgNullField
											);
									}
									else
									{
										$acceptedMime = array('image/png');

										if ( ! in_array( checkMime($_FILES['gambar']['name']) , $acceptedMime))
										{
											$response = array(
													'return' => false,
													'error_message' => 'File gambar harus berekstensi PNG'
												);
										}
										else
										{
											$dir = FCPATH.'images/'.date('Y-m-d').'/';
											$ext = strtolower(array_pop(explode('.',$_FILES['gambar']['name'])));
											$fileEncrypt = encryptFile($_FILES['gambar']['name']).'.'.$ext;

											if ( ! is_dir($dir))
											{
												@chmod($dir, '-R 777');
												@mkdir($dir);
											}

											$dataInsert = array(
													'nama' => $postdata['nama'],
													'gambar' => base_url().'images/'.date('Y-m-d').'/'.$fileEncrypt,
													'harga' => $postdata['harga'],
													'kategori' => $postdata['kategori']
												);


											$destination = $dir.$fileEncrypt;

											move_uploaded_file($_FILES['gambar']['tmp_name'], $destination);

											$this->db->insert('m_menu' , $dataInsert);

											$response = array(
													'return' => true,
													'message' => 'Berhasil input data menu'
												);
										}
									}
								break;

								case 'update':
									if ( ! $postdata['id_menu'])
									{
										$response = array(
												'return' => false,
												'error_message' => $this->msgNullField
											);
									}
									else
									{
										if ( $postdata['gambar'])
										{
											$acceptedMime = array('image/png');

											if ( ! in_array( checkMime($_FILES['gambar']['name']) , $acceptedMime))
											{
												$response = array(
														'return' => false,
														'error_message' => 'File gambar harus berekstensi PNG'
													);
											}
											else
											{
												$dir = FCPATH.'images/'.date('Y-m-d').'/';
												$ext = strtolower(array_pop(explode('.',$_FILES['gambar']['name'])));
												$fileEncrypt = encryptFile($_FILES['gambar']['name']).'.'.$ext;

												if ( ! is_dir($dir))
												{
													@chmod($dir, '-R 777');
													@mkdir($dir);
												}

												$destination = $dir.$fileEncrypt;

												move_uploaded_file($_FILES['gambar']['tmp_name'], $destination);

												$select = $this->db->get_where('m_menu' , 
													array(
															'id' => $postdata['id_menu']
														))->result()[0];

												$dataUpdate = array(
														'nama' => $postdata['nama'] ? $postdata['nama'] : $select->nama,
														'harga' => $postdata['harga'] ? $postdata['harga'] : $select->harga,
														'gambar' => $postdata['gambar'] ? base_url().'images/'.date('Y-m-d').'/'.$fileEncrypt : $select->gambar,
														'kategori' => $postdata['kategori'] ? $postdata['kategori'] : $select->kategori
													);

												$this->db->set($dataUpdate);
												$this->db->where( array('id' => $postdata['id_menu']));
												$this->db->update('m_menu');

												$response = array(
														'return' => true,
														'x' => $_FILES['gambar'],
														'message' => 'Berhasil ubah data menu'
													);
											}
										}
										else
										{
											$select = $this->db->get_where('m_menu' , 
												array(
														'id' => $postdata['id_menu']
													))->result()[0];

											$dataUpdate = array(
													'nama' => $postdata['nama'] ? $postdata['nama'] : $select->nama,
													'harga' => $postdata['harga'] ? $postdata['harga'] : $select->harga,
													'gambar' => $select->gambar,
													'kategori' => $postdata['kategori'] ? $postdata['kategori'] : $select->kategori
												);

											$this->db->set($dataUpdate);
											$this->db->where( array('id' => $postdata['id_menu']));
											$this->db->update('m_menu');

											$response = array(
													'return' => true,
													'x' => $_FILES['gambar'],
													'message' => 'Berhasil ubah data menu'
												);
										}
									}
								break;

								case 'delete':
									if ( ! $postdata['id_menu'])
									{
										$response = array(
												'return' => false,
												'error_message' => $this->msgNullField
											);
									}
									else
									{
										$select = $this->db->get_where('m_menu' , array(
												'id' => $postdata['id_menu']
											));

										if ( $select->num_rows() > 0)
										{
											$x = explode('/' , $select->result()[0]->gambar);

											$this->db->delete('m_menu' , array(
													'id' => $postdata['id_menu']
												));

											// $unlinkFile = unlink(FCPATH.'images/'.$x[count($x)-2].'/'.$x[count($x)]-1);

											$response = array(
													'return' => true,
													'message' => 'Berhasil di hapus!'
												);
										}
										else
										{
											$response = array(
													'return' => false,
													'error_message' => 'ID Menu tidak ditemukan!'
												);
										}
									}
								break;
							}
						}
					}
				}
			break;

			case 'outlet':
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
						$listMethod = array('add_outlet','update_outlet','delete_outlet');
						$method = $this->post('method');

						if ( ! in_array( trimLower($method) , $listMethod))
						{
							$response = array(
									'return' => false,
									'error_message' => $this->msgWrongMethod
								);
						}
						else
						{
							$postdata = array(
									'username' => $this->post('username'),
									'password' => $this->post('password'),
									'id_resto' => $this->post('id_resto'),
									'id_outlet' => $this->post('id_outlet'),
									'nama_outlet' => $this->post('nama_outlet'),
									'alamat' => $this->post('alamat'),
									'latitude' => $this->post('latitude'),
									'longitude' => $this->post('longitude')
								);

							switch($method)
							{
								case 'add_outlet':
									if ( ! $postdata['username'] || ! $postdata['password']
										|| ! $postdata['nama_outlet'] ||  ! $postdata['alamat'])
									{
										$response = array(
											'return' => false,
											'error_message' => $this->msgNullField
										);
									}
									else
									{
										$rowAdmin = $this->db->get_where('m_admin',array(
												'username' => $postdata['username']
											))->num_rows();

										if ( $rowAdmin > 0)
										{
											$response = array(
													'return' => false,
													'error_message' => 'Username sudah ada!'
												);
										}
										else
										{
											$sha = generate_key();
											$dataOutlet = array(
													'id_resto' => $postdata['id_resto'],
													'outlet' => $postdata['nama_outlet'],
													'alamat' => $postdata['alamat'],
													'lat' => null,
													'long' => null,
													'tanggal_waktu' => date('Y-m-d H:i:s'),
													'sha' => $sha
												);

											$this->db->insert('m_outlet', $dataOutlet);

											$queryOutlet = $this->db->get_where('m_outlet' , array(
													'sha' => $sha
												))->result()[0];

											$dataAdmin = array(
													'id_outlet' => $queryOutlet->id,
													'username' => $postdata['username'],
													'password' => md5($postdata['password']),
													'key' => generate_key(),
													'tanggal' => date('Y-m-d')
												);	

											$this->db->insert('m_admin' , $dataAdmin);

											$response = array(
												'return' => 'true',
												'result' => 'Berhasil tambah outlet!',
												'data' => $queryOutlet
												);
										}
									}
								break;

								case 'update_outlet':
									if ( ! $postdata['id_outlet'])
									{
										$response = array(
												'return' => false,
												'error_message' => $this->msgNullField
											);	
									}
									else
									{
										$query = $this->db->get_where('m_outlet',array(
												'id' => $postdata['id_outlet']
											))->result()[0];

										$dataUpdate = array(
												'outlet' => $postdata['nama_outlet'] ? 
												$postdata['nama_outlet'] : $query->outlet,
												'alamat' => $postdata['alamat'] ? 
												$postdata['alamat'] : $query->alamat,
												'lat' => $postdata['latitude'] ?
												$postdata['latitude'] : $query->lat,
												'long' => $postdata['longitude'] ?
												$postdata['longitude'] : $query->longitude,
												'sha' => generate_key()
											);

										$this->db->set($dataUpdate);
										$this->db->where( array('id' => $postdata['id_outlet']));
										$this->db->update('m_outlet');

										$response = array(
											'return' => 'true',
											'message' => 'Berhasil mengubah data outlet!'
											);
									}
								break;

								case 'delete_outlet':
									if ( ! $postdata['id_outlet'])
									{
										$response = array(
												'return' => false,
												'error_message' => $this->msgNullField
											);	
									}
									else
									{
										$this->db->delete('m_admin', array(
												'id_outlet' => $postdata['id_outlet']
											));
										$this->db->delete('m_outlet', array(
												'id' => $postdata['id_outlet']
											));

										$response = array(
												'return' => true,
												'message' => 'Berhasil menghapus outlet!'
											);
									}
								break;
							}
						}
					}
				}
			break;

			case 'user':
				// do
			break;

			case 'setting':
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
						$km = $this->post('km');

						if ( ! $km )
						{
							$response = array(
									'return' => false,
									'error_message' => $this->msgNullField
								);
						}
						elseif ( ! is_numeric($km))
						{
							$response = array(
									'return' => false,
									'error_message' => 'Harga per-kilometer harus berupa angka'
								);
						}
						else
						{
							$data = array(
									'value' => $km
								);

							$this->db->set($data);
							$this->db->where( array('key' => 'km'));
							$this->db->update('tools_value');

							$response = array(
									'return' => true,
									'message' => 'Berhasil mengubah harga per-kilometer'
								);
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