<?php 
namespace App\Controller\Admin;

use App\Entity\Supplements;
use App\Repository\SupplementsRepository;
use App\Form\SupplementsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManagerInterface;


#[Route('/admin')]

class SupplementsController extends AbstractController{

    // injecter le service SupplementsRepository
	public function __construct(private SupplementsRepository $supplementsRepository, private RequestStack $requestStack, private EntityManagerInterface $entityManager)
	{
		
	}


    #[Route('/supplements', name:'admin.supplements.index')]
    public function index():Response
    {
        // récupérer toutes les entrées de la table supplement
		$results = $this->supplementsRepository->findAll();
		//dd($results);

		return $this->render('admin/supplements/index.html.twig', [
			'results' => $results
        ]);
    }

    #[Route('/supplements/form', name:'admin.supplements.form')]
	#[Route('/supplements/form/{id}', name:'admin.supplements.form.update')]
	public function form(int $id = null):Response
	{
		// instances : classe de formulaire et l'entité
		$type = SupplementsType::class;

		// si l'id est null, un thème est en train d'être ajouté, sinon il est modifié
		$entity = $id ? $this->supplementsRepository->find($id) : new Supplements();

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
			$message = $id ? 'Supplément modifié' : 'Supplément créé';
			$this->addFlash('notice', $message);
			//dd($form);

			// redirection vers une route
			return $this->redirectToRoute('admin.supplements.index');

		}
		// afficher la vue
		return $this->render('admin/supplements/form.html.twig', [
			'form' => $form->createView(),
			]);
		
	}

	// supprimer un theme
	#[Route('/supplements/remove/{id}', name: 'admin.supplements.remove')]
	public function remove(int $id):Response
	{
		// sélectionner l'entité
		$entity = $this->supplementsRepository->find($id);

		// supprimer l'entité sélectionnée
		$this->entityManager->remove($entity);
		$this->entityManager->flush();

		// message de confirmation
		$this->addFlash('notice', 'Supplément supprimé');

		// redirection
		return $this->redirectToRoute('admin.supplements.index');
	}

}