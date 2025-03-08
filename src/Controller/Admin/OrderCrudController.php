<?php

namespace App\Controller\Admin;

use App\Classe\Mail;
use App\Classe\State;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class OrderCrudController extends AbstractCrudController
{
    private $em;
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Commande')
            ->setEntityLabelInPlural('Commandes')
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        $show = Action::new('Afficher')->linkToCrudAction('show');
        
        return $actions
            ->add(Crud::PAGE_INDEX, $show)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
        ;
    }

    /**
     * Fonction permettant le changement de statut de commande
     *
     * @param [type] $state
     * @return void
     */
    public function changeState($order, $state)
    {
        // 1. Modification du statut de la commande
        $order->setState($state);
        $this->em->flush();

        // 2. Affichage du Flash Message pour informer l'administrateur
        $this->addFlash(
            'success',
            "Statut de la commande correctement mis à jour."
        );

        // 3. Informer l'utilisateur par mail de la modification du statut de sa commande
        $mail = new Mail();
        $vars = [
            'firstname' => $order->getUser()->getFirstname(),
            'id_order' => $order->getId()
        ];
        $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstname().' '.$order->getUser()->getLastname(), State::STATE[$state]['email_subject'], State::STATE[$state]['email_template'], $vars);
        
        return $this->redirectToRoute('app_login');
    }
    
    public function show(AdminContext $context, AdminUrlGenerator $adminUrlGenerator, Request $request)
    {
        $order = $context->getEntity()->getInstance();
        
        // Récupérer l'URL de notre action "SHOW"
        $url = $adminUrlGenerator->setController(self::class)->setAction('show')->setEntityId($order->getId())->generateUrl();

        // Traitement des changements de statut
        if ($request->get('state')) {
            $this->changeState($order, $request->get('state'));
        }
        
        return $this->render('admin/order.html.twig', [
            'order' => $order,
            'current_url' => $url 
        ]);
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'Numéro commande'),
            DateField::new('createdAt', 'Date'),
            NumberField::new('state', 'Statut')->setTemplatePath('admin/state.html.twig'),
            AssociationField::new('user', 'Utilisateur'),
            TextField::new('carrierName', 'Transporteur'),
            NumberField::new('totalTva', 'Total TVA'),
            NumberField::new('totalWt', 'Total TTC'),
        ];
    }
}