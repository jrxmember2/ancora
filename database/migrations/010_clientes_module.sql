

-- =========================================================
-- CLIENTES MODULE
-- =========================================================
CREATE TABLE IF NOT EXISTS client_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    scope VARCHAR(50) NOT NULL,
    name VARCHAR(120) NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    sort_order INT NOT NULL DEFAULT 999,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_client_types_scope_name (scope, name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS client_entities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    entity_type ENUM('pf','pj') NOT NULL DEFAULT 'pf',
    profile_scope ENUM('avulso','contato') NOT NULL DEFAULT 'avulso',
    role_tag VARCHAR(50) NOT NULL DEFAULT 'outro',
    display_name VARCHAR(180) NOT NULL,
    legal_name VARCHAR(180) NULL,
    cpf_cnpj VARCHAR(32) NULL,
    rg_ie VARCHAR(32) NULL,
    gender VARCHAR(20) NULL,
    nationality VARCHAR(80) NULL,
    birth_date DATE NULL,
    profession VARCHAR(120) NULL,
    marital_status VARCHAR(50) NULL,
    pis VARCHAR(32) NULL,
    spouse_name VARCHAR(180) NULL,
    father_name VARCHAR(180) NULL,
    mother_name VARCHAR(180) NULL,
    children_info TEXT NULL,
    ctps VARCHAR(32) NULL,
    cnae VARCHAR(32) NULL,
    state_registration VARCHAR(32) NULL,
    municipal_registration VARCHAR(32) NULL,
    opening_date DATE NULL,
    legal_representative VARCHAR(180) NULL,
    phones_json LONGTEXT NULL,
    emails_json LONGTEXT NULL,
    primary_address_json LONGTEXT NULL,
    billing_address_json LONGTEXT NULL,
    shareholders_json LONGTEXT NULL,
    notes TEXT NULL,
    description LONGTEXT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    inactive_reason VARCHAR(255) NULL,
    contract_end_date DATE NULL,
    created_by INT NULL,
    updated_by INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_client_entities_scope (profile_scope),
    KEY idx_client_entities_role (role_tag),
    KEY idx_client_entities_active (is_active),
    KEY idx_client_entities_document (cpf_cnpj)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS client_condominiums (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(180) NOT NULL,
    condominium_type_id INT NULL,
    has_blocks TINYINT(1) NOT NULL DEFAULT 0,
    cnpj VARCHAR(32) NULL,
    cnae VARCHAR(32) NULL,
    state_registration VARCHAR(32) NULL,
    municipal_registration VARCHAR(32) NULL,
    address_json LONGTEXT NULL,
    syndico_entity_id INT NULL,
    administradora_entity_id INT NULL,
    bank_details TEXT NULL,
    characteristics LONGTEXT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    inactive_reason VARCHAR(255) NULL,
    contract_end_date DATE NULL,
    created_by INT NULL,
    updated_by INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_client_condominiums_name (name),
    KEY idx_client_condominiums_type (condominium_type_id),
    CONSTRAINT fk_client_condominium_type FOREIGN KEY (condominium_type_id) REFERENCES client_types(id) ON DELETE SET NULL,
    CONSTRAINT fk_client_condominium_syndic FOREIGN KEY (syndico_entity_id) REFERENCES client_entities(id) ON DELETE SET NULL,
    CONSTRAINT fk_client_condominium_admin FOREIGN KEY (administradora_entity_id) REFERENCES client_entities(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS client_condominium_blocks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    condominium_id INT NOT NULL,
    name VARCHAR(50) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY idx_client_blocks_condo (condominium_id),
    CONSTRAINT fk_client_blocks_condo FOREIGN KEY (condominium_id) REFERENCES client_condominiums(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS client_units (
    id INT AUTO_INCREMENT PRIMARY KEY,
    condominium_id INT NOT NULL,
    block_id INT NULL,
    unit_type_id INT NULL,
    unit_number VARCHAR(50) NOT NULL,
    owner_entity_id INT NULL,
    tenant_entity_id INT NULL,
    owner_notes TEXT NULL,
    tenant_notes TEXT NULL,
    created_by INT NULL,
    updated_by INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_client_units_condo (condominium_id),
    KEY idx_client_units_block (block_id),
    KEY idx_client_units_type (unit_type_id),
    KEY idx_client_units_owner (owner_entity_id),
    KEY idx_client_units_tenant (tenant_entity_id),
    CONSTRAINT fk_client_units_condo FOREIGN KEY (condominium_id) REFERENCES client_condominiums(id) ON DELETE CASCADE,
    CONSTRAINT fk_client_units_block FOREIGN KEY (block_id) REFERENCES client_condominium_blocks(id) ON DELETE SET NULL,
    CONSTRAINT fk_client_units_type FOREIGN KEY (unit_type_id) REFERENCES client_types(id) ON DELETE SET NULL,
    CONSTRAINT fk_client_units_owner FOREIGN KEY (owner_entity_id) REFERENCES client_entities(id) ON DELETE SET NULL,
    CONSTRAINT fk_client_units_tenant FOREIGN KEY (tenant_entity_id) REFERENCES client_entities(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS client_attachments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    related_type VARCHAR(40) NOT NULL,
    related_id INT NOT NULL,
    file_role ENUM('documento','contrato','outro') NOT NULL DEFAULT 'documento',
    original_name VARCHAR(255) NOT NULL,
    stored_name VARCHAR(255) NOT NULL,
    relative_path VARCHAR(255) NOT NULL,
    mime_type VARCHAR(120) NULL,
    file_size INT NOT NULL DEFAULT 0,
    uploaded_by INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY idx_client_attachments_related (related_type, related_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS client_timelines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    related_type VARCHAR(40) NOT NULL,
    related_id INT NOT NULL,
    note LONGTEXT NOT NULL,
    user_id INT NULL,
    user_email VARCHAR(190) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY idx_client_timelines_related (related_type, related_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO client_types (scope, name, is_active, sort_order)
VALUES
('condominium','Residencial',1,1),
('condominium','Comercial',1,2),
('condominium','Misto',1,3),
('unit','Apartamento',1,1),
('unit','Sala',1,2),
('unit','Loja',1,3)
ON DUPLICATE KEY UPDATE is_active = VALUES(is_active);

INSERT INTO system_modules (name, slug, icon_class, route_prefix, sort_order, is_enabled)
VALUES ('Clientes', 'clientes', 'fa-solid fa-users', '/clientes', 14, 1)
ON DUPLICATE KEY UPDATE name = VALUES(name), icon_class = VALUES(icon_class), route_prefix = VALUES(route_prefix), sort_order = VALUES(sort_order), is_enabled = VALUES(is_enabled);

-- Migração inicial de síndicos e administradoras do legado para clientes
INSERT INTO client_entities (
    entity_type, profile_scope, role_tag, display_name, legal_name, cpf_cnpj, phones_json, emails_json, notes, is_active, created_by, updated_by
)
SELECT
    CASE WHEN COALESCE(a.cnpj, '') <> '' THEN 'pj' ELSE 'pf' END AS entity_type,
    'contato' AS profile_scope,
    CASE WHEN a.type = 'sindico' THEN 'sindico' ELSE 'administradora' END AS role_tag,
    a.nome AS display_name,
    a.nome AS legal_name,
    a.cnpj AS cpf_cnpj,
    JSON_ARRAY(JSON_OBJECT('label','Principal','number',COALESCE(a.telefone,''))) AS phones_json,
    JSON_ARRAY(JSON_OBJECT('label','Principal','email',COALESCE(a.email,''))) AS emails_json,
    a.observacoes,
    1,
    1,
    1
FROM administradoras a
WHERE NOT EXISTS (
    SELECT 1 FROM client_entities ce WHERE ce.display_name = a.nome AND ce.role_tag = CASE WHEN a.type = 'sindico' THEN 'sindico' ELSE 'administradora' END
);
