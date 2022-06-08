<?php 
namespace App\Controller;

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


class ThemesController extends AbstractController{

    // injecter le service ThemesRepository
	public function __construct(private ThemesRepository $themesRepository, private RequestStack $requestStack, private EntityManagerInterface $entityManager)
	{
		
	}

    // lister les themes
	#[Route('/themes', name:'themes.index')]
	public function index():Response
	{
		// récupérer toutes les entrées de la table themes
		$results = $this->themesRepository->findAll();
		//dd($results);

		return $this->render('themes/index.html.twig', [
			'results' => $results
        ]);
    }

	

}
?>