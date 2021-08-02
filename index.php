<?php

require_once './source/fonctions.php';
require_once './vendor/autoload.php';

$pdo = get_pdo();

$langues = new Traduction\Langues($pdo);

$langues = $langues->recupere_langues();

$expression = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $expression_post = ucwords($_POST['expression']);

    $expressions = new Traduction\Traduction($pdo, $expression_post, $_POST['langue']);

    $expression = $expressions->getExpression();

    $traductions = $expressions->getTraduction($expression['id']);
}

?>

<?php require_once('./include/header.php') ?>
<?php require_once('./menu.php') ?>
<div class="container">
    <div class="">
        <form action="" method="POST">
            <div class="formulaire-de-recherche">
                <div class="langue-saisie">
                    <div class="form-group">
                        <label for="expression">Texte à traduire</label>
                        <textarea class="form-control" name="expression" id="expression" rows="3"></textarea>
                    </div>
                </div>

                <div class="langue-traduction">
                    <div class="form-group">
                        <label for="langue">Langue de traduction</label>
                        <select class="form-control" name="langue" id="langue">
                            <option selected>------------</option>
                            <?php foreach ($langues as $langue) : ?>
                                <option><?= $langue['nom'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="bouton-recherche">
                    <button>Traduire</button>
                </div>
            </div>
        </form>
    </div>
    <?php if ($expression) : ?>
        <div class="resultat-recherche">
            <p>
                <?= $expression_post ?>; <em> <?= strtolower($expression['nature']) ?></em> :
                <?php foreach ($traductions as $trad) : ?>
                    <span class="mot-saisie"><?= $trad['traduction'] ?></span> [<?= $trad['phonetique'] ?>],
                <?php endforeach; ?>
            </p>
        </div>
    <?php endif; ?>
</div>
<?php require_once('./include/footer.php') ?>