<?php
include '../../security/protect.php';
include '../../data/conect.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../../views/home.php");
    exit();
}

$taskId = $_GET['id'];
$userId = $_SESSION['id'];

$query = "SELECT id FROM tarefas WHERE id = ? AND usuario_id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("ii", $taskId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: ../../views/home.php");
    exit();
}

$deleteQuery = "DELETE FROM tarefas WHERE id = ? AND usuario_id = ?";
$stmt = $mysqli->prepare($deleteQuery);
$stmt->bind_param("ii", $taskId, $userId);

if ($stmt->execute()) {
    header("Location: ../../views/home.php?success=1");
} else {
    header("Location: ../../views/home.php?error=1");
}
?>
