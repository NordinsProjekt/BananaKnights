<?php
class UploadController
{
    function AddImage($folderPath)
    {
        //Bygger sökvägen
        $target_dir = $folderPath;
        $this->CreateFolder($target_dir);
        $file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        //Kontroller för att se om filen är ok.
        if(!$this->CheckFileExists($file))
        {
            if(!$this->CheckFileSize($file))
            {
                if(!$this->CheckFileFormat($file))
                {
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $file))
                    {
                        $_SESSION['Message'] = "File". $file . "has been uploaded";
                        unset($_FILES['fileToUpload']);
                    }
                    else
                    {
                        $_SESSION['Message'] = "Unknown error, could not upload";
                        unset($_FILES['fileToUpload']);
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
    }

    private function CreateFolder($folder)
    {
        if (!file_exists($folder)) 
        { 
            mkdir($folder, 0777, true); 
        }
    }
    private function CheckFileExists($file)
    {
        if (file_exists($file)) 
        {
            return false;
        }
    }

    private function CheckFileSize()
    {
        if ($_FILES["fileToUpload"]["size"] > 2000000) {
            return false;
        }
    }

    private function CheckFileFormat($file)
    {
        $imageFileType = strtolower(pathinfo($file,PATHINFO_EXTENSION));
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg")
        {
          return false;
        }
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
