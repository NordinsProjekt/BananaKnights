<?php
class moderatorController extends BaseController
{
    //private $db;

    function __construct()
    {
        //$this->db = new DefaultModel();
    }

    function __destruct()
    {
        
    }

    public function ShowModeratorPage()
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