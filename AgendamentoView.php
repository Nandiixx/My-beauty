<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Agendamentos - My Beauty</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Gerenciamento de Agendamentos</h1>
        <nav>
            <a href="">Início</a> |
        </nav>
    </header>

    <section>
        <h2>Novo Agendamento</h2>
        <form action="" method="POST">
            <label>Cliente:</label><br>
            <input type="text" name="cliente" required><br><br>

            <label>Profissional:</label><br>
            <input type="text" name="profissional" required><br><br>

            <label>Serviço:</label><br>
            <input type="text" name="servico" required><br><br>

            <label>Data e Hora:</label><br>
            <input type="datetime-local" name="dataHora" required><br><br>

            <button type="submit">Agendar</button>
        </form>
    </section>

    <section>
        <h2>Lista de Agendamentos</h2>
        <table border="1" cellpadding="8">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Profissional</th>
                    <th>Serviço</th>
                    <th>Data e Hora</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!isset($agendamentos) || !is_array($agendamentos)) {
                    $agendamentos = [];
                    }
                    foreach($agendamentos as $a): ?>
                <tr>
                    <td><?= $a['id'] ?></td>
                    <td><?= $a['cliente'] ?></td>
                    <td><?= $a['profissional'] ?></td>
                    <td><?= $a['servico'] ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($a['dataHora'])) ?></td>
                    <td><?= $a['status'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</body>
</html>