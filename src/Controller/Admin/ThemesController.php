<?php 
namespace App\Controller\Admin;

use App\Entity\Themes;
use App\Repository\ThemesRepository;
use App\Form\ThemesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\ByteString;

#[Route('/admin')]

class ThemesController extends AbstractController{

    // injecter le service ThemesRepository
	public function __construct(private ThemesRepository $themesRepository, private RequestStack $requestStack, private EntityManagerInterface $entityManager)
	{
		
	}

    // lister les themes
	#[Route('/themes', name:'admin.themes.index')]
	public function index():Response
	{
		// récupérer toutes les entrées de la table themes
		$results = $this->themesRepository->findAll();
		//dd($results);

		return $this->render('admin/themes/index.html.twig', [
			'results' => $results
        ]);
    }

	// ajouter/modifier un thème
	#[Route('/themes/form', name:'admin.themes.form')]
	#[Route('/themes/form/{id}', name:'admin.themes.form.update')]
	public function form(int $id = null):Response
	{
		// instances : classe de formulaire et l'entité
		$type = ThemesType::class;

		// si l'id est null, un thème est en train d'être ajouté, sinon il est modifié
		$entity = $id ? $this->themesRepository->find($id) : new Themes();

		// création du formulaire
		$form = $this->createForm($type, $entity);

		// récupération de la saisie dans la requête HTTP
		$form->handleRequest( $this->requestStack->getCurrentRequest() );

		// si le formulaire est valide
		if($form->isSubmitted() && $form->isValid()){
			if($entity->getImageIllustration() instanceof UploadedFile){
				$filename = ByteString::fromRandom(32)->lower();
				$extension = $entity->getImageIllustration()->guessClientExtension();

				$entity->getImageIllustration()->move('img',"$filename.$extension");
				$entity->setImageIllustration("$filename.$extension");

				// if ($entity->getId()){
				// 	unlink("img/{$entity->prevImage}");
				// }
			}
			
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
			$message = $id ? 'Thème modifié' : 'Thème créé';
			$this->addFlash('notice', $message);
			//dd($form);

			// redirection vers une route
			return $this->redirectToRoute('admin.themes.index');

		}
		// afficher la vue
		return $this->render('admin/themes/form.html.twig', [
			'form' => $form->createView(),
			]);
		
	}

	// supprimer un theme
	#[Route('/themes/remove/{id}', name: 'admin.themes.remove')]
	public function remove(int $id):Response
	{
		// sélectionner l'entité
		$entity = $this->themesRepository->find($id);

		// supprimer l'entité sélectionnée
		$this->entityManager->remove($entity);
		$this->entityManager->flush();

		// message de confirmation
		$this->addFlash('notice', 'Theme supprimé');

		// redirection
		return $this->redirectToRoute('admin.themes.index');
	}

}
?>