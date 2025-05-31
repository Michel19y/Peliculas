<?php

/**
 * A classe de Conexao é implementada por meio do Design Pattern (padrao de 
 * projeto) Singleton. Uma classe Singleton não permite que exista mais de 1 
 * instancia sua por vez. Isto é gerenciamento de memória
 */
class Conexao
{
    private static $banco = null;
    private $conexao;

    private $debug = true; //ativa mensagens de erro em tela
    private $servidor = 'localhost';
    private $login = 'root';
    private $senha = '';
    private $database = 'silvano';
    private $driver = 'mysql';

    /**
     * Metodo construct privado para bloquear instanciacoes de objetos fora da classe
     */
    private function __construct()
    {
        //blocos try-catch servem para tentar executar algo e caso ocorra algum erro, captura-o e trata-o corretamente
        try {
            $this->conexao = new PDO("$this->driver:host=$this->servidor;dbname=$this->database;", $this->login, $this->senha);
            if ($this->debug) $this->conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Problemas na conexão, volte mais tarde';
            //header('Location: pagina-manutencao.php');
            if ($this->debug) {
                echo $e->getMessage();
            }
        }
    }

    private function __clone()
    {
    }

    /**
     * Este metodo sera utilizado, via acesso estatico, para acessar uma instancia 
     * unica da classe
     */
    public static function getInstancia()
    {
        if (self::$banco == null) self::$banco = new Conexao();
        return self::$banco;
    }

    public function getConexao()
    {
        return $this->conexao;
    }
}
