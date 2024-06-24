<?php

namespace App\Controller\Api;

use App\Service\ColorPalette;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClassifycolorsController extends AbstractController
{
    #[Route('/api/classifycolors', name: 'app_api_classifycolors')]
    public function classifycolors(): JsonResponse
    {
        // permet d'obtenir une couleur aléatoire au format hexadécimal
        $randomcolor = '#' . strtoupper(dechex(rand(0, 10000000)));

        // instancie le service ColorPalette qui créer les couleurs du plus clair au plus foncé à partir d'une couleur qu'on lui transmets
        $color = new ColorPalette($randomcolor);

        // création de la palette de couleur
        $colorPalette = $color->createPalette();
        

        return $this->json([
            "colors" => $colorPalette
        ]);
    }
}
