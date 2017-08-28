<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Outlet extends REST_Controller {

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
						case 'order':
							if ( ! $this->get('order_id'))
							{
								$queryOrder = $this->db->from('m_order')
											->where( array('id_outlet' => $authToken[0]['outlet']['id_outlet']))
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
								if ( $row->status == 7)
								{
									continue;
								}

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

							$response = array(
									'return' => ($query->num_rows() > 0) ? true: false,
									($query->num_rows() > 0) ? 'data' : 'error_message' => ($query->num_rows() > 0) 
									? $query->result() : 'Data menu kosong'
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

/* End of file Outlet.php */
/* Location: ./application/controllers/Outlet.php */