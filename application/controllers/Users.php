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
							$query = $this->db
							->from('m_menu')
							->where( array('deleted' => 0))
							->order_by('id DESC')
							->get();

							$response = array(
									'return' => ($query->num_rows() > 0) ? true: false,
									($query->num_rows() > 0) ? 'data' : 'error_message' => ($query->num_rows() > 0) 
									? $query->result() : 'Data menu kosong'
								);
						break;
						
						case 'order':
							$dataUser = $authToken;
							$queryOrder = $this->db->from('m_order')
										->where( array('id_user' => $dataUser['id']))
										->order_by('tanggal_waktu DESC')
										->get();

							$dataOrder = array();

							foreach($queryOrder->result() as $row)
							{
								$total_belanja = 0;

								if ( $row->id_kurir != 0 || $row->id_kurir != null)
								{
									$kurir = $this->db->get_where('m_kurir' ,
										array('id' => $row->id_kurir))
									->result();

									foreach($kurir as $kurirdata)
									{
										$tmpkurir[] = array(
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

										$tmpoutlet[] = array(
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
										'id_user' => $dataUser['id'],
										'outlet' => ($row->id_outlet != 0) ? $tmpoutlet : 'nothing',
										'kurir' => ( $row->id_kurir != 0 ) ? $tmpkurir : 'nothing',
										'nama_user' => $dataUser['nama'],
										'email' => $dataUser['email'],
										'alamat_order' => $row->alamat,
										'lat_order' => $row->latitude,
										'long_order' => $row->longitude,
										'delivery_fee' => $row->delivery_fee,
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

									$rating = 0;

									foreach( $query->result() as $row)
									{
										$rowMenu = ( $row->id_menu != 0);
										$rowUser = ( $row->id_user != 0);
										$rowOutlet = ( $row->id_outlet != 0);
										$rowKurir = ( $row->id_kurir != 0);

										$rating += $row->rating;

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
											'total_rating' => $num > 0 ? (float) $rating / count($data) : 'nothing',
											$num > 0 ? 'data' : 'error_message' => 
											$num > 0 ? $data : 'Data tidak ditemukan!'
										);
								}
							}
						break;

						case 'search':
							$id_order = $this->get('id_order');

							if ( ! $id_order)
							{
								$response = array(
										'return' => false,
										'error_message' => $this->msgErrorParameter
									);
							}
							else
							{
								$dataUser = $authToken;
								$queryOrder = $this->db->from('m_order')
											->where( array('id' => $id_order))
											->order_by('tanggal_waktu DESC')
											->get();

								$dataOrder = array();

								foreach($queryOrder->result() as $row)
								{
									$total_belanja = 0;

									if ( $row->id_kurir != 0 || $row->id_kurir != null)
									{
										$kurir = $this->db->get_where('m_kurir' ,
											array('id' => $row->id_kurir))
										->result();

										foreach($kurir as $kurirdata)
										{
											$tmpkurir[] = array(
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

											$tmpoutlet[] = array(
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
											'id_user' => $dataUser['id'],
											'outlet' => ($row->id_outlet != 0) ? $tmpoutlet : 'nothing',
											'kurir' => ( $row->id_kurir != 0 ) ? $tmpkurir : 'nothing',
											'nama_user' => $dataUser['nama'],
											'email' => $dataUser['email'],
											'alamat_order' => $row->alamat,
											'lat_order' => $row->latitude,
											'long_order' => $row->longitude,
											'delivery_fee' => $row->delivery_fee,
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
						array('email' => $postdata['email'] , 'password' => md5($postdata['password']) , 
							'blacklist' => 0));

					if ( $authLogin->num_rows() > 0)
					{
						$response = array(
							'return' => true,
							'message' => 'Berhasil login',
							'nama' => $authLogin->row()->nama,
							'email' => $authLogin->row()->email,
							'access_token' => $authLogin->row()->key
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

			case 'rating':
				if ( ! $token)
				{
					$response = array(
							'return' => false,
							'error_message' => $this->msgErrorToken
						);
				}
				else
				{
					$user = $authToken;
					if ( $authToken)
					{
						$postdata = array(
								'id_user' => $this->post('id_user'),
								'method' => $this->post('method'),
								'id_menu' => $this->post('id_menu'),
								'id_outlet' => $this->post('id_outlet'),
								'id_kurir' => $this->post('id_kurir'),
								'rating' => $this->post('rating'),
								'keterangan' => $this->post('keterangan')
							);

						if ( ! $postdata['id_user'] || ! $postdata['method'])
						{
							$response = array(
									'return' => false,
									'error_message' => $this->msgNullField
								);
						}
						else
						{
							$list_method = array('rating_outlet','rating_menu','rating_kurir');

							if ( ! in_array($postdata['method'],$list_method))
							{
								$response = array(
										'return' => false,
										'error_message' => $this->msgWrongMethod
									);
							}
							else
							{
								$tipe = null;
								switch ( trimLower($postdata['method'])) {
									case 'rating_outlet':
										if ( ! $postdata['id_outlet'] 
											|| ! $postdata['rating'] || ! $postdata['keterangan'])
										{
											$response = array(
													'return' => false,
													'error_message' => $this->msgNullField
												);
										}
										else
										{
											$tipe = 'OUTLET';

											$data = array(
													'id_user' => $postdata['id_user'],
													'id_outlet' => $postdata['id_outlet'],
													'tipe' => $tipe,
													'rating' => $postdata['rating'],
													'keterangan' => $postdata['keterangan'],
													'datetime' => date('Y-m-d H:i:s')
												);

											$this->db->insert('t_rating', $data);

											$response = array(
													'return' => true,
													'message' => 'Berhasil memberi rating untuk Outlet'
												);
										}
									break;
									
									case 'rating_kurir':
										if ( ! $postdata['id_kurir'] 
											|| ! $postdata['rating'] || ! $postdata['keterangan'])
										{
											$response = array(
													'return' => false,
													'error_message' => $this->msgNullField
												);
										}
										else
										{
											$tipe = 'KURIR';

											$data = array(
													'id_user' => $postdata['id_user'],
													'id_kurir' => $postdata['id_kurir'],
													'tipe' => $tipe,
													'rating' => $postdata['rating'],
													'keterangan' => $postdata['keterangan'],
													'datetime' => date('Y-m-d H:i:s')
												);

											$this->db->insert('t_rating', $data);

											$response = array(
													'return' => true,
													'message' => 'Berhasil memberi rating untuk Kurir'
												);
										}
									break;

									case 'rating_menu':
										if ( ! $postdata['id_menu'] 
											|| ! $postdata['rating'] || ! $postdata['keterangan'])
										{
											$response = array(
													'return' => false,
													'error_message' => $this->msgNullField
												);
										}
										else
										{
											$tipe = 'MENU';

											$data = array(
													'id_user' => $postdata['id_user'],
													'id_menu' => $postdata['id_menu'],
													'tipe' => $tipe,
													'rating' => $postdata['rating'],
													'keterangan' => $postdata['keterangan'],
													'datetime' => date('Y-m-d H:i:s')
												);

											$this->db->insert('t_rating', $data);

											$response = array(
													'return' => true,
													'message' => 'Berhasil memberi rating untuk Menu'
												);
										}
									break;
								}
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
					$user = $authToken;
					if ( $authToken)
					{
						$postdata = array(
								'nama' => $this->post('nama'),
								'lokasi' => $this->post('lokasi'),
								'alamat' => $this->post('alamat') 
							);

						$data = array(
								'nama' => ( ! $postdata['nama']) ? $user['nama'] : $postdata['nama'],
								'location' => ( ! $postdata['lokasi']) ? $user['location'] : $postdata['lokasi'],
								'alamat' => ( ! $postdata['alamat']) ? $user['alamat'] : $postdata['alamat']
							);

						$this->db->set($data);
						$this->db->where( array(
								'id' => $user['id']
							));
						$this->db->update('m_user' , $data);

						$response = array(
								'return' => true,
								'message' => 'Data berhasil diubah!'
							);
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

			case 'order':
				if ( ! $token )
				{
					$response = array(
							'return' => false,
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
							'jumlah' => $this->post('jumlah'),
							'alamat' => $this->post('alamat'),
							'latitude' => $this->post('latitude'),
							'longitude' => $this->post('longitude'),
							'delivery_fee' => $this->post('delivery_fee'),
							'keterangan' => $this->post('keterangan'),
							'id_outlet' => $this->post('id_outlet')
						);

						$this->isNullField = array(
								'return' => false,
								'error_message' => $this->msgNullField
							);
					
						$acceptedMethod = array('add_item', 'new_order' , 'done');

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
								case 'add_item' :
									if ( ! $postdata['id_menu'] || ! $postdata['jumlah'])
									{
										$response = $this->isNullField;
									}
									else
									{
										/* Master Order */
										$dataMaster = array(
												'id' => $postdata['id_order'],
												'status' => 1
											);
										$selectMaster = $this->db->get_where('m_order', $dataMaster);
										if ($selectMaster->num_rows() > 0) {
											/* Tabel Menu */	
											$selectMenu = $this->db->get_where('m_menu' , array(
													'id' => $postdata['id_menu']
												))->result()[0];
											/* Tabel Menu */
											$dataOrder = array(
													'id_order' => $postdata['id_order'],
													'id_menu' => $postdata['id_menu'],
													'jumlah' => $postdata['jumlah'],
													'harga' =>	$selectMenu->harga,
													'total_harga' => $selectMenu->harga * $postdata['jumlah'],
													'keterangan' => ( $postdata['keterangan']) ? trim($postdata['keterangan']) : 'nothing'
												);											
											$insertTOrder = $this->db->insert('t_order' , $dataOrder);
											if ($insertTOrder) {
												$response = array(
														'return' => true,
														'message' => 'Item Order Berhasil Ditambahkan!'
													);
											}else{												
												$response = array(
														'return' => false,
														'message' => 'Item Order Gagal Ditambahkan!'
													);
											}
										}else{
											/*data master tidak ditemukan*/
											$response = array(
													'return' => false,
													'message' => 'Master Order Tidak Ditemukan!'
												);
										}
									}			
								break;

								case 'new_order':
										$generate_id = generate_id();
										$ternaryId = ( ! $postdata['id_order']) 
													? $generate_id : $postdata['id_order'];

										if ( ! $postdata['alamat'] || ! $postdata['delivery_fee'] || ! $postdata['latitude'] || ! $postdata['longitude'] || ! $postdata['id_outlet'])
										{
											$response = $this->isNullField;
										}
										else
										{
											/* Master Order */
											$dataMaster = array(
													// 'id' => $ternaryId,
													'id_user' => $user['id'],
													'id_kurir' => 0,
													'id_outlet' => $postdata['id_outlet'],
													'alamat' => $postdata['alamat'],
													'latitude' => $postdata['latitude'],
													'longitude' => $postdata['longitude'],
													'tanggal_waktu' => date('Y-m-d H:i:s'),
													'status' => 1,
													'keterangan' => ( $postdata['keterangan']) ? trim($postdata['keterangan']) : 'nothing',
													'delivery_fee' => $postdata['delivery_fee'],
													'sha' => generate_key()
												);

											$this->db->insert('m_order' , $dataMaster);
											/* Master Order */
											$selectMaster = $this->db->get_where('m_order', $dataMaster);
											$response = array(
													'return' => true,
													'message' => 'Berhasil input order!',
													'data'	=> $selectMaster->row()->id,
													'result' => $selectMaster->result()
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
												'status' => 5,
												'sha' => generate_key()
											);

										$checknum = $this->db->get_where('m_order' , array(
												'id' => $postdata['id_order']
											));

										$num = $checknum->num_rows();

										if ( $num > 0)
										{
											$this->db->set($data);
											$this->db->where( 
												array('id' => $postdata['id_order'], 'id_user' => $user['id']));
											$this->db->update('m_order');
										}

										$response = array(
												'return' => ($num > 0) ? true : false,
												($num > 0) ? 'message' : 'error_message' => 
												($num > 0) ? 'Status order berhasil diubah!'
												: 'ID Order tidak ditemukan!'
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