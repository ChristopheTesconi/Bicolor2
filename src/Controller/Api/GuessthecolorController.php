<?php

namespace App\Controller\Api;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GuessthecolorController extends AbstractController
{
    #[Route('/api/guessthecolor', name: 'app_api_guessthecolor')]
    public function guessthecolor(): JsonResponse
    {
        // Je crée un tableau d'options vide
        $options = [];

        // Je définis le nombre de couleurs aléatoires que je veux générer
        $numberOfColors = 4;

        // Je génère des couleurs aléatoires et les ajoute au tableau d'options
        for ($i = 0; $i < $numberOfColors; $i++) {
            // Génère un nombre aléatoire entre 0 et 16777215 (FFFFFF en hexadécimal)
            $randomNumber = rand(0, 16777215);

            // Convertit le nombre en format hexadécimal
            $hexColor = '#' . strtoupper(dechex($randomNumber));

            // Ajoute la couleur générée au tableau d'options
            $options[] = $hexColor;
        }

        // Je récupère une couleur dans le tableau options pour les valeurs color et answer du jeu
        $randomColor = $options[array_rand($options)];

        // Je retourne le tableau d'options sous forme de réponse JSON
        return $this->json([
            'options' => $options,
            'color' => $randomColor,
            'answer' => $randomColor
        ]);
    }
}
