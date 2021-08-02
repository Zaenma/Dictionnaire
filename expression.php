<?php

use Traduction\Expression;

require_once './source/fonctions.php';
require_once './vendor/autoload.php';

$pdo = get_pdo();

$expression = new Expression($pdo);
if (!isset($_GET['exp'])) {
    // On appelle la fonction pour afficher toutes les expressions
    $expressions = $expression->toutes_expressions();
} elseif (isset($_GET['exp'])) {
    $expressions = $expression->expression($_GET['exp']);
    $expression_post = $expressions['expression'];
    $traductions = $expression->traduction($_GET['exp']);
} elseif (isset($_GET['sup'])) {
    $expression->supprimer($_GET['sup']);
    header("location:./dictionnaire.php");
} elseif (isset($_GET['modif'])) {
    # code...
}

?>

<?php require_once('./include/header.php') ?>
<?php require_once('./menu.php') ?>

<div class="container">
    <!-- Si la variable $_GET['exp'] existe -->
    <?php if (isset($_GET['exp'])) : ?>
        <div class="resultat-recherche expression">
            <p>
            <h2><?= ucfirst($expression_post) ?>; <em> <?= strtolower($expressions['nature']) ?></em></h2> <br>
            <?php foreach ($traductions as $trad) : ?>
                <span class="mot-saisie"><?= ucfirst($trad['traduction']) ?></span> [<?= $trad['phonetique'] ?>] - <em><?= ucfirst($trad['dialecte']) ?></em>,
            <?php endforeach; ?>
            </p>
            <h4 class="exemples">Expemples et expressions : </h4>
        </div>
        <div class="btns">
            <a href="./form_traduction.php?exp_mod=<?= $expressions['id'] ?>">Modifier l'expression</a>
            <a href="./form_traduction.php?exp=<?= $expressions['id'] ?>">Ajouter des traductions</a>
            <a href="?sup=<?= $expressions['id'] ?>">Supprimer l'expression</a>
        </div>
    <?php endif; ?>

    <!-- Si la variable n'existe pas, on affiche toutes les expressions pour pouvoir faire des modifications -->


</div>
<?php require_once('./include/footer.php') ?>