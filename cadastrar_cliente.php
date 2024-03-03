<?php 

    if (count($_POST) > 0) {
        var_dump($_POST);
    }

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <a href="clientes.php">Voltar pra a lista</a>
    <form method="POST" action="">
        <p>
            <label for="nome">Nome: </label>
            <input type="text" name="nome" id="">
        </p>
        <p>
            <label for="email">Email: </label>
            <input type="text" name="email" id="">
        </p>
        <p>
            <label for="nascimento">Data de Nascimento: </label>
            <input type="text" name="nascimento" id="">
        </p>
        <p>
            <input type="submit" value="Salvar Cliente">
        </p>
    </form>    
</body>
</html>