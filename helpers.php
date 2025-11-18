<?php
/**
 * Arquivo de funções auxiliares (Helpers)
 * Funções reutilizáveis para formatação e apresentação de dados
 */

/**
 * Formata uma data/hora para exibição
 * 
 * @param string $data_hora Data/hora no formato Y-m-d H:i:s
 * @return array Array associativo com partes formatadas da data
 */
function formatarData($data_hora) {
    $dt = new DateTime($data_hora);
    return [
        'dia' => $dt->format('d'),
        'mes' => $dt->format('M'),
        'hora' => $dt->format('H:i'),
        'data_completa' => $dt->format('d/m/Y'),
        'dia_semana' => ucfirst($dt->format('l'))
    ];
}

/**
 * Retorna as classes CSS e texto para exibição de badge de status
 * 
 * @param string $status Status do agendamento (AGENDADO, CONCLUIDO, CANCELADO)
 * @return array Array com 'class' e 'texto' do status
 */
function statusBadge($status) {
    $badges = [
        'AGENDADO' => ['class' => 'status-badge--agendado', 'texto' => 'Agendado'],
        'CONCLUIDO' => ['class' => 'status-badge--concluido', 'texto' => 'Concluído'],
        'CANCELADO' => ['class' => 'status-badge--cancelado', 'texto' => 'Cancelado']
    ];
    return $badges[$status] ?? ['class' => '', 'texto' => $status];
}

/**
 * Escapa HTML para prevenir XSS
 * Atalho para htmlspecialchars com configurações padrão
 * 
 * @param string $string String para escapar
 * @return string String escapada
 */
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Verifica se o usuário está autenticado
 * 
 * @return bool True se autenticado, false caso contrário
 */
function estaAutenticado() {
    return isset($_SESSION['usuario_id']);
}

/**
 * Verifica se o usuário tem um tipo específico
 * 
 * @param string $tipo Tipo de usuário (CLIENTE, PROFISSIONAL, ADMIN)
 * @return bool True se o usuário tem o tipo especificado
 */
function verificarTipoUsuario($tipo) {
    return isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === $tipo;
}

/**
 * Verifica se o usuário tem um cargo específico
 * 
 * @param array $cargos Array de cargos permitidos
 * @return bool True se o usuário tem um dos cargos especificados
 */
function verificarCargo($cargos) {
    if (!isset($_SESSION['usuario_cargo'])) {
        return false;
    }
    return in_array($_SESSION['usuario_cargo'], (array)$cargos);
}

/**
 * Retorna o texto descritivo de um cargo
 * 
 * @param string $cargo Código do cargo
 * @return string Descrição do cargo
 */
function cargoTexto($cargo) {
    $cargos = [
        'PROFISSIONAL_BELEZA' => 'Profissional de Beleza',
        'RECEPCIONISTA' => 'Recepcionista',
        'PROPRIETARIO' => 'Proprietário',
        'GERENTE_FINANCEIRO' => 'Gerente Financeiro'
    ];
    return $cargos[$cargo] ?? $cargo;
}

/**
 * Retorna a classe CSS para badge de cargo
 * 
 * @param string $cargo Código do cargo
 * @return string Classe CSS do badge
 */
function cargoBadge($cargo) {
    $badges = [
        'PROFISSIONAL_BELEZA' => 'badge-primary',
        'RECEPCIONISTA' => 'badge-info',
        'PROPRIETARIO' => 'badge-warning',
        'GERENTE_FINANCEIRO' => 'badge-success'
    ];
    return $badges[$cargo] ?? 'badge-secondary';
}

/**
 * Formata data para input datetime-local
 * 
 * @param string $data_hora Data/hora no formato Y-m-d H:i:s
 * @return string Data formatada para input (Y-m-d\TH:i)
 */
function formatarDataParaInput($data_hora) {
    // Converte do formato do banco (YYYY-MM-DD HH:MM:SS) para o formato do input datetime-local (YYYY-MM-DDTHH:MM)
    $dt = new DateTime($data_hora);
    return $dt->format('Y-m-d\TH:i');
}
?>
