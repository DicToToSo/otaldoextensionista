function calcularIdade(dataNascimento) {
    const hoje = new Date();
    const nascimento = new Date(dataNascimento);
    let idade = hoje.getFullYear() - nascimento.getFullYear();
    const m = hoje.getMonth() - nascimento.getMonth();
    if (m < 0 || (m === 0 && hoje.getDate() < nascimento.getDate())) {
        idade--;
    }
    return idade;
}

function atualizarCampos() {
    const dataNasc = document.getElementById('dataNascimento').value;
    const idade = calcularIdade(dataNasc);
    const responsavelDiv = document.getElementById('responsavelDiv');

    if (idade < 18) {
        responsavelDiv.style.display = 'block';
        document.getElementById('responsavel').required = true;
    } else {
        responsavelDiv.style.display = 'none';
        document.getElementById('responsavel').required = false;
    }

    fetch(`buscar_turmas.php?idade=${idade}`)
        .then(response => response.json())
        .then(data => {
            const turmaSelect = document.getElementById('turma');
            turmaSelect.innerHTML = '';
            data.forEach(turma => {
                const option = document.createElement('option');
                option.value = turma.id;
                option.textContent = turma.nome;
                turmaSelect.appendChild(option);
            });
        });
}