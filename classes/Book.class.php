<?php
class Book
{
    private $userId,$bookTitle,$bookYear,$bookDescription,$bookISBN,$imgPath,$isDeleted,$created;
    private $validated = true;
    //Input får inte vara null
    public function __construct($userId,$bookTitle,$bookYear,$bookDescription,$bookISBN,$imgPath,$isDeleted,$created)
    {
        $this->setUserId($userId);
        $this->setBookTitle($bookTitle);
        $this->setBookYear($bookYear);
        $this->setBookDescription($bookDescription);
        $this->setBookISBN($bookISBN);
        $this->setImgPath($imgPath);
        $this->setIsDeleted($isDeleted);
        $this->setCreated($created);
    }
    
    public function __destruct()
    {

    }

    private function setUserId($userId)
    {
        $this->userId = $this->ScrubInputs($userId);
        if ($this->userId < 0 || !is_numeric($this->userId)) 
        {
            $this->validated = false;
        }
    }

    private function setBookTitle($bookTitle)
    {
        $this->bookTitle = $this->ScrubInputs($bookTitle);
        if (empty($this->bookTitle) || $this->bookTitle == "") 
        {
            $this->validated = false;
        }
    }

    private function setBookYear($bookYear)
    {
        $this->bookYear = $this->ScrubInputs($bookYear);
        if (empty($this->bookYear) || $this->bookYear < 0 || !is_numeric($this->bookYear) || $this->bookYear > 9999) 
        {
            if (empty($this->bookYear)){}
            else {
                $this->validated = false;
            }
        }
    }

    private function setBookDescription($bookDescription)
    {
        $this->bookDescription = $this->ScrubInputs($bookDescription);
        if (empty($this->bookDescription) || $this->bookDescription == "") 
        {
            $this->validated = false;
        }
    }

    private function setBookISBN($bookISBN)
    {
        $this->bookISBN = $this->ScrubInputsISBN($bookISBN);
        if (empty($this->bookISBN) || $this->bookISBN == "") 
        {
            $this->validated = false;
        }
    }

    private function setImgPath($imgPath)
    {
        $this->imgPath = $this->ScrubAll($imgPath);
        if (empty($this->imgPath) || $this->imgPath == "") 
        {
            $this->validated = false;
        }
    }

    private function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $this->ScrubInputs($isDeleted);
        if ($this->isDeleted < 0 || $this->isDeleted > 1 || !is_numeric($this->isDeleted)) 
        {
            $this->validated = false;
        }
    }

    private function setCreated($created)
    {
        $this->created = $created;
    }

    private function ScrubInputs($notsafeText)
    {
      $banlist = array("\t",".",";","/","<",">",")","(","=","[","]","+","*","#");
      $safe = trim(str_replace($banlist,"",$notsafeText));
      return $safe;
    }

    private function ScrubInputsISBN($notsafeText)
    {
      $banlist = array("\t","."," ",";","/","<",">",")","(","=","[","]","+","*","#");
      $safe = trim(str_replace($banlist,"",$notsafeText));
      return $safe;
    }

    private function ScrubAll($notsafeText)
    {
        $banlist = array("\t","."," ","-",";","/","<",">",")","(","=","[","]","+","*","#",":");
        $safe = trim(str_replace($banlist,"",$notsafeText));
        return $safe;
    }

    public function Validated()
    {
        return $this->validated;
    }

    public function getImagePath()
    {
        return $this->imgPath;
    }
    
    public function ToArray()
    {
        $arr = array (
            $this->userId,$this->bookTitle,$this->bookYear,$this->bookDescription,
            $this->bookISBN,$this->imgPath,$this->isDeleted,$this->created
        );
        return $arr;
    }
}
?>