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

    private function CheckUserInputs($notsafeText)
    {
      $banlist = array("\t",".",";"," ","/",",","<",">",")","(","=","[","]","+","*");
      $safe = str_replace($banlist,"",$notsafeText);
      return $safe;
    }

    //Mellanslag tillåtna
    private function CheckUserName($notsafeText)
    {
        $banlist = array("\t",".",";","/",",","<",">",")","(","=","[","]","+","*");
        $safe = str_replace($banlist,"",$notsafeText);
        return $safe;
    }
}
?>