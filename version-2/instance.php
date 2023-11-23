<?php

class Instance
{
    public $dirV2;
    public $fileNameV2;
    public $fileV2;
    public $fileTypeV2;
    public $fileTmpV2;

    public function setFileData()
    {
        $this->dirV2 = "up-loads/";
        $this->fileV2 = $_SESSION["instance"]->fileV2;
        $this->fileTypeV2 = $_SESSION["instance"]->fileTypeV2;
        $this->fileTmpV2 = $_SESSION["instance"]->fileTmpV2;
        $this->fileNameV2 = $_SESSION["instance"]->fileNameV2;
    }

    public function getFileData()
    {
        $_SESSION["instance"]->fileNameV2 = basename($_FILES["fileToUpload"]["name"]);
        $_SESSION["instance"]->fileV2 = $this->dirV2 . $this->fileNameV2;
        $_SESSION["instance"]->fileTypeV2 = strtolower(pathinfo($this->fileV2, PATHINFO_EXTENSION));
        $_SESSION["instance"]->fileTmpV2 = $_FILES["fileToUpload"]["tmp_name"];
        move_uploaded_file($this->fileTmpV2, $this->fileV2);
    }
}

?>