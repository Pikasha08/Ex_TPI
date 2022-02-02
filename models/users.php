<?php

require_once 'dbconnexion.php';

function addUser($nom, $prenom, $surnom, $pwdHash, $salt, $isAdmin)
{
    static $ps = null;
    $sql = 'INSERT INTO `users_bidon`.`users` (`nom`, `prenom`, `nickname`, `password`, `salt`, `is_admin`)';
    $sql .= ' VALUES (:NOM, :PRENOM, :SURNOM, :PWD, :SALT, :ISADMIN)';

    if ($ps == null)
    {
        $ps = connectDB()->prepare($sql);
    }
    $answer = false;

    try
    {
        $ps->bindParam(':NOM', $nom, PDO::PARAM_STR);
        $ps->bindParam(':PRENOM', $prenom, PDO::PARAM_STR);
        $ps->bindParam(':SURNOM', $surnom, PDO::PARAM_STR);
        $ps->bindParam(':PWD', $pwdHash, PDO::PARAM_STR);
        $ps->bindParam(':SALT', $salt, PDO::PARAM_STR);
        $ps->bindParam(':ISADMIN', $isAdmin, PDO::PARAM_BOOL);

        $answer = $ps->execute();
    }
    catch (PDOException $e)
    {
        echo $e->getMessage();
    }

    return $answer;
}

function userExists($nickname)
{
    static $ps = null;
    $sql = 'SELECT COUNT(nom) as nomUtilise FROM users';
    $sql .= ' WHERE nickname = :SURNOM';

    if ($ps == null)
    {
        $ps = connectDB()->prepare($sql);
    }
    $answer = false;

    try
    {
        $ps->bindParam(':SURNOM', $nickname, PDO::PARAM_STR);

        if ($ps->execute())
        {
            $answer = $ps->fetchAll(PDO::FETCH_ASSOC);
        }
    }
    catch (PDOException $e)
    {
        echo $e->getMessage();
    }

    return $answer;
}