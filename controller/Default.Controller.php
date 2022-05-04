<?php
require_once "model/Default.Model.php";
class DefaultController
{
    private $db;

    function __construct()
    {
        $this->db = new DefaultModel();
    }

    function __destruct()
    {
        
    }

}
?>