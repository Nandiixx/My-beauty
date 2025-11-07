<!--
    ⚠️ ATENÇÃO: Este arquivo precisa ser refatorado para seguir o padrão MVC.
    
    Problemas atuais:
    1. Usa chamadas de API JavaScript diretamente em vez de passar pelo Controller
    2. Faz include de arquivos que não existem (__DIR__ . '/../../includes/db_include.php')
    3. Não segue o padrão de Views sem lógica
    
    TODO: Refatorar para:
    - Criar FuncionarioController::mostrarEditar() e FuncionarioController::atualizar()
    - Mover toda lógica de validação e atualização para o Controller
    - Esta view deve apenas exibir o formulário com dados fornecidos pelo Controller
-->
<?php
// Esta view está temporariamente desabilitada até a refatoração
echo "<div style='padding: 2rem; background: #fee; color: #900; border: 2px solid #c00; margin: 2rem; border-radius: 8px;'>";
echo "<h2>⚠️ Página em Refatoração</h2>";
echo "<p>Esta funcionalidade está sendo refatorada para seguir o padrão MVC do projeto.</p>";
echo "<p><a href='../Index.php?acao=inicio_admin' style='color: #900; text-decoration: underline;'>← Voltar ao Dashboard</a></p>";
echo "</div>";
exit;
?>
<?php $id = $_GET['id'] ?? null; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Profissional</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <div class="container">
        <h1>Editar Profissional</h1>
        <form id="formEditar">
            <input type="hidden" id="profissionalId" value="<?php echo htmlspecialchars($id); ?>">
            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" id="nome" required>
            </div>
            <div class="form-group">
                <label for="matricula">Matrícula</label>
                <input type="text" id="matricula" required>
            </div>
            <div class="form-group">
                <label for="cargo">Cargo</label>
                <select id="cargo" required>
                    <option value="PROFISSIONAL_BELEZA">Profissional de Beleza</option>
                    <option value="RECEPCIONISTA">Recepcionista</option>
                    <option value="PROPRIETARIO">Proprietário</option>
                    <option value="GERENTE_FINANCEIRO">Gerente Financeiro</option>
                </select>
            </div>
            <div class="form-group">
                <label for="especialidade">Especialidade</label>
                <input type="text" id="especialidade">
            </div>
            <button type="submit" class="btn-primary">Salvar</button>
        </form>
    </div>

    <script>
        const profissionalId = document.getElementById('profissionalId').value;

        if (profissionalId) {
            fetch(`/api/profissionais.php/${profissionalId}`)
                .then(r=>r.json())
                .then(res=>{
                    if (res.success) {
                        const p = res.data;
                        document.getElementById('nome').value = p.nome || '';
                        document.getElementById('matricula').value = p.matricula || '';
                        document.getElementById('cargo').value = p.cargo || '';
                        document.getElementById('especialidade').value = p.especialidade || '';
                    } else {
                        alert(res.error || 'Erro ao carregar profissional');
                    }
                });
        }

        document.getElementById('formEditar').onsubmit = function(e){
            e.preventDefault();
            const id = profissionalId;
            const dados = {
                nome: document.getElementById('nome').value,
                matricula: document.getElementById('matricula').value,
                cargo: document.getElementById('cargo').value,
                especialidade: document.getElementById('especialidade').value
            };

            fetch(`/api/profissionais.php/${id}`, {
                method: 'PUT',
                headers: {'Content-Type':'application/json'},
                body: JSON.stringify(dados)
            })
            .then(r=>r.json())
            .then(res=>{
                if (res.success) {
                    alert(res.message || 'Atualizado');
                    window.location.href = '/Views/profissional/listar.php';
                } else {
                    alert(res.error || 'Erro ao atualizar');
                }
            });
        };
    </script>
</body>
</html>
