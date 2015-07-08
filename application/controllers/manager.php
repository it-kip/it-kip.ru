<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class manager extends CI_Controller {
    
    function __construct(){
        parent::__construct();
        $this->load->model('model_kolap_alpha', 'model_kolap');
    }
    
    function create_dir(){
        if($this->input->post("name"))
            $this->model_kolap->create_dir($this->input->post("name"), $this->input->post("fields"), $this->input->post("tree"));
        
        $this->content = $this->load->view('vw_dir', null, true);
    }
    
    function index(){
        $name = 'tree_site'; // заточка
        $this->js[] = 'jquery';
        $this->js[] = 'main';
        $this->js[] = 'valid';
        $this->js[] = 'jquery-ui-1.8.18.custom/js/jquery-ui-1.8.18.custom.min';
        $this->js[] = 'elrte-1.3/js/elrte.min';
        $this->js[] = 'elrte-1.3/js/i18n/elrte.ru';
        $this->js[] = 'elfinder-1.2/js/elfinder.min';
        $this->js[] = 'elfinder-1.2/js/i18n/elfinder.ru';
        //$this->css[] = 'jquery-ui-1.8.18.custom/css/redmond/jquery-ui-1.8.18.custom';
        //$this->css[] = 'elrte-1.3/css/elrte.min';
        //$this->css[] = 'elfinder-1.2/css/elfinder';
        
        $dir = $this->model_kolap->getInfo($name);
        
        if($this->input->post()){
            $post = $this->input->post();
            $this->db->where($dir['table']['key'], $post[$dir['table']['key']]);
            unset($post[$dir['table']['key']]);
            $post['tree_site_name_translit'] = $this->translit($post['tree_site_name']); // заточка
            $this->db->update($name, $post);
        }
        
        $dir['names'] = $this->name_fields($dir);
        $dir['data'] = $this->db->get($name)->result_array();
        if(isset($dir['table']['tree']))
            $this->db->where($dir['table']['tree'].' is null');
        
        $dir['data'] = $this->db->get($name)->result_array();
        $this->content = $this->load->view('vw_tree_site', $dir, true);
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
    
    function name_fields($info){
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
        $post = $this->input->post();
        $post['tree_site_name_translit'] = $this->translit($post['tree_site_name']); // заточка
        $this->db->insert($table, $post);
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
    
    
    function translit($str) {
        $tr = array(
            'а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
            'и' => 'i',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
            'ь' => '',    'ы' => 'y',   'ъ' => '',
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

            'А' => 'A',   'Б' => 'B',   'В' => 'V',
            'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
            'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
            'И' => 'I',   'Й' => 'Y',   'К' => 'K',
            'Л' => 'L',   'М' => 'M',   'Н' => 'N',
            'О' => 'O',   'П' => 'P',   'Р' => 'R',
            'С' => 'S',   'Т' => 'T',   'У' => 'U',
            'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
            'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
            'Ь' => '',    'Ы' => 'Y',   'Ъ' => '',
            'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
            
            ' ' => '_'
        );
        return strtr($str, $tr);
    }
}