<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    //*
    // &#x25BC = Down Arrow
    // &#x25B2 = Up Arrow
    // &#x25C0 = Left Arrow
    public function index() {
        $this->load->model('Sqlqu');
        $this->db->where('id', 2);
        $otherTable = $this->db->get('other');
        if($otherTable->row('value') === '0') $themed_day = "&#x25BC;&#x25BC;";
        if($otherTable->row('value') === '1') $themed_day = "&#x25B2;&#x25B2;";
        if($otherTable->row('value') === '2') $themed_day = "&#x25C0;&#x25C0;";
        $last_drank = new DateTime('2021-07-02');
        $today = new DateTime(date('Y-m-d'));
        $days_ago_last_drank = $last_drank->diff($today)->days;
        $data = array(
            'today_pretty'              => date('l jS'),
            'days_ago_last_drank'       => $days_ago_last_drank,
            'themed_day'                => $themed_day,
        );
        $meta = array('title' => $data['today_pretty']. " ¯\_(ツ)_/¯",
            'description' => 'There once was a man named little sausage roller.',
            'keywords' => 'sausage,roller',
        );
        $this->load->view('head_view', $meta);
        $this->load->view('blog_view',$data);
    }

}
