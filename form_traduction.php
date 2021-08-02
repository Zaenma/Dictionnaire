<?php

use Traduction\Expression;

require_once './source/fonctions.php';
require_once './vendor/autoload.php';

$pdo = get_pdo();
$exp = null;
$expression = new Expression($pdo);

if (isset($_GET['exp'])) {
    $ep = $expression->expression($_GET['exp']);
    if (isset($_POST['btn-ajout-traduction'])) {
        $expression->ajouter_traduction($_GET['exp'], $_POST['traduction'], $_POST['phonetique'], $_POST['langue-expression']);
        header("location:./dictionnaire.php");
    }
}

if (isset($_GET['exp_mod'])) {
    if (isset($_POST['btn-modif-expression'])) {
        $expression->modifier_expression($_POST['expression'], 1, $_POST['langue-expression'], $_POST['nature'], $_GET['exp_mod']);
        header('location:./form_traduction.php?exp=' . $_GET['exp_mod']);
    }
}

if (isset($_GET['exp_mod'])) {
    $exp = $expression->expression($_GET['exp_mod']);
}

if (isset($_GET['tra'])) {
    var_dump($_GET['tra']);
}

?>


<?php require_once('./include/header.php') ?>

<?php require_once('./menu.php') ?>
<div class="container">
    <?php if ($exp === null) : ?>
        <b class="text-center">Ajouter les traduction de l'expression <span class="text-primary"><?= $ep['expression'] ?></span> </b>
        <form action="" method="post">

            <div class="form-group">
                <label for="traduction">Traduction</label>
                <input type="text" name="traduction" class="form-control" placeholder="traduction" value="" required>
            </div>

            <div class="form-group">
                <label for="phonetique">Phonétique</label>
                <input type="text" name="phonetique" class="form-control" placeholder="phonetique" value="">
            </div>

            <div class="form-group">
                <label for="langue">Dialecte</label>
                <select class="form-control" name="langue-expression" id="langue">
                    <option selected>------------</option>
                    <option>Anjouan</option>
                    <option>Grand-Comores</option>
                    <option>Moheli</option>
                    <option>Mayotte</option>
                </select>
            </div>

            <div class="btns">
                <input type="submit" name="btn-ajout-traduction" value="Valider">
                <input type="submit" name="btn-continuer" value="Valider et ajouter">
            </div>
        </form>
        <!-- Formulaire de modification des expressions -->
    <?php elseif ($exp !== null) : ?>
        <h2>Modifier l'expression</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="langue">Langue</label>
                <select class="form-control" name="langue-expression" id="langue">
                    <option value="<?= $exp ? $exp['nom'] : ""; ?>"><?= $exp['nom'] ?></option>
                </select>
            </div>
            <div class="form-group">
                <label for="langue">Expression</label>
                <input type="text" name="expression" class="form-control" placeholder="Expression" value="<?= $exp ? $exp['expression'] : ""; ?>" required>
            </div>
            <div class="form-group">
                <label for="langue">Nature de l'expression</label>
                <input type="text" name="nature" class="form-control" placeholder="Langue" value="<?= $exp ? $exp['nature'] : ""; ?>" required>
            </div>
            <div class="bouton-recherche">
                <input type="submit" name="btn-modif-expression" value="Ajouter la langue">
            </div>
        </form>
    <?php endif; ?>
</div>

<?php require_once('./include/footer.php') ?>