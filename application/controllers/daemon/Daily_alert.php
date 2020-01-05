<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Daily_alert extends CI_Controller {

    public function email_night() {
        $this->load->model('Sqlqu');
        $this->load->library('email');
        $today = new DateTime(date('Y-m-d'));
        $data = array(
            'today_pretty'              => date('l jS'),
            'darkskyDaily'              => $this->Sqlqu->getDark8Days(),
            'darkskyYesterday'          => $this->Sqlqu->getDarknetYesterday(),
        );
        $subjet = $data['darkskyDaily']['1']['min'] - $data['darkskyDaily']['0']['min'];
        //echo $subjet;
        //vdebug($data);

        $this->email->from('home@loopnova.com', 'Night time email');
        $this->email->to('ryan@pinescore.com');

        $this->email->subject($subjet);

        $this->email->send();
    }

}
