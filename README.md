# symfony-simple-blog
Un blog collaboratif fait en symfony et MysSql

## Pour l'installation :<br/>
 1 - faire un git clone <br>
 2 -composer install <br/>
 3 - si on le demande :
    composer update<br/>
 4 -npm install<br/>
 5 - npm run build<br/>

 6 - composer require symfony/intl<br/>
(dans le cas d'une installation en local avec Xampp editer le fichier php.ini et enlever le ; devant extension=intl ) <br/>

 7 - ajouter un fichier .env.local (dans le meme dossier que .env) et y mettre les infos de connexion <br/>
si tu veux utiliser MySql avec Xampp alors :<br/>
DATABASE_URL = "mysql://root:mot_de_passe@127.0.0.1:3306/nom_du_blog_de_ton_choix" <br/>    
si il n'y pas de mot de passe : <br/>
DATABASE_URL = "mysql://root:@127.0.0.1:3306/nom_du_blog_de_ton_choix"  <br/>

 8 - pour finir tape <br/>
composer prepare <br/>
cela lance un script qui créer la base de données avec quelques données de test <br/>
