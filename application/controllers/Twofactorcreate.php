<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Twofactorcreate extends CI_Controller {
        
    public function index() {
    }

    public function twofac() {
        $this->load->library('mygoogle2factor');
        $this->load->library('encrypt');
        $ga = new PHPGangsta_GoogleAuthenticator();
        $secret = $ga->createSecret();
        echo "Secret is: ".$secret."<p><p>";
        $qrCodeUrl = $ga->getQRCodeGoogleUrl('Blog', $secret);
        echo "Google Charts URL for the QR-Code: "."<img src=\"$qrCodeUrl\">"."<p><p>";

        echo "encoded it is: ".$this->encrypt->encode($secret);
    }
}
