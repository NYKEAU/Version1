# Convertir un fichier CSV/XML en JSON

Ce projet permet de convertir n'importe quel fichier CSV ou XML* en JSON et de télécharger ce dernier instantanément.

**(Version 2 uniquement)*

## Explication

### Les versions

Le projet est découpé en deux versions/parties principales :

- La première (Version 1) concerne le fichier **index.php** ainsi que les deux dossiers **uploads** et **downloads**. Le fichier **index.php** comprends toute la logique du projet et permet facilement de convertir un fichier CSV en JSON. Le dossier **uploads** stocke les fichiers importés par l'utilisateur, tandis que le dossier **downloads** stocke les fichiers JSON créés suite à l'importation du fichier CSV.
- La seconde (Version 2) fonctionne de la même manière mais ajoute deux nouveautés : la compatibilité du format **XML** et l'utilisation d'un code refactorisé en **POO** *(Programmation Orientée Objet)*.

### Le code

#### Première version

Pour la première version, il n'y a qu'un seul fichier **index.php** qui va réalisé toute la logique, il fonctionne ainsi :

``` html
<form action="index.php" method="post" enctype="multipart/form-data">
    Séléctionner le fichier CSV à convertir :
    <input type="file" name="fileToUpload" id="fileToUpload" accept=".csv">
    <input type="submit" value="Importer CSV" name="submit">
</form>
```
Le code HTML permettant de créer le formulaire qui propose à l'utilisateur de sélectionner un fichier CSV pour le convertir en JSON. Notez l'attribut 'action' qui renvoie directement sur la même page, cela permet d'éviter d'utiliser une nouvelle page uniquement pour télécharger notre fichier JSON.

``` php
if (isset($_POST["submit"])) {
    $dir = "uploads/";
    $fileName = basename($_FILES["fileToUpload"]["name"]);
    $file = $dir . $fileName;
    $fileType = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $fileTmp = $_FILES["fileToUpload"]["tmp_name"];

    move_uploaded_file($fileTmp, $file);

    if ($fileType != "csv") {
        echo "Désolé, seuls les fichiers CSV sont acceptés.";
        $uploadOk = 0;
    } else {
        $uploadOk = 1;
    }
} else {
    echo "Veuillez sélectionner un fichier.";
}
```
Ce code permet de récupérer les informations du fichier qui vient d'être soumis par l'utilisateur. En premier lieu il récupère son nom, son emplacement *(dans le dossier uploads)* et son type. On vérifie ensuite qu'il s'agit bien d'un fichier au format CSV pour indiquer que le convertion sera possible *(grâce à la variable **uploadOk**)*, dans le cas où aucun fichier n'est ou n'as été sélectionner, le message 'Veuillez sélectionner un fichier.' est affiché sur la page.

``` php
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
```
Enfin, on vérifie que la convertion est possible *(uploadOk == 1)*, si c'est le cas :

- On créer un bouton permettant de télécharger le fichier JSON
- On vérifie que le fichier CSV est bien utilisable et non corompu
- On récupère les différentes colonnes pour en faire des *arrays*
- On met enfin toutes les données dans le fichier JSON grâce au formattage qu'on viens d'effectuer

##### Seconde version

Pour la seconde version, on a trois fichiers différents mais toujours nos deux dossiers, nommés maintenant **up-loads** et **down-loads** *(pour ne pas porter à confusion avec la première version)*. Tout d'abord le fichiers **instance.php** :

``` php
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
```

C'est ici qu'on initialise notre instance avec toutes nos variables, on y trouve notamment nos deux fonctions principales **setFileData()** et **getFileData()**, celles-ci permettent respectivement de *set* les valeurs du fichier que l'utilisateur a soumis dans l'instance et de récupérer ces valeurs qui pourront ensuite nous permettre de créer notre fichier JSON. Mais avant ça, revenons au programme 'source' de la version 2 - **version2.php** :

``` php
<input type="file" name="fileToUpload" id="fileToUpload" accept=".csv, .xml">

...

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
```

Ici on retrouve de nouveau en premier lieu le formulaire permettant de récupérer le fichier de l'utilisateur, on remarquera notamment que l'input accepte désormais les fichies **CSV** mais également les fichiers **XML**. Ensuite on utilise une instance pour créer notre fichier JSON : tout d'abord, après avoir créé notre instance, on initialise un nouveau **Converter**. Ensuite il ne nous reste plus qu'à vérifier si le fichier à été soumis pour récupérer ses données et en créer un fichier JSON, en effet, la conversion se fait désormais entièrement dans le fichier converter.php :

``` php
elseif ($type == "xml") {
    $filePath = file_get_contents($_SESSION["instance"]->fileV2);

    $fileChange = str_replace(array("\n", "\r", "\t"), '', $filePath);

    $fileTrim = trim(str_replace('"', "'", $fileChange));

    $array = simplexml_load_string($fileTrim);

    file_put_contents('down-loads/' . substr($_SESSION["instance"]->fileNameV2, 0, -4) . '.json', json_encode($array));
    echo '<a href="down-loads/' . substr($_SESSION["instance"]->fileNameV2, 0, -4) . '.json" download>Télécharger JSON</a>';
} else {
    echo "Veuillez sélectionner un fichier de type CSV ou XML uniquement.";
}
```

Ici on retrouve une nouvelle manière de créer notre fichier JSON : en effet la convertion **CSV->JSON** est différente de la convertion **XML->JSON**. On va récupérer le contenu de notre fichier XML et le formatter à nouveau (comme pour le format CSV), puis enfin le mettre à télécharger via un bouton comme pour la version 1. Enfin on retrouve le dernier *else* qui permet d'afficher un message indiquant que le fichier sélectionner par l'utilisateur n'as pas le bon format (ni CSV, ni XML).


## Installation

### PHP

Le projet n'utilise **que PHP et ne requiert aucun framework additionnel** pour le faire fonctionner, ce dernier est installé directement lors de l'installation de *AMP. La version de PHP utilisée lors de la création de ce projet est la **8.0.26**, il est donc conseillé d'utiliser celle-ci ou une version supérieure.

### *AMP

Assurez vous avant tout d'avoir **Wamp** *(Windows)*, **Lamp** *(Linux)*, **Mamp** *(MacOS)* ou **Xampp** *(Multi-OS)* d'installer sur votre poste *(pour savoir quelle version privilégiée, retrouvez plus d'informations [ici](https://www.letecode.com/quest-ce-que-wamp-lamp-mamp-xampp-et-quelle-difference-faut-il-faire))*.

### Clonage Git

Une fois fait, pour cloner le projet, rendez vous dans le répertoire prévu à cet effet *(exemple : 'C:\wamp64\www' sous Windows)*, puis utilisez Git pour l'installer :

```bash
git clone https://github.com/NYKEAU/Version1.git
```
Appuyez sur Entrée pour lancer le clone, il ne vous restera plus qu'à y accéder :
```bash
cd Version1
```

## Utilisation

Maintenant que le projet est cloné, exécutez *AMP de la manière adaptée *(exemple : lancez l'exécutable **Wampserver64.exe** sous Windows)*.

Une fois fait, il ne vous reste plus qu'à lancer votre navigateur favori et vous rendre sur **[https://localhost/{VOTRE CHEMIN}/Version1]()** en sachant que localhost correspond au niveau du répertoire **www**, exemple :

- Si dans votre répertoire **www** vous avez un dossier **php** contenant le projet **Version1**, alors il faudra se rendre à l'adresse **[https://localhost/php/Version1](https://localhost/php/Version1)**
- Si dans votre répertoire **www** vous avez directement installer le projet **Version1**, alors il faudra se rendre à l'adresse **[https://localhost/Version1](https://localhost/Version1)**

## Contribution

Les *Pull requests* sont les bienvenues, pour des modifications plus importantes, créez plutôt une *Issue* en premier lieu pour pouvoir discuter des changements que vous souhaitez apporter.