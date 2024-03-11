<?php

if (isset($_POST['email']) && isset($_POST['senha'])) {

    include('conexao.php');
    $email = $mysqli->real_escape_string($_POST['email']);
    $senha = $_POST['senha'];

    $sql_code = "SELECT * FROM clientes WHERE email = '$email'";
    $sql_query = $mysqli->query($sql_code) or die($msqli->error);

    if($sql_query->num_rows == 0){
        echo "O e-mail informado é incorredo";
    }else {
        $usuario = $sql_query->fetch_assoc();
        if(!password_verify($senha, $usuario['senha'])){
            echo "A senha informada está incorreta";
        } else {
            if(!isset($_SESSION)){
                session_start();
            }
            $_SESSION['usuario'] = $usuario['id'];
            $_SESSION['admin'] = $usuario['admin'];
            header("Location: clientes.php");
        }
    }


}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <form action="" method="post">
        <p>
            <Label for="email">E-mail</Label>
            <input type="email" name="email">
        </p>
        <p>
            <Label for="senha">Senha</Label>
            <input type="password" name="senha">
        </p>
        <input type="submit" value="Logar">
    </form>
</body>
</html>