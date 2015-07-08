<?php

class work extends CI_Controller{
    
    function __construct(){
        parent::__construct();
        $this->load->model('model_work');
    }
    
    public function index(){
        $this->title = "KOLAP";
        $this->content = $this->load->view('vw_welcome', null, true);
    }
    
    function create_dir(){
        if($this->input->post("name")){
            $fields = ($this->input->post("fields"))?$this->input->post("fields"):"";
            $this->model_work->create_dir($this->input->post("name"), $fields);
        }
        
        $this->content = $this->load->view('vw_dir', null, true);
    }
    
    function create_cube(){
        if($this->input->post("name") && ($this->input->post("one") || $this->input->post("many")))
            $this->model_work->create_cube($this->input->post("name"), $this->input->post("one"), $this->input->post("many"), $this->input->post("out"));
        
        $data['dirs'] = $this->model_work->select_dir();
        $data['dirs'] = $this->load->view('vw_seldir2', $data, true);
        $this->content = $this->load->view('vw_cube', $data, true);
    }

    function select_dir(){
        $data = array();
        $data['dirs'] = $this->model_work->select_dir();
        $this->content = $this->load->view('vw_seldir', $data, true);
    }
    
    function select_cube(){
        $data = array();
        $data['cubes'] = $this->model_work->select_cube();
        $this->content = $this->load->view('vw_selcube', $data, true);
    }
    
    function cube_slices(){
        $this->load->view('vw_cube_slices', $this->model_work->cube_slices($this->input->post("name")));
    }
    
    function cuberesult(){
        $top = json_decode($this->input->post("top"));
        $left = json_decode($this->input->post("left"));

        $res = $this->model_work->getDataCube(
            $this->input->post("cube"),
            $top,
            $left,
            json_decode($this->input->post("fixed")),
            $this->input->post("value")
        );

        $data = array();
        $data['data'] = $res['res'];
        $data['top'] = $top;
        $data['left'] = $left;
        //$data['vector'] = $res['vector']; // для RW

        $this->load->view('vw_cube_result', $data);
    }
}