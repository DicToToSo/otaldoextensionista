<?php
$conn = new mysqli("localhost", "usuario", "senha", "banco");

$idade = isset($_GET['idade']) ? (int)$_GET['idade'] : 0;

if ($idade > 0) {
    $stmt = $conn->prepare("SELECT id, nome FROM turmas WHERE idade_minima <= ? AND idade_maxima >= ?");
    $stmt->bind_param("ii", $idade, $idade);
    $stmt->execute();
    $result = $stmt->get_result();

    $turmas = [];
    while ($row = $result->fetch_assoc()) {
        $turmas[] = $row;
    }

    echo json_encode($turmas);
    $stmt->close();
}

$conn->close();
?>