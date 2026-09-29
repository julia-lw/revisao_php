CREATE DATABASE IF NOT EXISTS sistema_fotos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sistema_fotos;

-- Tabela de Usuários
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

-- Tabela de Fotos com CHAVE ESTRANGEIRA (FOREIGN KEY)
CREATE TABLE fotos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    titulo VARCHAR(100) NOT NULL,
    caminho VARCHAR(255) NOT NULL,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_usuario_foto 
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id) 
        ON DELETE CASCADE
) ENGINE=InnoDB;