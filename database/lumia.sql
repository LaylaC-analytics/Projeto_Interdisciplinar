CREATE DATABASE IF NOT EXISTS lumia CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE lumia;

CREATE TABLE usuarios (
 id INT AUTO_INCREMENT PRIMARY KEY,
 nome VARCHAR(120) NOT NULL,
 email VARCHAR(160) NOT NULL UNIQUE,
 senha VARCHAR(255) NOT NULL,
 telefone VARCHAR(30),
 tipo ENUM('admin','cliente') NOT NULL DEFAULT 'cliente',
 ativo TINYINT(1) NOT NULL DEFAULT 1,
 criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE categorias (
 id INT AUTO_INCREMENT PRIMARY KEY,
 nome VARCHAR(100) NOT NULL UNIQUE,
 criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE marcas (
 id INT AUTO_INCREMENT PRIMARY KEY,
 nome VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE produtos (
 id INT AUTO_INCREMENT PRIMARY KEY,
 categoria_id INT NULL,
 marca_id INT NULL,
 nome VARCHAR(160) NOT NULL,
 descricao TEXT,
 preco DECIMAL(10,2) NOT NULL DEFAULT 0,
 estoque INT NOT NULL DEFAULT 0,
 ativo TINYINT(1) NOT NULL DEFAULT 1,
 criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_prod_categoria FOREIGN KEY(categoria_id) REFERENCES categorias(id) ON DELETE SET NULL,
 CONSTRAINT fk_prod_marca FOREIGN KEY(marca_id) REFERENCES marcas(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE pedidos (
 id INT AUTO_INCREMENT PRIMARY KEY,
 usuario_id INT NOT NULL,
 total DECIMAL(10,2) NOT NULL,
 status ENUM('Pendente','Em preparação','Enviado','Concluído','Cancelado') DEFAULT 'Pendente',
 endereco TEXT NOT NULL,
 criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_pedido_usuario FOREIGN KEY(usuario_id) REFERENCES usuarios(id)
) ENGINE=InnoDB;

CREATE TABLE itens_pedido (
 id INT AUTO_INCREMENT PRIMARY KEY,
 pedido_id INT NOT NULL,
 produto_id INT NOT NULL,
 quantidade INT NOT NULL,
 preco_unitario DECIMAL(10,2) NOT NULL,
 CONSTRAINT fk_item_pedido FOREIGN KEY(pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
 CONSTRAINT fk_item_produto FOREIGN KEY(produto_id) REFERENCES produtos(id)
) ENGINE=InnoDB;

CREATE TABLE logs (
 id INT AUTO_INCREMENT PRIMARY KEY,
 usuario_id INT NULL,
 acao VARCHAR(255) NOT NULL,
 ip VARCHAR(45),
 data_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_log_usuario FOREIGN KEY(usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE visitas (
 id INT AUTO_INCREMENT PRIMARY KEY,
 pagina VARCHAR(180),
 ip VARCHAR(45),
 visitado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO categorias(nome) VALUES ('Perfumes'),('Body Splash'),('Presentes'),('Corporal'),('Rosto'),('Banho');
INSERT INTO marcas(nome) VALUES ('LUMIA Essencial'),('Aurora'),('Flora');

INSERT INTO produtos(categoria_id,marca_id,nome,descricao,preco,estoque) VALUES
(1,1,'Perfume Rosa Encanto — 100ml','Fragrância floral delicada para o dia a dia.',189.90,24),
(4,2,'Óleo Corporal Lumina — 200ml','Óleo corporal para uma rotina de cuidado especial.',69.90,32),
(5,3,'Creme Hidratante Vida — 150ml','Hidratação diária com toque suave.',49.90,40),
(5,1,'Sérum Luminosidade — 30ml','Sérum para complementar sua rotina de cuidados.',89.90,18),
(6,2,'Sabonete Artesanal — Kit 3un','Kit com três sabonetes artesanais.',39.90,28);

-- A senha do administrador inicial é definida no primeiro acesso por setup_admin.php.
