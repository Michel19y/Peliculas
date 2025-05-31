
<?php
require_once 'Conexao.php';




class Peliculas {
    private $modelosCompativeis; // Sem acentos

    private $id;
    private $nome;
    private $quantidades;
    private $marca_id;
    private $banco;

    public function __construct() {
        $this->banco = Conexao::getInstancia();
    }
    public function setModelosCompativeis($modelos) {
        $this->modelosCompativeis = $modelos;
    }
    
    public function getModelosCompativeis() {
        return $this->modelosCompativeis;
    }
    

    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }


    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getNome() {
        return $this->nome;
    }
    public function setQuantidades($quantidades) {
        $this->quantidades = $quantidades;
    }

    public function getQuantidades() {
        return $this->quantidades;
    }
    public function setMarcaId($marca_id) {
        $this->marca_id = $marca_id;
    }

    public function getMarcaId() {
        return $this->marca_id;
    }

    // Seleciona todas as películas
    public function selecionarTodas()
    {
        try {
            $sql = 'SELECT p.*, m.nome AS marca 
                    FROM peliculas p
                    JOIN marcas m ON p.marca_id = m.id
                    ORDER BY m.nome, p.nome';
            $conexao = $this->banco->getConexao();
            $consulta = $conexao->query($sql);
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Seleciona uma película pelo ID
    public function selecionarPorId($id)
    {
        try {
            $sql = 'SELECT p.*, m.nome AS marca 
                    FROM peliculas p
                    JOIN marcas m ON p.marca_id = m.id
                    WHERE p.id = :id';
            $conexao = $this->banco->getConexao();
            $consulta = $conexao->prepare($sql);
            $consulta->bindParam(':id', intval($id), PDO::PARAM_INT);
            var_dump($id);
exit;

            $consulta->execute();
         
            return $consulta->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

  // Salva uma nova película e adiciona um modelo compatível
  public function salvar($modelos)
  {
      try {
          $conexao = $this->banco->getConexao();
          $conexao->beginTransaction();
  
          // Inserir película
          $sqlPeliculas = 'INSERT INTO peliculas (nome, marca_id, quantidades) VALUES (:nome, :marca_id, :quantidades)';
          $consulta = $conexao->prepare($sqlPeliculas);
          $consulta->bindValue(':nome', $this->nome, PDO::PARAM_STR);
          $consulta->bindValue(':quantidades', $this->quantidades, PDO::PARAM_INT);
          $consulta->bindValue(':marca_id', $this->marca_id, PDO::PARAM_INT);
          $consulta->execute();
          $peliculaId = $conexao->lastInsertId();
  
          // Inserir os modelos compatíveis
          $sqlModelos = 'INSERT INTO modelos (modelo, pelicula_id) VALUES (:modelo, :pelicula_id)';
          $consulta = $conexao->prepare($sqlModelos);
  
          foreach ($modelos as $modelo) {
              $modelo = trim($modelo);
              if (!empty($modelo)) {
                  $consulta->bindValue(':modelo', $modelo, PDO::PARAM_STR);
                  $consulta->bindValue(':pelicula_id', $peliculaId, PDO::PARAM_INT);
                  $consulta->execute();
              }
          }
  
          $conexao->commit();
          return true;
      } catch (PDOException $e) {
          $conexao->rollBack();
          return false;
      }
  }
  
 public function atualizar()
{
    try {
        $conexao = $this->banco->getConexao();
        $conexao->beginTransaction();

        // Atualizar a película
        $sql = 'UPDATE peliculas 
                SET nome = :nome, quantidades = :quantidades, marca_id = :marca_id 
                WHERE id = :id';
        $consulta = $conexao->prepare($sql);
        $consulta->bindValue(':nome', trim($this->nome), PDO::PARAM_STR);
        $consulta->bindValue(':quantidades', $this->quantidades, PDO::PARAM_INT); // Permite 0
        $consulta->bindValue(':marca_id', intval($this->marca_id), PDO::PARAM_INT);
        $consulta->bindValue(':id', intval($this->id), PDO::PARAM_INT);
        $consulta->execute();

        // Atualizar os modelos compatíveis: estratégia -> excluir e reinserir
        if (isset($this->modelosCompativeis)) {
            // Remove os modelos antigos
            $sqlDelete = 'DELETE FROM modelos WHERE pelicula_id = :pelicula_id';
            $stmtDelete = $conexao->prepare($sqlDelete);
            $stmtDelete->bindValue(':pelicula_id', $this->id, PDO::PARAM_INT);
            $stmtDelete->execute();

            // Insere os novos modelos
            $sqlInsert = 'INSERT INTO modelos (modelo, pelicula_id) VALUES (:modelo, :pelicula_id)';
            $stmtInsert = $conexao->prepare($sqlInsert);

            $modelos = array_map('trim', explode(',', $this->modelosCompativeis));

            foreach ($modelos as $modeloNome) {
                if (!empty($modeloNome)) {
                    $stmtInsert->bindValue(':modelo', $modeloNome, PDO::PARAM_STR);
                    $stmtInsert->bindValue(':pelicula_id', $this->id, PDO::PARAM_INT);
                    $stmtInsert->execute();
                }
            }
        }

        $conexao->commit();
        return true;
    } catch (PDOException $e) {
        $conexao->rollBack();
        error_log("Erro ao atualizar película e modelos: " . $e->getMessage());
        return false;
    }
}



    // Exclui uma película
    public function excluir($id)
    {
        try {
            $sql = 'DELETE FROM peliculas WHERE id = :id';
            $conexao = $this->banco->getConexao();
            $consulta = $conexao->prepare($sql);
            $consulta->bindValue(':id', intval($id), PDO::PARAM_INT);
            return $consulta->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
