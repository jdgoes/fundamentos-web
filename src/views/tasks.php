<?php
include '../security/protect.php';
include '../data/conect.php';

$usuarioId = $_SESSION['id'];

$catQuery = "SELECT * FROM categorias WHERE usuario_id = ?";
$stmt = $mysqli->prepare($catQuery);
$stmt->bind_param("i", $usuarioId);
$stmt->execute();
$catResult = $stmt->get_result();

$success = null;
$errorMsg = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $categoriaId = $_POST['categoria_id'];
    $status = $_POST['status'];

    $checkCatQuery = "SELECT * FROM categorias WHERE id = ? AND usuario_id = ?";
    $stmtCheckCat = $mysqli->prepare($checkCatQuery);
    $stmtCheckCat->bind_param("ii", $categoriaId, $usuarioId);
    $stmtCheckCat->execute();
    $catResultCheck = $stmtCheckCat->get_result();

    if ($catResultCheck->num_rows === 0) {
        $success = false;
        $errorMsg = "Categoria inválida para este usuário.";
    } else {
        $addTaskQuery = "INSERT INTO tarefas (titulo, descricao, categoria_id, status, usuario_id, data_criacao) 
                         VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $mysqli->prepare($addTaskQuery);
        $stmt->bind_param("ssisi", $titulo, $descricao, $categoriaId, $status, $usuarioId);

        if ($stmt->execute()) {
            $success = true;
        } else {
            $success = false;
            $errorMsg = "Erro ao adicionar a tarefa. Tente novamente.";
        }

        $stmt->close();
    }
    $stmtCheckCat->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskManager - Adicionar Tarefa</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/tasks.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-green" id="navbar">
        <div class="container">
            <a class="navbar-brand" href="../views/home.php">TaskManager</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tasks.php">Tarefas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="categories.php">Categorias</a>
                    </li>
                </ul>
                <form action="../security/logout.php" method="post" class="form-inline">
                    <button type="submit" class="btn btn-light my-2 my-sm-0" id="logout-button">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="card mt-5 container" id="add-task-form">
        <h3 class="card-header" id="add-task-title">Adicionar Nova Tarefa</h3>
        <form action="" method="post" class="card-body">
            <div class="form-group">
                <label for="titulo">Título</label>
                <input type="text" name="titulo" id="task-title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="descricao">Descrição</label>
                <textarea name="descricao" id="task-description" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label for="categoria">Categoria</label>
                <select name="categoria_id" id="task-category" class="form-control" required>
                    <?php while ($cat = $catResult->fetch_assoc()): ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['nome']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="task-status" class="form-control" required>
                    <option value="0">Iniciada</option>
                    <option value="1">Não Iniciada</option>
                    <option value="2">Concluída</option>
                </select>
            </div>
            <button type="submit" id="add-task-button" class="btn btn-success">Adicionar Tarefa</button>
        </form>
    </div>

    <script>
        <?php if ($success === true): ?>
            Swal.fire({
                icon: 'success',
                title: 'Tarefa adicionada com sucesso!',
                showConfirmButton: false,
                timer: 2000
            });
        <?php elseif ($success === false): ?>
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: '<?php echo $errorMsg; ?>',
                showConfirmButton: false,
                timer: 2000
            });
        <?php endif; ?>
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>
