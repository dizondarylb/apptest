<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    $title = 'Users';

    $this->load->view('templates/admin_master', array('title' => $title, 'content' => $this->load->view('contents/users', '', true)));
?>