<?php

include '../data/conect.php';

if (isset($_POST['email']) && isset($_POST['senha'])) {

    if (strlen($_POST['email']) == 0) {
        $_SESSION['login_error'] = "Preencha seu email";
    } else if (strlen($_POST['senha']) == 0) {
        $_SESSION['login_error'] = "Preencha sua senha";
    } else {
        $email = $mysqli->real_escape_string($_POST['email']);
        $senha = $mysqli->real_escape_string($_POST['senha']);
        $sql_code = "SELECT * FROM usuarios WHERE email = '$email'";
        $sql_query = $mysqli->query($sql_code) or die("Falha na consulta SQL" . $mysqli->error);

        $quantidade = $sql_query->num_rows;

        if ($quantidade == 1) {
            $usuario = $sql_query->fetch_assoc();

            if (password_verify($senha, $usuario['senha'])) {
                if (!isset($_SESSION)) {
                    session_start();
                }
                $_SESSION['id'] = $usuario['id'];
                $_SESSION['nome'] = $usuario['nome'];

                header('Location: home.php');
                exit();
            } else {
                $_SESSION['login_error'] = "Email ou senha incorretos";
            }
        } else {
            $_SESSION['login_error'] = "Email ou senha incorretos";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/login.css" rel="stylesheet">
</head>

<body>

    <div id="login-container" class="container d-flex justify-content-center align-items-center vh-100">
        <div id="login-card" class="card p-4" style="width: 100%; max-width: 400px;">
            <h1 id="login-title" class="text-center mb-4">LOGIN</h1>
            <form id="login-form" action="" method="POST">
                <div id="login-email" class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div id="login-password" class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" class="form-control" id="senha" name="senha" required>
                </div>
                <div id="login-buttons" class="form-group text-center">
                    <button type="submit" class="btn btn-primary btn-block">Entrar</button>
                    <a href="register.php" class="d-block mt-3">Registre-se</a>
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
        <?php if (isset($_SESSION['login_error'])): ?>
            const errorMessage = "<?php echo $_SESSION['login_error']; ?>";
            showLoginError(errorMessage);
            <?php unset($_SESSION['login_error']); ?>
        <?php endif; ?>
    </script>
</body>

</html>