<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class install extends CI_Controller {
    
    function index(){
        $this->load->model('model_kolap_alpha', 'model_kolap');
        
    }
}