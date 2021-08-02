<?php

namespace Traduction;

use App\Valide;

class Validator extends Valide
{
    public function valide(array $donnees)
    {
        parent::validees($donnees);
        $this->validee('expression', 'taille_min', 5);
        return $this->erreurs;
    }
}
