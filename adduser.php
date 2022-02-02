<?php

/**
 * Projet: Exercice d'administration TPI
 * Page: adduser.php
 * Description: Page d'ajout d'utilisateur
 * Auteur: Crockett Sasha
 * Version 1.0.0 PC 26.01.2022 / Codage initial
*/

require_once 'models/users.php';

// Init des variables
$errors = array();
$nom = "";
$prenom = "";
$surnom = "";

/**
 * Checks the password validity (8 characters long, has a number, an uppercase and a lowercase character)
 *
 * @param string $pwd
 * @return bool
*/
function checkPasswordValidity($pwd)
{
    $hasLowercase = false;
    $hasUppercase = false;
    $hasNumber = false;
    $minSize = 8;

    if (strlen($pwd) < $minSize)
        return false;

    for ($i = 0; strlen($pwd) > $i; $i++)
    {
        if (str_contains($pwd, strtoupper($pwd[$i])))
            $hasUppercase = true;

        if (str_contains($pwd, strtolower($pwd[$i])))
            $hasLowercase = true;
        if (is_numeric($pwd[$i]))
            $hasNumber = true;
        
        if ($hasUppercase && $hasLowercase && $hasNumber)
            return true;
    }

    return false;
}

if (filter_has_var(INPUT_POST, 'submit'))
{
    // Récup des données saisies par l'utilisateur
    $nom = trim(filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING));
    $prenom = trim(filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING));
    $surnom = trim(filter_input(INPUT_POST, 'surnom', FILTER_SANITIZE_STRING));
    $pwd = trim(filter_input(INPUT_POST, 'pwd', FILTER_SANITIZE_STRING));
    $cnfPwd = trim(filter_input(INPUT_POST, 'cnfPwd', FILTER_SANITIZE_STRING));

    $messErrPwd = "Votre mot de passe doit au minimum faire 8 caractères, doit contenir une minuscule, une majuscule et un chiffre";

    // Vérification des données saisies
    if (empty($nom))
        $errors['nom'] = "Votre nom ne peut pas être vide";
    if (empty($prenom))
        $errors['prenom'] = "Votre prénom ne peut pas être vide";
    if (empty($surnom))
        $errors['surnom'] = "Votre surnom ne peut pas être vide";
    elseif (userExists($surnom))
        $errors['surnom'] = "Nom d'utilisateur déjà utilisé";
    if (empty($pwd) && checkPasswordValidity($pwd))
        $errors['pwd'] = $messErrPwd;
    if ($pwd != $cnfPwd)
        $errors['pwd'] = "Les mots de passe doivent être identiques";

    if (empty($errors))
    {
        $salt = openssl_random_pseudo_bytes(25);
        $pwdHash = sha1($pwd + $salt);

        addUser($nom, $prenom, $surnom, $pwdHash, $salt, false);
    }
}
?>

<form method="POST" action="" class="form-horizontal">
    <div class="form-group">
        <label for="nomInput" class="control-label col-sm-3">Nom* : </label>
        <div class="col-sm-9">
            <input type="text" name="nom" value="<?= $nom ?>" id="nomInput" class="form-control" placeholder="Entrez votre nom..." required>
        </div>
    </div>
    <?php if (!empty($errors['nom'])) : ?>
        <div class="alert alert-danger col-sm-9 col-sm-offset-3">
            <?php echo $errors['nom']; ?>
        </div>
    <?php endif; ?>

    <div class="form-group">
        <label for="prenomInput" class="control-label col-sm-3">Prénom* : </label>
        <div class="col-sm-9">
            <input type="text" name="prenom" value="<?= $prenom ?>" id="prenomInput" class="form-control" placeholder="Entrez votre prénom..." required>
        </div>
    </div>
    <?php if (!empty($errors['prenom'])) : ?>
        <div class="alert alert-danger col-sm-9 col-sm-offset-3">
            <?php echo $errors['prenom']; ?>
        </div>
    <?php endif; ?>

    <div class="form-group">
        <label for="surnomInput" class="control-label col-sm-3">Nom d'utilisateur* : </label>
        <div class="col-sm-9">
            <input type="text" name="surnom" value="<?= $surnom ?>" id="surnomInput" class="form-control" placeholder="Entrez votre nom d'utilisateur..." required>
        </div>
    </div>
    <?php if (!empty($errors['surnom'])) : ?>
        <div class="alert alert-danger col-sm-9 col-sm-offset-3">
            <?php echo $errors['surnom']; ?>
        </div>
    <?php endif; ?>

    <div class="form-group">
        <label for="pwd" class="control-label col-sm-3">Mot de passe* : </label>
        <div class="col-sm-9">
            <input type="password" name="pwd" id="pwd" required class="form-control" placeholder="Entrez votre mot de passe...">
        </div>
    </div>
    <?php if (!empty($errors['pwd'])) : ?>
        <div class="alert alert-danger col-sm-9 col-sm-offset-3">
            <?php echo $errors['pwd']; ?>
        </div>
    <?php endif; ?>

    <div class="form-group">
        <label for="cnfPwd" class="control-label col-sm-3">Confirmer le MDP : </label>
        <div class="col-sm-9">
            <input type="password" name="cnfPwd" id="cnfPwd" required class="form-control" placeholder="Confirmez votre mot de passe...">
        </div>
    </div>
    
    <div class="form-group">
        <div class="col-sm-3">
            (* champs obligatoires)
        </div>
        <div class="col-sm-9">
            <input type="submit" name="submit" value="S'inscrire" class="btn btn-primary">
        </div>
    </div>
</form>