<?php

namespace App\Controller;

/* Appel des éléments qui seront utilisés par ce controller */

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\ProductRepository;
use App\Entity\Product;
use App\Entity\ProductCategory;
use App\Entity\Orders;
use App\Repository\OrdersRepository;
use App\Entity\Users;
use App\Entity\OrderDetails;
use App\Repository\OrderDetailsRepository;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Security\Core\User\UserInterface;

class CartController extends AbstractController {

    /**
     * @Route("/cart", name="cart_index")
     */
    public function index(SessionInterface $session, ProductRepository $productRepository) {
        
        $count = 0;
        /* Impute a la variable '$panier' la valeur récupérée 'panier' */
        $panier = $session->get('panier', []);
        /* Indique que la variable $panierWithData est un tableau */
        $panierWithData = [];
        /* Pour chaque variable id/quantité dans la variable 'panier' */
        foreach ($panier as $id => $quantity) {
            /* On impute un autre tableau dans le tableau de variable 'panierWithData' les valeurs 'product' et 'quantity' */
            $panierWithData[] = [
                'product' => $productRepository->find($id),
                'quantity' => $quantity
            ];
        }
        /* On impute la valeur 0 à la variable total */
        $total = 0;
        /* Pour chaque variable 'item' dans le tableau de valeur 'panierWithData' */
        foreach ($panierWithData as $item) {
            /* Impute a la variable $totalItem la multiplication de l'object 'product' à l'object 'quantity' */
            $totalItem = $item['product']->getPrice() * $item['quantity'];
            /* Additionne la variable 'totalItem' a la variable 'total' */
            $total += $totalItem;
            /* Additionne la valeur 'quantity' de la variable 'item' à la variable 'count' */
            $count += $item['quantity'];
        }
        //ajoute un champs Fcount a la session avec le nombre de produit total dans le panier 
        $session->set('Fcount', $count);
        $session->set('FullCart',$panierWithData);
         // Si Le panier est valider alors vide le panier .
      if($session->get('Validate',"false")=="true"){
            unset($panierWithData);
            $session->remove('Validate');
            $session->remove('panier');
            $session->set('Fcount',0);
            return $this->redirectToRoute("profil");
        }
        /* Renvoie la fonction sur la vue */
        return $this->render('cart/index.html.twig', [
                    'items' => $panierWithData,
                    'total' => $total,
                    'Cquantity' => $count
        ]);
    }

    /**
     * @Route("/cart/add/{id}" , name="cart_add")
     */
    public function add($id, SessionInterface $session) {
        /* Récupère la valeur 'panier' et l'impute a la variable 'panier', dans le cas où il ne récupère rien, il créer un tableau vide */
        $panier = $session->get('panier', []);
        /* Récupère la valeur 'Fcount' et l'impute à la variable 'count', dans le cas où il récupère rien, il impute la valeur 0 */
        $count = $session->get('Fcount', 0);
        /* Si la variable 'id' de la variable 'panier' n'est pas vide */
        if (!empty($panier[$id])) {
            /* Ajoute 1 à la variable 'id' du tableau 'panier' */
            $panier[$id]++;
            /* Ajoute 1 à la variable 'count' */
            $count++;
        } else {
            /* Impute la valeur 1 à la variable 'id' du tableau 'panier' */
            $panier[$id] = 1;
            /* Ajoute 1 à la variable 'count' */
            $count++;
        }
        /* Attribution de la valeur 'panier' à la variable 'panier' */
        $session->set('panier', $panier);
        /* Attribution de la valeur 'Fcount' à la variable 'count' */
        $session->set('Fcount', $count);
        /* Flash affiche une notification sur la vue catalog */
        $this->addFlash('add_product', 'Produit ajouté au panier');

        /* Retourne l'évènement en cours vers 'catalog' */
        return $this->redirectToRoute("catalog");
    }

    /**
     * @Route("/cart/remove/{id}", name="cart_remove")
     */
    public function remove($id, SessionInterface $session) {
        /* Récupère la valeur 'panier' et l'impute à la variable 'panier, dans le cas où il ne récupère rien, il créer un tableau vide */
        $panier = $session->get('panier', []);
        /* Si la variable 'id' de la variable 'panier' n'est pas vide */
        if (!empty($panier[$id])) {
            if($_POST['nb_product']<=0){              
                    /* Vidage du panier */ 
                unset($panier[$id]);
               }else{
               $panier[$id]= $_POST['nb_product'];          
               }            
      }else{
      unset($panier[$id]);
      }
      /* Attribution de la valeur 'panier' à la variable 'panier' */
      $session->set('panier',$panier);
        // Flash affiche une notification sur la vue cart
        $this->addFlash('delete_product', 'Quantité mise à jour');
        /* Retourne l'évènement en cours vers la page 'cart_index' */
        return $this->redirectToRoute("cart_index");
    }
    /**
    * @Route("/cart/validate", name="cart_validate")
    */
    
    // Validation du panier.
    public function validate(SessionInterface $session, UserInterface $user, OrdersRepository $listOrders) {         
        $order = new Orders();  //Crée un objet Orders
        $order->setUsersID($user);    //récupère les valeurs
        $order->setDate(new \DateTime());
        $order->setStatus("En cours de traitement"); // a changer dans l'interface employés
        /*** Rajouter les valeurs ***/
        $order->setBill('jeej');
        $order->setDeliveryForm('jeej');
        
         $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($order);
            $entityManager->flush();
            
            foreach ($session->get('FullCart',[])as $produit){  //pour chaque produit
                $orderDetails = new OrderDetails(); //on crée un objet OrderDetails
                $orderDetails->setOrders($order);//récupere la valeur de order
                $orderDetails->setQuantity($produit['quantity']); 
                $orderDetails->setProduct($produit['product']);
            $entityManager->merge($orderDetails);
            $entityManager->flush();    //On le rajoute dans la base de donnée
            }
            
            //Rajoute toute les Commandes au fichier JSON
            $encoders = array(new JsonEncoder());  //crée l'encoder (format Array => json) 
            $normalizers = array(new ObjectNormalizer());   //crée le normalizer (format Objet => Array)
            $serializer = new Serializer($normalizers, $encoders);  //crée le serializer (format objet => json)
            $listOrders=$listOrders->findAll(); //récupère toute les commandes
            $jsonOrders= $serializer->serialize($listOrders, 'json');   //les met en format json
            $fileOrders=fopen('../public/json/orders.json','w+');   //ouvre le fichier orders.json en mode écriture
            fputs($fileOrders,$jsonOrders);   //écrit le resultat dans le fichier
            fclose($fileOrders);    //ferme le fichier
            
            $session->set('Validate',"true");
        return $this->redirectToRoute("cart_index");    //redirige vers le profil
    }
    
    
    
      }



