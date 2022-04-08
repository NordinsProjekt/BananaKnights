<?php
class User
{
    private $email;
    private $emailConfirmed;
    private $passwordHash;
    private $phoneNumber;
    private $phoneNumberConfirmed;
    private $twoFactorEnabled;
    private $locloutEndDateUtc;
    private $lockoutEbabled;
    private $accessFailedCount;
    private $username;
    private $validate = true;

    public function __construct()
    {
        
    }
    
    public function __destruct()
    {
        
    }
    public function ValidateInput()
    {

    }


}
?>