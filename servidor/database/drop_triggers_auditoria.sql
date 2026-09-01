-- ================================================
-- ELIMINAR TODOS LOS TRIGGERS DE AUDITORÍA
-- Ejecutar este archivo para deshacer la auditoría.
-- ================================================

DROP TRIGGER IF EXISTS trg_personas_after_insert;
DROP TRIGGER IF EXISTS trg_personas_after_update;
DROP TRIGGER IF EXISTS trg_personas_after_delete;

DROP TRIGGER IF EXISTS trg_usuarios_after_insert;
DROP TRIGGER IF EXISTS trg_usuarios_after_update;
DROP TRIGGER IF EXISTS trg_usuarios_after_delete;

DROP TRIGGER IF EXISTS trg_pagos_after_insert;
DROP TRIGGER IF EXISTS trg_pagos_after_update;
DROP TRIGGER IF EXISTS trg_pagos_after_delete;

DROP TRIGGER IF EXISTS trg_actividades_after_insert;
DROP TRIGGER IF EXISTS trg_actividades_after_update;
DROP TRIGGER IF EXISTS trg_actividades_after_delete;

DROP TRIGGER IF EXISTS trg_inscripcion_after_insert;
DROP TRIGGER IF EXISTS trg_inscripcion_after_update;
DROP TRIGGER IF EXISTS trg_inscripcion_after_delete;

DROP TRIGGER IF EXISTS trg_eventos_after_insert;
DROP TRIGGER IF EXISTS trg_eventos_after_update;
DROP TRIGGER IF EXISTS trg_eventos_after_delete;
