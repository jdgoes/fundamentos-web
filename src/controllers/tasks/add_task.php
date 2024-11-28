<?php
include '../../security/protect.php';
include '../../data/conect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $categoria_id = $_POST['categoria_id'];
    $status = $_POST['status'];
    $userId = $_SESSION['id'];

    if (empty($titulo) || empty($descricao) || empty($categoria_id) || empty($status)) {
        $error = "Todos os campos são obrigatórios.";
    } else {
        if ($status == 'Concluída') {
            $status = 2;
            $data_conclusao = date('Y-m-d H:i:s');
        } elseif ($status == 'não iniciada') {
            $status = 1;
            $data_conclusao = NULL;
        } else {
            $status = 0;
            $data_conclusao = NULL;
        }

        $query = "INSERT INTO tarefas (usuario_id, categoria_id, titulo, descricao, status, data_criacao, data_conclusao) 
                  VALUES (?, ?, ?, ?, ?, NOW(), ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("iissss", $userId, $categoria_id, $titulo, $descricao, $status, $data_conclusao);

        if ($stmt->execute()) {
            $success = "Tarefa adicionada com sucesso!";
        } else {
            $error = "Erro ao adicionar a tarefa. Tente novamente.";
        }
    }
}

$statusOptions = [
    '0' => 'Iniciada',
    '1' => 'Não Iniciada',
    '2' => 'Concluída'
];

foreach ($statusOptions as $key => $value) {
    $selected = ($status == $key) ? 'selected' : '';
    echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
}
?>
