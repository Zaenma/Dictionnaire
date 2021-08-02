<?php

use Traduction\Expression;
use Traduction\Traduction;

require_once './source/fonctions.php';
require_once './vendor/autoload.php';

$pdo = get_pdo();

$expression = new Expression($pdo);
$expressions = $expression->dictionnaire_expression();
$traductions = $expression->dictionnaire_traduction();

?>

<?php require_once('./include/header.php') ?>
<?php require_once('./menu.php') ?>

<div class="container">
    <h2>Dictionnaire</h2>

    <div class="form-group">
        <input type="text" name="recherche" class="form-control" placeholder="recherche">
    </div>

    <div class="dictionnaire">
        <?php foreach ($expressions as $exp) : ?>
            <p>
                <a href="./expression.php?exp=<?= $exp['id'] ?>" class="mot-saisie"> <?= ucfirst($exp['expression']) ?></a>; <span class="nature"> <?= strtolower($exp['nature']) ?></span> -
                <?php foreach ($traductions as $tra) : ?>
                    <?php if ($exp['id'] === $tra['id_expression']) : ?>
                        <a href="./expression.php?tra=<?= $tra['id'] ?>" class="traduction"> <?= ucfirst($tra['traduction']) ?></a> : <em> [<?= $tra['phonetique'] ?>] </em>, <span class="dialecte"> [<?= $tra['dialecte'] ?>] </span>.
                    <?php endif; ?>
                <?php endforeach; ?>
            </p>
        <?php endforeach; ?>
    </div>

</div>


<?php require_once('./include/footer.php') ?>