<?php 
namespace App\Controller\Admin;

use App\Entity\Reservations;
use App\Entity\Supplements;
use App\Entity\ReservationsSupplements;
use App\Form\ReservationsType;
use App\Form\SupplementsChoiceType;
use App\Repository\ReservationsRepository;
use App\Repository\ReservationsSupplementsRepository;
use App\Repository\SupplementsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form;

#[Route('/admin')]

class ReservationsController extends AbstractController{


    // injecter le service ReservationsRepository
	public function __construct(
		private ReservationsRepository $reservationsRepository,
		private SupplementsRepository $supplementsRepository,
		private ReservationsSupplementsRepository $reservationsSupplementsRepository,
		private RequestStack $requestStack,
		private EntityManagerInterface $entityManager)
	{
		
	}


    #[Route('/reservations', name:'admin.reservations.index')]
    public function index():Response
    {
        // récupérer toutes les entrées de la table supplement
		$results = $this->reservationsRepository->findAll();
		//dd($results);

		return $this->render('admin/reservations/index.html.twig', [
			'results' => $results
        ]);
    }

    #[Route('/reservations/form', name:'admin.reservations.form')]
	#[Route('/reservations/form/{id}', name:'admin.reservations.form.update')]
	public function form(int $id = null):Response
	{
		// instances : classe de formulaire et l'entité
		$type = ReservationsType::class;

		// si l'id est null, un thème est en train d'être ajouté, sinon il est modifié
		$entity = $id ? $this->reservationsRepository->find($id) : new Reservations();

		// création du formulaire
		$form = $this->createForm($type, $entity);

		// récupération de la saisie dans la requête HTTP
		$form->handleRequest( $this->requestStack->getCurrentRequest() );

		// si le formulaire est valide
		if($form->isSubmitted() && $form->isValid()){
			
			
			// l'entité est automatiquement remplie
			//dd($entity);
			/*
				insérer l'entité dans la table
					- persist : la requête sql est en file d'attente
					- flush : exécuter la file d'attente des requêtes
			*/
			$this->entityManager->persist($entity);
			$this->entityManager->flush();

			// message de confirmation
			// message flash : message stocké en session
			$message = $id ? 'Réservation modifiée' : 'Réservation créé';
			$this->addFlash('notice', $message);
			//dd($form);

			// redirection vers une route
			return $this->redirectToRoute('admin.reservations.index');

		}
		// afficher la vue
		return $this->render('admin/reservations/form.html.twig', [
			'form' => $form->createView(),
			]);
		
	}

	// supprimer un theme
	#[Route('/reservations/remove/{id}', name: 'admin.reservations.remove')]
	public function remove(int $id):Response
	{
		// sélectionner l'entité
		$entity = $this->reservationsRepository->find($id);

		// supprimer l'entité sélectionnée
		$this->entityManager->remove($entity);
		$this->entityManager->flush();

		// message de confirmation
		$this->addFlash('notice', 'Réservation supprimée');

		// redirection
		return $this->redirectToRoute('admin.reservations.index');
	}

	// Voir les suppléments
	#[Route('/reservations/supplements/{id}', name: 'admin.reservations.supplements')]
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
			return $this->redirectToRoute('admin.reservations.index');
		}

		// afficher la vue
		return $this->render('admin/reservations/supplements.html.twig', [
			'form' => $form->createView(),
			'supplem' => $supplements,
			]);
	
	}
}