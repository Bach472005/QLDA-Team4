<?php 

class HomeController
{
    // public $modelProduct;

    public function __construct() {
      
    }

    public function home() {
       

        require_once './views/home.php';
    }

    public function contact_view(){
        require_once './views/Contact.php';
    }
}