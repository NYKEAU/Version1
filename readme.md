# Convertir un fichier CSV/XML en JSON

Ce projet permet de convertir n'importe quel fichier CSV ou XML* en JSON et de télécharger ce dernier instantanément.

**(Version 2 uniquement)*

## Explication

Le projet est découpé en deux versions/parties principales :

- La première (Version 1) concerne le fichier **index.php** ainsi que les deux dossiers **uploads** et **downloads**. Le fichier **index.php** comprends toute la logique du projet et permet facilement de convertir un fichier CSV en JSON. Le dossier **uploads** stocke les fichiers importés par l'utilisateur, tandis que le dossier **downloads** stocke les fichiers JSON créés suite à l'importation du fichier CSV.
- La seconde (Version 2) fonctionne de la même manière mais ajoute deux nouveautés : la compatibilité du format **XML** et l'utilisation d'un code refactorisé en **POO** *(Programmation Orientée Objet)*.

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