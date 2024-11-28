<?php
include '../../security/protect.php';
include '../../data/conect.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../../views/home.php");
    exit();
}

$taskId = $_GET['id'];
$userId = $_SESSION['id'];

$query = "SELECT id, titulo, descricao, categoria_id, status FROM tarefas WHERE id = ? AND usuario_id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("ii", $taskId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: ../../views/home.php");
    exit();
}

$task = $result->fetch_assoc();

$categoriesQuery = "SELECT id, nome FROM categorias WHERE usuario_id = ?";
$categoriesStmt = $mysqli->prepare($categoriesQuery);
$categoriesStmt->bind_param("i", $userId);
$categoriesStmt->execute();
$categoriesResult = $categoriesStmt->get_result();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $categoria_id = $_POST['categoria_id'];
    $status = $_POST['status'];

    if (empty($titulo) || empty($descricao) || empty($categoria_id)) {
        $error = "Todos os campos são obrigatórios.";
    } else {
        $date_conclusion = ($status == 2) ? date('Y-m-d H:i:s') : null;

        $updateQuery = "UPDATE tarefas SET titulo = ?, descricao = ?, categoria_id = ?, status = ?, data_conclusao = ? WHERE id = ? AND usuario_id = ?";
        $stmt = $mysqli->prepare($updateQuery);
        $stmt->bind_param("sssisii", $titulo, $descricao, $categoria_id, $status, $date_conclusion, $taskId, $userId);

        if ($stmt->execute()) {
            $success = "Tarefa atualizada com sucesso!";
        } else {
            $error = "Erro ao atualizar a tarefa. Tente novamente.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tarefa</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/register.css">
</head>

<body>

    <div id="cadastro-container" class="container d-flex justify-content-center align-items-center vh-100">
        <div id="cadastro-card" class="card p-4" style="width: 100%; max-width: 400px;">
            <h1 id="cadastro-title" class="text-center mb-4">EDITAR TAREFA</h1>
            <form action="" method="POST">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($success)): ?>
                    <div class="alert alert-success">
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>

                <div id="cadastro-nome" class="form-group">
                    <label for="titulo">Título</label>
                    <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo $task['titulo']; ?>" required>
                </div>
                <div id="cadastro-email" class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea class="form-control" id="descricao" name="descricao" required><?php echo $task['descricao']; ?></textarea>
                </div>
                <div id="cadastro-senha" class="form-group">
                    <label for="categoria_id">Categoria</label>
                    <select class="form-control form-control-lg" id="categoria_id" name="categoria_id" required>
                        <?php while ($category = $categoriesResult->fetch_assoc()): ?>
                            <option value="<?php echo $category['id']; ?>" <?php echo $task['categoria_id'] == $category['id'] ? 'selected' : ''; ?>>
                                <?php echo $category['nome']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div id="cadastro-status" class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control form-control-lg" id="status" name="status" required>
                        <option value="0" <?php echo $task['status'] == 0 ? 'selected' : ''; ?>>Iniciada</option>
                        <option value="1" <?php echo $task['status'] == 1 ? 'selected' : ''; ?>>Não Iniciada</option>
                        <option value="2" <?php echo $task['status'] == 2 ? 'selected' : ''; ?>>Concluída</option>
                    </select>
                </div>

                <div id="cadastro-buttons" class="form-group text-center">
                    <button type="submit" class="btn btn-primary btn-block">Atualizar</button>
                    <a id="cadastro-link" href="../../views/home.php" class="d-block mt-3">Voltar para as Tarefas</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/alert.js"></script>

</body>

</html>
