<?php 

if (!isset($_SESSION)) {
    session_start();
}

    if(!isset($_SESSION['admin']) || !$_SESSION['admin']){
    header("Location: clientes.php");
    die();
}
    
    include('conexao.php');
    include('upload.php');
    $id = intval($_GET['id']);
    function limpar_texto($str){
        return preg_replace("/[^0-9]/", "", $str);
    }

    if (count($_POST) > 0) {

        $erro = false;
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        $nascimento = $_POST['nascimento'];
        $senha = $_POST['senha'];
        $sql_code_extra = "";
        $admin = $_POST['admin'];

        if(!empty($senha)){
            if (strlen($senha > 6) && strlen($senha < 16 )) {
                $erro = "A  senha deve ter entre 6 e 16 caracteres";
            }else {
                $senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);
                $sql_code_extra = " senha = ' $senha_criptografada ' , ";
            }
        }
    
        if(empty($nome)) {
            $erro = "Preencha o nome";
        };
        if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Preencha o e-mail";
        };

        if (!empty($nascimento)) {
            $pedacos = explode('/', $nascimento);
            if (count($pedacos) == 3) {
                $nascimento = implode('-', array_reverse($pedacos));
            } else {
                $erro = "A data de nascimento deve seguir o padrão dia/mes/ano";
            };
        }
        if(!empty($telefone)){
            $telefone = limpar_texto($telefone);
            if(strlen($telefone) != 11)
                $erro = "O telefone deve ser preenchido no padrão (11) 99999-9999";
        };

        if (isset($_FILES['foto'])) {
            $arq = $_FILES['foto'];
            $path = enviarArquivo($arq['error'],$arq['size'],$arq['name'], $arq['tmp_name']);
            if($path == false){
                $erro = "Falha ao enviar arquivo. Tente novamente";
            }else{
                $sql_code_extra .= "foto = '$path',";
            }
               

            if (!empty($_POST['foto_antiga'])) {
                unlink($_POST['foto_antiga']);
            }
        }

        if($erro) {
            echo "<p><b>ERRO: $erro</b></p>";
        } else {

           
            $sql_code = "UPDATE clientes 
            SET nome = '$nome',
            email = '$email',
            $sql_code_extra 
            telefone = '$telefone', 
            nascimento = '$nascimento',
            admin = '$admin'
            WHERE id = '$id'";

            $deu_certo = $mysqli->query($sql_code) or die($mysqli->error);
            if ($deu_certo) {
                print('<p><b>Cliente atualizado com sucesso!!!
                </b></p>');
                unset($_POST);
            };
        };
    };
    
    $sql_cliente = "SELECT * FROM clientes WHERE id = '$id'";
    $query_cliente = $mysqli->query($sql_cliente) or die($mysqli->error);
    $cliente = $query_cliente->fetch_assoc();
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
    <form enctype="multipart/form-data" method="POST">
        <p>
            <label for="nome">Nome: </label>
            <input value="<?php echo $cliente['nome']?>" type="text" name="nome"/>
        </p>
        <p>
            <label for="email">Email: </label>
            <input value="<?php echo $cliente['email']?>" type="text" name="email"/>
        </p>
        <p>
            <label for="telefone">Telefone: </label>
            <input value="<?php if (!empty($cliente['telefone'])) echo formatar_telefone($cliente['telefone'])?>"
             placeholder="(11) 99999-9999" type="text" name="telefone"/>
        </p>
        <p>
            <label for="nascimento">Data de Nascimento: </label>
            <input value="<?php if (!empty($cliente['nascimento'])) echo formatar_data($cliente['nascimento'])?>" type="text" name="nascimento"/>
        </p>
        <p>
            <label for="senha">Senha: </label>
            <input type="password" name="senha"/>
        </p>
        <input type="hidden" name="foto_antiga" value="<?php echo $cliente['foto'] ?>"/>
        <?php if($cliente['foto']) { ?>
            <p>
            <label for="foto">Foto Atual: </label>
            <img src="<?php echo $cliente['foto'];?>" height="50" alt="">
        </p>
        <?php } ?>
        <p>
            <label for="foto">Nova Foto do Usuário: </label>
            <input type="file" name="foto"/>
        </p>
        <p>
            <label for="admin">Tipo: </label>
            <input type="radio" value="1" name="admin"/><span>ADMIN</span>
            <input type="radio" checked value="0" name="admin"/><span>CLIENTE</span>
        </p>
        <p>
            <input type="submit" value="Salvar Cliente"/>
        </p>
    </form>    
</body>
</html>