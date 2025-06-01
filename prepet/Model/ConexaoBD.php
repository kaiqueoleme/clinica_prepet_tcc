<?php
class ConexaoBD {
    private $serverName = "localhost";
    private $userName = "root";
    private $password = ""; // ou "usbw", dependendo do XAMPP
    private $dbName = "clinica_veterinaria";

    public function conectar() {
        $conn = new mysqli($this->serverName, $this->userName, $this->password, $this->dbName);
        return $conn;
    }
}
?>
