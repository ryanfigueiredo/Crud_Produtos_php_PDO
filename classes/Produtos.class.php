<?php

require_once 'Conexao.class.php';
require_once 'Funcoes.class.php';


class Produtos
{

    private $con;
    private $objProd;
    private $id;
    private $nome;
    private $descricao;
    private $preco;
    private $dataCadastro;

    public function __construct()
    {
        $this->con = new Conexao();
        $this->objProd = new Funcoes();
    }


    public function __set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }
    public function __get($atributo)
    {
        return $this->$atributo;
    }



    public function querySeleciona($dado)
    {
        try {
            $this->id = $this->objProd->base64($dado, 2);
            $cst = $this->con->conectar()->prepare("SELECT id, nome, descricao, preco, data_cadastro FROM `produtos` WHERE `id` = :id;");
            $cst->bindParam(":id", $this->id, PDO::PARAM_INT);
            $cst->execute();
            return $cst->fetch();
        } catch (PDOException $ex) {
            return 'error ' . $ex->getMessage();
        }
    }

    public function querySelect()
    {
        try {
            $cst = $this->con->conectar()->prepare("SELECT `id`, `nome` , `descricao`, `preco` , `imagem` FROM `produtos`;");
            $cst->execute();
            return $cst->fetchAll();
        } catch (PDOException $ex) {
            return 'error ' . $ex->getMessage();
        }
    }

    public function queryInsert($dados)
    {
        try {
            $this->nome = $this->objProd->tratarCaracter($dados['nome'], 1);
            $this->descricao = $this->objProd->tratarCaracter($dados['descricao'], 1);
            $this->preco = $dados['preco'];
            $this->dataCadastro = $this->objProd->dataAtual(2);

            $filename = $_FILES['myfile']['name'];
            $filetmpname = $_FILES['myfile']['tmp_name'];
            $folder = 'uploads/';
            move_uploaded_file($filetmpname, $folder . $filename);

            $cst = $this->con->conectar()->prepare("INSERT INTO `produtos` (`nome` , `descricao`, `preco` , `data_cadastro` , `imagem`) VALUES (:nome, :descricao, :preco, :dataCadastro, '$filename');");
            $cst->bindParam(":nome", $this->nome, PDO::PARAM_STR);
            $cst->bindParam(":descricao", $this->descricao, PDO::PARAM_STR);
            $cst->bindParam(":preco", $this->preco, PDO::PARAM_STR);
            $cst->bindParam(":dataCadastro", $this->dataCadastro, PDO::PARAM_STR);
            $cst->bindColumn(":imagem" , $filename );
            if ($cst->execute()) {
                return 'ok';
            } else {
                return 'error';
            }
        } catch (PDOException $ex) {
            return 'error ' . $ex->getMessage();
        }
    }

    public function queryUpdate($dados)
    {
        try {
            $this->id = $this->objProd->base64($dados['prod'], 2);
            $this->nome = $this->objProd->tratarCaracter($dados['nome'], 1);
            $this->descricao = $this->objProd->tratarCaracter($dados['descricao'], 1);
            $this->preco = $dados['preco'];

            if (isset($_FILES['myfile']['name']) && ($_FILES['myfile']['name']) != "") {
                $filename = $_FILES['myfile']['name'];
                $filetmpname = $_FILES['myfile']['tmp_name'];
                $folder = 'uploads/';
        
        
                move_uploaded_file($filetmpname, $folder . $filename);
            }


            $cst = $this->con->conectar()->prepare("UPDATE `produtos` SET `nome` = :nome, `descricao` = :descricao, `preco` = :preco, `imagem` ='$filename' WHERE `id` = :id;");

            $cst->bindParam(":id", $this->id, PDO::PARAM_INT);
            $cst->bindParam(":nome", $this->nome, PDO::PARAM_STR);
            $cst->bindParam(":descricao", $this->descricao, PDO::PARAM_STR);
            $cst->bindParam(":preco", $this->preco, PDO::PARAM_STR);
            $cst->bindColumn(":imagem" , $filename );
            if ($cst->execute()) {
                return 'ok';
            } else {
                return 'error';
            }
        } catch (PDOException $ex) {
            return 'error ' . $ex->getMessage();
        }
    }

    public function queryDelete($dado)
    {
        try {
            $this->id = $this->objProd->base64($dado, 2);
            $cst = $this->con->conectar()->prepare("DELETE FROM `produtos` WHERE `id` = :id;");
            $cst->bindParam(":id", $this->id, PDO::PARAM_INT);
            if ($cst->execute()) {
                return 'ok';
            } else {
                return 'error';
            }
        } catch (PDOException $ex) {
            return 'error ' . $ex->getMessage();
        }
    }
}
