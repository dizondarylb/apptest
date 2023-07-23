<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    $title = 'Login';

    $this->load->view('templates/guest_master', array('title' => $title, 'content' => $this->load->view('contents/login', '', true)));
?>