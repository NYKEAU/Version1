<form action="version2.php" method="post" enctype="multipart/form-data">
    Séléctionner le fichier CSV ou XML à convertir :
    <input type="file" name="fileToUpload" id="fileToUpload" accept=".csv, .xml">
    <input type="submit" value="Importer fichier" name="submit">
</form>

<?php

require_once("./instance.php");
require_once("./converter.php");
session_start();
!isset($_SESSION["instance"]) ? $_SESSION["instance"] = new Instance() : "";
$instance = $_SESSION["instance"];
$converter = new Converter();

if (isset($_POST["submit"])) {
    $_SESSION["instance"]->setFileData();
    $_SESSION["instance"]->getFileData();
    $converter->convert($_SESSION["instance"]->fileTypeV2);
} else {
    echo "Veuillez sélectionner un fichier.";
}

?>