# symfony-simple-blog
Un blog collaboratif fait en symfony et MysSql

Pour l'installation :
faire un git clone 
composer install
si on le demande composer update
npm install
npm run build

composer require symfony/intl
(dans le cas d'une installation en local avec Xampp editer le fichier php.ini et enlever le ; devant extension=intl )

ensuite ajouter un fichier .env.local (dans le meme dossier que .env) et y mettre les infos de connexion
sit tu veux utiliser MySql avec Xampp alors :
DATABASE_URL = "mysql://root:mot_de_passe@127.0.0.1:3306/nom_du_blog_de_ton_choix"    
si il n'y pas de mot de passe :
DATABASE_URL = "mysql://root:@127.0.0.1:3306/nom_du_blog_de_ton_choix"  

pour finir tape 
composer prepare
cela lance un script qui créer la base de données avec quelques données de test
