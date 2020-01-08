<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nightly extends CI_Controller {

    public function index() {
        $old =  "updated < (NOW() - INTERVAL 7 DAY)";

        $this->db->where($old);
        $this->db->delete('darksky_daily');

        $this->db->where($old);
        $this->db->delete('darksky_summary');

    }
}
