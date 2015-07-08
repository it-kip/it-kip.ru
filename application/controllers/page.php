<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class page extends CI_Controller {
    
    var $page = false;
    var $url = '/page';
    
    function index(){
        $n = 2;
        //$ch = false;
        //$sidebar;
        while($this->uri->segment($n) !== false){
            $this->db->where('tree_site_name_translit', $this->uri->segment($n));
            $this->db->where('tree_site_active', 1);
            if($this->page)
                $this->db->where('tree_site_parent', $this->page);
            else
                $this->db->where('tree_site_parent is null');

            $page = $this->db->get('tree_site')->row_array();
            if(count($page) == 0) show_404();
            $this->page = $page['tree_site_id'];
            $this->url .= "/".$page['tree_site_name_translit'];
            $n++;
            
            // sidebar
            /*
            $this->db->order_by('tree_site_sort');
            $res = $this->db->get_where('tree_site', array('parent_id' => $this->page, 'menu' => 1))->result_array();
            $c = false;
            $level = array();
            foreach($res as $r){
                $arr = array();
                $arr['text'] = $r['tree_site_name'];
                $arr['url'] = $this->url."/".$r['tree_site_name_translit'];
                if($r['tree_site_name_translit'] == $this->uri->segment($n)){
                    $arr['open'] = true;
                    $arr['childs'] = true;
                    $c = count($level);
                }
                $level[] = $arr;
            }
            
            if($ch !== false)
                $ch = $level;
            else
                $sidebar = $level;
            
            if($c !== false){
                if($ch)
                    $ch = &$ch[$c]['childs'];
                else
                    $ch = &$sidebar[$c]['childs'];
            }
            */
        };
        //$this->val['sidebar'] = $sidebar;
        
        $this->content = $page['tree_site_content'];
    }
}