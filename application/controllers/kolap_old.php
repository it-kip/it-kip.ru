<?php

class kolap extends CI_Controller{
    
    function __construct(){
        parent::__construct();
        $this->load->model('model_kolap');
    }
    
    function index(){
        $this->content = $this->load->view('vw_operation_cube', null, true);
    }
    
    function create_dir(){
        if($this->input->post("name"))
            $this->model_kolap->create_dir($this->input->post("name"), $this->input->post("fields"), $this->input->post("tree"));
        
        $this->content = $this->load->view('vw_dir', null, true);
    }
    
    function workwithdir($name){
        $this->js[] = 'jquery';
        $this->js[] = 'main';
        $this->js[] = 'valid';
        $this->js[] = 'jquery-ui-1.8.18.custom/js/jquery-ui-1.8.18.custom.min';
        //$this->js[] = 'elrte-1.3/js/elrte.min';
        //$this->js[] = 'elrte-1.3/js/i18n/elrte.ru';
        //$this->js[] = 'elfinder-1.2/js/elfinder.min';
        //$this->js[] = 'elfinder-1.2/js/i18n/elfinder.ru';
        
        $dir = $this->model_kolap->getInfo($name);
        if($this->input->post()){
	$post = $this->input->post();
	if( (int) $post[$dir['table']['key']] ){
            		$this->db->where($dir['table']['key'], $post[$dir['table']['key']]);
            		unset($post[$dir['table']['key']]);
            		$this->db->update($name, $post);
	}
	else{
		$this->db->insert($name, $post);
		echo $this->db->insert_id();
	}
	exit;
        }
        
        $dir['names'] = $this->name_fields($dir); // переделать и => view тоже 
        $this->db->order_by(implode(", ", $dir['table']['names']));
        $dir['data'] = $this->db->get($name)->result_array();
        if(isset($dir['table']['tree']))
            $this->db->where($dir['table']['tree'].' is null');
        
        $dir['data'] = $this->db->get($name)->result_array();
        //$this->content = $this->load->view('vw_workwithdir', $dir, true);

	$this->content = $this->load->view('vw_wwdir', array(
		'table' => isset($dir['info']['n'])? $dir['info']['n']: $dir['table']['name'], // n???
		'meta' => $dir['info'],
		'data' => $dir['data'],
		'key' => $dir['table']['key'],
		'url' => ''
	), true);
    }
    
    function tree_children($table, $parent = 0){
        $dir = $this->model_kolap->getInfo($table);
        
        if($parent)
            $this->db->where($dir['table']['tree'], $parent);
        else
            $this->db->where($dir['table']['tree'].' is null');
        
        echo json_encode($this->db->get($table)->result_array());
    }
    
    function dir_vals($table, $id = 0){//
        $dir = $this->model_kolap->getInfo($table);
        $this->db->where($dir['table']['key'], (int)$id);
        echo json_encode($this->db->get($table)->row_array());
    }
    
    function name_fields(&$info){
        $arr = array();
        foreach($info['info'] as $field)
            if(isset($field['comment'])){
                $comment = (array)  json_decode($field['comment']);
                if(isset($comment['n']))
                    $arr[$field['name']] = $comment['n'];
                // тупо не охото заморачиваться, вставил сюда. ps и & в пар-ры поставил
                if(isset($comment['h']))
                    unset($info['table']['out'][$field['name']]);
            }
        return $arr;
    }
    
    function addrec($table){
        $this->db->insert($table, $this->input->post());
        echo $this->db->insert_id();
    }

    function delrec($table){
        $dir = $this->model_kolap->getInfo($table);
        $this->db->delete($table, array($dir['table']['key'] => $this->input->post('id')));
    }
    
    function rechild($table){
        $parent = (int)$this->input->post('parent');
        $child = (int)$this->input->post('child');
        $dir = $this->model_kolap->getInfo($table);
        $this->db->where($dir['table']['key'], $child);
        $this->db->update($table, array($dir['table']['tree'] => $parent));
    }

    // -------------------------------------------------------------------------
    function create_cube(){
        if($this->input->post("name") && ($this->input->post("one") || $this->input->post("many")))
            $this->model_kolap->create_cube($this->input->post("name"), $this->input->post("one"), $this->input->post("many"), $this->input->post("out"));
        
        $data['dirs'] = $this->model_kolap->select_dir();
        $data['dirs'] = $this->load->view('vw_seldir2', $data, true);
        $this->content = $this->load->view('vw_cube', $data, true);
    }

    function select_dir(){
        $data = array();
        $data['dirs'] = $this->model_kolap->select_dir();
        $this->content = $this->load->view('vw_seldir', $data, true);
    }
    
    function select_cube(){
        $data = array();
        $data['cubes'] = $this->model_kolap->select_cube();
        $this->content = $this->load->view('vw_selcube', $data, true);
    }
    
    function cube_slices(){
        $this->load->view('vw_cube_slices', $this->model_kolap->cube_slices($this->input->post("name")));
    }
    
    function cuberesult(){
        $top = json_decode($this->input->post("top"));
        $left = json_decode($this->input->post("left"));

        $res = $this->model_kolap->getDataCube(
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
    
    function savedata(){
        $cube = $this->input->post("cube");
        $value = $this->input->post("value");
        $top = json_decode($this->input->post("top"));
        $left = json_decode($this->input->post("left"));
        $fix = json_decode($this->input->post("fixed"));
        $val = $this->input->post("val");
        $i = $this->input->post("i");
        $j = $this->input->post("j");
        
        $this->model_kolap->savedata($cube, $top, $left, $fix, $value, $val, $i, $j);
    }
    
    
    // ------------------ бд -----------------------------
    function conn_db(){
        $this->content = $this->load->view('vw_conn_db', null, true);
    }
}