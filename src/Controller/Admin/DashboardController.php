<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        // return parent::index();
        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(UserCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Objectif développeur');
    }

    public function configureMenuItems(): iterable
    {

        yield MenuItem::linkToUrl('Aller sur le site', 'fa fa-home', $this->generateUrl('index'));
        yield MenuItem::linkToUrl('Compte auteur', 'fas fa-house-user', $this->generateUrl('profil'));
        // yield MenuItem::linkToDashboard('Accueil  ', 'fas fa-chalkboard-teacher');
        yield MenuItem::linkToCrud('Auteurs', 'fas fa-user', User::class)
            ->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Articles', 'fas fa-file-alt', Post::class);
        yield MenuItem::linkToCrud('Commentaires', 'fas fa-comment', Comment::class)
            ->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Catégories', 'fas fa-list', Category::class)
            ->setPermission('ROLE_ADMIN');
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            ->addMenuItems([
                MenuItem::linkToRoute('Mon profil', 'fa fa-id-card', 'account_index', ['controller_name' => 'AccountController']),
                //  MenuItem::linkToRoute('Settings', 'fa fa-user-cog', '...', ['...' => '...']),
                MenuItem::section(),
                //   MenuItem::linkToLogout('Logout', 'fa fa-sign-out'),
            ]);;
    }
}
