<?php 
namespace App\Controller;

use App\Entity\Reservations;
use App\Entity\Supplements;
use App\Entity\ReservationsSupplements;
use App\Form\ReservationsNormalType;
use App\Form\SupplementsChoiceType;
use App\Repository\ReservationsRepository;
use App\Repository\ThemesRepository;
use App\Repository\ReservationsSupplementsRepository;
use App\Repository\SupplementsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form;


class ReservationsController extends AbstractController{


    // injecter le service ReservationsRepository
	public function __construct(
		private ReservationsRepository $reservationsRepository,
		private SupplementsRepository $supplementsRepository,
		private ThemesRepository $themesRepository,
		private ReservationsSupplementsRepository $reservationsSupplementsRepository,
		private RequestStack $requestStack,
		private EntityManagerInterface $entityManager)
	{
		
	}


    #[Route('/reservations', name:'reservations.index')]
    public function index():Response
    {
        // récupérer toutes les entrées de la table réservations pour cet utilisateur
		$results = $this->reservationsRepository->findAll();
		//dd($results);

		return $this->render('reservations/index.html.twig', [
			'results' => $results
        ]);
    }

    #[Route('/reservations/new/{id}', name:'reservations.new')]
	public function form(int $id):Response
	{
		// instances : classe de formulaire et l'entité
		$type = ReservationsNormalType::class;

		// si l'id est null, un thème est en train d'être ajouté, sinon il est modifié
		$entity = new Reservations();

		// création du formulaire
		$form = $this->createForm($type, $entity);

		// récupération de la saisie dans la requête HTTP
		$form->handleRequest( $this->requestStack->getCurrentRequest() );

		// si le formulaire est valide
		if($form->isSubmitted() && $form->isValid()){
			
			
			$entity->setThemeId($id);
			$entity->setUserId($this->getUser()->getId());
			$this->entityManager->persist($entity);
			$this->entityManager->flush();

			// message de confirmation
			// message flash : message stocké en session
			$message = 'Réservation créé';
			$this->addFlash('notice', $message);
			//dd($form);

			// redirection vers une route
			return $this->redirectToRoute('reservations.index');

		}
		// afficher la vue
		return $this->render('reservations/form.html.twig', [
			'form' => $form->createView(),
			]);
		
	}


	// Voir les suppléments
	#[Route('/reservations/supplements/{id}', name: 'reservations.supplements')]
	public function addSupplement(int $id):Response
	{
		// sélectionner les suppléments
		$supplements = $this->supplementsRepository->findAll();

		$currentSupplements = $this->reservationsSupplementsRepository->findBy(array('reservationsId' => $id));
		//dd($currentSupplements);

		$type = SupplementsChoiceType::class;

		//dd($supplements);

		// création du formulaire
		$form = $this->createForm($type, $supplements);

		//dd($form);

		$form->handleRequest( $this->requestStack->getCurrentRequest() );

		// si le formulaire est valide
		if($form->isSubmitted() && $form->isValid()){

			foreach ($form->all()["suppl"]->getData() as $entry){	
				$suppLiaison = new ReservationsSupplements;
				$suppLiaison->setReservationsId($id);
				$suppLiaison->setSupplementsId($entry->getId());

				$this->entityManager->persist($suppLiaison);
			}
			$this->entityManager->flush();
			
			$message = 'Suppléments ajoutés';
			$this->addFlash('notice', $message);

			// redirection vers une route
			return $this->redirectToRoute('reservations.index');
		}

		// afficher la vue
		return $this->render('reservations/supplements.html.twig', [
			'form' => $form->createView(),
			'supplem' => $supplements,
			]);
	
	}

    #[Route('/reservations/devis/{id}', name: 'reservations.devis')]
	public function getDevis(int $id):Response
	{

        $supplementsLiens = $this->reservationsSupplementsRepository->findBy(array('reservationsId' => $id));
        $suppArray = array();
        $item = 0;
        $reservation = $this->reservationsRepository->find($id);
        $theme = $this->themesRepository->find($reservation->getThemeId());
        $price = $theme->getPrix();
        foreach ($supplementsLiens as $suppl){
            $supplement = $this->supplementsRepository->find($suppl->getSupplementsId());
            array_push($suppArray, $supplement);
            $price += $supplement->getPrix();
        } 

        return $this->render('reservations/devis.html.twig', [
			'supplem' => $suppArray,
            'theme' => $theme,
            'prix' => $price
			]);
	
    }

}