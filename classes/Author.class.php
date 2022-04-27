<?php
class Author
{
    private $authorId,$firstname,$lastname,$country,$born,$death,$imagePath;
    private $validated = true;
    //Input får inte vara null
    public function __construct($authorId = 99999999,$firstname,$lastname,$country,$born,$death,$imagePath)
    {
        $this->setAuthorId($authorId);
        $this->setFirstname($firstname);
        $this->setLastname($lastname);
        $this->setCountry($country);
        //Dessa får vara null är de "" blir de null
        $this->setBorn($born);
        $this->setDeath($death);
        $this->setImagePath($imagePath);
    }
    
    public function __destruct()
    {

    }

    private function setAuthorId($authorId)
    {
        $this->authorId = $this->ScrubInputs($authorId);
        if ($this->authorId < 0 || !is_numeric($this->authorId)) 
        {
            $this->validated = false;
        }
    }

    private function setFirstname($firstname)
    {
        $this->firstname = $this->ScrubInputs($firstname);
        if (empty($this->firstname) || $this->firstname == "") 
        {
            $this->validated = false;
        }
    }

    private function setLastname($lastname)
    {
        $this->lastname = $this->ScrubInputs($lastname);
        if (empty($this->lastname) || $this->lastname == "") 
        {
            $this->validated = false;
        }
    }
    private function setCountry($country)
    {
        $this->country = $this->ScrubInputs($country);
        if (empty($this->country) || $this->country == "") 
        {
            $this->validated = false;
        }
    }
    private function setBorn($born)
    {
        $this->born = $this->ScrubInputs($born);
        if ($this->born == "")
        {
            $this->born = NULL;
            return;
        }
        if (!$this->validateDate($this->born)) 
        {
            $this->validated = false;
        }
    }

    private function setDeath($death)
    {
        $this->death = $this->ScrubInputs($death);
        if ($this->death == "")
        {
            $this->death = NULL;
            return;
        }
        if (!$this->validateDate($this->death)) 
        {
            $this->validated = false;
        }
    }

    private function setImagePath($imgPath)
    {
        $this->imagePath = $this->ScrubAll($imgPath);
        if (empty($this->imagePath) || $this->imagePath == "") 
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
      $banlist = array("\t",";","/","<",">",")","(","=","[","]","+","*","#");
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
        return $this->imagePath;
    }

    public function ToArrayUpdate()
    {
        $arr = array (
            $this->firstname,$this->lastname,$this->country,$this->born,$this->death,$this->imagePath,$this->authorId
        );
        return $arr;
    }

    private function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}
?>