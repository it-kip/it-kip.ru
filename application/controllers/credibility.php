<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class credibility extends CI_Controller {
    
	function index(){
		$this->content = $this->load->view('vw_credibility', array(), true);
	}

}