-- Criar o banco de dados
CREATE DATABASE IF NOT EXISTS tcc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Usar o banco criado
USE tcc;

-- Tabela base para todos os usuários que podem fazer login
CREATE TABLE IF NOT EXISTS Usuario (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    telefone VARCHAR(20),
    senha_hash VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela para Clientes
CREATE TABLE IF NOT EXISTS Cliente (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL UNIQUE,
    FOREIGN KEY (usuario_id) REFERENCES Usuario(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela para Funcionários
CREATE TABLE IF NOT EXISTS Funcionario (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL UNIQUE,
    matricula VARCHAR(50) NOT NULL UNIQUE,
    cargo ENUM('RECEPCIONISTA', 'PROFISSIONAL_BELEZA', 'PROPRIETARIO', 'GERENTE_FINANCEIRO') NOT NULL,
    especialidade VARCHAR(100),
    FOREIGN KEY (usuario_id) REFERENCES Usuario(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela para os Serviços
CREATE TABLE IF NOT EXISTS Servico (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10, 2) NOT NULL,
    duracao_minutos INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela central de Agendamentos
CREATE TABLE IF NOT EXISTS Agendamento (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cliente_id INT NOT NULL,
    profissional_id INT NOT NULL,
    data_hora DATETIME NOT NULL,
    status ENUM('AGENDADO', 'CONCLUIDO', 'CANCELADO') NOT NULL DEFAULT 'AGENDADO',
    FOREIGN KEY (cliente_id) REFERENCES Cliente(id) ON DELETE CASCADE,
    FOREIGN KEY (profissional_id) REFERENCES Funcionario(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de ligação Agendamento_Servicos
CREATE TABLE IF NOT EXISTS Agendamento_Servicos (
    agendamento_id INT NOT NULL,
    servico_id INT NOT NULL,
    PRIMARY KEY (agendamento_id, servico_id),
    FOREIGN KEY (agendamento_id) REFERENCES Agendamento(id) ON DELETE CASCADE,
    FOREIGN KEY (servico_id) REFERENCES Servico(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela para Recuperação de Senha
CREATE TABLE IF NOT EXISTS RecuperacaoSenha (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    token VARCHAR(255) NOT NULL UNIQUE,
    data_expiracao DATETIME NOT NULL,
    utilizado TINYINT(1) NOT NULL DEFAULT 0,
    data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES Usuario(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserir alguns serviços de exemplo
INSERT INTO Servico (nome, descricao, preco, duracao_minutos) VALUES
('Corte de Cabelo', 'Corte de cabelo feminino ou masculino', 35.00, 60),
('Escova', 'Escova com secador', 25.00, 30),
('Coloração', 'Coloração completa do cabelo', 120.00, 180),
('Manicure', 'Manicure simples', 20.00, 45),
('Pedicure', 'Pedicure simples', 25.00, 45),
('Maquiagem', 'Maquiagem para eventos', 80.00, 90)
ON DUPLICATE KEY UPDATE nome=nome;

