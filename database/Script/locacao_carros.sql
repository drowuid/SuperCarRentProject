CREATE TABLE tipo_bens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) UNIQUE NOT NULL
);

CREATE TABLE marca (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_bem_id INT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    observacao TEXT,
    FOREIGN KEY (tipo_bem_id) REFERENCES tipo_bens(id)
);

CREATE TABLE bens_locaveis (
	id INT AUTO_INCREMENT PRIMARY KEY,
    marca_id INT NOT NULL,
    modelo VARCHAR(100),
    registo_unico_publico VARCHAR(20),
    cor VARCHAR(20),
    numero_passageiros INT,
    combustivel ENUM('gasolina', 'diesel', 'elétrico', 'híbrido', 'outro') NOT NULL,
    numero_portas INT,
    transmissao ENUM('manual', 'automática'),
    ano INT,
    manutencao BOOLEAN DEFAULT TRUE,
    preco_diario DECIMAL(10,2),
    observacao VARCHAR(200),
    FOREIGN KEY (marca_id) REFERENCES marca(id)
);

CREATE TABLE localizacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bem_locavel_id INT,
    cidade VARCHAR(100) NOT NULL,
    filial VARCHAR(100),
    posicao VARCHAR(100) NOT NULL,
    FOREIGN KEY (bem_locavel_id) REFERENCES bens_locaveis(id) ON DELETE CASCADE,
    UNIQUE (filial, posicao)
);

CREATE TABLE caracteristicas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) UNIQUE NOT NULL
);

CREATE TABLE bem_caracteristicas (
    bem_locavel_id INT,
    caracteristica_id INT,
    PRIMARY KEY (bem_locavel_id, caracteristica_id),
    FOREIGN KEY (bem_locavel_id) REFERENCES bens_locaveis(id) ON DELETE CASCADE,
    FOREIGN KEY (caracteristica_id) REFERENCES caracteristicas(id) ON DELETE CASCADE
);

INSERT INTO tipo_bens (id, nome) VALUES (1, 'Carro');

INSERT INTO marca (id, tipo_bem_id, nome, observacao) VALUES
(1, 1, 'Toyota', 'A confiabilidade e eficiência japonesa que movem o mundo!'),
(2, 1, 'Honda', 'ecnologia japonesa e eficiência compacta para levar você mais longe!'),
(3, 1, 'Ford', 'A tradição americana e a inovação automotiva ao seu alcance!.'),
(4, 1, 'Volkswagen', 'Engenharia alemã, performance e conforto para qualquer destino!'),
(5, 1, 'Renault', 'Elegância e economia francesas, sem abrir mão da qualidade!');

INSERT INTO bens_locaveis (
    marca_id, modelo, registo_unico_publico, cor,
    numero_passageiros, combustivel, numero_portas, transmissao,
    ano, manutencao, preco_diario, observacao
) VALUES
(1, 'Corolla', '01-AC-01', 'branco', 5, 'gasolina', 4, 'automática', 2020, FALSE, 50.00, ''),
(1, 'Corolla', 'RS-39-SC', 'cinza', 5, 'gasolina', 4, 'manual', 2022, FALSE, 55.00, ''),
(1, 'Yaris', 'MS-BA-02', 'vermelho', 5, 'gasolina', 4, 'manual', 2021, FALSE, 48.00, ''),
(1, 'Yaris', '09-TO-PE', 'azul', 5, 'híbrido', 4, 'automática', 2021, FALSE, 50.00, ''),
(1, 'RAV4', '07-SE-AL', 'preto', 5, 'híbrido', 5, 'automática', 2023, FALSE, 65.00, ''),
(1, 'RAV4', 'AD-CT-09', 'branco', 5, 'híbrido', 5, 'automática', 2024, FALSE, 70.00, ''),
(2, 'Civic', 'AB-10-RN', 'cinza', 5, 'gasolina', 4, 'manual', 2020, FALSE, 55.00, ''),
(2, 'Civic', 'YG-FC-08', 'azul', 5, 'gasolina', 4, 'automática', 2022, FALSE, 60.00, ''),
(2, 'Fit', 'GB-78-AH', 'vermelho', 5, 'gasolina', 4, 'manual', 2020, FALSE, 50.00, ''),
(2, 'Fit', 'EH-16-PA', 'branco', 5, 'gasolina', 4, 'automática', 2022, FALSE, 54.00, ''),
(2, 'HR-V', 'WS-54-RJ', 'preto', 5, 'híbrido', 5, 'automática', 2020, FALSE, 65.00, ''),
(2, 'HR-V', 'SP-24-PB', 'cinza', 5, 'híbrido', 5, 'automática', 2022, FALSE, 70.00, ''),
(3, 'Focus', 'JV-95-HP', 'branco', 5, 'Gasolina', 4, 'manual', 2020, FALSE, 55.00, ''),
(3, 'Focus', 'PM-BP-90', 'preto', 5, 'diesel', 4, 'automática', 2022, FALSE, 59.00, ''),
(3, 'Fiesta', 'PA-12-AP', 'vermelho', 5, 'gasolina', 4, 'manual', 2020, FALSE, 48.00, ''),
(3, 'Fiesta', 'MT-64-MG', 'azul', 5, 'gasolina', 4, 'manual', 2022, FALSE, 52.00, ''),
(3, 'EcoSport', 'AA-A1-03', 'preto', 5, 'gasolina', 5, 'automática', 2020, FALSE, 60.00, ''),
(3, 'EcoSport', 'HY-10-27', 'branco', 5, 'diesel', 5, 'automática', 2022, FALSE, 66.00, ''),
(4, 'Golf', 'DF-83-03', 'cinza', 5, 'gasolina', 4, 'manual', 2020, FALSE, 70.00, ''),
(4, 'Golf', 'MA-PA-27', 'preto', 5, 'gasolina', 4, 'automática', 2022, FALSE, 75.00, ''),
(4, 'Polo', 'AM-10-31', 'vermelho', 5, 'gasolina', 4, 'manual', 2020, FALSE, 58.00, ''),
(4, 'Polo', 'CE-93-RO', 'cinza', 5, 'gasolina', 4, 'manual', 2022, FALSE, 62.00, ''),
(4, 'Tiguan', 'AC-RM-33', 'azul', 5, 'diesel', 5, 'automática', 2020, FALSE, 80.00, ''),
(4, 'Tiguan', '12-PM-36', 'preto', 5, 'diesel', 5, 'automática', 2022, FALSE, 85.00, ''),
(5, 'Clio', 'AC-MS-90', 'branco', 5, 'gasolina', 4, 'manual', 2020, FALSE, 45.00, ''),
(5, 'Clio', 'PR-59-23', 'azul', 5, 'gasolina', 4, 'manual', 2021, FALSE, 47.00, ''),
(5, 'Captur', '21-ES-34', 'preto', 5, 'híbrido', 5, 'automática', 2020, FALSE, 60.00, ''),
(5, 'Captur', 'BA-93-57', 'vermelho', 5, 'híbrido', 5, 'automática', 2021, FALSE, 63.00, ''),
(5, 'Megane', 'GO-AL-68', 'cinza', 5, 'diesel', 4, 'manual', 2020, FALSE, 68.00, ''),
(5, 'Megane', '29-SE-97', 'azul', 5, 'diesel', 4, 'automática', 2022, FALSE, 73.00, '');

INSERT INTO caracteristicas (nome) VALUES
('Ar-condicionado'),
('Direção assistida'),
('GPS'),
('Bluetooth'),
('Câmara de marcha-atrás'),
('Sensores de estacionamento'),
('Caixa automática'),
('Isofix'),
('Bagageira grande');

INSERT INTO bem_caracteristicas (bem_locavel_id, caracteristica_id)
SELECT id, 1 FROM bens_locaveis;
INSERT INTO bem_caracteristicas (bem_locavel_id, caracteristica_id)
SELECT id, 2 FROM bens_locaveis;

INSERT INTO bem_caracteristicas (bem_locavel_id, caracteristica_id)
SELECT id, 3 FROM bens_locaveis WHERE MOD(id, 2) = 1;

INSERT INTO bem_caracteristicas (bem_locavel_id, caracteristica_id)
SELECT id, 4 FROM bens_locaveis WHERE MOD(id, 3) IN (0, 2);

INSERT INTO bem_caracteristicas (bem_locavel_id, caracteristica_id)
SELECT id, 5 FROM bens_locaveis WHERE MOD(id, 3) = 0;

INSERT INTO bem_caracteristicas (bem_locavel_id, caracteristica_id) VALUES
(7,6),(8,6),(9,6),
(16,6),(17,6),(18,6),
(25,6),(26,6),(27,6),
(20,6),(15,6),(6,6);

INSERT INTO bem_caracteristicas (bem_locavel_id, caracteristica_id)
SELECT id, 7 FROM bens_locaveis WHERE MOD(id, 3) = 0;

INSERT INTO bem_caracteristicas (bem_locavel_id, caracteristica_id) VALUES
(13,8),(14,8),(15,8),
(16,8),(17,8),(18,8),
(30,8),(28,8),(22,8),
(23,8),(10,8),(12,8);

INSERT INTO bem_caracteristicas (bem_locavel_id, caracteristica_id) VALUES
(1,9),(2,9),(3,9),
(7,9),(8,9),(9,9),
(10,9),(11,9),(12,9),
(16,9),(17,9),(18,9),
(19,9),(20,9),(21,9),
(23,9),(30,9),(29,9);

INSERT INTO localizacoes (bem_locavel_id, cidade, filial, posicao) VALUES
(1, 'Lisboa', 'Unidade Lisboa Aeroporto', 'A1'),
(2, 'Braga', 'Unidade Braga Centro', 'A2'),
(3, 'Lisboa', 'Unidade Lisboa Aeroporto', 'A3'),
(4, 'Porto', 'Unidade Porto Centro', 'B1'),
(5, 'Braga', 'Unidade Braga Nogueira', 'B2'),
(6, 'Porto', 'Unidade Porto Centro', 'B2'),
(7, 'Braga', 'Unidade Braga Centro', 'A1'),
(8, 'Braga', 'Unidade Braga Centro', 'A3'),
(9, 'Braga', 'Unidade Braga Nogueira', 'B1'),
(10, 'Coimbra', 'Unidade Coimbra Estação', 'A1'),
(11, 'Braga', 'Unidade Braga Nogueira', 'A1'),
(12, 'Coimbra', 'Unidade Coimbra Estação', 'A2'),
(13, 'Lisboa', 'Unidade Lisboa Aeroporto', 'A2'),
(14, 'Porto', 'Unidade Porto Centro', 'B3'),
(15, 'Coimbra', 'Unidade Coimbra Estação', 'B2'),
(16, 'Braga', 'Unidade Braga Nogueira', 'A2'),
(17, 'Braga', 'Unidade Braga Centro', 'A4'),
(18, 'Braga', 'Unidade Braga Nogueira', 'C1'),
(19, 'Porto', 'Unidade Porto Centro', 'A1'),
(20, 'Coimbra', 'Unidade Coimbra Estação', 'B1'),
(21, 'Braga', 'Unidade Braga Centro', 'B1'),
(22, 'Lisboa', 'Unidade Lisboa Aeroporto', 'A4'),
(23, 'Lisboa', 'Unidade Lisboa Aeroporto', 'A5'),
(24, 'Porto', 'Unidade Porto Centro', 'A2'),
(25, 'Coimbra', 'Unidade Coimbra Estação', 'C1'),
(26, 'Porto', 'Unidade Porto Centro', 'A3'),
(27, 'Braga', 'Unidade Braga Centro', 'B2'),
(28, 'Braga', 'Unidade Braga Nogueira', 'C2'),
(29, 'Braga', 'Unidade Braga Centro', 'B3'),
(30, 'Coimbra', 'Unidade Coimbra Estação', 'C2');
