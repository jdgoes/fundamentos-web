<?php
include 'protect.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarefas</title>
</head>

<body>
    <h1>Bem-vindo, <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Visitante'; ?>!</h1>
    <p>Aqui estão suas tarefas:</p>
    <form action="logout.php" method="post">
        <button type="submit">Logout</button>
    </form>
</body>

</html>