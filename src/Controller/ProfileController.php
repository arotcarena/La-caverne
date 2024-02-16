<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/mon-compte')]
class ProfileController extends AbstractController
{
    #[Route('/informations-personnelles', name: 'profile_infos')]
    public function infos(): Response
    {
        return $this->render('view/profile/infos.html.twig', [
            'current_menu' => 'profile',
            'current_submenu' => 'profile_infos'
        ]);
    }

    #[Route('/mes-adresses', name: 'profile_addresses')]
    public function addresses(AddressRepository $addressRepository, EntityManagerInterface $em, Request $request): Response
    {
        if(isset($_POST['delete']))         //A FAIRE AJOUTER CSRF
        {
            $address = $addressRepository->find($_POST['delete']);
            $em->remove($address);
            $em->flush();
            $this->addFlash('success', 'L\'adresse a bien été supprimée !');
        }

        if(isset($_POST['update']))  // dans le cas ou le formulaire est déjà soumis, un input hidden envoie le $_POST['update']
        {                                   // ce qui permet de traquer la bonne entité pour le flush qui va suivre
            $address = $addressRepository->find($_POST['update']);
            $action = 'modifiée';
        }
        else
        {
            $address = new Address();
            $action = 'ajoutée';
        }

        $form = $this->createForm(AddressType::class, $address);
        
        $form->handleRequest($request);
        if($form->isSubmitted() AND $form->isValid())
        {
            //a gérer différemment / problème des checkbox qui renvoient false si non cochées
            if($address->isDelivery() === false)
            {
                $address->setDelivery(null);
            }
            if($address->isInvoice() === false)
            {
                $address->setInvoice(null);
            }
            $address->setUser($this->getUser());
            $em->persist($address);
            $em->flush();
            $this->addFlash('success', 'L\'adresse a bien été '.$action.' !');
            return $this->redirectToRoute('profile_addresses');
        }
        else
        {
            $em->detach($address);  // pour éviter que la requête suivante récupére la address hydratée dans form et non valide
        }
        $addresses = $addressRepository->findByUser($this->getUser());
        
        return $this->renderForm('view/profile/addresses.html.twig', [
            'current_menu' => 'profile',
            'current_submenu' => 'profile_addresses',
            'addresses' => $addresses,
            'address_selected' => $address,
            'form' => $form
        ]);
    }

    #[Route('/mes-commandes', name: 'profile_orders')]
    public function orders(): Response
    {
        return $this->render('view/profile/orders.html.twig', [
            'current_menu' => 'profile',
            'current_submenu' => 'profile_orders'
        ]);
    }

}
