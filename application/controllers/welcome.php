<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {
    
    public function index(){
        $this->content = $this->load->view('welcome_message', null, true);
    }
}