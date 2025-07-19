<?php
/**
 * Configuração de conexão com o banco de dados MySQL
 */

class Database {
    private $host = 'localhost';
    private $dbname = 'mini_erp';
    private $username = 'root';
    private $password = '';
    private $connection;

    /**
     * Estabelece conexão com o banco de dados
     */
    public function connect() {
        $this->connection = null;

        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->dbname . ";charset=utf8mb4";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erro na conexão com o banco de dados: " . $e->getMessage());
        }

        return $this->connection;
    }

    /**
     * Fecha a conexão com o banco
     */
    public function disconnect() {
        $this->connection = null;
    }
}
?>
