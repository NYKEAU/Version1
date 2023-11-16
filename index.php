<form action="index.php" method="post" enctype="multipart/form-data">
    Séléctionner le fichier CSV à convertir :
    <input type="file" name="fileToUpload" id="fileToUpload" accept=".csv">
    <input type="submit" value="Importer CSV" name="submit">
</form>

<?php

$uploadOk = 0;

if (isset($_POST["submit"])) {
    $dir = "uploads/";
    $fileName = basename($_FILES["fileToUpload"]["name"]);
    $file = $dir . $fileName;
    $fileType = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $fileTmp = $_FILES["fileToUpload"]["tmp_name"];

    move_uploaded_file($fileTmp, $file);

    if ($fileType != "csv") {
        echo "Sorry, only CSV files are allowed.";
        $uploadOk = 0;
    } else {
        $uploadOk = 1;
    }
} else {
    echo "Veuillez sélectionner un fichier.";
}

if ($uploadOk == 1) {
    echo '<a href="downloads/' . substr($fileName, 0, -4) . '" download>Télécharger JSON</a>';

    if (($csv = fopen('uploads/' . $fileName . '', 'r')) === false) {
        die('Error opening file');
    }

    $headers = fgetcsv($csv, 1024, ',');
    $array = array();

    while ($row = fgetcsv($csv, 1024, ',')) {
        $array[] = array_combine($headers, $row);
    }

    fclose($csv);

    file_put_contents('downloads/' . substr($fileName, 0, -4) . '.json', json_encode($array));
}

?>
