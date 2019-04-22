<?php
require_once 'classes/Produtos.class.php';
require_once 'classes/Funcoes.class.php';

$objProd = new Produtos();
$objfc = new Funcoes();

if (isset($_POST['btnCadastrar'])) {
    if ($objProd->queryInsert($_POST) == 'ok') {
        header('location: index.php');
    } else {
        echo '<script type="text/javascript">alert("Erro em cadastrar")</script>';
    }
}

if (isset($_POST['sair'])) {
    header('location: index.php');
}

if (isset($_POST['btnAlterar'])) {
    if ($objProd->queryUpdate($_POST) == 'ok') {
        header('location: index.php');
    } else {
        echo '<script type="text/javascript">alert("Erro em alterar")</script>';
    }
}

if (isset($_GET['acao'])) {
    switch ($_GET['acao']) {
        case 'edit':
            $prod = $objProd->querySeleciona($_GET['prod']);
            break;
        case 'delete':
            if ($objProd->queryDelete($_GET['prod']) == 'ok') {
                header('location: index.php');
            } else {
                echo '<script type="text/javascript">alert("Erro ao alterar")</script>';
            }
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
</head>

<style>
    input[type='file'] {
        display: none
    }
</style>

<body>

    <!-- formulario de cadastro -->
    <div class="container">
        <h2 class="text-center">Cadastro de produtos</h2>
        <form method="post" enctype="multipart/form-data">
            <div class="row form-group mt-3">
                <div class="col-3">
                    <input type="text" name="nome" value="<?= $objfc->tratarCaracter((isset($prod['nome'])) ? ($prod['nome']) : (''), 2) ?>" class="form-control" placeholder="Nome">
                </div>
                <div class="col-3">
                    <input type="text" name="descricao" value="<?= $objfc->tratarCaracter((isset($prod['descricao'])) ? ($prod['descricao']) : (''), 2) ?>" class="form-control" placeholder="Descrição">
                </div>
                <div class="col-3">
                    <input type="text" name="preco" value="<?= (isset($prod['preco'])) ? ($prod['preco']) : ('') ?>" class="form-control" placeholder="Preço">
                </div>
                <div class="col-1">
                    <label for='imagem' class="btn btn-primary"><i class="fas fa-image"></i></label>
                    <input id='imagem' type='file' name="myfile">
                </div>
                <div class="col-2">
                    <?php if (isset($_GET['acao']) == 'edit') : ?>
                        <button type="submit" name="btnAlterar" class="btn btn-info"><i class="fas fa-edit"></i></button>
                        <button type="submit" name="sair" class="btn btn-dark"><i class="fas fa-times"></i></button>
                        <input type="hidden" name="prod" value="<?= (isset($prod['id'])) ? ($objfc->base64($prod['id'], 1)) : ('') ?>">
                    <?php else : ?>
                        <button type="submit" name="btnCadastrar" class="btn btn-success"><i class="fas fa-plus"></i></button>
                    <?php endif; ?>

                </div>
            </div>
        </form>
    </div>



    <!-- tabela produtos  -->
    <div class="container">
        <div class="card mt-5">
            <div class="card-header text-center">
                <h2>Produtos</h2>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Nome</th>
                        <th>Descricao</th>
                        <th>Preco</th>
                        <th>Imagem</th>
                        <th>Ações</th>
                    </tr>
                    <?php foreach ($objProd->querySelect() as $prod) { ?>
                        <tr class="text-center">
                            <td><?= $objfc->tratarCaracter($prod['nome'], 2) ?></td>
                            <td><?= $objfc->tratarCaracter($prod['descricao'], 2) ?></td>
                            <td>R$ <?= $prod['preco'] ?></td>
                            <td><img src="uploads/<?= $prod['imagem'] ?>" class="img-fluid"></td>
                            <td>
                                <a href="?acao=edit&prod=<?= $objfc->base64($prod['id'], 1) ?>" class="btn btn-info"><i class="fas fa-edit"></i></a>
                                <a href="?acao=delete&prod=<?= $objfc->base64($prod['id'], 1) ?>" class="btn btn-danger"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>



    </div>





</body>

</html>