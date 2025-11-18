-- Arquivo: sql/criar_tabela_despesa.sql
-- Script para criar a tabela Despesa no banco de dados tcc

USE tcc;

-- Tabela para Despesas do Salão
CREATE TABLE IF NOT EXISTS Despesa (
    id INT PRIMARY KEY AUTO_INCREMENT,
    descricao TEXT NOT NULL,
    valor DECIMAL(10, 2) NOT NULL,
    data DATE NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Índice para busca por data
CREATE INDEX idx_despesa_data ON Despesa(data);

-- Índice para busca por categoria
CREATE INDEX idx_despesa_categoria ON Despesa(categoria);

-- Inserir alguns dados de exemplo (opcional)
INSERT INTO Despesa (descricao, valor, data, categoria) VALUES
('Aluguel do salão - Janeiro 2024', 2500.00, '2024-01-01', 'Aluguel'),
('Conta de energia elétrica', 450.00, '2024-01-10', 'Energia'),
('Produtos para tratamento capilar', 850.00, '2024-01-15', 'Produtos'),
('Manutenção de equipamentos', 300.00, '2024-01-20', 'Manutenção'),
('Conta de água', 120.00, '2024-01-25', 'Água'),
('Campanha de marketing nas redes sociais', 500.00, '2024-02-01', 'Marketing'),
('Internet e telefonia', 150.00, '2024-02-05', 'Internet');
