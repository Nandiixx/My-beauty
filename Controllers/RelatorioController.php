<?php
// Inicia a sessão se já não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclui os Modelos necessários
require_once __DIR__ . '/../Models/ConexaoDB.php';

class RelatorioController
{
    /**
     * Verifica se o usuário é ADMIN
     */
    private function verificarAutorizacao()
    {
        if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['usuario_cargo'] ?? '', ['PROPRIETARIO', 'GERENTE_FINANCEIRO'])) {
            $_SESSION['erro'] = "Acesso negado. Apenas administradores podem acessar relatórios.";
            header("Location: Index.php?acao=login_mostrar");
            exit;
        }
    }
    
    /**
     * Exibe o relatório financeiro
     * Mostra agendamentos concluídos e valores de serviços
     */
    public function mostrarRelatorioFinanceiro()
    {
        $this->verificarAutorizacao();
        
        try {
            $pdo = ConexaoDB::getConnection();
            
            // Busca agendamentos concluídos com valores de serviços
            $sql = "SELECT 
                        a.id as agendamento_id,
                        a.data_hora,
                        c_user.nome as cliente_nome,
                        f_user.nome as profissional_nome,
                        GROUP_CONCAT(s.nome SEPARATOR ', ') as servicos,
                        SUM(s.preco) as valor_total
                    FROM Agendamento a
                    JOIN Cliente c ON a.cliente_id = c.id
                    JOIN Usuario c_user ON c.usuario_id = c_user.id
                    JOIN Funcionario f ON a.profissional_id = f.id
                    JOIN Usuario f_user ON f.usuario_id = f_user.id
                    JOIN Agendamento_Servicos asv ON a.id = asv.agendamento_id
                    JOIN Servico s ON asv.servico_id = s.id
                    WHERE a.status = 'CONCLUIDO'
                    GROUP BY a.id
                    ORDER BY a.data_hora DESC";
                    
            $stmt = $pdo->query($sql);
            $agendamentos = $stmt->fetchAll(PDO::FETCH_OBJ);
            
            // Calcula o total geral
            $total_geral = 0;
            foreach ($agendamentos as $agendamento) {
                $total_geral += $agendamento->valor_total;
            }
            
            // Estatísticas adicionais
            $stmt_total_agendamentos = $pdo->query("SELECT COUNT(*) as total FROM Agendamento WHERE status = 'CONCLUIDO'");
            $total_agendamentos = $stmt_total_agendamentos->fetch()['total'] ?? 0;
            
            // Prepara dados para a View
            $dados = [
                'agendamentos' => $agendamentos,
                'total_geral' => $total_geral,
                'total_agendamentos' => $total_agendamentos
            ];
            
            // Inclui a View
            require_once __DIR__ . '/../Views/Admin/Relatorios/financeiro.php';
            
        } catch (Exception $e) {
            $_SESSION['erro'] = "Erro ao gerar relatório financeiro: " . $e->getMessage();
            header("Location: Index.php?acao=inicio_admin");
            exit;
        }
    }
    
    /**
     * Exibe o relatório de clientes
     * Mostra estatísticas de clientes e agendamentos
     */
    public function mostrarRelatorioClientes()
    {
        $this->verificarAutorizacao();
        
        try {
            $pdo = ConexaoDB::getConnection();
            
            // Busca estatísticas de clientes
            $sql = "SELECT 
                        c.id,
                        u.nome,
                        u.email,
                        u.telefone,
                        COUNT(CASE WHEN a.status = 'AGENDADO' THEN 1 END) as agendamentos_ativos,
                        COUNT(CASE WHEN a.status = 'CONCLUIDO' THEN 1 END) as agendamentos_concluidos,
                        COUNT(CASE WHEN a.status = 'CANCELADO' THEN 1 END) as agendamentos_cancelados,
                        COUNT(a.id) as total_agendamentos,
                        MAX(a.data_hora) as ultimo_agendamento,
                        MIN(a.data_hora) as primeiro_agendamento
                    FROM Cliente c
                    JOIN Usuario u ON c.usuario_id = u.id
                    LEFT JOIN Agendamento a ON c.id = a.cliente_id
                    GROUP BY c.id, u.nome, u.email, u.telefone
                    ORDER BY total_agendamentos DESC, u.nome";
                    
            $stmt = $pdo->query($sql);
            $clientes = $stmt->fetchAll(PDO::FETCH_OBJ);
            
            // Estatísticas gerais
            $stmt_total_clientes = $pdo->query("SELECT COUNT(*) as total FROM Cliente");
            $total_clientes = $stmt_total_clientes->fetch()['total'] ?? 0;
            
            $stmt_clientes_ativos = $pdo->query("SELECT COUNT(DISTINCT cliente_id) as total FROM Agendamento WHERE status = 'AGENDADO'");
            $clientes_ativos = $stmt_clientes_ativos->fetch()['total'] ?? 0;
            
            // Prepara dados para a View
            $dados = [
                'clientes' => $clientes,
                'total_clientes' => $total_clientes,
                'clientes_ativos' => $clientes_ativos
            ];
            
            // Inclui a View
            require_once __DIR__ . '/../Views/Admin/Relatorios/clientes.php';
            
        } catch (Exception $e) {
            $_SESSION['erro'] = "Erro ao gerar relatório de clientes: " . $e->getMessage();
            header("Location: Index.php?acao=inicio_admin");
            exit;
        }
    }
}
?>
