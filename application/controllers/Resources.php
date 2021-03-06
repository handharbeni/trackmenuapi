<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Resources extends REST_Controller {

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


	public function index_get($action = '' , $option = '')
	{
		if ( $action != null)
		{
			switch( trimLower($action))
			{
				case 'tools_value':
					if ( ! $this->get('access'))
					{
						$response = array(
							'return' => false,
							'error_message' => $this->msgErrorParameter
						);
					}
					else
					{
						$query = $this->db->from('tools_value');

						if ( $this->get('key'))
						{
							$this->key = $this->get('key');
							$query->where( array(
									'key' => $this->key
								));
						}

						$response = array(
								'return' => true,
								'data' => $query->get()->result()
							);		
					}
				break;

				case 'feature':
					if ( ! $this->get('access') || ! $this->get('type'))
					{
						$response = array(
							'return' => false,
							'error_message' => $this->msgErrorParameter
						);
					}
					else
					{
						switch( trimLower($this->get('type')))
						{
							case 'hot-order':
								$response = array(
									'return' => true,
									'message' => 'Coming soon feature'
								);
							break;

							case 'banner':
								if ( $this->get('sha'))
								{
									$query = $this->db
									->from('t_banner')
									->where( array('sha' => $this->get('sha')))
									->get();
								}
								else
								{
									$query = $this->db
									->from('t_banner')
									->where( array('deleted' => 0))
									->order_by('position DESC')
									->get();
								}

								$num = $query->num_rows();

								$data = null;

								foreach($query->result() as $row)
								{
									$data[] = array(
											'id' => $row->id,
											'posisi' => $row->position,
											'nama' => $row->nama,
											'keterangan' => $row->keterangan,
											'gambar' => $row->gambar,
											'link' => $row->link,
											'ditambahkan' => array(
													'oleh' => $row->added_by,
													'tanggal_waktu' => $row->added_datetime,
													'timestamp' => strtotime($row->added_datetime)
												),
											'diubah' => array(
													'oleh' => $row->modified_by,
													'tanggal_waktu' => $row->modified_datetime,
													'timestamp' => strtotime($row->modified_datetime)
												),
											'sha' => $row->sha,
										);
								}

								$response = array(
										'return' => $num > 0 ? true : false,
										$num > 0 ? 'data' : 'error_message' => 
										$num > 0 ? $data : 'Data banner masih kosong!'
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
				break;

				case 'list':
					if ( ! $this->get('access') || ! $this->get('type'))
					{
						$response = array(
							'return' => false,
							'error_message' => $this->msgErrorParameter
						);
					}
					else
					{
						switch( trimLower($this->get('type')))
						{
							case 'outlet':
								$sql = "SELECT m_outlet.id AS id_outlet, m_outlet.* , m_resto.* FROM m_resto , m_outlet 
								WHERE m_resto.id = m_outlet.id_resto AND m_outlet.deleted = 0 ORDER BY m_outlet.tanggal_waktu DESC";
								$query = $this->db->query($sql);

								$data = null;

								foreach($query->result() as $row)
								{
									$token = $this->db->get_where('m_admin',
										array('id_outlet' => $row->id_outlet))
										->result()[0];

									$data[] = array(
											'id_outlet' => $row->id_outlet,
											'restaurant' => array(
													'id_resto' => $row->id_resto,
													'nama_resto' => $row->resto,
												),
											'nama_outlet' => $row->outlet,
											'alamat' => $row->alamat,
											'latitude' => $row->lat,
											'longitude' => $row->long,
											'tanggal_waktu' => $row->tanggal_waktu,
											'token' => $token->key,
											'sha' => $row->sha,
										);
								}
								$response = array(
										'return' => true,
										'data' => $data
									);
							break;

							case 'user':
								$query = $this->db
								->from('m_user')
								->where( array('blacklist' => 0))
								->order_by('tanggal_buat DESC')
								->get();

								$data = null;

								foreach($query->result() as $row)
								{
									$data[] = array(
											'id_user' => $row->id,
											'nama' => $row->nama,
											'email' => $row->email,
											'no_hp' => $row->no_hp,
											'alamat' => $row->alamat,
											'location' => $row->location,
											'key' => $row->key,
											'tanggal_buat' => $row->tanggal_buat
										);
								}

								$response = array(
										'return' => true,
										'data' => $data
									);
							break;

							case 'admin':
								$query = $this->db
								->from('m_admin')
								->order_by('tanggal DESC')
								->get();

								$data = null;

								foreach($query->result() as $row)
								{
									$sql = "SELECT m_outlet.id AS id_outlet, m_outlet.* , m_resto.* FROM m_resto , m_outlet 
									WHERE m_resto.id = m_outlet.id_resto AND m_outlet.id = ".$row->id_outlet." ORDER BY m_outlet.tanggal_waktu DESC";
									$queryoutlet = $this->db->query($sql);

									$outletdata = null;

									foreach($queryoutlet->result() as $x)
									{
										$outletdata = array(
												'id_outlet' => $x->id_outlet,
												'restaurant' => array(
														'id_resto' => $x->id_resto,
														'nama_resto' => $x->resto,
													),
												'nama_outlet' => $x->outlet,
												'alamat' => $x->alamat,
												'latitude' => $x->lat,
												'longitude' => $x->long,
												'tanggal_waktu' => $x->tanggal_waktu,
												'sha' => $x->sha,
											);
									}

									$outlet = ($row->id_outlet == 0) ? "SuperUser" : $outletdata;

									$data[] = array(
											'id_admin' => $row->id,
											'outlet' => $outlet,
											'username' => $row->username,
											'token' => $row->key,
											'tanggal' => $row->tanggal 
										);
								}

								$response = array(
										'return' => true,
										'data' => $data
									);
							break;

							case 'kurir':
								if ( ! $this->get('key'))
								{
									$query = $this->db
									->from('m_kurir')
									->where( array('deleted' => 0))
									->order_by('tanggal DESC')
									->get();
								}
								else
								{
									$query = $this->db
									->from('m_kurir')
									->where( array('deleted' => 0 , 'key' => $this->get('key')))
									->order_by('tanggal DESC')
									->get();
								}

								$data = null;
								foreach($query->result() as $row)
								{
									$data[] = array(
											'id' => $row->id,
											'nama' => $row->nama,
											'username' => $row->username,
											'foto_profil' => $row->foto_profil,
											'no_hp' => $row->no_hp,
											'no_plat' => $row->no_plat,
											'key' => $row->key,
											'tanggal' => $row->tanggal
										);
								}

								$response = array(
										'return' => true,
										'data' => $data
									);
							break;

							case 'resto':
								$query = $this->db->get('m_resto');

								$response = array(
										'return' => true,
										'data' => $query->result()
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
					'return' => false,
					'error_message' => $this->msgErrorParameter
				);
		}

		return $this->response($response);
	}

}

/* End of file Resources.php */
/* Location: ./application/controllers/Resources.php */