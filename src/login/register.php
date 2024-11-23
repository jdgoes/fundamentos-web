<?php

include 'conect.php';

if (isset($_POST['cadastro_nome']) && isset($_POST['cadastro_email']) && isset($_POST['cadastro_senha'])) {

    if (strlen($_POST['cadastro_nome']) == 0) {
        echo "Preencha seu nome";
    } else if (strlen($_POST['cadastro_email']) == 0) {
        echo "Preencha seu email";
    } else if (strlen($_POST['cadastro_senha']) == 0) {
        echo "Preencha sua senha";
    } else {
        $cadastro_nome = $mysqli->real_escape_string($_POST['cadastro_nome']);
        $cadastro_email = $mysqli->real_escape_string($_POST['cadastro_email']);
        $cadastro_senha = password_hash($_POST['cadastro_senha'], PASSWORD_DEFAULT);

        $sql_code = "INSERT INTO usuarios (nome, email, senha) VALUES ('$cadastro_nome', '$cadastro_email', '$cadastro_senha')";
        $sql_query = $mysqli->query($sql_code) or die("Falha na consulta SQL" . $mysqli->error);

        if ($sql_query) {
            echo "Usuário cadastrado com sucesso!<br>";
            echo "<a href='index.php'>Voltar para a página inicial</a>";
        } else {
            echo "Falha ao cadastrar usuário.<br>";
            echo "<a href='index.php'>Voltar para a página inicial</a>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastra-se</title>
</head>

<body>
    <h1>Cadastre-se</h1>
    <form action="" method="POST">
        <div>
            <label for="cadastro_nome">Nome:</label>
            <input type="text" id="cadastro_nome" name="cadastro_nome" required>
        </div>
        <div>
            <label for="cadastro_email">Email:</label>
            <input type="email" id="cadastro_email" name="cadastro_email" required>
        </div>
        <div>
            <label for="cadastro_senha">Senha:</label>
            <input type="password" id="cadastro_senha" name="cadastro_senha" required>
        </div>
        <div>
            <button type="submit">Cadastrar</button>
        </div>
    </form>
</body>

</html>
