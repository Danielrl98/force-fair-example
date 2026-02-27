# Fluxograma → Implementação

-Consulta → médico gera prescrição

-Paciente entra no site

-WooCommerce + wp_users

-Paciente envia receita - tabela: wp_ff_order_prescriptions

-Paciente escolhe farmácia - tabela: wp_ff_order_pharmacy

-Farmácia valida - status possíveis

```
    pending
    approved
    recused
```

-Farmácia envia produto - status possíveis

```
    shipped
    delivered
```

-Comissão gerada - tabela: wp_ff_commissions

- Relação visual simplificada:

```
    wp_users
    ├── wp_ff_doctors
    └── wp_ff_patients

    wp_ff_prescriptions
    └── wp_ff_prescription_items

    wp_wc_orders
    ├── wp_ff_order_prescriptions
    └── wp_ff_order_pharmacy

    wp_ff_pharmacies

    wp_ff_commissions
```

### Não precisar recriar pedidos, usar woocommerce, página de checkout:

```
    add_action('woocommerce_checkout_order_processed', 'ff_link_prescription');
```

### Estrutura plugin:

```
    forcefair-plugin/
    │
    ├── forcefair.php
    ├── includes/
    │ ├── database/
    │ │ ├── create-tables.php
    │ │
    │ ├── api/
    │ │ ├── prescriptions-controller.php
    │ │ ├── pharmacy-controller.php
    │ │ ├── commissions-controller.php
    │ │
    │ ├── services/
    │ │ ├── prescription-service.php
    │ │ ├── commission-service.php
    │ │
    │ └── hooks/
    │ ├── woocommerce-hooks.php
```

# Tabelas SQL

## Médicos

| campo                 | tipo               | descrição |
| --------------------- | ------------------ | --------- |
| id                    | BIGINT PK          |           |
| user_id               | BIGINT FK wp_users |           |
| crm                   | VARCHAR(50)        |           |
| crm_state             | VARCHAR(10)        |           |
| specialty             | VARCHAR(100)       |           |
| commission_percentage | DECIMAL(5,2)       |           |
| created_at            | DATETIME           |           |

## Pacientes

| campo      | tipo               |
| ---------- | ------------------ |
| id         | BIGINT PK          |
| user_id    | BIGINT FK wp_users |
| cpf        | VARCHAR(20)        |
| birth_date | DATE               |
| phone      | VARCHAR(20)        |
| created_at | DATETIME           |

## Prescrições

| campo             | tipo                |
| ----------------- | ------------------- |
| id                | BIGINT PK           |
| doctor_id         | BIGINT              |
| patient_id        | BIGINT              |
| prescription_code | VARCHAR(100) UNIQUE |
| file_url          | TEXT                |
| status            | ENUM                |

### status possíveis

```
    pending
    approved
    rejected
    expired
    fulfilled
```

## Itens da prescrição

| campo           | tipo                         |
| --------------- | ---------------------------- |
| id              | BIGINT PK                    |
| prescription_id | BIGINT                       |
| product_id      | BIGINT (WooCommerce product) |
| quantity        | INT                          |
| dosage          | VARCHAR(255)                 |

## Farmácias parceiras

| campo      | tipo         |
| ---------- | ------------ |
| id         | BIGINT PK    |
| name       | VARCHAR(255) |
| cnpj       | VARCHAR(20)  |
| email      | VARCHAR(255) |
| phone      | VARCHAR(20)  |
| address    | TEXT         |
| active     | BOOLEAN      |
| created_at | DATETIME     |

## Relacionamento Pedido ↔ Prescrição

| campo           | tipo      |
| --------------- | --------- |
| id              | BIGINT PK |
| order_id        | BIGINT    |
| prescription_id | BIGINT    |
| validated       | BOOLEAN   |
| validated_at    | DATETIME  |

## Farmácia responsável pelo pedido

| campo       | tipo      |
| ----------- | --------- |
| id          | BIGINT PK |
| order_id    | BIGINT    |
| pharmacy_id | BIGINT    |
| status      | ENUM      |

## Comissão do médico

| campo      | tipo          |
| ---------- | ------------- |
| id         | BIGINT PK     |
| doctor_id  | BIGINT        |
| order_id   | BIGINT        |
| amount     | DECIMAL(10,2) |
| percentage | DECIMAL(5,2)  |
| status     | ENUM          |

### status possíveis

```
    pending
    approved
    paid
    cancelled
```

## Auditória

| campo       | tipo         |
| ----------- | ------------ |
| id          | BIGINT       |
| user_id     | BIGINT       |
| action      | VARCHAR(100) |
| entity_type | VARCHAR(50)  |
| entity_id   | BIGINT       |
| data        | JSON         |
| created_at  | DATETIME     |
