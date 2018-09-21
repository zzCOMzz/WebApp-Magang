<?php 
namespace Model;

class InitDb {
    
    private $iPdo;

    public function __construct(){
        $this->grantToItMor();
        $this->initTable();
        $this->$iPdo = new \PDO("mysql:host=webapp-db;dbname=bukutamu_db","it-mor","pertamina");
    }

    private function grantToItMor(){
        $pdo= new \PDO("mysql:host=webapp-db","root","pertamina");
        $pdo->exec(
            "grant all privileges on *.* to 'it-mor'@'localhost' identified by 'pertamina'"
        );
    }

    public function getPdo(): \PDO{
        return $this->$iPdo;
    }

    private function initTable(){
        $pdo = new \PDO("mysql:host=webapp-db;dbname=bukutamu_db","it-mor","pertamina");
        $pdo->exec(
            "create table if not exists tamu (
                id varchar(250) primary key,
                number_identity varchar(250),
                name varchar(250),
                gender varchar(20),
                address varchar(250),
                phone_no varchar(20),
                purpose varchar(250),
                date_in date
            )"
        );
    }
}

