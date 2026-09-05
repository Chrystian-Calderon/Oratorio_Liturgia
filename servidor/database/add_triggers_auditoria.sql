-- ================================================
-- TRIGGERS DE AUDITORÍA - MariaDB 11.8 Compatible
-- ================================================
-- Estos triggers registran automáticamente todas las
-- operaciones INSERT, UPDATE y DELETE en las 6 tablas
-- sensibles del sistema.
--
-- REQUISITO: Antes de cada operación, ejecutar:
--   SET @audit_user_id = <id_usuario>;
--   SET @audit_usuario_nombre = '<nombre del usuario>';
-- desde PHP para que el trigger conozca el usuario.
-- ================================================

-- Cambiar delimitador para poder definir triggers con ;
DELIMITER //


-- ================================================
-- TABLA: personas
-- Excluye el campo password por seguridad.
-- ================================================

-- AFTER INSERT personas
CREATE TRIGGER trg_personas_after_insert
AFTER INSERT ON personas
FOR EACH ROW
BEGIN
    INSERT INTO auditoria (
        id_usuario, usuario_mysql, accion, tabla_afectada,
        registro_id, fecha_hora, descripcion, valores_nuevos
    ) VALUES (
        @audit_user_id,
        @audit_usuario_nombre,
        'INSERT',
        'personas',
        NEW.id_persona,
        NOW(),
        'Nuevo registro de persona creado',
        JSON_OBJECT(
            'id_persona', NEW.id_persona,
            'ci', NEW.ci,
            'nombres', NEW.nombres,
            'apellidos', NEW.apellidos,
            'genero', NEW.genero,
            'correo', NEW.correo,
            'tipo_persona', NEW.tipo_persona,
            'estado', NEW.estado,
            'id_universidad', NEW.id_universidad,
            'id_usuario', NEW.id_usuario
        )
    );
END //

-- AFTER UPDATE personas
CREATE TRIGGER trg_personas_after_update
AFTER UPDATE ON personas
FOR EACH ROW
BEGIN
    IF OLD.ci != NEW.ci
       OR OLD.nombres != NEW.nombres
       OR OLD.apellidos != NEW.apellidos
       OR OLD.genero != NEW.genero
       OR OLD.direccion != NEW.direccion
       OR OLD.telefono != NEW.telefono
       OR OLD.correo != NEW.correo
       OR OLD.tipo_persona != NEW.tipo_persona
       OR OLD.estado != NEW.estado
       OR OLD.id_universidad != NEW.id_universidad
       OR OLD.id_usuario != NEW.id_usuario
       OR OLD.foto_perfil != NEW.foto_perfil
    THEN
        INSERT INTO auditoria (
            id_usuario, usuario_mysql, accion, tabla_afectada,
            registro_id, fecha_hora, descripcion,
            valores_anteriores, valores_nuevos
        ) VALUES (
            @audit_user_id,
            @audit_usuario_nombre,
            'UPDATE',
            'personas',
            OLD.id_persona,
            NOW(),
            CONCAT('Campos modificados: ',
                IF(OLD.ci != NEW.ci, 'ci, ', ''),
                IF(OLD.nombres != NEW.nombres, 'nombres, ', ''),
                IF(OLD.apellidos != NEW.apellidos, 'apellidos, ', ''),
                IF(OLD.genero != NEW.genero, 'genero, ', ''),
                IF(OLD.direccion != NEW.direccion, 'direccion, ', ''),
                IF(OLD.telefono != NEW.telefono, 'telefono, ', ''),
                IF(OLD.correo != NEW.correo, 'correo, ', ''),
                IF(OLD.tipo_persona != NEW.tipo_persona, 'tipo_persona, ', ''),
                IF(OLD.estado != NEW.estado, 'estado, ', ''),
                IF(OLD.id_universidad != NEW.id_universidad, 'id_universidad, ', ''),
                IF(OLD.id_usuario != NEW.id_usuario, 'id_usuario, ', ''),
                IF(OLD.foto_perfil != NEW.foto_perfil, 'foto_perfil, ', '')
            ),
            JSON_OBJECT(
                'ci', OLD.ci,
                'nombres', OLD.nombres,
                'apellidos', OLD.apellidos,
                'genero', OLD.genero,
                'direccion', OLD.direccion,
                'telefono', OLD.telefono,
                'correo', OLD.correo,
                'tipo_persona', OLD.tipo_persona,
                'estado', OLD.estado,
                'id_universidad', OLD.id_universidad,
                'id_usuario', OLD.id_usuario,
                'foto_perfil', OLD.foto_perfil
            ),
            JSON_OBJECT(
                'ci', NEW.ci,
                'nombres', NEW.nombres,
                'apellidos', NEW.apellidos,
                'genero', NEW.genero,
                'direccion', NEW.direccion,
                'telefono', NEW.telefono,
                'correo', NEW.correo,
                'tipo_persona', NEW.tipo_persona,
                'estado', NEW.estado,
                'id_universidad', NEW.id_universidad,
                'id_usuario', NEW.id_usuario,
                'foto_perfil', NEW.foto_perfil
            )
        );
    END IF;
END //

-- AFTER DELETE personas
CREATE TRIGGER trg_personas_after_delete
AFTER DELETE ON personas
FOR EACH ROW
BEGIN
    INSERT INTO auditoria (
        id_usuario, usuario_mysql, accion, tabla_afectada,
        registro_id, fecha_hora, descripcion, valores_anteriores
    ) VALUES (
        @audit_user_id,
        @audit_usuario_nombre,
        'DELETE',
        'personas',
        OLD.id_persona,
        NOW(),
        CONCAT('Persona eliminada: ', OLD.nombres, ' ', OLD.apellidos, ' (CI: ', OLD.ci, ')'),
        JSON_OBJECT(
            'id_persona', OLD.id_persona,
            'ci', OLD.ci,
            'nombres', OLD.nombres,
            'apellidos', OLD.apellidos,
            'genero', OLD.genero,
            'direccion', OLD.direccion,
            'telefono', OLD.telefono,
            'correo', OLD.correo,
            'tipo_persona', OLD.tipo_persona,
            'estado', OLD.estado,
            'id_universidad', OLD.id_universidad,
            'id_usuario', OLD.id_usuario
        )
    );
END //


-- ================================================
-- TABLA: usuarios_sistema
-- ================================================

-- AFTER INSERT usuarios_sistema
CREATE TRIGGER trg_usuarios_after_insert
AFTER INSERT ON usuarios_sistema
FOR EACH ROW
BEGIN
    INSERT INTO auditoria (
        id_usuario, usuario_mysql, accion, tabla_afectada,
        registro_id, fecha_hora, descripcion, valores_nuevos
    ) VALUES (
        @audit_user_id,
        @audit_usuario_nombre,
        'INSERT',
        'usuarios_sistema',
        NEW.id_usuario,
        NOW(),
        'Nuevo usuario de sistema creado',
        JSON_OBJECT(
            'id_usuario', NEW.id_usuario,
            'rol', NEW.rol,
            'permisos', NEW.permisos,
            'estado', NEW.estado
        )
    );
END //

-- AFTER UPDATE usuarios_sistema
CREATE TRIGGER trg_usuarios_after_update
AFTER UPDATE ON usuarios_sistema
FOR EACH ROW
BEGIN
    IF OLD.rol != NEW.rol
       OR OLD.permisos != NEW.permisos
       OR OLD.estado != NEW.estado
    THEN
        INSERT INTO auditoria (
            id_usuario, usuario_mysql, accion, tabla_afectada,
            registro_id, fecha_hora, descripcion,
            valores_anteriores, valores_nuevos
        ) VALUES (
            @audit_user_id,
            @audit_usuario_nombre,
            'UPDATE',
            'usuarios_sistema',
            OLD.id_usuario,
            NOW(),
            CONCAT('Campos modificados: ',
                IF(OLD.rol != NEW.rol, 'rol, ', ''),
                IF(OLD.permisos != NEW.permisos, 'permisos, ', ''),
                IF(OLD.estado != NEW.estado, 'estado, ', '')
            ),
            JSON_OBJECT(
                'rol', OLD.rol,
                'permisos', OLD.permisos,
                'estado', OLD.estado
            ),
            JSON_OBJECT(
                'rol', NEW.rol,
                'permisos', NEW.permisos,
                'estado', NEW.estado
            )
        );
    END IF;
END //

-- AFTER DELETE usuarios_sistema
CREATE TRIGGER trg_usuarios_after_delete
AFTER DELETE ON usuarios_sistema
FOR EACH ROW
BEGIN
    INSERT INTO auditoria (
        id_usuario, usuario_mysql, accion, tabla_afectada,
        registro_id, fecha_hora, descripcion, valores_anteriores
    ) VALUES (
        @audit_user_id,
        @audit_usuario_nombre,
        'DELETE',
        'usuarios_sistema',
        OLD.id_usuario,
        NOW(),
        CONCAT('Usuario de sistema eliminado: rol=', OLD.rol),
        JSON_OBJECT(
            'id_usuario', OLD.id_usuario,
            'rol', OLD.rol,
            'permisos', OLD.permisos,
            'estado', OLD.estado
        )
    );
END //


-- ================================================
-- TABLA: pagos
-- ================================================

-- AFTER INSERT pagos
CREATE TRIGGER trg_pagos_after_insert
AFTER INSERT ON pagos
FOR EACH ROW
BEGIN
    INSERT INTO auditoria (
        id_usuario, usuario_mysql, accion, tabla_afectada,
        registro_id, fecha_hora, descripcion, valores_nuevos
    ) VALUES (
        @audit_user_id,
        @audit_usuario_nombre,
        'INSERT',
        'pagos',
        NEW.id_pago,
        NOW(),
        CONCAT('Pago registrado: ', NEW.concepto, ' - Bs. ', NEW.monto),
        JSON_OBJECT(
            'id_pago', NEW.id_pago,
            'id_persona', NEW.id_persona,
            'concepto', NEW.concepto,
            'monto', NEW.monto,
            'fecha_pago', NEW.fecha_pago,
            'metodo_pago', NEW.metodo_pago,
            'estado', NEW.estado
        )
    );
END //

-- AFTER UPDATE pagos
CREATE TRIGGER trg_pagos_after_update
AFTER UPDATE ON pagos
FOR EACH ROW
BEGIN
    IF OLD.concepto != NEW.concepto
       OR OLD.monto != NEW.monto
       OR OLD.fecha_pago != NEW.fecha_pago
       OR OLD.metodo_pago != NEW.metodo_pago
       OR OLD.estado != NEW.estado
       OR OLD.observaciones != NEW.observaciones
    THEN
        INSERT INTO auditoria (
            id_usuario, usuario_mysql, accion, tabla_afectada,
            registro_id, fecha_hora, descripcion,
            valores_anteriores, valores_nuevos
        ) VALUES (
            @audit_user_id,
            @audit_usuario_nombre,
            'UPDATE',
            'pagos',
            OLD.id_pago,
            NOW(),
            CONCAT('Campos modificados: ',
                IF(OLD.concepto != NEW.concepto, 'concepto, ', ''),
                IF(OLD.monto != NEW.monto, 'monto, ', ''),
                IF(OLD.fecha_pago != NEW.fecha_pago, 'fecha_pago, ', ''),
                IF(OLD.metodo_pago != NEW.metodo_pago, 'metodo_pago, ', ''),
                IF(OLD.estado != NEW.estado, 'estado, ', ''),
                IF(OLD.observaciones != NEW.observaciones, 'observaciones, ', '')
            ),
            JSON_OBJECT(
                'concepto', OLD.concepto,
                'monto', OLD.monto,
                'fecha_pago', OLD.fecha_pago,
                'metodo_pago', OLD.metodo_pago,
                'estado', OLD.estado,
                'observaciones', OLD.observaciones
            ),
            JSON_OBJECT(
                'concepto', NEW.concepto,
                'monto', NEW.monto,
                'fecha_pago', NEW.fecha_pago,
                'metodo_pago', NEW.metodo_pago,
                'estado', NEW.estado,
                'observaciones', NEW.observaciones
            )
        );
    END IF;
END //

-- AFTER DELETE pagos
CREATE TRIGGER trg_pagos_after_delete
AFTER DELETE ON pagos
FOR EACH ROW
BEGIN
    INSERT INTO auditoria (
        id_usuario, usuario_mysql, accion, tabla_afectada,
        registro_id, fecha_hora, descripcion, valores_anteriores
    ) VALUES (
        @audit_user_id,
        @audit_usuario_nombre,
        'DELETE',
        'pagos',
        OLD.id_pago,
        NOW(),
        CONCAT('Pago eliminado: ', OLD.concepto, ' - Bs. ', OLD.monto),
        JSON_OBJECT(
            'id_pago', OLD.id_pago,
            'id_persona', OLD.id_persona,
            'concepto', OLD.concepto,
            'monto', OLD.monto,
            'fecha_pago', OLD.fecha_pago,
            'metodo_pago', OLD.metodo_pago,
            'estado', OLD.estado
        )
    );
END //


-- ================================================
-- TABLA: actividades
-- ================================================

-- AFTER INSERT actividades
CREATE TRIGGER trg_actividades_after_insert
AFTER INSERT ON actividades
FOR EACH ROW
BEGIN
    INSERT INTO auditoria (
        id_usuario, usuario_mysql, accion, tabla_afectada,
        registro_id, fecha_hora, descripcion, valores_nuevos
    ) VALUES (
        @audit_user_id,
        @audit_usuario_nombre,
        'INSERT',
        'actividades',
        NEW.id_actividad,
        NOW(),
        CONCAT('Actividad creada: ', NEW.nombre_actividad),
        JSON_OBJECT(
            'id_actividad', NEW.id_actividad,
            'nombre_actividad', NEW.nombre_actividad,
            'tipo_actividad', NEW.tipo_actividad,
            'fecha_inicio', NEW.fecha_inicio,
            'fecha_fin', NEW.fecha_fin,
            'costo', NEW.costo,
            'cupo_maximo', NEW.cupo_maximo,
            'id_evento', NEW.id_evento,
            'estado', NEW.estado
        )
    );
END //

-- AFTER UPDATE actividades
CREATE TRIGGER trg_actividades_after_update
AFTER UPDATE ON actividades
FOR EACH ROW
BEGIN
    IF OLD.nombre_actividad != NEW.nombre_actividad
       OR OLD.tipo_actividad != NEW.tipo_actividad
       OR OLD.fecha_inicio != NEW.fecha_inicio
       OR OLD.fecha_fin != NEW.fecha_fin
       OR OLD.costo != NEW.costo
       OR OLD.cupo_maximo != NEW.cupo_maximo
       OR OLD.cupo_disponible != NEW.cupo_disponible
       OR OLD.estado != NEW.estado
       OR OLD.id_evento != NEW.id_evento
    THEN
        INSERT INTO auditoria (
            id_usuario, usuario_mysql, accion, tabla_afectada,
            registro_id, fecha_hora, descripcion,
            valores_anteriores, valores_nuevos
        ) VALUES (
            @audit_user_id,
            @audit_usuario_nombre,
            'UPDATE',
            'actividades',
            OLD.id_actividad,
            NOW(),
            CONCAT('Campos modificados: ',
                IF(OLD.nombre_actividad != NEW.nombre_actividad, 'nombre_actividad, ', ''),
                IF(OLD.tipo_actividad != NEW.tipo_actividad, 'tipo_actividad, ', ''),
                IF(OLD.fecha_inicio != NEW.fecha_inicio, 'fecha_inicio, ', ''),
                IF(OLD.fecha_fin != NEW.fecha_fin, 'fecha_fin, ', ''),
                IF(OLD.costo != NEW.costo, 'costo, ', ''),
                IF(OLD.cupo_maximo != NEW.cupo_maximo, 'cupo_maximo, ', ''),
                IF(OLD.cupo_disponible != NEW.cupo_disponible, 'cupo_disponible, ', ''),
                IF(OLD.estado != NEW.estado, 'estado, ', ''),
                IF(OLD.id_evento != NEW.id_evento, 'id_evento, ', '')
            ),
            JSON_OBJECT(
                'nombre_actividad', OLD.nombre_actividad,
                'tipo_actividad', OLD.tipo_actividad,
                'fecha_inicio', OLD.fecha_inicio,
                'fecha_fin', OLD.fecha_fin,
                'costo', OLD.costo,
                'cupo_maximo', OLD.cupo_maximo,
                'cupo_disponible', OLD.cupo_disponible,
                'estado', OLD.estado,
                'id_evento', OLD.id_evento
            ),
            JSON_OBJECT(
                'nombre_actividad', NEW.nombre_actividad,
                'tipo_actividad', NEW.tipo_actividad,
                'fecha_inicio', NEW.fecha_inicio,
                'fecha_fin', NEW.fecha_fin,
                'costo', NEW.costo,
                'cupo_maximo', NEW.cupo_maximo,
                'cupo_disponible', NEW.cupo_disponible,
                'estado', NEW.estado,
                'id_evento', NEW.id_evento
            )
        );
    END IF;
END //

-- AFTER DELETE actividades
CREATE TRIGGER trg_actividades_after_delete
AFTER DELETE ON actividades
FOR EACH ROW
BEGIN
    INSERT INTO auditoria (
        id_usuario, usuario_mysql, accion, tabla_afectada,
        registro_id, fecha_hora, descripcion, valores_anteriores
    ) VALUES (
        @audit_user_id,
        @audit_usuario_nombre,
        'DELETE',
        'actividades',
        OLD.id_actividad,
        NOW(),
        CONCAT('Actividad eliminada: ', OLD.nombre_actividad),
        JSON_OBJECT(
            'id_actividad', OLD.id_actividad,
            'nombre_actividad', OLD.nombre_actividad,
            'tipo_actividad', OLD.tipo_actividad,
            'fecha_inicio', OLD.fecha_inicio,
            'fecha_fin', OLD.fecha_fin,
            'costo', OLD.costo,
            'id_evento', OLD.id_evento,
            'estado', OLD.estado
        )
    );
END //


-- ================================================
-- TABLA: inscripcion
-- ================================================

-- AFTER INSERT inscripcion
CREATE TRIGGER trg_inscripcion_after_insert
AFTER INSERT ON inscripcion
FOR EACH ROW
BEGIN
    INSERT INTO auditoria (
        id_usuario, usuario_mysql, accion, tabla_afectada,
        registro_id, fecha_hora, descripcion, valores_nuevos
    ) VALUES (
        @audit_user_id,
        @audit_usuario_nombre,
        'INSERT',
        'inscripcion',
        NEW.id_inscripcion,
        NOW(),
        CONCAT('Inscripción registrada: actividad=', NEW.id_actividad, ', persona=', NEW.id_persona),
        JSON_OBJECT(
            'id_inscripcion', NEW.id_inscripcion,
            'id_actividad', NEW.id_actividad,
            'id_persona', NEW.id_persona,
            'id_pago', NEW.id_pago,
            'cumple_requisitos', NEW.cumple_requisitos,
            'estado', NEW.estado
        )
    );
END //

-- AFTER UPDATE inscripcion
CREATE TRIGGER trg_inscripcion_after_update
AFTER UPDATE ON inscripcion
FOR EACH ROW
BEGIN
    IF OLD.id_actividad != NEW.id_actividad
       OR OLD.id_persona != NEW.id_persona
       OR OLD.id_pago != NEW.id_pago
       OR OLD.cumple_requisitos != NEW.cumple_requisitos
       OR OLD.estado != NEW.estado
       OR OLD.observaciones != NEW.observaciones
       OR OLD.asistencia != NEW.asistencia
       OR OLD.calificacion != NEW.calificacion
    THEN
        INSERT INTO auditoria (
            id_usuario, usuario_mysql, accion, tabla_afectada,
            registro_id, fecha_hora, descripcion,
            valores_anteriores, valores_nuevos
        ) VALUES (
            @audit_user_id,
            @audit_usuario_nombre,
            'UPDATE',
            'inscripcion',
            OLD.id_inscripcion,
            NOW(),
            CONCAT('Campos modificados: ',
                IF(OLD.id_actividad != NEW.id_actividad, 'id_actividad, ', ''),
                IF(OLD.id_persona != NEW.id_persona, 'id_persona, ', ''),
                IF(OLD.id_pago != NEW.id_pago, 'id_pago, ', ''),
                IF(OLD.cumple_requisitos != NEW.cumple_requisitos, 'cumple_requisitos, ', ''),
                IF(OLD.estado != NEW.estado, 'estado, ', ''),
                IF(OLD.observaciones != NEW.observaciones, 'observaciones, ', ''),
                IF(OLD.asistencia != NEW.asistencia, 'asistencia, ', ''),
                IF(OLD.calificacion != NEW.calificacion, 'calificacion, ', '')
            ),
            JSON_OBJECT(
                'id_actividad', OLD.id_actividad,
                'id_persona', OLD.id_persona,
                'id_pago', OLD.id_pago,
                'cumple_requisitos', OLD.cumple_requisitos,
                'estado', OLD.estado,
                'observaciones', OLD.observaciones,
                'asistencia', OLD.asistencia,
                'calificacion', OLD.calificacion
            ),
            JSON_OBJECT(
                'id_actividad', NEW.id_actividad,
                'id_persona', NEW.id_persona,
                'id_pago', NEW.id_pago,
                'cumple_requisitos', NEW.cumple_requisitos,
                'estado', NEW.estado,
                'observaciones', NEW.observaciones,
                'asistencia', NEW.asistencia,
                'calificacion', NEW.calificacion
            )
        );
    END IF;
END //

-- AFTER DELETE inscripcion
CREATE TRIGGER trg_inscripcion_after_delete
AFTER DELETE ON inscripcion
FOR EACH ROW
BEGIN
    INSERT INTO auditoria (
        id_usuario, usuario_mysql, accion, tabla_afectada,
        registro_id, fecha_hora, descripcion, valores_anteriores
    ) VALUES (
        @audit_user_id,
        @audit_usuario_nombre,
        'DELETE',
        'inscripcion',
        OLD.id_inscripcion,
        NOW(),
        CONCAT('Inscripción eliminada: actividad=', OLD.id_actividad, ', persona=', OLD.id_persona),
        JSON_OBJECT(
            'id_inscripcion', OLD.id_inscripcion,
            'id_actividad', OLD.id_actividad,
            'id_persona', OLD.id_persona,
            'id_pago', OLD.id_pago,
            'cumple_requisitos', OLD.cumple_requisitos,
            'estado', OLD.estado
        )
    );
END //


-- ================================================
-- TABLA: eventos
-- ================================================

-- AFTER INSERT eventos
CREATE TRIGGER trg_eventos_after_insert
AFTER INSERT ON eventos
FOR EACH ROW
BEGIN
    INSERT INTO auditoria (
        id_usuario, usuario_mysql, accion, tabla_afectada,
        registro_id, fecha_hora, descripcion, valores_nuevos
    ) VALUES (
        @audit_user_id,
        @audit_usuario_nombre,
        'INSERT',
        'eventos',
        NEW.id_evento,
        NOW(),
        CONCAT('Evento creado: ', NEW.nombre_evento),
        JSON_OBJECT(
            'id_evento', NEW.id_evento,
            'nombre_evento', NEW.nombre_evento,
            'descripcion', NEW.descripcion,
            'estado', NEW.estado,
            'fecha_evento', NEW.fecha_evento
        )
    );
END //

-- AFTER UPDATE eventos
CREATE TRIGGER trg_eventos_after_update
AFTER UPDATE ON eventos
FOR EACH ROW
BEGIN
    IF OLD.nombre_evento != NEW.nombre_evento
       OR OLD.descripcion != NEW.descripcion
       OR OLD.estado != NEW.estado
       OR OLD.fecha_evento != NEW.fecha_evento
    THEN
        INSERT INTO auditoria (
            id_usuario, usuario_mysql, accion, tabla_afectada,
            registro_id, fecha_hora, descripcion,
            valores_anteriores, valores_nuevos
        ) VALUES (
            @audit_user_id,
            @audit_usuario_nombre,
            'UPDATE',
            'eventos',
            OLD.id_evento,
            NOW(),
            CONCAT('Campos modificados: ',
                IF(OLD.nombre_evento != NEW.nombre_evento, 'nombre_evento, ', ''),
                IF(OLD.descripcion != NEW.descripcion, 'descripcion, ', ''),
                IF(OLD.estado != NEW.estado, 'estado, ', ''),
                IF(OLD.fecha_evento != NEW.fecha_evento, 'fecha_evento, ', '')
            ),
            JSON_OBJECT(
                'nombre_evento', OLD.nombre_evento,
                'descripcion', OLD.descripcion,
                'estado', OLD.estado,
                'fecha_evento', OLD.fecha_evento
            ),
            JSON_OBJECT(
                'nombre_evento', NEW.nombre_evento,
                'descripcion', NEW.descripcion,
                'estado', NEW.estado,
                'fecha_evento', NEW.fecha_evento
            )
        );
    END IF;
END //

-- AFTER DELETE eventos
CREATE TRIGGER trg_eventos_after_delete
AFTER DELETE ON eventos
FOR EACH ROW
BEGIN
    INSERT INTO auditoria (
        id_usuario, usuario_mysql, accion, tabla_afectada,
        registro_id, fecha_hora, descripcion, valores_anteriores
    ) VALUES (
        @audit_user_id,
        @audit_usuario_nombre,
        'DELETE',
        'eventos',
        OLD.id_evento,
        NOW(),
        CONCAT('Evento eliminado: ', OLD.nombre_evento),
        JSON_OBJECT(
            'id_evento', OLD.id_evento,
            'nombre_evento', OLD.nombre_evento,
            'descripcion', OLD.descripcion,
            'estado', OLD.estado,
            'fecha_evento', OLD.fecha_evento
        )
    );
END //


-- Restaurar delimitador original
DELIMITER ;
