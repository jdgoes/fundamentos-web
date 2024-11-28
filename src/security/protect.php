<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    echo '<!DOCTYPE html>
            <html lang="pt-br">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Redirecionamento</title>
                <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script src="../js/alert.js"></script>
            </head>
            <body>
                <script>
                    showNotLoginAlert();
                </script>
                <noscript>
                    <p>Você precisa estar logado para acessar esta página. Redirecionando...</p>
                </noscript>
            </body>
            </html>';
    header("Refresh: 3; url=/Trabalho/src/views/login.php"); // Fallback para redirecionamento automático.
    exit();
}
?>
