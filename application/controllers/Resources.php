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
								$response = array(
									'return' => true,
									'message' => 'Coming soon feature'
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
								$sql = "SELECT m_outlet.* , m_resto.* FROM m_resto , m_outlet 
								WHERE m_resto.id = m_outlet.id_resto ORDER BY m_outlet.tanggal_waktu DESC";
								$query = $this->db->query($sql);

								$data = null;

								foreach($query->result() as $row)
								{
									$data[] = array(
											'id_outlet' => $row->id,
											'restaurant' => array(
													'id_resto' => $row->id_resto,
													'nama_resto' => $row->resto,
												),
											'nama_outlet' => $row->outlet,
											'alamat' => $row->alamat,
											'latitude' => $row->lat,
											'longitude' => $row->long,
											'tanggal_waktu' => $row->tanggal_waktu,
											'sha' => $row->sha
										);
								}
								$response = array(
										'return' => true,
										'data' => $data
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