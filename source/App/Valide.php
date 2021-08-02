<?php


namespace App;

class Valide
{
    private $donnees;
    protected $erreurs = [];

    public function __construct(array $donnees = [])
    {
        $this->vadonneesr = $donnees;
    }

    /**
     * validee
     *
     * @param  mixed $donnees
     * @return void
     */
    public function validees(array $donnees)
    {
        $this->erreurs = [];
        $this->donnees = $donnees;

        return $this->erreurs;
    }

    /**
     * validee
     *
     * @param  mixed $champ
     * @param  mixed $regle_validation
     * @param  mixed $parametres
     * @return bool
     */
    public function validee(string $champ, string $regle_validation, ...$parametres)
    {
        if (!isset($this->donnees[$champ])) {
            $this->erreurs[$champ] = "Le champs $champ n'est pas rempli";
            return FALSE;
        } else {
            return call_user_func([$this, $regle_validation], $champ, ...$parametres);
        }
    }

    /**
     * taille_min
     *
     * @param  mixed $champ
     * @param  mixed $taille
     * @return void
     */
    public function taille_min(string $champ, int $taille)
    {
        if (mb_strlen($champ) < $taille) {
            $this->erreurs[$champ] = "Le champs $champ doit avoir au minimum $taille caractères pour bien être explicite !";
        }
    }
}
