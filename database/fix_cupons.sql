-- Script de correção para adicionar a coluna 'ativo' na tabela cupons
-- Execute este script no phpMyAdmin se você estiver tendo problemas com a coluna 'ativo'

USE mini_erp;

-- Adiciona a coluna 'ativo' se ela não existir
ALTER TABLE cupons 
ADD COLUMN IF NOT EXISTS ativo BOOLEAN DEFAULT TRUE AFTER valor_minimo;

-- Atualiza cupons existentes para ficarem ativos por padrão
UPDATE cupons SET ativo = TRUE WHERE ativo IS NULL;

-- Verifica se a coluna foi criada
DESCRIBE cupons;
