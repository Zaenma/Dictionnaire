<?php

namespace Traduction;

class Langues
{
    /**
     * Constructeur
     *
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * recupere toutes les langues
     * 
     * @return array
     */
    public function recupere_langues(): array
    {
        $sql = "SELECT * FROM langues";

        $langues = $this->pdo->query($sql);
        $resultats = $langues->fetchAll();
        return $resultats;
    }

    /**
     * Récupère une langue en question
     *
     * @param integer $id_langue - identifiant de la lange
     * @return array
     */
    public function recuper_langue(int $id_langue): array
    {
        $sql = "SELECT * FROM langues WHERE id = $id_langue";

        $langue = $this->pdo->query($sql);
        $resultat = $langue->fetch();
        return $resultat;
    }

    /**
     * Ajouter une langue dans la base de données
     *
     * @param string $langue
     * @param integer $id_admin
     * @return boolean
     */
    public function ajouter_langue(string $langue, int $id_admin): bool
    {
        $sql = $this->pdo->prepare("INSERT INTO langues (nom, id_admin) VALUES (:nom, :id_admin)");
        $sql->bindParam(':nom', $langue);
        $sql->bindParam(':id_admin', $id_admin);

        return $sql->execute();
    }

    public function modifier_langue(string $langue, int $id_langue): bool
    {
        $sql = $this->pdo->prepare("UPDATE langues SET nom = ? WHERE id = ?");
        return $sql->execute([$langue, $id_langue]);
    }

    /**
     * Supprimer une langue
     *
     * @param integer $id_langue
     * @return boolean
     */
    public function supprimer_langue(int $id_langue): bool
    {
        $sql = $this->pdo->prepare("DELETE FROM langues WHERE id = ?");

        return $sql->execute([$id_langue]);
    }
}
