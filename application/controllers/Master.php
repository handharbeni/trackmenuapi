<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Master extends REST_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index_get($option = '')
	{
		$accesstoken = $this->get('token');

		switch( trimLower($option))
		{
			case 'user':
				$role = trimLower($this->get('role'));

				switch($role)
				{
					/*
					GET Admin
					/master/user?role=admin&token={access_token}
					*/
					case 'admin':
						$query = $this->db
						->select( array('nama','username','key','tanggal'))
						->from('m_admin')
						->get();

						$response = array(
								'result' => true,
								'data' => $query->result()
							);
					break;

					/*
					GET Kurir
					/master/user?role=kurir&token={access_token}
					*/
					case 'kurir':
						$query = $this->db
						->select( array('nama','username','key','tanggal'))
						->from('m_kurir')
						->get();

						$response = array(
								'result' => true,
								'data' => $query->result()
							);
					break;

					/*
					GET User
					/master/user?role=pengguna&token={access_token}
					*/
					case 'pengguna':
						$query = $this->db
						->select( array('nama','email','key','tanggal_buat'))
						->from('m_user')
						->get();

						$response = array(
								'result' => true,
								'data' => $query->result()
							);;	
					break;

					default:
						$response = array(
								'return' => false,
								'error_message' => 'Metode tidak ditemukan!'
							);
					break;
				}
			break;

			case 'order':
				$query = $this->db->get_where('m_user' , array(
						'key' => $accesstoken
					));

				if($query->num_rows() > 0)
				{
					$order = $this->db->get_where('m_order' , array('id_user' => $query->result()[0]->id));

					if ( $order->num_rows() != 0)
					{
						$orderdata = array();
						foreach($order->result() as $data)
						{
							$kurir = $this->db
							->get_where('m_kurir' , array('id' => $data->id_kurir))
							->result()[0];

							$status_order = array(
									1 => 'New Order',
									2 => 'Accept by Kurir',
									3 => 'Procesing Delivery',
									4 => 'Delivery Completed',
									5 => 'Cancel'
								);
 							
 							$item = $this->db->get_where('t_order' , array('t_order.id_order' => $data->id));

 							$items = array();
 							foreach($item->result() as $row)
 							{
 								$menu = $this->db->get_where('m_menu' , array('m_menu.id' => $row->id_menu))
 								->result()[0];

 								$items[] = array(
 										'nama_menu' => $menu->nama,
 										'jumlah' => $row->jumlah,
 										'harga' => $row->harga,
 										'total_harga' => $row->total_harga
 									);
 							}

							$orderdata[] = array(
									'id_order' => $data->id,
									'nama_kurir' => $kurir->nama,
									'tanggal' => $data->tanggal,
									'status_order' => $status_order[$data->status],
									'items' => $items
								);
						}

						$response = array(
							'return' => true,
							'data' => $orderdata
						);
					}
					else
					{
						$response = array(
							'return' => false,
							'error_message' => 'Data order masih kosong!'
						);
					}
				}
				else
				{
					$response = array(
							'return' => false,
							'error_message' => 'Data user tidak ditemukan!'
						);
				}
			break;

			case 'tracking':
				$id_kurir = $this->get('kurir');

				if ( ! $id_kurir)
				{
					$response = array(
							'return' => false,
							'error_message' => 'Parameter kurir tidak ditemukan!'
						);
				}
				else
				{
					$kurir = $this->db->get_where('m_kurir' , array('id' => $id_kurir));

					if ( $kurir->num_rows() != 0)
					{
						$track = $this->db->get_where('t_tracking' , array('id_kurir' => $kurir->result()[0]->id));

						if ( $track->num_rows() != 0)
						{
							$result = array(
									'nama_kurir' => $kurir->result()[0]->nama,
									'latitude' => $track->result()[0]->latitude,
									'longitude' => $track->result()[0]->longitude
								);

							$response = array(
									'return' => true,
									'data' => $result
								);
						}
						else
						{
							$response = array(
									'return' => false,
									'error_message' => 'Data tracking kurir tidak ada!'
								);
						}
					}
					else
					{
						$response = array(
								'return' => false,
								'data' => 'Kurir tidak ditemukan!'
							);	
					}
				}
			break;

			default:
				$response = array(
						'return' => false,
						'error_message' => 'Metode tidak ditemukan!'
					);
			break;
		}

		$this->response($response);
	}
}

/* End of file Resources.php */
/* Location: ./application/controllers/Master.php */