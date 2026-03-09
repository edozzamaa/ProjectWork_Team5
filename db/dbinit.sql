CREATE DATABASE IF NOT EXISTS projectWorkTeam5
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE projectWorkTeam5;


CREATE TABLE CATEGORIA (
    codCat VARCHAR(10) PRIMARY KEY,
    tipo VARCHAR(50) NOT NULL
);


CREATE TABLE FORNITORE(
    ragSoc VARCHAR(100) PRIMARY KEY,
    partIVA VARCHAR(20),
    telefono VARCHAR(20),
    indirizzo VARCHAR(255),
    email VARCHAR(100)
);


CREATE TABLE CODIFICA_REG (
    codReg VARCHAR(50) PRIMARY KEY,
    descrizione TEXT NOT NULL
);


CREATE TABLE CODIFICA_OE (
    codOE VARCHAR(50) PRIMARY KEY,
    descrizione TEXT NOT NULL,
    ragSoc VARCHAR(100),
    FOREIGN KEY (ragSoc) REFERENCES FORNITORE(ragSoc)
);


CREATE TABLE PRODOTTO (
    codProd VARCHAR(20) PRIMARY KEY,
    qtaRiordino INT DEFAULT 0 CHECK (qtaRiordino >= 0),
    codCat VARCHAR(10),
    codReg VARCHAR(50),
    codOE VARCHAR(50),
    FOREIGN KEY (codCat) REFERENCES CATEGORIA(codCat),
    FOREIGN KEY (codReg) REFERENCES CODIFICA_REG(codReg),
    FOREIGN KEY (codOE) REFERENCES CODIFICA_OE(codOE)
);


CREATE TABLE ATTRIBUTO (
    codAttr VARCHAR(10) PRIMARY KEY,
    nome VARCHAR(50) NOT NULL
);


CREATE TABLE ATTR_PROD (
    codProd VARCHAR(20),
    codAttr VARCHAR(10),
    valore VARCHAR(100),
    PRIMARY KEY (codProd, codAttr),
    FOREIGN KEY (codProd) REFERENCES PRODOTTO(codProd),
    FOREIGN KEY (codAttr) REFERENCES ATTRIBUTO(codAttr)
);


CREATE TABLE ARMADIO (
    codArmadio VARCHAR(10) PRIMARY KEY,
    descrizione VARCHAR(100)
);


CREATE TABLE POSIZIONE (
    codArmadio VARCHAR(10),
    codScaffale VARCHAR(10),
    descrizione TEXT,
    PRIMARY KEY (codArmadio, codScaffale),
    FOREIGN KEY (codArmadio) REFERENCES ARMADIO(codArmadio)
);

CREATE TABLE POS_PROD(
    codProd VARCHAR(20),
    codArmadio VARCHAR(10),
    codScaffale VARCHAR(10),
    qta INT DEFAULT 0 CHECK (qta >= 0),
    PRIMARY KEY (codProd, codArmadio, codScaffale),
    FOREIGN KEY (codProd) REFERENCES PRODOTTO(codProd),
    FOREIGN KEY (codArmadio, codScaffale) REFERENCES POSIZIONE(codArmadio, codScaffale)
);


CREATE OR REPLACE VIEW v_stock_alert AS
SELECT
    p.codProd,
    cr.descrizione      AS descrizione_regionale,
    cat.tipo            AS categoria,
    p.qtaRiordino,
    COALESCE(SUM(pp.qta), 0) AS qtaTotale
FROM PRODOTTO p
LEFT JOIN POS_PROD pp ON pp.codProd = p.codProd
LEFT JOIN CATEGORIA cat ON cat.codCat = p.codCat
LEFT JOIN CODIFICA_REG cr ON cr.codReg = p.codReg
GROUP BY p.codProd, cr.descrizione, cat.tipo, p.qtaRiordino
HAVING COALESCE(SUM(pp.qta), 0) < p.qtaRiordino;


CREATE OR REPLACE VIEW v_stock_overview AS
SELECT
    p.codProd,
    cr.descrizione      AS descrizione_regionale,
    co.descrizione      AS descrizione_fornitore,
    p.codReg,
    p.codOE,
    cat.tipo            AS categoria,
    f.ragSoc            AS fornitore,
    a.codArmadio,
    a.descrizione       AS armadio_desc,
    pos.codScaffale,
    pp.qta,
    p.qtaRiordino,
    CASE
        WHEN pp.qta < p.qtaRiordino THEN 1
        ELSE 0
    END                 AS sotto_soglia
FROM POS_PROD pp
JOIN PRODOTTO p ON p.codProd = pp.codProd
JOIN POSIZIONE pos ON pos.codArmadio = pp.codArmadio AND pos.codScaffale = pp.codScaffale
JOIN ARMADIO a ON a.codArmadio = pp.codArmadio
LEFT JOIN CATEGORIA cat ON cat.codCat = p.codCat
LEFT JOIN CODIFICA_REG cr ON cr.codReg = p.codReg
LEFT JOIN CODIFICA_OE co ON co.codOE = p.codOE
LEFT JOIN FORNITORE f ON f.ragSoc = co.ragSoc;

-- ============================================================
-- Categorie obbligatorie
-- ============================================================
INSERT INTO CATEGORIA (codCat, tipo) VALUES
    ('VES', 'Vestiario'),
    ('CAL', 'Calzature'),
    ('DIS', 'Dispositivi');

-- ============================================================
-- Attributi predefiniti
-- ============================================================
INSERT INTO ATTRIBUTO (codAttr, nome) VALUES
    ('GEN', 'Genere'),
    ('STG', 'Stagione'),
    ('TGL', 'Taglia'),
    ('COL', 'Colore');


