-- Migración: Agregar campo fecha_cambio_password a tabla personas
-- Permite rastrear cuándo se cambió la contraseña por última vez
-- Se usa para restringir cambios de contraseña cada 45 días

ALTER TABLE `personas`
ADD COLUMN `fecha_cambio_password` datetime DEFAULT NULL AFTER `token_expira`;
