<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$hook['post_controller'] = array(
    'class'    => 'Hook',
    'function' => 'content',
    'filename' => 'hook.php',
    'filepath' => 'hooks'
);
