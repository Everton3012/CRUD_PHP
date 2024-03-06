<?php 
if (isset($_POST['confirmar'])) {

    include('conexao.php');
    $id = intval($_GET['id']);
    $sql_code = "DELETE FROM clientes WHERE id = '$id'";
    $sql_query = $mysqli->query($sql_code) or die($mysqli->error);

    if($sql_query){ ?>

        <H1>Cliente deletado com sucesso</H1>   
        <p><a href="clientes.php">Clique aqui</a> para voltar para a lista de clientes</p>     

    <?php
    die();
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deletar Cliente</title>
</head>
<body>
    <h1>Tem certeza que deseja deletar esse cliente?</h1>
    <button><a href="clientes.php">Não</a></button>
    <form method="post">
        <input name="confirmar" type="submit" value="Sim">
    </form>
</body>
</html>