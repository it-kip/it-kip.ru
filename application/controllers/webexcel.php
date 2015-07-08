<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class webexcel extends CI_Controller {
    
    function index(){
        $this->js[] = 'main';
        $this->js[] = 'WebExcel';
        $this->content = $this->load->view('vw_webexcel', null, true);
    }
}