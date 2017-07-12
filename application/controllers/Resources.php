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


	public function index_get($action = '')
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