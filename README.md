Gesmail
=======

Outil de gestion des mails pour les assos de l'UTC.

Gesmail devient une API REST qui effectue des requêtes sur sa base de données en répondant à des requêtes HTTP.

Les mailing listes nécessitent l'exécution de commandes par l'utilisateur de mailman. Un cron exécute régulièrement le fichier `traiterml.php` avec cet utilisateur pour permettre cela.

TODO
----

* Ajouter Composer et installer Slim avec
* Retirer tous les templates/css/images/js... et les routes vers l'UI
* Sortir la config de la DB du `index.php` et faire une classe pour la récupérer
* Déplacer `index.php` dans un dossier web pour éviter l'accès aux classes (cf. payutc)
* Revoir l'architecture du code rapidement (il faudrait mettre toute la logique dans une classe Gesmail)
* Vérifier que toutes les actions (Create, Retrieve, Update, Delete) sont possibles pour les alias et les ML
* Faire un client (par exemple basé sur koala-client)