<?php

namespace App\Service;

class ColorPalette{
    public $color;

    public function __construct($color){
        $this->color = $color;
    }

    /**
     * Modifie la luminosité d'une couleur envoyé au format hexadécimal
     *
     * @param  $hex couleur hexadecimal
     * @param  integer $diff
     * @return void
     */
    public function color_mod($hex, $diff) {

        // passe le code hexadecimal $hex en format RGB dans un tableau
        $rgb = str_split(trim($hex, '# '), 2);

        // Parcours chaque composante de couleur (rouge, vert, bleu) et ajuste la valeur
        foreach ($rgb as &$hex) {

            // Convertit la composante hexadécimale en décimale
            $dec = hexdec($hex);

             // Ajoute ou soustrait la différence $diff à la composante (pour augmenter ou diminuer la luminosité)
            if ($diff >= 0) {
                $dec += $diff;
            }
            else {
                $dec -= abs($diff);    
            }

            // Assure que la valeur reste dans la plage valide de 0 à 255
            $dec = max(0, min(255, $dec));

            // Convertit la valeur décimale en hexadécimal et ajoute un zéro à gauche si nécessaire
            $hex = str_pad(dechex($dec), 2, '0', STR_PAD_LEFT);
        }

        // Combine les composantes modifiées en un code couleur hexadécimal complet
        return '#'.implode($rgb);
    }

    /**
     * Créer la palette de 4 couleurs du plus clair au plus foncée
     *
     * @param integer $colorCount
     * @return void
     */
    public function createPalette($colorCount=10){

        // création d'un tableau vide pour la palette de couleur
        $colorPalette = array();
        
        // Génère chaque couleur en fonction de la variation de lumière spécifiée
        for($i=1; $i<=$colorCount; $i++){
            if($i == 1){
                $color = $this->color;
                $colorVariation = -($i * 2);
            }
            if($i == 2){
                $color = $this->color;
                $colorVariation = -($i * 3);
            }
            if($i == 3){
                $color = $this->color;
                $colorVariation = -($i * 4);
            }
            if($i == 4){
                $color = $this->color;
                $colorVariation = -($i * 5);
            }
            if($i == 5){
                $color = $this->color;
                $colorVariation = -($i * 6);
            }
            if($i == 6){
                $color = $this->color;
                $colorVariation = -($i * 7);
            }
            if($i == 7){
                $color = $this->color;
                $colorVariation = -($i * 8);
            }
            if($i == 8){
                $color = $this->color;
                $colorVariation = -($i * 9);
            }
            if($i == 9){
                $color = $this->color;
                $colorVariation = -($i * 9);
            }
            if($i == 10){
                $color = $this->color;
                $colorVariation = -($i * 11);
            }
            
            
            // Crée une nouvelle couleur en appliquant la variation de luminosité à la couleur de base.
            $newColor = $this->color_mod($color, $colorVariation);

            // la nouvelle couleur est ajoutée au tableau
            array_push($colorPalette, $newColor);
        }
        return $colorPalette;
        
    }
}