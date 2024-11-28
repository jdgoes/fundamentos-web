<?php
session_start();
include '../data/conect.php';

if (isset($_POST['cadastro_nome']) && isset($_POST['cadastro_email']) && isset($_POST['cadastro_senha'])) {
    if (strlen($_POST['cadastro_nome']) == 0) {
        $_SESSION['status_cadastro'] = 'Preencha seu nome';
    } else if (strlen($_POST['cadastro_email']) == 0) {
        $_SESSION['status_cadastro'] = 'Preencha seu email';
    } else if (strlen($_POST['cadastro_senha']) == 0) {
        $_SESSION['status_cadastro'] = 'Preencha sua senha';
    } else {
        $cadastro_nome = $mysqli->real_escape_string($_POST['cadastro_nome']);
        $cadastro_email = $mysqli->real_escape_string($_POST['cadastro_email']);
        $cadastro_senha = password_hash($_POST['cadastro_senha'], PASSWORD_DEFAULT);


        $email_check = $mysqli->query("SELECT * FROM usuarios WHERE email = '$cadastro_email'");
        if ($email_check->num_rows > 0) {
            $_SESSION['status_cadastro'] = 'O email informado já está cadastrado.';
        } else {
            $nome_check = $mysqli->query("SELECT * FROM usuarios WHERE nome = '$cadastro_nome'");
            if ($nome_check->num_rows > 0) {
                $_SESSION['status_cadastro'] = 'O nome de usuário já existe.';
            } else {
                $sql_code = "INSERT INTO usuarios (nome, email, senha) VALUES ('$cadastro_nome', '$cadastro_email', '$cadastro_senha')";
                $sql_query = $mysqli->query($sql_code);

                if ($sql_query) {
                    $_SESSION['status_cadastro'] = 'success';
                } else {
                    $_SESSION['status_cadastro'] = 'error';
                }
            }
        }

        $_SESSION['cadastro_nome'] = $cadastro_nome;
        $_SESSION['cadastro_email'] = $cadastro_email;
    }

    header("Location: ../views/register.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar-se</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/register.css">
</head>

<body>

    <div id="cadastro-container" class="container d-flex justify-content-center align-items-center vh-100">
        <div id="cadastro-card" class="card p-4" style="width: 100%; max-width: 400px;">
            <h1 id="cadastro-title" class="text-center mb-4">REGISTRE-SE</h1>
            <form action="" method="POST">
                <div id="cadastro-nome" class="form-group">
                    <label for="cadastro_nome">Nome</label>
                    <input type="text" class="form-control" id="cadastro_nome" name="cadastro_nome" value="<?php echo $_SESSION['cadastro_nome'] ?? ''; ?>" required>
                </div>
                <div id="cadastro-email" class="form-group">
                    <label for="cadastro_email">Email</label>
                    <input type="email" class="form-control" id="cadastro_email" name="cadastro_email" value="<?php echo $_SESSION['cadastro_email'] ?? ''; ?>" required>
                </div>
                <div id="cadastro-senha" class="form-group">
                    <label for="cadastro_senha">Senha</label>
                    <input type="password" class="form-control" id="cadastro_senha" name="cadastro_senha" required>
                </div>
                <div id="cadastro-buttons" class="form-group text-center">
                    <button type="submit" class="btn btn-primary btn-block">Enviar</button>
                    <a id="cadastro-link" href="login.php" class="d-block mt-3">Já tem uma conta? Login </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/alert.js"></script>

    <script>
        <?php if (isset($_SESSION['status_cadastro'])): ?>
            const statusCadastro = "<?php echo $_SESSION['status_cadastro']; ?>";
            showAlert(statusCadastro);
            <?php unset($_SESSION['status_cadastro']); ?>
        <?php endif; ?>
    </script>

</body>

</html>

