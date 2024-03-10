<?php 

    function limpar_texto($str){
        return preg_replace("/[^0-9]/", "", $str);
    }

    $erro = false;
    if (count($_POST) > 0) {

        include('conexao.php');
        include('upload.php');
        include('send.php');
        
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        $nascimento = $_POST['nascimento'];
        $senha_descriptografada = $_POST['senha'];

        if (strlen($senha_descriptografada > 6 && $senha_descriptografada < 16 )) {
            $erro = "A  senha deve ter entre 6 e 16 caracteres";
        } else
            

        
    
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

        $path = "";
        if (isset($_FILES['foto'])) {
            $arq = $_FILES['foto'];
            $path = enviarArquivo($arq['error'],$arq['size'],$arq['name'], $arq['tmp_name']);
            if($path == false)
                $erro = "Falha ao enviar arquivo. Tente novamente";
        }

        if($erro) {
            echo "<p><b>ERRO: $erro</b></p>";
        } else {
            $senha = password_hash($senha_descriptografada, PASSWORD_DEFAULT);
            $sql_code = "INSERT INTO clientes (nome, email, senha, telefone, foto , nascimento , data ) VALUES ('$nome', '$email', '$senha' ,'$telefone','$path'  , '$nascimento', NOW())";
            $deu_certo = $mysqli->query($sql_code) or die($mysqli->error);
            if ($deu_certo) {
                enviar_email($email,"sua conta foi criada com sucesso ","<h1>Sua conta ja esta ativa</h1>
                    <p>
                        <b>Login: </b> $email <br/>
                        <b>Senha: </b> $senha_descriptografada
                    </p>
                ");
                print('<p><b>Cliente cadastrado com sucesso!!!
                </b></p>');
                unset($_POST);
            };
        };
    };
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
            <input value="<?php if(isset($_POST['nome'])) echo $_POST['nome']?>" type="text" name="nome"/>
        </p>
        <p>
            <label for="email">Email: </label>
            <input value="<?php if(isset($_POST['email'])) echo $_POST['email']?>" type="text" name="email"/>
        </p>
        <p>
            <label for="telefone">Telefone: </label>
            <input value="<?php if(isset($_POST['telefone'])) echo $_POST['telefone']?>"
             placeholder="(11) 99999-9999" type="text" name="telefone"/>
        </p>
        <p>
            <label for="nascimento">Data de Nascimento: </label>
            <input value="<?php if(isset($_POST['nascimento'])) echo $_POST['nascimento']?>" type="text" name="nascimento"/>
        </p>
        <p>
            <label for="senha">Senha: </label>
            <input value="<?php if(isset($_POST['senha'])) echo $_POST['senha']?>" type="password" name="senha"/>
        </p>
        <p>
            <label for="senha">Foto do Usuário: </label>
            <input type="file" name="foto"/>
        </p>
        <p>
            <input type="submit" value="Salvar Cliente"/>
        </p>
    </form>    
</body>
</html>