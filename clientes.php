<?php 

include("conexao.php");

if (!isset($_SESSION)) {
    session_start();
}

if(!isset($_SESSION['usuario'])){
    header("Location: index.php");
    die();
}

$id = $_SESSION['usuario'];

$sql_clientes = "SELECT * FROM clientes WHERE id != '$id'";
$query_clientes = $mysqli->query($sql_clientes) or die($mysqli->error);
$num_clientes = $query_clientes->num_rows;

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Clientes</title>
</head>
<body>
    <h1>Lista de Clientes</h1>
    <?php if($_SESSION['admin']) {?>
    <p><a href="cadastrar_cliente.php">Cadastrar um Cliente</a></p>
    <?php }?>
    <p>Estes são os clientes cadastrados no sistema: </p>
    <table border="1" cellpadding="10">
        <thead>
            <th>ID</th>
            <th>Privilegio</th>
            <th>Foto</th>
            <th>Nome</th>
            <th>E-mail</th>
            <th>Telefone</th>
            <th>Nascimento</th>
            <th>Data de cadastro</th>
            <?php if($_SESSION['admin']) {?>
            <th>Ações</th>
            <?php }?>
        </thead>
        <tbody>
            <?php if ($num_clientes == 0) { ?> 
                <tr>
                    <td colspan="<?php echo $_SESSION['admin'] ? '9' : '8'?>">nenhum cliente cadastrado</td>
                </tr>
            <?php 
            } else { 
                while($cliente = $query_clientes->fetch_assoc()) {  
                    $telefone = "Não informado";
                    if (!empty($cliente['telefone'])) {
                        $telefone = formatar_telefone(($cliente['telefone']));
                    }
                    $nascimento = "Não informada";
                    if (!empty($cliente['nascimento'])) {
                        $nascimento = formatar_data($cliente['nascimento']);
                    }
                    $data_cadastro = date("d/m/Y H:i", strtotime($cliente['data']));
                        
            ?>
                <tr>
                    
                    <td><?php echo $cliente['id']?></td>
                    <td><?php echo $cliente['admin'] ? 'Admin' : 'cliente';
                    ?></td>
                    <td><img src="<?php echo $cliente['foto']?>" height="40" alt="foto de usuario"></td>
                    <td><?php echo $cliente['nome']?></td>
                    <td><?php echo $cliente['email']?></td>
                    <td><?php echo $telefone ?></td>
                    <td><?php echo $nascimento ?></td>
                    <td><?php echo $data_cadastro ?></td>
                    <?php if($_SESSION['admin']) {?>
                    <td>
                        <a href="editar_cliente.php?id=<?php echo $cliente['id']; ?>">Editar</a>
                        <a href="deletar_cliente.php?id=<?php echo $cliente['id']; ?>">Deletar</a>
                    </td>
                    <?php }?>
                </tr>
            <?php } 
            }
            ?>
        </tbody>
    </table>
    <a href="logout.php">Sair do Sistema</a>
</body>
</html>