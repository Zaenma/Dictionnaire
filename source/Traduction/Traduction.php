<?php

namespace Traduction;


class Traduction
{
    private $expression;
    private $langue;

    /**
     * Constructeur de la classe
     *
     * @param \PDO $pdo
     * @param string $expression
     * @param string $langue
     */
    public function __construct(\PDO $pdo, string $expression = null, string $langue = null)
    {
        $this->pdo = $pdo;
        $this->expression = $expression;
        $this->langue = $langue;
    }

    public function getExpression()
    {
        $sql = "SELECT E.id, E.expression, E.nature
                FROM expression E, langues L
                WHERE E.expression = '$this->expression'
                AND L.nom = '$this->langue'";

        $expression = $this->pdo->query($sql);
        $expression = $expression->fetch();
        return $expression;
    }


    /**
     * Fonction qui récupère les ou la traduction(s) d'une expression
     *
     * @param integer $id
     * @return array
     */
    public function getTraduction(int $id): array
    {
        $sql = "SELECT traduction, phonetique, dialecte
                FROM traduction T, expression E, langues L
                WHERE E.id = $id
                AND E.id = T.id_expression
                AND L.id = E.id_langues";

        $expressions = $this->pdo->query($sql);
        $expressions = $expressions->fetchAll();
        return $expressions;
    }
}
