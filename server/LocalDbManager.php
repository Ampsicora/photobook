<?php

namespace CloudPhoto;

use PDO;
use PDOException;

class LocalDbManager
{
    private $queryString;
    private $pdo;
    public const CONN_STR = 'mysql:dbname=cloud-photo;host=127.0.0.1';

    public function __construct()
    {
        $this->pdo = new PDO($this::CONN_STR, 'root', '');
    }
    
    public function getUser($userName, $password)
    {
        $this->queryString = "SELECT USERNAME, PASSWORD FROM A_UTENTI 
            WHERE USERNAME = :qUserName AND PASSWORD = :qPassword";
        $sth = $this->pdo->prepare($this->queryString);
        $sth->bindValue(':qUserName', $userName);
        $sth->bindValue(':qPassword', $password);
        try
        {
            $sth->execute();
            $result = $sth->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
        catch (PDOException $ex)
        {
            return false;
        }
    }

    public function getPicsByUser ($idUser)
    {
        $this->queryString = "SELECT ID_FOTO, URL_FOTO FROM P_FOTO
            WHERE ID_UTENTE = :qIdUser;";
        $sth = $this->pdo->prepare($this->queryString);
        $sth->bindValue(':qIdUser', $idUser);
        try {
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        catch (PDOException $ex)
        {
            return false;
        }
    }

    public function getPicsByTag ($tagName)
    {
        $this->queryString = "SELECT p.ID_FOTO, f.NOME_FOTO FROM P_TAG AS t
            INNER JOIN P_FOTO AS f ON t.ID_FOTO = f.ID_FOTO
            WHERE t.DESCR = :qTagName";
        $sth = $this->pdo->prepare($this->queryString);
        $sth->bindValue(':qTagName', $tagName);
        try {
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        catch (PDOException $ex)
        {
            return false;
        }
    }

    public function insertNewUser($userName, $password) :  bool
    {
        $this->queryString = 'INSERT INTO a_utenti (USERNAME, PASSWORD) VALUES (:qUserName, :qPassword)';
        $sth = $this->pdo->prepare($this->queryString);
        try 
        {
            $sth->execute([
                ':qUserName' => $userName,
                ':qPassword' => $password
            ]);
            return true;
        }
        catch (PDOException $ex)
        {
            return false;
        }
    }

    public function insertNewPic($idUser, $imgName)
    {
        $this->queryString = "INSERT INTO P_FOTO (NOME_FOTO, ID_UTENTE)
            VALUES (:qImgName, :qIdUser);";
        $sth = $this->pdo->prepare($this->queryString);
        $sth -> bindValue(':qImgName', $imgName);
        $sth -> bindValue(':qIdUser', $idUser);

        try {
            $sth->execute();
            return true;
        }
        catch (PDOException $ex)
        {
            return false;
        }
    }

    public function insertTags($idPic, $tags)
    {
        foreach ($tags['Labels'] as $tag)
        {
            $this->queryString = "INSERT INTO P_TAG (DESCR, PRECISIONE, ID_FOTO) VALUES
                ( :qDescr, :qPrec, q:idPic);";
            $sth = $this->pdo->prepare($this->queryString);
            $sth->bindValue(':qDescr', $tag['Name']);
            $sth->bindValue(':qPrec', $tag['Confidence']);
            $sth->bindValue(':qIdPic', $idPic);
            
            try {
                $sth->execute();
            }
            catch(PDOException $ex)
            {
                return false;
            }
        }
        return true;
    }
}

?>