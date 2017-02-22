<?php
/**
 * Created by PhpStorm.
 * User: Ben-usr
 * Date: 08/02/2017
 * Time: 15:56
 */

namespace MDL\CoreBundle\Controller;

use MDL\CoreBundle\Entity\Registration;
use MDL\CoreBundle\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class MainController extends Controller
{
    public function homeAction()
    {
        $content = $this->get('templating')->render('MDLCoreBundle:Home:index.html.twig');
        return new Response($content);
    }

    public function registrationAction(Request $request)
    {
        //Création d'une nouvelle réservation
        $registration = new Registration();
        //On créé le formulaire déjà défini dans le fichier RegistrationType
        $form = $this->get('form.factory')->create(RegistrationType::class, $registration);

        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('MDLCoreBundle:Pricing')
        ;

        $listPrices = $repository->findAll();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            //Création du code de réservation unique

            //Récupération de la date du jour
            $currentDate = new \DateTime();

            //Détermination du code à utiliser selon le type de réservation
            if($registration->getTicketDuration() == 'Demi-journée')
            {
                $dayCode = 'DJ';
            }else
            {
                $dayCode = 'JC';
            }

            //Génération d'un identifiant unique pour la réservation
            $idRegistration = md5(uniqid(rand(), true));

            //Concaténation de la date du jour du type de réservation et de l'id dans la BDD pour formé le code de réservation
            $registrationCode = ''. date_format($currentDate,'Y') .''.date_format($currentDate,'m').''.date_format($currentDate,'d').''.$dayCode.''.$idRegistration.'';
            $registration->setRegistrationCode($registrationCode);

            //Création des compteurs pour le nombre de ticket et le prix total de la réservation
            $ticketNb =0;
            $totalPrice =0;

            foreach($registration->getVisitors() as $visitor)
            {
                $birthdayVisitor = $visitor ->getDateOfBirth();
                $ageVisitor = $currentDate->diff($birthdayVisitor)->y;

                //Si l'utilisateur coche le tarif réduit et que le visiteur n'est pas éligible au tarif enfant ou moins de 4 ans
                if($visitor->getReducedPricing() == true && $ageVisitor >=12)
                {
                    //On applique le tarif réduit
                    $visitor->setPrice($repository->find(5));
                }else
                    //Sinon si le visiteur n'est pas éligible au tarif réduit on lui applique le tarif correspondant à son âge
                {
                    switch ($ageVisitor)
                    {
                        case ($ageVisitor >=0 && $ageVisitor <4) :
                            $visitor->setPrice($repository->find(1));
                            break;
                        case ($ageVisitor >=4 && $ageVisitor <12) :
                            $visitor->setPrice($repository->find(2));
                            break;
                        case ($ageVisitor >=12 && $ageVisitor <60) :
                            $visitor->setPrice($repository->find(3));
                            break;
                        case ($ageVisitor >=60) :
                            $visitor->setPrice($repository->find(4));
                            break;
                    }
                }
                //On affecte la réservation correspondant au visiteur
                $visitor->setRegistration($registration);


                //on compte le nombre de ticket
                $ticketNb++;

                //On calcule le prix total a payé pour cette réservation
                $pricing = $visitor->getPrice();
                $totalPrice += $pricing->getPrice();
            }

            //On affecte les valeurs  de prix total et du nombre de billets, obtenus dans la boucle, à l'objet réservation correspondant
            $registration->setNbTicket($ticketNb);
            $registration->setTotalPrice($totalPrice);


            //On ajoute les données dans la bdd
            $em = $this->getDoctrine()->getManager();
            $em->persist($registration);
            $em->flush();


            return $this->redirectToRoute('mdl_core_payment', array('id' => $registration->getId()));
        }

        return $this->render('MDLCoreBundle:Registration:registration.html.twig', array(
            'form' => $form->createView(),
            'listPrices' => $listPrices,
        ));

    }

    public function paymentAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        // Pour récupérer une seule annonce, on utilise la méthode find($id)
        $registration = $em->getRepository('MDLCoreBundle:Registration')->find($id);

        // $advert est donc une instance de OC\PlatformBundle\Entity\Advert
        // ou null si l'id $id n'existe pas, d'où ce if :
        if (null === $registration) {
            throw new NotFoundHttpException("Réservation introuvable");
        }

        // Récupération de la liste des candidatures de l'annonce
        $listVisitors = $em
            ->getRepository('MDLCoreBundle:Visitor')
            ->findBy(array('registration' => $registration))
        ;

        $tableLines = [];

        foreach($listVisitors as $key=>$visitor )
        {
            $pricing = $visitor->getPrice();
            $tableLines[$key]=array('nom'=>$visitor->getLastname(), 'prenom'=>$visitor->getFirstname(), 'tarif'=>$pricing->getType(), 'prix'=>$pricing->getPrice());
        }

        $content = $this->get('templating')->render('MDLCoreBundle:Registration:payment.html.twig',array(
            'tableLines'=>$tableLines,
            'registration'=>$registration,
        ));
        return new Response($content);
    }
}