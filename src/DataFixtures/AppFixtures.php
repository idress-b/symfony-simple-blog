<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private $encoder;


    public function __construct(UserPasswordHasherInterface  $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
       $admin = new User();
       $admin->setPseudo('admin');
       $admin->setEmail('email@email.com');
       $admin->setRoles(['ROLE_ADMIN']);
        $admin->setAvatar('avatar.jpg');
       $admin->setPassword($this->encoder->hashPassword(
           $admin,
           'admin'
       ));
        $manager->persist($admin);

        for ($a= 1; $a <= 10; $a++) {
            $user = new User();
            $user->setEmail(sprintf("email+%d@email.com", $a));
            $user->setPseudo(sprintf("pseudo+%d", $a));
            $user->setAvatar('avatar.jpg');
            $plaintextPassword = "motdepasse" . "$a";
            $hashedPassword = $this->encoder->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hashedPassword);
            $manager->persist($user);


            for ($i = 1; $i <= rand(2,8); $i++) {
                $post = new Post();
                $post->setIsPublished(true);
                $post->setTitle("article N° " . $i);
                $post->setContent("Dessert jelly beans candy cheesecake gingerbread. Oat cake cake lollipop shortbread jelly beans dragée. Cheesecake marzipan jelly liquorice tart jujubes candy carrot cake. Soufflé chocolate cake topping bear claw soufflé. Toffee candy lemon drops chocolate bar chupa chups chocolate danish croissant. Chocolate bar chocolate cake apple pie candy pastry shortbread dessert sweet roll. Sweet pudding caramels oat cake chocolate pastry powder donut powder. Caramels jelly beans powder sesame snaps jujubes marshmallow chocolate cake tiramisu soufflé. Jelly-o donut caramels sesame snaps donut. Brownie shortbread gummi bears ice cream cake dessert jelly-o bear claw. Bonbon tootsie roll icing shortbread halvah lollipop carrot cake danish. Cake powder caramels jujubes croissant carrot cake cake carrot cake lemon drops.

Soufflé sweet pastry bear claw bonbon gummi bears ice cream lemon drops. Tart bonbon icing tootsie roll muffin fruitcake. Chupa chups chocolate bar powder liquorice dessert icing sweet bear claw cake. Fruitcake jelly beans cotton candy toffee apple pie. Macaroon icing pie candy toffee fruitcake. Fruitcake candy canes powder toffee chupa chups croissant toffee pie croissant. Lollipop pastry lemon drops dessert pudding. Jelly beans brownie cupcake bear claw shortbread. Chocolate cookie wafer liquorice pie cookie carrot cake tart. Powder jelly beans sweet roll dessert tiramisu cheesecake sweet roll. Jelly beans jelly-o danish cookie liquorice ice cream. Cookie halvah gummies pastry chupa chups dragée jelly beans marshmallow biscuit. " );
                $post->setImage("img".rand(1,3).".png");
                $post->setAuthor($user);
                $manager->persist($post);

                for ($j = 1; $j <= rand(3, 12); $j++) {
                    $comment = new Comment();
                    $comment->setAuthor("Auteur N° " . $j);
                    $comment->setContent("commentaire N° " . $j);
                    $comment->setIsValidatedByAdmin(1);
                    $comment->setPost($post);
                    $manager->persist($comment);
                }
            }
        }
        $manager->flush();
    }
}
