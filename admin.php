<?php


require_once './source/fonctions.php';
require_once './vendor/autoload.php';

$pdo = get_pdo();

$langues = new Traduction\Langues($pdo);

$liste_langues = $langues->recupere_langues();

$expressions = new Traduction\Expression($pdo);

//Une langue récupérée pour la midification
$l = null;

if (isset($_GET['supprimer'])) {
    $langues->supprimer_langue($_GET['supprimer']);
    header('location:/admin.php');
}

if (isset($_GET['modifier'])) {

    $l = $langues->recuper_langue($_GET['modifier']);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $langues->modifier_langue($_POST['langue'], $_GET['modifier']);
        header('location:/admin.php');
    }
} else if (isset($_POST['btn-ajout-expression'])) {
    $last_id = $expressions->ajouter_expression($_POST['expression'], 1, $_POST['langue-expression'], $_POST['nature']);
    header('location:./form_traduction.php?exp=' . $last_id);
} else if (!$l) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $langues->ajouter_langue($_POST['langue'], 1);
        header('location:/admin.php');
    }
}


?>


<?php require_once('./include/header.php') ?>
<?php require_once('./menu.php') ?>
<div class="container">
    <h1>Administration</h1>
    <hr>

    <h2>Ajouter une langue</h2>

    <div class="langue-saisie">
        <form action="" method="post">
            <div class="form-group">
                <label for="langue">Texte à traduire</label>
                <input type="text" name="langue" class="form-control" placeholder="Langue" value="<?= $l !== null ? $l['nom'] : ""; ?>" required>
            </div>
            <div class="bouton-recherche">
                <button>Ajouter la langue</button>
            </div>
        </form>
    </div>

    <div class="affichage-langue">
        <?php foreach ($liste_langues as $lang) : ?>
            <p><?= $lang['nom'] ?> <a href="?supprimer=<?= $lang['id'] ?>">Supprimer</a> <a href="?modifier=<?= $lang['id'] ?>">Modifier</a></p>
        <?php endforeach; ?>
    </div>

    <hr>

    <h2>Ajout d'une expression</h2>
    <form action="" method="post">
        <div class="form-group">
            <label for="langue">Langue</label>
            <select class="form-control" name="langue-expression" id="langue">
                <option selected>------------</option>
                <?php foreach ($liste_langues as $langue) : ?>
                    <option><?= $langue['nom'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="langue">Expression</label>
            <input type="text" name="expression" class="form-control" placeholder="Expression" value="<?= $l !== null ? $l['nom'] : ""; ?>" required>
        </div>
        <div class="form-group">
            <label for="langue">Nature de l'expression</label>
            <input type="text" name="nature" class="form-control" placeholder="Langue" value="<?= $l !== null ? $l['nom'] : ""; ?>" required>
        </div>
        <div class="bouton-recherche">
            <input type="submit" name="btn-ajout-expression" value="Ajouter la langue">
        </div>
    </form>
</div>



<?php require_once('./include/footer.php') ?>