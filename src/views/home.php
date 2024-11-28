<?php
include '../security/protect.php';
include '../data/conect.php';

$userId = $_SESSION['id'];
$query = "SELECT tarefas.id, tarefas.titulo, tarefas.descricao, tarefas.status, 
    tarefas.data_criacao, tarefas.data_conclusao, categorias.nome AS categoria
      FROM tarefas 
      JOIN categorias ON tarefas.categoria_id = categorias.id 
      WHERE tarefas.usuario_id = ?";

$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskManager</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/home.css">
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

    <div class="container mt-5 text-center" id="task-container">
    <h2 class="mb-4" id="welcome-message">Bem-vindo, <?php echo isset($_SESSION['nome']) ? $_SESSION['nome'] : 'Visitante'; ?>!</h2>

    <h3 id="task-title">Suas Tarefas</h3>

    <div class="row">
        <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="col-md-4 mb-4">
            <div class="card task-card" style="background-color: #f9f9f9; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                <div class="card-body">
                <p><strong>Título: </strong> <?php echo htmlspecialchars($row['titulo']); ?></p>
                <p><strong>Categoria: </strong> <?php echo htmlspecialchars($row['categoria']); ?></p>
                <p><strong>Descrição: </strong> <?php echo htmlspecialchars($row['descricao']); ?></p>

                <p><strong>Status:</strong>
                    <?php
                    echo $row['status'] == '1' ? 'Não Iniciada' : ($row['status'] == '0' ? 'Iniciada' : ($row['status'] == '2' ? 'Concluída' : 'Status desconhecido'));
                    ?>
                </p>

                <p><strong>Data de Criação:</strong>
                    <?php echo date('d/m/y', strtotime($row['data_criacao'])); ?></p>
                <p><strong>Data de Conclusão:</strong>
                    <?php echo $row['data_conclusao'] ? date('d/m/y', strtotime($row['data_conclusao'])) : 'N/A'; ?></p>

                <div class="task-action-buttons">
                    <a id="edit-button" href="../controllers/tasks/edit_task.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Editar</a>
                    <a id="delete-button" href="../controllers/tasks/delete_task.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir esta tarefa?');">Excluir</a>
                </div>

                </div>
            </div>
            </div>
        <?php endwhile; ?>
        <?php else: ?>
        <p class="text-muted">Nenhuma tarefa encontrada.</p>
        <?php endif; ?>
    </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/alert.js"></script>
    <script>
    categoryError();
    </script>
</body>

</html>
