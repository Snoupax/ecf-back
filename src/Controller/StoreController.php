<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class StoreController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ManagerRegistry $doctrine): Response
    {

        $produits = $doctrine->getRepository(Produit::class)->findBy(
            ['actif' => true],
        );
        return $this->render('store/index.html.twig', ['produits' => $produits]);
    }

    #[Route('/panier', name: 'panier')]
    public function panier(SessionInterface $session, ProduitRepository $produitRepository, Request $request): Response
    {
        if ($request->request->get('commander')) {
            $session->clear();
        }

        $panier = $session->get("panier", []);
        $dataPanier = [];
        $total = 0;

        foreach ($panier as $id => $quantite) {
            $produit = $produitRepository->find($id);
            $dataPanier[] = [
                "produit" => $produit,
                "quantite" => $quantite
            ];
            $total += $produit->getTarif() * $quantite;
        }



        return $this->render('store/panier.html.twig', ['dataPanier' => $dataPanier, 'total' => $total]);
    }

    #[Route('/p/{id}/{slug}', name: 'produit', requirements: ['id' => "\d+", 'slug' => '.{1,}'])]
    #[ParamConverter('Produit', class: Produit::class)]
    public function produit(Produit $produit, Request $request, SessionInterface $session): Response
    {

        // SI VOUS AVEZ CLIQUEZ SUR LE BOUTON AJOUT AU PANIER (POLI2/PAGE23)
        // POUR LES SESSION (POLI2/PAGE27) POUR AVOIR UN EXEMPLE
        if ($request->request->get('ajout')) {
            //AFFICHAGE DANS LA TOOLBAR DEBUG DES DONNES DU FORMULAIRE

            dump($request->request->get('quantite'));
            dump($request->request->get('produit'));

            $quantite = $request->request->get('quantite');
            $produitName = $produit->getNom();

            // On récupère le panier actuel
            $panier = $session->get("panier", []);
            $id = $produit->getId();

            if (!empty($panier[$id])) {
                $panier[$id] = $panier[$id] + $quantite;
            } else {
                $panier[$id] = $quantite;
            }

            // On sauvegarde dans la session
            $session->set("panier", $panier);


            $this->addFlash('info', "Vous avez ajouté $quantite X $produitName votre panier");
        }


        return $this->render('store/produit.html.twig', ['produit' => $produit]);
    }


    public function menu(SessionInterface $session)
    {
        $listMenu = [
            ['title' => 'Super Boutique', 'text' => 'Accueil', 'url' => $this->generateUrl('home',)],
            ['title' => 'Panier', 'text' => 'Panier', 'url' => $this->generateUrl('panier',)],
        ];
        $panier = $session->get('panier');
        if (!empty($panier)) {
            $countPanier = count($panier);
            return $this->render('parts/menu.html.twig', ['listMenu' => $listMenu, 'countPanier' => $countPanier]);
        }

        return $this->render('parts/menu.html.twig', ['listMenu' => $listMenu]);
    }
}
