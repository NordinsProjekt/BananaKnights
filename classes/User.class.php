<?php
class User
{
    private $email;
    private $emailConfirmed;
    private $passwordHash;
    private $phoneNumber;
    private $phoneNumberConfirmed;
    private $twoFactorEnabled;
    private $lockoutEndDateUtc;
    private $lockoutEnabled;
    private $accessFailedCount;
    private $username;
    private $validated = true;

    public function __construct($email, $emailConfirmed,$passwordHash,$phoneNumber,$phoneNumberConfirmed,
    $twoFactorEnabled,$lockoutEndDateUtc,$lockoutEnabled,$accessFailedCount,$username)
    {
        $this->setEmail($email);
        $this->setEmailConfirmed($emailConfirmed);
        $this->setPasswordHash($passwordHash);
        $this->setPhoneNumber($phoneNumber);
        $this->setPhoneNumberConfirmed($phoneNumberConfirmed);
        $this->setTwoFactorEnabled($twoFactorEnabled);
        $this->setLockoutEndDateUtc($lockoutEndDateUtc);
        $this->setLockoutEnabled($lockoutEnabled);
        $this->setAccessFailedCount($accessFailedCount);
        $this->setUsername($username);
    }
    
    public function __destruct()
    {
        
    }
    public function ValidateInput()
    {

    }

    private function setEmail($email)
    {
        $this->email = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL))  
        {
            $this->validated = false;
        }
    }

    private function setEmailConfirmed($emailConfirmed)
    {
        $this->emailConfirmed = $this->ScrubInputs($emailConfirmed);
        if ($this->emailConfirmed < 0 || $this->emailConfirmed> 1 || !is_numeric($this->emailConfirmed)) 
        {
            $this->validated = false;
        }
    }

    private function setPasswordHash($passwordHash)
    {
        $this->passwordHash = $passwordHash;
    }

    private function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $this->ScrubInputs($phoneNumber);
        if (empty($this->phoneNumber) || $this->phoneNumber == "")
        {
            if (empty($this->phoneNumber)){}
            else {
                $this->validated = false;
            }
        }
    }

    private function setPhoneNumberConfirmed($phoneNumberConfirmed)
    {
        $this->phoneNumberConfirmed = $this->ScrubInputs($phoneNumberConfirmed);
        if ($this->phoneNumberConfirmed < 0 || $this->phoneNumberConfirmed > 1 || !is_numeric($this->phoneNumberConfirmed)) 
        {
            $this->validated = false;
        }
    }

    private function setTwoFactorEnabled($twoFactorEnabled)
    {
        $this->twoFactorEnabled = $this->ScrubInputs($twoFactorEnabled);
        if ($this->twoFactorEnabled< 0 || $this->twoFactorEnabled > 1 || !is_numeric($this->twoFactorEnabled)) 
        {
            $this->validated = false;
        }
    }

    private function setLockoutEndDateUtc($lockoutEndDateUtc)
    {
        $this->lockoutEndDateUtc = $this->ScrubInputs($lockoutEndDateUtc);
        if (empty($this->lockoutEndDateUtc) || $this->lockoutEndDateUtc == "")
        {
            if (empty($this->lockoutEndDateUtc)){}
            else {
                $this->validated = false;
            }
        }
    }

    private function setLockoutEnabled($lockoutEnabled)
    {
        $this->lockoutEnabled = $this->ScrubInputs($lockoutEnabled);
        if ($this->lockoutEnabled < 0 || $this->lockoutEnabled > 1 || !is_numeric($this->lockoutEnabled)) 
        {
            $this->validated = false;
        }
    }

    private function setAccessFailedCount($accessFailedCount)
    {
        $this->accessFailedCount = $accessFailedCount;
    }

    private function setUsername($username)
    {
        $this->username = $this->ScrubInputs($username);
        if (empty($this->username) || $this->username == "") 
        {
            $this->validated = false;
        }
    }

    private function ScrubInputs($notsafeText)
    {
      $banlist = array("\t",".",";","/","<",">",")","(","=","[","]","+","*","#");
      $safe = trim(str_replace($banlist,"",$notsafeText));
      return $safe;
    }
    public function getUsername()
    {
        return $this->username;
    }

    public function getPasswordHash()
    {
        return $this->passwordHash;
    }

    public function Validated()
    {
        return $this->validated;
    }

    public function ToArray()
    {
        $arr = array (
            $this->email,$this->emailConfirmed,$this->passwordHash,
            $this->phoneNumber,$this->phoneNumberConfirmed,$this->twoFactorEnabled,
            $this->lockoutEndDateUtc,$this->lockoutEnabled,$this->accessFailedCount,
            $this->username);
        return $arr;
    }
}
?>