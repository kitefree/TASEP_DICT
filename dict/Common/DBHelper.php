<?php
namespace Common;

/* PHP 內鍵 Start */
use PDO,PDOException;
/* PHP 內鍵 END Start */


class DBHelper
{
    private $pdo;
    private $results;
    private $op;
    public function __construct($host, $dbname, $username, $password)
    {
        try {            
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error connecting to database: " . $e->getMessage();
            exit();
        }
    }

    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }

    public function first(){        
        return reset($this->results);
    }
    public function all()
    {
        return $this->results;
    }

    public function query($sql, $params = [])
    {
        $this->results = NULL;
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            //$this->results = $stmt->fetchAll(PDO::FETCH_ASSOC);      
            $this->results = $stmt->fetchAll(PDO::FETCH_OBJ);      
                  
            return $this;
        } catch (PDOException $e) {
            echo "Error executing query: " . $e->getMessage();
            exit();
        }
    }

    public function execute($sql, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            echo "Error executing query: " . $e->getMessage();
            exit();
        }
    }

    public function getLastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    public function beginTransaction()
    {
        return $this->pdo->beginTransaction();
    }

    public function commit()
    {
        return $this->pdo->commit();
    }

    public function rollback()
    {
        return $this->pdo->rollBack();
    }

    public function close()
    {
        $this->pdo = null;
    }
}