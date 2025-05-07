<?php
$conn = new mysqli("localhost", "usuario", "senha", "banco");

$idMatricula = $_POST["id"];
$nome = $_POST["nome"];
$dataNascimento = $_POST["dataNascimento"];
$idTurma = $_POST["turma"];
$responsavel = isset($_POST["responsavel"]) ? trim($_POST["responsavel"]) : '';

$dataHoje = new DateTime();
$dataNasc = new DateTime($dataNascimento);
$idade = $dataHoje->diff($dataNasc)->y;

$stmt = $conn->prepare("SELECT idade_minima, idade_maxima FROM turmas WHERE id = ?");
$stmt->bind_param("i", $idTurma);
$stmt->execute();
$stmt->bind_result($idadeMin, $idadeMax);

if ($stmt->fetch()) {
    if ($idade < $idadeMin || $idade > $idadeMax) {
        die("Erro: idade incompatível com a turma.");
    }

    if ($idade < 18 && empty($responsavel)) {
        die("Erro: responsável obrigatório para menores.");
    }

    $stmt->close();
    $stmt = $conn->prepare("UPDATE matriculas SET nome = ?, data_nascimento = ?, turma_id = ?, responsavel = ? WHERE id = ?");
    $stmt->bind_param("ssisi", $nome, $dataNascimento, $idTurma, $responsavel, $idMatricula);
    $stmt->execute();

    echo "Matrícula atualizada com sucesso!";
} else {
    echo "Erro: turma não encontrada.";
}

$stmt->close();
$conn->close();
?>