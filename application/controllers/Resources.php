<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Resources extends REST_Controller {

	public function __construct()
	{
		parent::__construct();
	}


	public function index_get()
	{
		$response = array(
				'result' => false,
				'error_message' => 'Parameter tidak ditemukan'
			);

		return $this->response($response);
	}

}

/* End of file Resources.php */
/* Location: ./application/controllers/Resources.php */