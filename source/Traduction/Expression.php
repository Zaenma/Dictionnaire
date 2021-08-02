<?php

namespace Traduction;

class Expression
{

    /**
     * Constructeur de la classe
     *
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Fonction qui retourne l'identifiant d'une langue
     *
     * @param string $langue
     * @return array
     */
    private function id_langue(string $langue)
    {
        $sql = "SELECT id FROM langues WHERE nom = '$langue'";

        $id = $this->pdo->query($sql);
        $id = $id->fetch();
        return $id;
    }

    /**
     * Ajouter une expression
     *
     * @param string $expression
     * @param integer $id_admin
     * @param string $langue
     * @param string $nature
     * @return boolean
     */
    public function ajouter_expression(string $expression, int $id_admin, string $langue, string $nature)
    {
        if (!empty($langue) && !empty($expression) && !empty($nature)) {

            $langue = (int)$this->id_langue($langue);

            $sql = $this->pdo->prepare("INSERT INTO expression (expression, id_admin, id_langues, nature) VALUES (:expression, :id_admin, :id_langues, :nature)");

            $sql->bindParam(':expression', $expression);
            $sql->bindParam(':id_admin', $id_admin);
            $sql->bindParam(':id_langues', $langue);
            $sql->bindParam(':nature', $nature);
            $sql->execute();
            return $this->pdo->lastInsertId();
        }
    }

    /**
     * Ajouter les traductions d'une expression
     *
     * @param string $id_expression
     * @param integer $traduction
     * @param string $phonetique
     * @param string $dialecte
     * @return void
     */
    public function ajouter_traduction(int $id_expression, string $traduction, string $phonetique, string $dialecte)
    {
        if (!empty($id_expression) && !empty($traduction) && !empty($dialecte)) {

            $sql = $this->pdo->prepare("INSERT INTO traduction (id_expression, traduction, phonetique, dialecte) VALUES (:id_expression, :traduction, :phonetique, :dialecte)");

            $sql->bindParam(':id_expression', $id_expression);
            $sql->bindParam(':traduction', $traduction);
            $sql->bindParam(':phonetique', $phonetique);
            $sql->bindParam(':dialecte', $dialecte);
            $sql->execute();
            return $this->pdo->lastInsertId();
        }
    }

    /**
     * Fonction qui renvoie les expression avec ses traductions
     *
     * @return array
     */
    private function expressions_traductions(): array
    {
        $sql = "SELECT";

        $exp_traduites = $this->pdo->query($sql);
        $exp_traduites = $exp_traduites->fetchAll();
        return $exp_traduites;
    }

    /**
     * Fonction qui retourne les traductions
     *
     * @return array
     */
    public function dictionnaire_traduction(): array
    {
        $sql = "SELECT id, id_expression, traduction, phonetique, dialecte  
                FROM traduction 
                WHERE id_expression IN (SELECT id
                                        FROM expression)";

        $traduction = $this->pdo->query($sql);
        $traduction = $traduction->fetchAll();
        return $traduction;
    }
    /**
     * Fonction qui retourne les expressions traduites
     *
     * @return array
     */
    public function dictionnaire_expression(): array
    {
        $sql = "SELECT * 
                FROM expression 
                WHERE id IN ( SELECT id
                            FROM expression 
                            WHERE id IN (SELECT id_expression
                                        FROM traduction))
                ORDER BY expression ASC";

        $expression = $this->pdo->query($sql);
        $expression = $expression->fetchAll();
        return $expression;
    }

    /**
     * Toutes les expressions existantes dans la base de données
     *
     * @return array
     */
    public function toutes_expressions(): array
    {
        $sql = "SELECT * FROM expression";

        $sql = $this->pdo->query($sql);
        $expressions = $sql->fetchAll();
        return $expressions;
    }
    /**
     * Fonction qui renvoie l'eexpression pour un identifiant donnée
     *
     * @param integer $id
     * @return array
     */
    public function expression(int $id): array
    {
        $sql = "SELECT expression.id, expression, nature, L.nom
                FROM expression, langues L
                WHERE expression.id = '$id'
                AND L.id = expression.id_langues";

        $expression = $this->pdo->query($sql);
        $expression = $expression->fetch();
        return $expression;
    }


    /**
     * Fonction qui renvoie les traductions d'une expression 
     *
     * @param integer $id
     * @return array
     */
    public function traduction(int $id): array
    {
        $sql = "SELECT traduction, phonetique, dialecte
                FROM traduction T, expression E
                WHERE E.id = $id
                AND E.id = T.id_expression";

        $expressions = $this->pdo->query($sql);
        $expressions = $expressions->fetchAll();
        return $expressions;
    }

    /**
     * Fonction permetant de supprimer une expression et toutes ses traductions
     *
     * @param integer $id
     * @return boolean
     */
    public function supprimer(int $id): bool
    {
        // Supprime d'abord toutes les traduction de l'expression passée en paramètre
        $this->pdo->prepare("DELETE FROM traduction WHERE id_expression = ?")->execute([$id]);
        //après on supprime l'expression en question
        $delete_expression = $this->pdo->prepare("DELETE FROM expression WHERE id = ?");
        return $delete_expression->execute([$id]);
    }

    /**
     * Modifier une expression
     *
     * @param string $expression
     * @param integer $id_admin
     * @param string $langue
     * @param string $nature
     * @param integer $id
     * @return bool
     */
    public function modifier_expression(string $expression, int $id_admin, string $langue, string $nature, int $id): bool
    {
        if (!empty($langue) && !empty($expression) && !empty($nature)) {

            $id_langue = (int)$this->id_langue($langue);

            $sql = $this->pdo->prepare("UPDATE expression SET expression = ?, id_admin = ?, id_langues = ?, nature = ? WHERE id = ?");
            return $sql->execute([$expression, $id_admin, $id_langue, $nature, $id]);
        }
    }
}
