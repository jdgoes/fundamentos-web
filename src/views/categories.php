<?php
include '../security/protect.php';
include '../data/conect.php';

$userId = $_SESSION['id'];

$query = "SELECT * FROM categorias WHERE usuario_id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomeCategoria = $mysqli->real_escape_string($_POST['nome_categoria']);

    $checkQuery = "SELECT * FROM categorias WHERE usuario_id = ? AND nome = ?";
    $stmtCheck = $mysqli->prepare($checkQuery);
    $stmtCheck->bind_param("is", $userId, $nomeCategoria);
    $stmtCheck->execute();
    $checkResult = $stmtCheck->get_result();

    if ($checkResult->num_rows > 0) {
        header("Location: categories.php?error=" . urlencode("Você já tem uma categoria com esse nome."));
        exit();
    } else {
        $insertQuery = "INSERT INTO categorias (usuario_id, nome) VALUES (?, ?)";
        $stmtInsert = $mysqli->prepare($insertQuery);
        $stmtInsert->bind_param("is", $userId, $nomeCategoria);

        if ($stmtInsert->execute()) {
            header("Location: categories.php?success=" . urlencode("Categoria adicionada com sucesso!"));
            exit();
        } else {
            header("Location: categories.php?error=" . urlencode("Erro ao adicionar categoria: " . $mysqli->error));
            exit();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorias - TaskManager</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/categories.css">
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
                        <a class="nav-link active" href="categories.php">Categorias</a>
                    </li>
                </ul>
                <form action="../security/logout.php" method="post" class="form-inline">
                    <button type="submit" class="btn btn-light my-2 my-sm-0" id="logout-button">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="text-center mb-4" id="page-title">Gerenciar Categorias</h2>

        <div class="d-flex fle gap-4" id="cards-container">
            <div class="card" style="flex: 1 1 48%;" id="add-category-card">
                <div class="card-header" id="add-category-header">
                    Adicionar Nova Categoria
                </div>
                <div class="card-body" id="add-category-body">
                    <form action="categories.php" method="post" id="add-category-form">
                        <div class="form-group" id="category-name-group">
                            <label for="nome_categoria" id="category-name-label">Nome da Categoria</label>
                            <input type="text" name="nome_categoria" id="nome_categoria" class="form-control" required>
                        </div>
                        <button type="submit" id="add-category-button" class="btn btn-success">Adicionar Categoria</button>
                    </form>
                </div>
            </div>

            <div class="card mt-4 categorias-existentes" id="existing-categories-card">
                <div class="card-header" id="existing-categories-header">
                    Categorias Existentes
                </div>
                <div class="card-body" id="existing-categories-body">
                    <?php if ($result->num_rows > 0): ?>
                        <ul class="list-group" id="categories-list">
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center" id="category-<?php echo $row['id']; ?>">
                                    <span><?php echo htmlspecialchars($row['nome']); ?></span>
                                    <div>
                                        <a id="edit-button" href="../controllers/category/edit_category.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning mr-2" title="Editar">
                                            Editar
                                        </a>

                                        <form action="../controllers/category/delete_category.php" method="post" style="display:inline;">
                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                            <button id="delete-button" type="submit" class="btn btn-sm btn-danger" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir esta categoria?');">
                                                Excluir
                                            </button>
                                        </form>

                                    </div>

                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted" id="no-categories-msg">Nenhuma categoria encontrada.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="../js/alert.js"></script>
        <script>
            categoryError();
        </script>
</body>

</html>

<?php
$mysqli->close();
?>
