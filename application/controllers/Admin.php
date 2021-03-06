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

		$this->statusMessage = statusMessages();
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
							if ( $this->get('sha'))
							{
								$query = $this->db
								->from('m_menu')
								->where( array('deleted' => 0 ,'sha' => $this->get('sha')))
								->order_by('id DESC')
								->get();
							}
							else
							{
								$query = $this->db
								->from('m_menu')
								->where( array('deleted' => 0))
								->order_by('id DESC')
								->get();
							}

							$data = null;

							foreach($query->result() as $row)
							{
								$queryStokAvailable = $this->db->get_where('m_stok' , array('id_menu' => $row->id));
								$queryStokUsed = $this->db->get_where('t_pemakaian_stok' , array('id_menu' => $row->id));

								$numStokAvailable = $queryStokAvailable->num_rows();
								$numStokUsed = $queryStokUsed->num_rows();

								if ( $numStokUsed > 0)
								{
									$stokUsed = null;

									foreach($queryStokUsed->result() as $x)
									{
										$stokUsed += $x->jumlah;
									}
								}

								if ( $numStokAvailable > 0)
								{
									$stokAvailable = null;

									foreach($queryStokAvailable->result() as $y)
									{
										$stokAvailable += $y->jumlah;
									}
								}

								$total = ($numStokAvailable > 0 ? $stokAvailable : 0) - ($numStokUsed > 0 ? $stokUsed : 0);

								$data[] = array(
										'id' => $row->id,
										'nama' => $row->nama,
										'gambar' => $row->gambar,
										'harga' => $row->harga,
										'kategori' => $row->kategori,
										'sha' => $row->sha,
										'stok' => array(
												'jumlah' => $numStokAvailable > 0 ? $stokAvailable : 0,
												'digunakan' => $numStokUsed > 0 ? $stokUsed : 0,
												'sisa' => (int) $total,
											)
									);
							}

							$response = array(
									'return' => ($query->num_rows() > 0) ? true: false,
									($query->num_rows() > 0) ? 'data' : 'error_message' => ($query->num_rows() > 0) 
									? $data : 'Data menu kosong'
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

						case 'rating':
							$opsi = $this->get('opsi');

							if ( ! $opsi)
							{
								$response = array(
										'return' => false,
										'error_message' => $this->msgNullField
									);
							}
							else
							{
								$list_opsi = array('menu','kurir','outlet');

								if ( ! in_array($opsi , $list_opsi))
								{
									$response = array(
											'return' => false,
											'error_message' => $this->msgWrongMethod
										);
								}
								else
								{
									$query = $this->db->get_where('t_rating' , array(
											'tipe' => $opsi
										));

									$num = $query->num_rows();

									$data = null;

									foreach( $query->result() as $row)
									{
										$rowMenu = ( $row->id_menu != 0);
										$rowUser = ( $row->id_user != 0);
										$rowOutlet = ( $row->id_outlet != 0);
										$rowKurir = ( $row->id_kurir != 0);
										if ( $rowMenu)
										{
											// menu
											$menu = $this->db->get_where('m_menu' , array(
													'id' => $row->id_menu
												))->result()[0];
										}

										if ( $rowUser)
										{
											// user
											$user = $this->db->get_where('m_user' , array(
													'id' => $row->id_user
												))->result()[0];
										}

										if ( $rowOutlet)
										{
											// outlet
											$outlet = $this->db->get_where('m_outlet' , array(
													'id' => $row->id_outlet
												))->result()[0];

											// resto
											$resto = $this->db->get_where('m_resto' , array(
													'id' => $outlet->id_resto
												))->result()[0];
										}

										if ( $rowKurir)
										{
											// kurir
											$kurir = $this->db->get_where('m_kurir' , array(
													'id' => $row->id_kurir
												))->result()[0];
										}


										$data[] = array(
												'id_rating' => $row->id,
												'menu' => $rowMenu ? array(
														'id_menu' => $menu->id,
														'nama' => $menu->nama,
														'gambar' => $menu->gambar,
														'harga' => $menu->harga,
														'kategori' => $menu->kategori
													) : 'nothing',

												'user' => $rowUser ? array(
														'id_user' => $user->id,
														'nama' => $user->nama,
														'email' => $user->email,
														'no_hp' => $user->no_hp,
														'alamat' => $user->alamat,
														'location' => $user->location,
													) : 'nothing' ,

												'outlet' => $rowOutlet ? array(
														'id_outlet' => $outlet->id,
														'resto' => $resto,
														'outlet' => $outlet->outlet,
														'alamat' => $outlet->alamat,
														'lokasi' => array(
																'latitude' => $outlet->lat,
																'longitude' => $outlet->long
															),
													) : 'nothing' ,

												'kurir' => $rowKurir ? array(
														'id_kurir' => $kurir->id,
														'nama' => $kurir->nama,
														'username' => $kurir->username,
														'foto_profil' => $kurir->foto_profil,
														'no_hp' => $kurir->no_hp,
														'no_plat' => $kurir->no_plat,
													) : 'nothing',
												'rating' => $row->rating,
												'keterangan' => $row->keterangan,
												'tanggal_waktu' => $row->datetime
											);
									}

									$response = array(
											'return' => $num > 0 ? true : false,
											$num > 0 ? 'data' : 'error_message' => 
											$num > 0 ? $data : 'Data tidak ditemukan!'
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
					if ( $this->get('getpwd'))
					{
						$authToken = authToken('admin', $token, TRUE);
					}

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
						$postdata = array(
								'method' => $this->post('method'),
								'sha' => $this->post('sha')
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
								case 'accept_order':
									if ( ! $postdata['sha'])
									{
										$response = array(
											'return' => false,
											'error_message' => $this->msgNullField
										);
									}
									else
									{
										$query = $this->db->get_where('m_order', array('sha' => $postdata['sha']));

										$num = $query->num_rows();

										if ( $num > 0)
										{
											$data = array(
													'status' => 2,
													'sha' => generate_key()
												);

											$this->db->set($data);
											$this->db->where( array('sha' => $postdata['sha']));
											$this->db->update('m_order');
										}

										$response = array(
												'return' => $num > 0 ? true : false,
												$num > 0 ? 'message' : 'error_message' =>
												$num > 0 ? 'Order behasil diterima' : 'Order tidak ditemukan!'
											);
									}
								break;

								case 'send_order':
									if ( ! $postdata['sha'])
									{
										$response = array(
											'return' => false,
											'error_message' => $this->msgNullField
										);
									}
									else
									{
										$query = $this->db->get_where('m_order', array('sha' => $postdata['sha']));

										$num = $query->num_rows();

										if ( $num > 0)
										{
											$data = array(
													'status' => 3,
													'sha' => generate_key()
												);

											$this->db->set($data);
											$this->db->where( array('sha' => $postdata['sha']));
											$this->db->update('m_order');
										}

										$response = array(
												'return' => $num > 0 ? true : false,
												$num > 0 ? 'message' : 'error_message' =>
												$num > 0 ? 'Order berhasil dikirim' : 'Order tidak ditemukan!'
											);
									}
								break;

								case 'cancel_order':
									if ( ! $postdata['sha'])
									{
										$response = array(
											'return' => false,
											'error_message' => $this->msgNullField
										);
									}
									else
									{
										$query = $this->db->get_where('m_order', array('sha' => $postdata['sha']));

										$num = $query->num_rows();

										if ( $num > 0)
										{
											$data = array(
													'status' => 6,
													'sha' => generate_key()
												);

											$this->db->set($data);
											$this->db->where( array('sha' => $postdata['sha']));
											$this->db->update('m_order');
										}

										$response = array(
												'return' => $num > 0 ? true : false,
												$num > 0 ? 'message' : 'error_message' =>
												$num > 0 ? 'Order berhasil dibatalkan' : 'Order tidak ditemukan!'
											);
									}
								break;

								case 'temp_delete':
									if ( ! $postdata['sha'])
									{
										$response = array(
											'return' => false,
											'error_message' => $this->msgNullField
										);
									}
									else
									{
										$query = $this->db->get_where('m_order', array('sha' => $postdata['sha']));

										$num = $query->num_rows();

										if ( $num > 0)
										{
											$data = array(
													'status' => 7,
													'sha' => generate_key()
												);

											$this->db->set($data);
											$this->db->where( array('sha' => $postdata['sha']));
											$this->db->update('m_order');
										}

										$response = array(
												'return' => $num > 0 ? true : false,
												$num > 0 ? 'message' : 'error_message' =>
												$num > 0 ? 'Order berhasil dihapus' : 'Order tidak ditemukan!'
											);
									}
								break;

								case 'undo':
									if ( ! $postdata['sha'])
									{
										$response = array(
											'return' => false,
											'error_message' => $this->msgNullField
										);
									}
									else
									{
										$query = $this->db->get_where('m_order', array('sha' => $postdata['sha']));

										$num = $query->num_rows();

										if ( $num > 0)
										{
											$data = array(
													'status' => 6,
													'sha' => generate_key()
												);

											$this->db->set($data);
											$this->db->where( array('sha' => $postdata['sha']));
											$this->db->update('m_order');
										}

										$response = array(
												'return' => $num > 0 ? true : false,
												$num > 0 ? 'message' : 'error_message' =>
												$num > 0 ? 'Order berhasil dihapus' : 'Order tidak ditemukan!'
											);
									}
								break;

								// case 'perm_delete':
								// 	if ( ! $postdata['sha'])
								// 	{
								// 		$response = array(
								// 			'return' => false,
								// 			'error_message' => $this->msgNullField
								// 		);
								// 	}
								// 	else
								// 	{
								// 		$query = $this->db->get_where('m_order', array('sha' => $postdata['sha']));

								// 		$num = $query->num_rows();

								// 		if ( $num > 0)
								// 		{
								// 			$this->db->delete('m_order', array('sha' => $postdata['sha']));
								// 		}

								// 		$response = array(
								// 				'return' => $num > 0 ? true : false,
								// 				$num > 0 ? 'message' : 'error_message' =>
								// 				$num > 0 ? 'Order berhasil dihapus permanen' : 'Order tidak ditemukan!'
								// 			);
								// 	}
								// break;

								default:
									$response = array(
											'return' => false,
											'error_message' => $this->msgWrongMethod
										);
								break;
							}
						}
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
								'password' => $this->post('password'),
								'foto' => $this->post('foto'),
								'no_hp' => $this->post('no_hp'),
								'no_plat' => $this->post('no_plat')
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
									if ( ! $postdata['nama'] || !$postdata['username'] || ! $postdata['password']
										|| ! $postdata['foto'] || ! $postdata['no_hp'] || ! $postdata['no_plat'])
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
												'foto_profil' => $postdata['foto'],
												'no_hp' => $postdata['no_hp'],
												'no_plat' => $postdata['no_plat'],
												'key' => generate_key(),
												'tanggal' => date('Y-m-d H:i:s'),
												'deleted' => 0
											);

										$num = $dataKurir->num_rows();

										if ( $num == 0)
										{
											$this->db->insert('m_kurir' , $data);

											$kurir = $this->db->get_where('m_kurir' , array(
													'username' => $postdata['username']
												))->result()[0];

											$this->db->insert('t_tracking' , array(
													'id_kurir' => $kurir->id,
													'latitude' => '-7.9414768',
													'longitude' => '112.6208363'
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
									if ( ! $this->post('token_kurir'))
									{
										$response = array(
												'return' => false,
												'error_message' => $this->msgNullField
											);
									}
									else
									{
										$data = authToken('kurir', $this->post('token_kurir'));

										if ( ! $data)
										{
											$response = array(
													'return' => false,
													'error_message' => $this->msgWrongToken
												);
										}
										else
										{
											$this->db->set( array('deleted' => 1));
											$this->db->where( array('key' => $data['token']));
											$this->db->update('m_kurir');

											$response = array(
													'return' => true,
													'message' => 'Berhasil menghapus kurir!'
												);
										}
									}
								break;

								case 'undo':
									if ( ! $this->post('token_kurir'))
									{
										$response = array(
												'return' => false,
												'error_message' => $this->msgNullField
											);
									}
									else
									{
										$data = authToken('kurir', $this->post('token_kurir'));

										if ( ! $data)
										{
											$response = array(
													'return' => false,
													'error_message' => $this->msgWrongToken
												);
										}
										else
										{
											$this->db->set( array('deleted' => 0));
											$this->db->where( array('key' => $data['token']));
											$this->db->update('m_kurir');
											
											$response = array(
													'return' => true,
													'message' => 'Berhasil membatalkan!'
												);
										}
									}
								break;

								case 'update_kurir':
									if ( ! $this->post('token_kurir'))
									{
										$response = array(
												'return' => false,
												'error_message' => $this->msgNullField
											);
									}
									else
									{
										$data = authToken('kurir', $this->post('token_kurir'), TRUE);

										if ( ! $data)
										{
											$response = array(
													'return' => false,
													'error_message' => $this->msgWrongToken
												);
										}
										else
										{
											$dataUpdate = array(
													'nama' => $postdata['nama'] ?
														$postdata['nama'] : $data['nama'],
													'username' => $postdata['username'] ?
														$postdata['username'] : $data['username'],
													'password' => $postdata['password'] ?
														$postdata['password'] : $data['password'],
													'foto_profil' => $postdata['foto'] ?
														$postdata['foto'] : $data['foto_profil'],
													'no_hp' => $postdata['no_hp'] ? 
														$postdata['no_hp'] : $data['no_hp'],
													'no_plat' => $postdata['no_plat'] ?
														$postdata['no_plat'] : $data['no_plat']
												);

											$this->db->set($dataUpdate);
											$this->db->where( array('key' => $data['token']));
											$this->db->update('m_kurir');

											$response = array(
													'return' => true,
													'message' => 'Berhasil mengubah data kurir!'
												);
										}
									}
								break;

								default:
									$response = array(
											'return' => false,
											'error_message' => $this->msgWrongMethod
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
						$listMethod = array('add','update','delete','undo','add_stok','update_stok');
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
									'gambar' => $this->post('gambar'),
									'harga' => $this->post('harga'),
									'kategori' => $this->post('kategori'),
									'sha' => $this->post('sha'),
									'jumlah' => $this->post('jumlah')
								);

							switch($method)
							{
								// case 'add':
								// 	if ( ! $postdata['nama'] || ! $postdata['gambar'] 
								// 		|| ! $postdata['harga'] || ! $postdata['kategori'])
								// 	{
								// 		$response = array(
								// 				'return' => false,
								// 				'error_message' => $this->msgNullField
								// 			);
								// 	}
								// 	else
								// 	{
								// 		$acceptedMime = array('image/png');

								// 		if ( ! in_array( checkMime($_FILES['gambar']['name']) , $acceptedMime))
								// 		{
								// 			$response = array(
								// 					'return' => false,
								// 					'error_message' => 'File gambar harus berekstensi PNG'
								// 				);
								// 		}
								// 		else
								// 		{
								// 			$dir = FCPATH.'images/'.date('Y-m-d').'/';
								// 			$ext = strtolower(array_pop(explode('.',$_FILES['gambar']['name'])));
								// 			$fileEncrypt = encryptFile($_FILES['gambar']['name']).'.'.$ext;

								// 			if ( ! is_dir($dir))
								// 			{
								// 				@chmod($dir, '-R 777');
								// 				@mkdir($dir);
								// 			}

								// 			$dataInsert = array(
								// 					'nama' => $postdata['nama'],
								// 					'gambar' => base_url().'images/'.date('Y-m-d').'/'.$fileEncrypt,
								// 					'harga' => $postdata['harga'],
								// 					'kategori' => $postdata['kategori']
								// 				);


								// 			$destination = $dir.$fileEncrypt;

								// 			move_uploaded_file($_FILES['gambar']['tmp_name'], $destination);

								// 			$this->db->insert('m_menu' , $dataInsert);

								// 			$response = array(
								// 					'return' => true,
								// 					'message' => 'Berhasil input data menu'
								// 				);
								// 		}
								// 	}
								// break;

								// case 'update':
								// 	if ( ! $postdata['id_menu'])
								// 	{
								// 		$response = array(
								// 				'return' => false,
								// 				'error_message' => $this->msgNullField
								// 			);
								// 	}
								// 	else
								// 	{
								// 		if ( $postdata['gambar'])
								// 		{
								// 			$acceptedMime = array('image/png');

								// 			if ( ! in_array( checkMime($_FILES['gambar']['name']) , $acceptedMime))
								// 			{
								// 				$response = array(
								// 						'return' => false,
								// 						'error_message' => 'File gambar harus berekstensi PNG'
								// 					);
								// 			}
								// 			else
								// 			{
								// 				$dir = FCPATH.'images/'.date('Y-m-d').'/';
								// 				$ext = strtolower(array_pop(explode('.',$_FILES['gambar']['name'])));
								// 				$fileEncrypt = encryptFile($_FILES['gambar']['name']).'.'.$ext;

								// 				if ( ! is_dir($dir))
								// 				{
								// 					@chmod($dir, '-R 777');
								// 					@mkdir($dir);
								// 				}

								// 				$destination = $dir.$fileEncrypt;

								// 				move_uploaded_file($_FILES['gambar']['tmp_name'], $destination);

								// 				$select = $this->db->get_where('m_menu' , 
								// 					array(
								// 							'id' => $postdata['id_menu']
								// 						))->result()[0];

								// 				$dataUpdate = array(
								// 						'nama' => $postdata['nama'] ? $postdata['nama'] : $select->nama,
								// 						'harga' => $postdata['harga'] ? $postdata['harga'] : $select->harga,
								// 						'gambar' => $postdata['gambar'] ? base_url().'images/'.date('Y-m-d').'/'.$fileEncrypt : $select->gambar,
								// 						'kategori' => $postdata['kategori'] ? $postdata['kategori'] : $select->kategori
								// 					);

								// 				$this->db->set($dataUpdate);
								// 				$this->db->where( array('id' => $postdata['id_menu']));
								// 				$this->db->update('m_menu');

								// 				$response = array(
								// 						'return' => true,
								// 						'x' => $_FILES['gambar'],
								// 						'message' => 'Berhasil ubah data menu'
								// 					);
								// 			}
								// 		}
								// 		else
								// 		{
								// 			$select = $this->db->get_where('m_menu' , 
								// 				array(
								// 						'id' => $postdata['id_menu']
								// 					))->result()[0];

								// 			$dataUpdate = array(
								// 					'nama' => $postdata['nama'] ? $postdata['nama'] : $select->nama,
								// 					'harga' => $postdata['harga'] ? $postdata['harga'] : $select->harga,
								// 					'gambar' => $select->gambar,
								// 					'kategori' => $postdata['kategori'] ? $postdata['kategori'] : $select->kategori
								// 				);

								// 			$this->db->set($dataUpdate);
								// 			$this->db->where( array('id' => $postdata['id_menu']));
								// 			$this->db->update('m_menu');

								// 			$response = array(
								// 					'return' => true,
								// 					'x' => $_FILES['gambar'],
								// 					'message' => 'Berhasil ubah data menu'
								// 				);
								// 		}
								// 	}
								// break;

								// case 'delete':
								// 	if ( ! $postdata['id_menu'])
								// 	{
								// 		$response = array(
								// 				'return' => false,
								// 				'error_message' => $this->msgNullField
								// 			);
								// 	}
								// 	else
								// 	{
								// 		$select = $this->db->get_where('m_menu' , array(
								// 				'id' => $postdata['id_menu']
								// 			));

								// 		if ( $select->num_rows() > 0)
								// 		{
								// 			$x = explode('/' , $select->result()[0]->gambar);

								// 			$this->db->delete('m_menu' , array(
								// 					'id' => $postdata['id_menu']
								// 				));

								// 			// $unlinkFile = unlink(FCPATH.'images/'.$x[count($x)-2].'/'.$x[count($x)]-1);

								// 			$response = array(
								// 					'return' => true,
								// 					'message' => 'Berhasil di hapus!'
								// 				);
								// 		}
								// 		else
								// 		{
								// 			$response = array(
								// 					'return' => false,
								// 					'error_message' => 'ID Menu tidak ditemukan!'
								// 				);
								// 		}
								// 	}
								// break;

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
										$sha = generate_key();
										$dataInsert = array(
												'nama' => $postdata['nama'],
												'gambar' => $postdata['gambar'],
												'harga' => $postdata['harga'],
												'kategori' => $postdata['kategori'],
												'sha' => $sha
											);

										$this->db->insert('m_menu' , $dataInsert);

										$queryMenu = $this->db->get_where('m_menu' , array('sha' => $sha))->result()[0];

										$dataStok = array(
												'id_menu' => $queryMenu->id,
												'date_add' => date('Y-m-d H:i:s'),
												'jumlah' => 0
											);

										$this->db->insert('m_stok' , $dataStok);

										$response = array(
												'return' => true,
												'message' => 'Berhasil input data menu'
											);
									}
								break;

								case 'update':
									if ( ! $postdata['sha'])
									{
										$response = array(
												'return' => false,
												'error_message' => $this->msgNullField
											);
									}
									else
									{
										$select = $this->db->get_where('m_menu' , 
											array(
													'sha' => $postdata['sha']
												))->result()[0];

										$newSha = generate_key();
										$dataUpdate = array(
												'nama' => $postdata['nama'] ? $postdata['nama'] : $select->nama,
												'harga' => $postdata['harga'] ? $postdata['harga'] : $select->harga,
												'gambar' => $postdata['gambar'] ? $postdata['gambar'] : $select->gambar,
												'kategori' => $postdata['kategori'] ? $postdata['kategori'] : $select->kategori,
												'sha' => $newSha
											);

										$this->db->set($dataUpdate);
										$this->db->where( array('sha' => $postdata['sha']));
										$this->db->update('m_menu');

										$response = array(
												'return' => true,
												'message' => 'Berhasil ubah data menu',
												'new_sha' => $newSha
											);
									}
								break;

								case 'delete':
									if ( ! $postdata['sha'])
									{
										$response = array(
												'return' => false,
												'error_message' => $this->msgNullField
											);
									}
									else
									{
										$select = $this->db->get_where('m_menu' , array(
												'sha' => $postdata['sha']
											));

										if ( $select->num_rows() > 0)
										{
											$x = explode('/' , $select->result()[0]->gambar);

											$newSha = generate_key();

											$data = array(
													'deleted' => 1,
													'sha' => $newSha
												);

											$this->db->set($data);
											$this->db->where( array('sha' => $postdata['sha']));
											$this->db->update('m_menu');

											// $unlinkFile = unlink(FCPATH.'images/'.$x[count($x)-2].'/'.$x[count($x)]-1);

											$response = array(
													'return' => true,
													'message' => 'Berhasil di hapus!',
													'new_sha' => $newSha
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

								case 'undo':
									if ( ! $postdata['sha'])
									{
										$response = array(
												'return' => false,
												'error_message' => $this->msgNullField
											);
									}
									else
									{
										$select = $this->db->get_where('m_menu' , array(
												'sha' => $postdata['sha']
											));

										if ( $select->num_rows() > 0)
										{
											$x = explode('/' , $select->result()[0]->gambar);

											$data = array(
													'deleted' => 0,
													'sha' => generate_key()
												);

											$this->db->set($data);
											$this->db->where( array('sha' => $postdata['sha']));
											$this->db->update('m_menu');

											// $unlinkFile = unlink(FCPATH.'images/'.$x[count($x)-2].'/'.$x[count($x)]-1);

											$response = array(
													'return' => true,
													'message' => 'Berhasil di undo!'
												);
										}
										else
										{
											$response = array(
													'return' => false,
													'error_message' => 'Sha tidak ditemukan!'
												);
										}
									}
								break;

								case 'add_stok':
									if ( ! $postdata['sha'] || ! $postdata['jumlah'])
									{
										$response = array(
												'return' => false,
												'error_message' => $this->msgNullField
											);
									}
									else
									{
										$sql = "SELECT m_menu.* , m_menu.id AS id_menu , m_stok.* , m_stok.id AS id_stok FROM 
										m_menu , m_stok WHERE m_menu.id = m_stok.id_menu AND m_menu.sha = '".$postdata['sha']."'";

										$query = $this->db->query($sql);

										$num = $query->num_rows();

										if ( $num > 0)
										{
											$stokSekarang = $query->result()[0]->jumlah;

											$total = $stokSekarang + (int) $postdata['jumlah'];

											$data = array(
													'jumlah' => (int) $postdata['jumlah'],
													'date_add' => date('Y-m-d H:i:s')
												);

											$this->db->set($data);
											$this->db->where( array('id_menu' => $query->result()[0]->id_menu));
											$this->db->update('m_stok');

											$this->db->set( array('sha' => generate_key()));
											$this->db->where( array('id' => $query->result()[0]->id_menu));
											$this->db->update('m_menu');
										}

										$response = array(
												'return' => $num > 0 ? true : false,
												$num > 0 ? 'message' : 'error_message' => 
												$num > 0 ? 'Stok telah ditambahkan!' : 'Menu tidak ditemukan!',
											);
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
						$listMethod = array('add_outlet','update_outlet','delete_outlet','undo');
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
										|| ! $postdata['nama_outlet'] ||  ! $postdata['alamat']
										|| ! $postdata['latitude'] || ! $postdata['longitude'] 
											|| ! $postdata['resto'])
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
													'id_resto' => $postdata['resto'],
													'outlet' => $postdata['nama_outlet'],
													'alamat' => $postdata['alamat'],
													'lat' => $postdata['latitude'],
													'long' => $postdata['longitude'],
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
									$authToken = authToken('admin' , $token , true);
									$selectAdmin = $this->db->get_where('m_admin' , array(
											'username' => $postdata['username']
										));

									$result = ($selectAdmin->num_rows() > 0) ? 
												( $authToken[0]['username']== $postdata['username'] ? 
												'continue' : 'username_exist') : 'not_yet';

									if ( $result == 'username_exist')
									{
										$response = array(
												'return' => false,
												'error_message' => 'Username sudah ada!'
											);
									}
									else
									{	
										$query = $this->db->get_where('m_outlet',array(
												'id' => $authToken[0]['outlet']['id_outlet']
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
										$this->db->where( array('id' => $authToken[0]['outlet']['id_outlet']));
										$this->db->update('m_outlet');

										$updateAdmin = array(
												'username' => $postdata['username'],
												'password' => $postdata['password'] ? 
													md5($postdata['password']) : $authToken[0]['password']
											);

										$this->db->set($updateAdmin);
										$this->db->where( array('id_outlet' => $authToken[0]['outlet']['id_outlet']));
										$this->db->update('m_admin');

										$response = array(
												'return' => 'true',
												'message' => 'Berhasil mengubah data outlet!'
											);
									}
								break;

								case 'delete_outlet':
									$id_outlet = $authToken[0]['outlet']['id_outlet'];

									$data = array(
											'deleted' => 1,
											'sha' => generate_key()
										);

									$this->db->set($data);
									$this->db->where( array('id' => $id_outlet));
									$this->db->update('m_outlet');

									$response = array(	
											'return' => true,
											'message' => 'Berhasil menghapus outlet!'
										);
								break;

								case 'undo':
									$id_outlet = $authToken[0]['outlet']['id_outlet'];

									$data = array(
											'deleted' => 0,
											'sha' => generate_key()
										);

									$this->db->set($data);
									$this->db->where( array('id' => $id_outlet));
									$this->db->update('m_outlet');

									$response = array(	
											'return' => true,
											'message' => 'Berhasil mengembalikan outlet!'
										);
								break;
							}
						}
					}
				}
			break;

			case 'user':
				$postdata = $this->post();

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
					elseif( ! $postdata['method'])
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
							case 'delete_user':
								if ( ! $postdata['user_token'])
								{
									$response = array(
											'return' => false,
											'error_message' => $this->msgNullField
										);
								}
								else
								{
									$userToken = authToken('users' , $postdata['user_token']);
									$userdata = $userToken;

									if ( ! $userdata)
									{
										$response = array(
												'return' => false,
												'error_message' => $this->msgWrongToken
											);
									}
									else
									{
										$data = array(
												'blacklist' => 1
											);

										$this->db->set($data);
										$this->db->where( array('key' => $postdata['user_token']));
										$this->db->update('m_user');

										$response = array(
												'return' => true,
												'message' => 'Berhasil menghapus pengguna!'
											);
									}
								}
							break;

							case 'undo':
								if ( ! $postdata['user_token'])
								{
									$response = array(
											'return' => false,
											'error_message' => $this->msgNullField
										);
								}
								else
								{
									$userToken = authToken('users' , $postdata['user_token']);
									$userdata = $userToken;

									if ( ! $userdata)
									{
										$response = array(
												'return' => false,
												'error_message' => $this->msgWrongToken
											);
									}
									else
									{
										$data = array(
												'blacklist' => 0
											);

										$this->db->set($data);
										$this->db->where( array('key' => $postdata['user_token']));
										$this->db->update('m_user');

										$response = array(
												'return' => true,
												'message' => 'Berhasil membatalkan!'
											);
									}
								}
							break;

							default:
								$response = array(
										'return' => false,
										'error_message' => $this->msgWrongMethod
									);	
							break;
						}
					}
				}
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

			case 'profile':
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
						$postdata = $this->post();
						if ( ! $postdata['method'])
						{
							$response = array(
									'return' => false,
									'error_message' => $this->msgNullField
								);
						}
						else
						{
							switch ( trimLower($postdata['method'])) {
								case 'update':
									if ( $authToken[0]['outlet']['id_outlet'] == 0)
									{
										$data = array(
												'username' => $postdata['username'] ? 
													$postdata['username'] : $authToken[0]['username'],
												'password' => md5($postdata['password'])
											);

										$this->db->set($data);
										$this->db->where( array('key' => $token));
										$this->db->update('m_admin');

										$response = array(
												'return' => true,
												'message' => 'Berhasil merubah!'
											);
										}
									else
									{
										$response = array(
												'return' => false,
												'error_message' => 'You\'re not authorize in here!'
											);
									}
								break;

								default:	
									$response = array(
										'return' => false,
										'error_message' => $this->msgWrongMethod
									);
								break;
							}
						}
					}
				}
			break;

			case 'banner':
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
								'nama' => $this->post('nama'),
								'gambar' => $this->post('gambar'),
								'keterangan' => $this->post('keterangan'),
								'posisi' => $this->post('posisi'),
								'link_banner' => $this->post('link_banner'),
								'added_by' => $this->post('added_by'),
								'modified_by' => $this->post('modified_by'),
								'sha' => $this->post('sha')
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
							switch($postdata['method'])
							{
								case 'add_banner':
									if ( ! $postdata['nama'] || ! $postdata['gambar']
											|| ! $postdata['keterangan']
											|| ! $postdata['link_banner'] || ! $postdata['added_by'])
									{
										$response = array(
												'return' => false,
												'error_message' => $this->msgNullField
											);
									}
									else
									{
										$banner = $this->db->get_where('t_banner')->num_rows();

										$query = $this->db->get_where('t_banner' , array(
												'position' => $postdata['posisi'] , 'deleted' => 0 
											));

										$num = $query->num_rows();

										$count = $banner + 1;
										$dataInsert = array(
												'position' => (int) $count,
												'nama' => $postdata['nama'],
												'keterangan' => $postdata['keterangan'],
												'gambar' => $postdata['gambar'],
												'link' => $postdata['link_banner'],
												'sha' => generate_key(),
												'added_by' => $postdata['added_by'],
												'added_datetime' => date('Y-m-d H:i:s'),
												'modified_by' => 'nothing',
												'modified_datetime' => date('Y-m-d H:i:s')
 											);

										if ( $num == 0 )
										{
											$this->db->insert('t_banner' , $dataInsert);
										}

										$response = array(
												'return' => $num == 0 ? true : false,
												$num == 0 ? 'message' : 'error_message' =>
												$num == 0 ? 'Berhasil menginput data banner!' : 
													($num > 0 ? 'Posisi banner sudah ada' : 'Gagal menginput data banner')
											);
									}
								break;

								case 'update_banner':
									if ( ! $postdata['sha'] || ! $postdata['modified_by'])
									{
										$response = array(
												'return' => false,
												'error_message' => $this->msgNullField
											);
									}
									else
									{
										$selectBanner = $this->db->get_where('t_banner' , array(
												'sha' => $postdata['sha']
											));

										$num = $selectBanner->num_rows();

										if ( $num > 0 )
										{
											// $result == true ? continue : break;
											// $result = null;

											// cek posisi banner
											// if ( $postdata['posisi'])
											// {
											// 	$query = $this->db->get_where('t_banner' , array(
											// 			'position' => $postdata['posisi'] , 'deleted' => 0
											// 		));

											// 	$posisi = $query->num_rows();

											// 	// $posisi > 0 ? ada : tidak ada
											// 	$result = $posisi > 0 ? false : true;										
											// }

											// if ( $result )
											// {
											$row = $selectBanner->result()[0];

											$data = array(
												'position' => $postdata['posisi'] ? 
													$postdata['posisi'] : $row->position,
												'nama' => $postdata['nama'] ?
													$postdata['nama'] : $row->nama,
												'keterangan' => $postdata['keterangan'] ? 
													$postdata['keterangan'] : $row->keterangan,
												'gambar' => $postdata['gambar'] ?
													$postdata['gambar'] : $row->gambar,
												'link' => $postdata['link_banner'] ?
													$postdata['link_banner'] : $row->link,
												'sha' => generate_key(),
												'modified_by' => $postdata['modified_by'],
												'modified_datetime' => date('Y-m-d H:i:s')
											);

											$this->db->set($data);
											$this->db->where( array('sha' => $postdata['sha']));
											$this->db->update('t_banner');

											$response = array( 
													'return' => true,
													'message' => 'Berhasil mengubah banner!'
												);
											// }
											// else
											// {
											// 	$response = array(
											// 			'return' => false,
											// 			'error_message' => "Posisi banner sudah ada!"
											// 		);
											// }
										}
										else
										{
											$response = array(
													'return' => false,
													'error_message' => "SHA Tidak ditemukan"
												);
										}
									}
								break;

								case 'delete_banner':
									if ( ! $postdata['sha'])
									{
										$response = array(
												'return' => false,
												'error_message' => $this->msgNullField
											);
									}
									else
									{
										$query = $this->db->get_where('t_banner' , array(
												'sha' => $postdata['sha']
											));

										// > 0 ? ada : gada
										$num = $query->num_rows();

										if ( $num > 0)
										{
											// $dataUpdate = array(
											// 		'deleted' => 1
											// 	);
											// $this->db->set($dataUpdate);
											// $this->db->where( array('sha' => $query->result()[0]->sha));
											// $this->db->update('t_banner');
											$this->db->delete('t_banner', array('sha' => $postdata['sha']));
										}

										$response = array(
												'return' => $num > 0 ? true : false,
												$num > 0 ? 'message' : 'error_message' =>
												$num > 0 ? 'Berhasil mengapus banner!' : 'SHA tidak ditemukan!'
											);
									}
								break;

								case 'undo':
									if ( ! $postdata['sha'])
									{
										$response = array(
												'return' => false,
												'error_message' => $this->msgNullField
											);
									}
									else
									{
										$query = $this->db->get_where('t_banner' , array(
												'sha' => $postdata['sha']
											));

										// > 0 ? ada : gada
										$num = $query->num_rows();

										if ( $num > 0)
										{
											$dataUpdate = array(
													'deleted' => 0
												);
											$this->db->set($dataUpdate);
											$this->db->where( array('sha' => $query->result()[0]->sha));
											$this->db->update('t_banner');
										}

										$response = array(
												'return' => $num > 0 ? true : false,
												$num > 0 ? 'message' : 'error_message' =>
												$num > 0 ? 'Berhasil membatalkan!' : 'SHA tidak ditemukan!'
											);
									}
								break;

								default:
									$response = array(
											'return' => false,
											'error_message' => $this->msgWrongMethod
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