# FORCEFAIR --- Arquitetura Técnica e Especificação de Implementação

## Visão Geral

A ForceFair é uma plataforma de venda de produtos farmacêuticos
integrada ao WooCommerce, onde:

-   Médicos geram prescrições e links de indicação (referral)
-   Pacientes compram produtos
-   Farmácias validam prescrições e enviam produtos
-   Médicos recebem comissão sobre vendas originadas de suas prescrições

O WooCommerce é utilizado como sistema central de pedidos, pagamentos e
checkout.

O plugin ForceFair adiciona:

-   Sistema de prescrições
-   Sistema de farmácias parceiras
-   Sistema de comissões
-   Sistema de referral médico
-   Controle de fluxo farmacêutico
-   APIs internas

------------------------------------------------------------------------

## Princípio fundamental

WooCommerce = source of truth para pedidos e pagamentos\
ForceFair = source of truth para prescrições, farmácia e comissões

------------------------------------------------------------------------

## Fluxo resumido

1.  Médico gera link referral
2.  Paciente acessa site
3.  Paciente faz checkout WooCommerce
4.  Prescrição é vinculada ao pedido
5.  Farmácia valida
6.  Farmácia envia produto
7.  Comissão é criada

------------------------------------------------------------------------

## Estrutura de tabelas

### wp_ff_doctors

-   id
-   user_id
-   crm
-   crm_state
-   specialty
-   commission_percentage
-   referral_code
-   created_at

### wp_ff_patients

-   id
-   user_id
-   cpf
-   birth_date
-   phone
-   created_at

### wp_ff_pharmacies

-   id
-   name
-   cnpj
-   email
-   phone
-   address
-   active
-   created_at

### wp_ff_prescriptions

-   id
-   doctor_id
-   patient_id
-   prescription_code
-   status
-   expires_at
-   created_at

### wp_ff_prescription_files

-   id
-   prescription_id
-   file_url
-   mime_type
-   created_at

### wp_ff_order_prescriptions

-   id
-   order_id
-   prescription_id
-   validated
-   validated_at

### wp_ff_order_pharmacy

-   id
-   order_id
-   pharmacy_id
-   status
-   tracking_code
-   shipped_at
-   delivered_at

### wp_ff_order_doctor_referral

-   id
-   order_id
-   doctor_id
-   referral_code
-   commission_percentage

### wp_ff_commissions

-   id
-   doctor_id
-   order_id
-   amount
-   percentage
-   status
-   created_at
-   paid_at

------------------------------------------------------------------------

## Hooks WooCommerce

-   woocommerce_checkout_order_processed
-   woocommerce_payment_complete
-   woocommerce_order_status_changed

------------------------------------------------------------------------

## Estrutura plugin

forcefair-plugin/

includes/ - core/ - database/ - services/ - api/ - hooks/

------------------------------------------------------------------------

## Segurança

Utilizar:

current_user_can()

para validar acesso.

------------------------------------------------------------------------

## Sistema pronto para produção
