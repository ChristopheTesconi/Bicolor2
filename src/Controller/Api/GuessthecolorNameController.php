<?php

namespace App\Controller\Api;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use ourcodeworld\NameThatColor\ColorInterpreter as NameThatColor;

// Pour installer le bundle NameThatColor = composer require ourcodeworld/name-that-color:dev-master  
// :dev-master car le créateur du bundle n'a pas mis en ligne une version stable

class GuessthecolorNameController extends AbstractController
{
    #[Route('/api/guessthecolor/name', name: 'app_api_guessthecolor_name')]
    public function guessthecolorName(): JsonResponse
    {
        // Je crée un tableau d'options vide
        $options = [];

        // Je définis le nombre de couleurs aléatoires que je veux générer
        $numberOfColors = 4;

        for ($i = 0; $i < $numberOfColors; $i++) {
            //permet d'obtenir une couleur hexadécimal aléatoire
            $randomColor = '#' . strtoupper(dechex(rand(0, 10000000)));

            // nouvelle instance du bundle NameThatColor
            $instance = new NameThatColor();

            //génère un tableau avec une couleur aléatoire au format hexadécimal et son nom
            $result = $instance->name($randomColor);

            // j'ajoute le tableau de la couleur dans le tableau d'options
            $options[] = $result;
        }
        
        // Je récupère une couleur dans le tableau options pour les valeurs color et answer du jeu
        $randomColor = $options[array_rand($options)];
        // je récupère uniquement le format hexadecimal pour l'ajouter au json
        $hexColor = $randomColor["hex"];
        $nameColor = $randomColor["name"];
        


        return $this->json([
            "options" => $options,
            "color" => $nameColor,
            "answer" => $hexColor
        ]);
    }
}
