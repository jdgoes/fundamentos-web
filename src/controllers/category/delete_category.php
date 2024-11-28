<?php
include '../../security/protect.php';
include '../../data/conect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryId = (int)$_POST['id'];
    $userId = $_SESSION['id'];

    $checkQuery = "SELECT * FROM categorias WHERE id = ? AND usuario_id = ?";
    $stmtCheck = $mysqli->prepare($checkQuery);
    $stmtCheck->bind_param("ii", $categoryId, $userId);
    $stmtCheck->execute();
    $checkResult = $stmtCheck->get_result();

    if ($checkResult->num_rows > 0) {
        $deleteQuery = "DELETE FROM categorias WHERE id = ?";
        $stmtDelete = $mysqli->prepare($deleteQuery);
        $stmtDelete->bind_param("i", $categoryId);

        if ($stmtDelete->execute()) {
            header("Location: ../../views/categories.php?success=" . urlencode("Categoria excluída com sucesso!"));
            exit();
        } else {
            header("Location: ../../views/categories.php?error=" . urlencode("Erro ao excluir categoria: " . $mysqli->error));
            exit();
        }
    } else {
        header("Location: ../../views/categories.php?error=" . urlencode("Categoria não encontrada ou não pertence a você."));
        exit();
    }
}
?>
