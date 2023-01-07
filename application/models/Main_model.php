<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
		// $this->load->library("mpdf60/mpdf");
    }

	public function line_notify_model()
	{


        // $access_token = '9PcsALwysGeWlDrzVMk12kancDFQM0GXhRLofFiGCHg';
        $access_token = $_POST['line_token'];
        $message = $_POST['washing_machine'].' less 1 minute';
        
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => "Authorization: Bearer $access_token\r\n" .
                            "Content-Type: application/x-www-form-urlencoded",
                'content' => http_build_query([
                    'message' => $message,
                ]),
            ],
        ];
        
        $context = stream_context_create($options);
        $result = file_get_contents('https://notify-api.line.me/api/notify', false, $context);
        
        echo $result;
        
    

	}
}		