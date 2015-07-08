<?php

class Hook{

    function content(){
        
        $this->CI =& get_instance();

        if(!empty($this->CI->content)){

            if(!empty($this->CI->val) && is_array($this->CI->val)) 
                $data = $this->CI->val;
            else
                $data = array();

            if(!empty($this->CI->css)){
                if(is_array($this->CI->css)){
                    $data['css'] = "";
                    foreach($this->CI->css as $css)
                        $data['css'] .= "<link rel='stylesheet' href='/public/css/".$css.".css' />";
                }
                elseif(is_string($this->CI->css))
                    $data['css'] = "<link rel='stylesheet' href='/public/css/".$this->CI->css.".css' />";
            }

            if(!empty($this->CI->js)){
                if(is_array($this->CI->js)){
                    $data['js'] = "";
                    foreach($this->CI->js as $js)
                        $data['js'] .= "<script type='text/javascript' src='/public/js/".$js.".js'></script>";
                }
                elseif(is_string($this->CI->js))
                    $data['js'] = "<script type='text/javascript' src='/public/js/".$this->CI->js.".js'></script>";
            }
            
            $template = !empty($this->CI->template)? $this->CI->template: $this->CI->config->item('template');
            $data['title'] = !empty($this->CI->title)? "<title>".$this->CI->title."</title>": "";
            $data['content'] = $this->CI->content;
            $this->CI->load->view($template, $data);
        }
    }
}
