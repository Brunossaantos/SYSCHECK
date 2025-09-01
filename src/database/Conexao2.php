<?php

namespace database;

require_once __DIR__ . '/../constantes/constConexao.php';

use mysqli;

class Conexao2{
    private $server = HOST2;
    private $userName = USER2;
    private $password = SENHA2;
    private $dbname = DBNAME2;
    private $conn;

    function conectar(){
        $this->conn = new mysqli($this->server, $this->userName, $this->password, $this->dbname);
        if($this->conn->connect_error){
            die("Erro na conexão com o banco de dados: ".$this->conn->connect_error);
        }
        return $this->conn;
    }

    function fecharConexao(){
        if($this->conn){
            $this->conn->close();
        }
    }

    function __toString(){
        return 
        "<br>Servidor: ".$this->server.
        "<br>Usário: ".$this->userName;
        "<br>Senha: ".$this->password;
        "<br>Bando de dados: ".$this->dbname."<br>";
    }
}

?>