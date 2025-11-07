<?php
/*
 * Ponto de entrada único da aplicação (Front-Controller)
 */

// Inicia a sessão em um único lugar, antes de qualquer saída.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Inclui funções auxiliares (helpers)
require_once __DIR__ . '/helpers.php';

require_once __DIR__ . '/Controllers/Navegacao.php';
?>