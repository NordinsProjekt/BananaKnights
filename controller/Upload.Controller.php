<?php
class UploadController
{
    function AddImage($folderPath,$uploadedFile)
    {
        //Bygger sökvägen
        $this->CreateFolder($folderPath);
        $file = $folderPath ."/" . basename($uploadedFile["name"]);
        //Kontroller för att se om filen är ok.
        if($this->CheckFileExists($file))
        {
            if($this->CheckFileSize($file))
            {
                if($this->CheckFileFormat($file))
                {
                    if (move_uploaded_file($uploadedFile["tmp_name"], $file))
                    {
                        $_SESSION['Message'] = "File". $file . "has been uploaded";
                        return true;
                    }
                    else
                    {
                        $_SESSION['Message'] = "Unknown error, could not upload";
                        return false;
                    }
                }
                else
                {
                    $_SESSION['Message'] = "Fel filformat, jpg, jpeg och png stöds bara.";
                }
            }
            else
            {
                $_SESSION['Message'] = "Filen är för stor";
            }
        }
        else
        {
            $_SESSION['Message'] = "Filen finns redan, ".$file;

        }
        return false;
    }

    private function CreateFolder($folder)
    {
        echo "inne i createfolder";
        var_dump($folder);
        if (!file_exists($folder)) 
        { 
            echo "nu skapar vi katalogen";
            mkdir($folder, 0777, true); 
        }
    }
    private function CheckFileExists($file)
    {
        if (file_exists($file)) 
        {
            return false;
        }
        return true;
    }

    private function CheckFileSize()
    {
        if ($_FILES["BookPicture"]["size"] > 2000000) {
            return false;
        }
        return true;
    }

    private function CheckFileFormat($file)
    {
        $imageFileType = strtolower(pathinfo($file,PATHINFO_EXTENSION));
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg")
        {
          return false;
        }
        return true;
    }

    function DeleteProductImage($folderPath, $file){ //tabort produktbild

        $imgpath = $folderPath.'/'.$file;

        //kollar om Filen existerar
        if(!$this->CheckFileExists($file))
        {
            //tar bort bilden
            if(unlink($imgpath)){
                $_SESSION['message'] =  $imgpath." DELETED";
            } else {
                $_SESSION['message'] =  "Gick inte att ta bort".$imgpath;
            }

        }        
    }

    function ListProductIDimagePaths($productID){ //returnerar alla sökvägar för en produkts bilder
        
        $dir = 'img/products/' .$productID;

        //kollar om directory:t existerar
        if (file_exists($dir)) {
            $imagePaths = scandir($dir);
            
            //tvättar arrayen
            unset($imagePaths[0]);
            unset($imagePaths[1]);
            
            return  $imagePaths;    
        } 
        else {
            return (bool)0;
        }
    }
}
?>