<?php

namespace application\lib;

use PDO;

class Db
{

    protected $db;

    public function __construct()
    {
        $configDb = require 'application/config/db.php';
        $this->db = new PDO(
            'mysql:host=' . $configDb['host'] . ';dbname=' . $configDb['name'] . ';charset=utf8;',
            $configDb['user'], $configDb['password'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
        );
    }

    public function query($sql, $params = [])
    {
        $stmt = $this->db->prepare($sql);

        if (!empty($params)) {
            foreach ($params as $key => $val) {
                $stmt->bindValue(':' . $key, $val);
            }
        }

        $stmt->execute();
        return $stmt;
    }

    public function fetchAll($sql, $params = [])
    {
        $result = $this->query($sql, $params);

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function rowCount($sql, $params = [])
    {
        $result = $this->query($sql, $params);
        return $result->rowCount();
    }
}
