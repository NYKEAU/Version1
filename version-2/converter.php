<?php
require_once("./instance.php");

class Converter
{
    public function convert($type)
    {
        if ($type == "csv") {
            if (($csv = fopen('up-loads/' . $_SESSION["instance"]->fileNameV2 . '', 'r')) === false) {
                die('Error opening file');
            }

            $headers = fgetcsv($csv, 1024, ',');
            $array = array();

            while ($row = fgetcsv($csv, 1024, ',')) {
                $array[] = array_combine($headers, $row);
            }

            fclose($csv);

            file_put_contents('down-loads/' . substr($_SESSION["instance"]->fileNameV2, 0, -4) . '.json', json_encode($array));
            echo '<a href="down-loads/' . substr($_SESSION["instance"]->fileNameV2, 0, -4) . '.json" download>Télécharger JSON</a>';
        } elseif ($type == "xml") {
            $filePath = file_get_contents($_SESSION["instance"]->fileV2);

            $fileChange = str_replace(array("\n", "\r", "\t"), '', $filePath);

            $fileTrim = trim(str_replace('"', "'", $fileChange));

            $array = simplexml_load_string($fileTrim);

            file_put_contents('down-loads/' . substr($_SESSION["instance"]->fileNameV2, 0, -4) . '.json', json_encode($array));
            echo '<a href="down-loads/' . substr($_SESSION["instance"]->fileNameV2, 0, -4) . '.json" download>Télécharger JSON</a>';
        } else {
            echo "Veuillez sélectionner un fichier de type CSV ou XML uniquement.";
        }
    }
}

?>