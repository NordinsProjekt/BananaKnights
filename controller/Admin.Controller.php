<?php
require_once "model/Admin.Model.php";
class AdminController
{
    private $db;

    function __construct()
    {
        $this->db = new AdminModel();
    }

    function __destruct()
    {
        
    }

    public function Show()
    {
        //Visa upp adminpanelen.
        
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