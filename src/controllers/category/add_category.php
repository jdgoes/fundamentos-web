<?php
include '../../security/protect.php';
include '../../data/conect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoriaNome = $_POST['nome'];

    $checkQuery = "SELECT COUNT(*) FROM categorias WHERE nome = ?";
    $stmtCheck = $mysqli->prepare($checkQuery);
    $stmtCheck->bind_param("s", $categoriaNome);
    $stmtCheck->execute();
    $stmtCheck->bind_result($count);
    $stmtCheck->fetch();
    $stmtCheck->close();

    if ($count > 0) {
        header("Location: ../../views/categories.php?error=Categoria%20já%20existe");
        exit();
    } else {
        $query = "INSERT INTO categorias (nome) VALUES (?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("s", $categoriaNome);

        if ($stmt->execute()) {
            header("Location: ../../views/categories.php?success=Categoria%20adicionada%20com%20sucesso");
            exit();
        } else {
            header("Location: ../../views/categories.php?error=Erro%20ao%20adicionar%20categoria");
            exit();
        }
    }
}
?>
