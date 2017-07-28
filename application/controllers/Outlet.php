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
		$authToken = authToken('admin' , $token);

		if ( $token)
		{
			if ( $authToken)
			{
				if ( ! empty($action))
				{
					switch( trimLower($action))
					{
						// case 'order':
						// 	$x = $authToken[0];
						// 	$queryOrder = $this->db->from('m_order')
						// 				->where( array('id_outlet' => $x['outlet']['id_outlet']))
						// 				->order_by('tanggal_waktu DESC')
						// 				->get();

						// 	$dataOrder = array();

						// 	foreach($queryOrder->result() as $row)
						// 	{
						// 		$total_belanja = 0;

						// 		if ( $row->id_kurir != 0 || $row->id_kurir != null)
						// 		{
						// 			$kurir = $this->db->get_where('m_kurir' ,
						// 				array('id' => $row->id_kurir))
						// 			->result();

						// 			foreach($kurir as $kurirdata)
						// 			{
						// 				$tmpkurir[] = array(
						// 						'id' => $kurirdata->id,
						// 						'nama' => $kurirdata->nama,
						// 						'foto_profil' => $kurirdata->foto_profil,
						// 						'no_hp' => $kurirdata->no_hp,
						// 						'no_plat' => $kurirdata->no_plat
						// 					);
						// 			}
						// 		}

						// 		if ( $row->id_outlet != 0 || $row->id_outlet != null)
						// 		{
						// 			$outlet = $this->db->get_where('m_outlet' ,
						// 				array('id' => $row->id_outlet))
						// 			->result();

						// 			foreach($outlet as $outletdata)
						// 			{
						// 				$resto = $this->db->get_where('m_resto' ,
						// 					array('id' => $outletdata->id_resto))
						// 				->result()[0];

						// 				$tmpoutlet[] = array(
						// 						'id' => $outletdata->id,
						// 						'resto' => array(
						// 								'id_resto' => $resto->id,
						// 								'nama_resto' => $resto->resto
						// 							),
						// 						'outlet' => $outletdata->outlet,
						// 						'alamat' => $outletdata->alamat,
						// 						'latitude' => $outletdata->lat,
						// 						'longitude' => $outletdata->long,
						// 						'tanggal_waktu' => $outletdata->tanggal_waktu,
						// 						'sha' => $outletdata->sha
						// 					);
						// 			}
						// 		}

						// 		$items = $this->db->get_where('t_order' , 
						// 			array('id_order' => $row->id));

						// 		$tmpitems = null;

						// 		foreach($items->result() as $menudata)
						// 		{
						// 			$menu = $this->db->get_where('m_menu' , 
						// 				array('id' => $menudata->id_menu))
						// 				->result()[0];

						// 			$total_belanja += $menudata->total_harga;

						// 			$tmpitems[] = array(
						// 					'id' => $menudata->id,
						// 					'id_order' => $menudata->id_order,
						// 					'menu' => array(
						// 							'id_menu' => $menu->id,
						// 							'nama_menu' => $menu->nama,
						// 							'gambar' => $menu->gambar,
						// 							'sha' => $menu->sha
						// 						),
						// 					'jumlah' => $menudata->jumlah,
						// 					'harga' => $menudata->harga,
						// 					'total_harga' => $menudata->total_harga,
						// 					'keterangan' => $menudata->keterangan
						// 				);
						// 		}

						// 		$x = explode(" " , $row->tanggal_waktu);

						// 		$dataOrder[] = array(
						// 				'id_order' => $row->id,
						// 				'outlet' => ($row->id_outlet != 0) ? $tmpoutlet : 'nothing',
						// 				'kurir' => ( $row->id_kurir != 0 ) ? $tmpkurir : 'nothing',
						// 				'total_belanja' => $total_belanja,
						// 				'tanggal' => $x[0],
						// 				'jam' => $x[1],
						// 				'status' => array('key' => $row->status , 'value' => $this->statusMessage[$row->status]), 
						// 				'sha' => $row->sha,
						// 				'items' => $tmpitems
						// 			);
						// 	}

						// 	$response = array(
						// 			'return' => true,
						// 			'data' => $dataOrder
						// 		);
						// break;

						case 'order':
							$queryOrder = $this->db->from('m_order')
										->order_by('tanggal_waktu DESC')
										->get();

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

}

/* End of file Outlet.php */
/* Location: ./application/controllers/Outlet.php */