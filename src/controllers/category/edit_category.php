<?php
include '../../security/protect.php';
include '../../data/conect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryId = (int)$_POST['id'];
    $nomeCategoria = $mysqli->real_escape_string($_POST['nome_categoria']);
    $userId = $_SESSION['id'];

    $updateQuery = "UPDATE categorias SET nome = ? WHERE id = ? AND usuario_id = ?";
    $stmtUpdate = $mysqli->prepare($updateQuery);
    $stmtUpdate->bind_param("sii", $nomeCategoria, $categoryId, $userId);

    if ($stmtUpdate->execute()) {
        header("Location: ../../views/categories.php?success=" . urlencode("Categoria editada com sucesso!"));
        exit();
    } else {
        header("Location: ../../views/categories.php?error=" . urlencode("Erro ao editar categoria: " . $mysqli->error));
        exit();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $categoryId = (int)$_GET['id'];
    $userId = $_SESSION['id'];

    $query = "SELECT * FROM categorias WHERE id = ? AND usuario_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ii", $categoryId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $categoria = $result->fetch_assoc();
    } else {
        header("Location: ../../views/categories.php?error=" . urlencode("Categoria não encontrada ou não pertence a você."));
        exit();
    }
} else {
    header("Location: ../../views/categories.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Categoria</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/register.css">
</head>
<body>
    <div id="cadastro-container" class="container d-flex justify-content-center align-items-center vh-100">
        <div id="cadastro-card" class="card p-4" style="width: 100%; max-width: 400px;">
            <h1 id="cadastro-title" class="text-center mb-4">Editar Categoria</h1>
            <form action="edit_category.php" method="post">
                <input type="hidden" name="id" value="<?php echo $categoria['id']; ?>">
                <div id="cadastro-nome" class="form-group">
                    <label for="nome_categoria">Nome da Categoria</label>
                    <input type="text" name="nome_categoria" id="nome_categoria" class="form-control" value="<?php echo htmlspecialchars($categoria['nome']); ?>" required>
                </div>
                <div id="cadastro-buttons" class="form-group text-center">
                    <button type="submit" class="btn btn-success btn-block">Salvar Alterações</button>
                    <a id="cadastro-link" href="../../views/categories.php" class="d-block mt-3">Voltar para categorias</a>
                </div>
            </form>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../js/alert.js"></script>

    <script>
        <?php if (isset($_SESSION['status_cadastro'])): ?>
            const statusCadastro = "<?php echo $_SESSION['status_cadastro']; ?>";
            showAlert(statusCadastro);
            <?php unset($_SESSION['status_cadastro']); ?>
        <?php endif; ?>
    </script>
</body>
</html>
