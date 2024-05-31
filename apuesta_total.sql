DROP DATABASE apuesta_total;

-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS apuesta_total;

-- Utilizar la base de datos recién creada
USE apuesta_total;

-- ------------------------------------------------------
-- Creacion de tablas
-- -------------------------------------------------------
	CREATE TABLE clientes(
	    id BIGINT AUTO_INCREMENT PRIMARY KEY,
	    player_id VARCHAR(50) NOT NULL,
	    nombres VARCHAR(100) NOT NULL,
	    apellidos VARCHAR(100) NOT NULL,
	    saldo DECIMAL(10, 2) DEFAULT 0,
	    INDEX idx_player_id (player_id),
	    INDEX idx_nombres_d (nombres),
	    INDEX idx_apellidos_id (apellidos)
	);
	
	-- Tabla de roles
	CREATE TABLE roles (
	    id SMALLINT AUTO_INCREMENT PRIMARY KEY,
	    nombre VARCHAR(100) NOT NULL,
	    estado ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo'
	);
	
	-- Tabla de usuarios (usuarios)
	CREATE TABLE usuarios (
	    id BIGINT AUTO_INCREMENT PRIMARY KEY,
	    nombre_usuario VARCHAR(100) NOT NULL,
	    clave VARCHAR(100) NOT NULL,
	    rol_id SMALLINT,
	    FOREIGN KEY (rol_id) REFERENCES roles(id)
	);
	
	-- Tabla de canal de comunicación
	CREATE TABLE canales_comunicacion (
	    id SMALLINT AUTO_INCREMENT PRIMARY KEY,
	    nombre VARCHAR(50) NOT NULL,
	    estado ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo'
	);
	
	-- Tabla de bancos
	CREATE TABLE bancos (
	    id SMALLINT AUTO_INCREMENT PRIMARY KEY,
	    codigo VARCHAR(20) NOT NULL,
	    descripcion VARCHAR(100) NOT NULL,
	    estado ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo'
	);
	
	
	CREATE TABLE recargas (
	    id BIGINT AUTO_INCREMENT PRIMARY KEY,
	    monto DECIMAL(10, 2) NOT NULL,
	    fecha_hora DATETIME NOT NULL,
	    foto_voucher VARCHAR(150),
	    cliente_id BIGINT,
	    usuario_id BIGINT,
	    banco_id SMALLINT,
	    canal_id SMALLINT,
	    FOREIGN KEY (cliente_id) REFERENCES clientes(id),
	    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
	    FOREIGN KEY (banco_id) REFERENCES bancos(id),
	    FOREIGN KEY (canal_id) REFERENCES canales_comunicacion(id),
	    INDEX idx_cliente_id(cliente_id),
	    INDEX idx_usuario_id(usuario_id)
	);
	
	CREATE TABLE auditoria_logs (
	    id INT AUTO_INCREMENT PRIMARY KEY,
	    tabla_afectada VARCHAR(100) NOT NULL,
	    operacion VARCHAR(10) NOT NULL,
	    fecha_hora DATETIME NOT NULL,
	    usuario_id BIGINT, -- El usuario que realizó la operación
	    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
	);

-- -----------------------------------------------------------------------
-- Inserts de tablas
-- -----------------------------------------------------------------------
   INSERT INTO roles (nombre)
   VALUES 
		('Admin'),
   	('Asesor'),
   	('Player');
   
	-- Insert tabla clientes
    INSERT INTO clientes (player_id, nombres, apellidos)
    VALUES ('123456', 'Juan', 'Perez');
   
	-- Insert tabla usuarios
    INSERT INTO usuarios (nombre_usuario, clave, rol_id)
    VALUES ('gabriela12', '$2y$10$iWuFjY6RKKENQw8RHlpQZeIj6lD94mtw/AGI3iFqlZ8LNXqs85aAC', 2);
    
	-- Insert tabla canales_comunicacion
    INSERT INTO canales_comunicacion(nombre)
    VALUES 
	 	('Facebook Messenger'),
		('WhatsApp'),
	 	('Telegram');
    
	-- Insert tabla bancos
    INSERT INTO bancos (codigo, descripcion)
    VALUES 
	 	('BCP', 'Banco de Crédito'),
     	('Interbank',  'Banco Interbank'),
     	('BBVA', 'Banco Continental');
     	
-- -----------------------------------------------------------------------
-- Creacion de Procedimientos Almacenados
-- -----------------------------------------------------------------------
DELIMITER  //

CREATE PROCEDURE realizar_recarga(
    IN p_UsuarioID BIGINT,
    IN p_PlayerID BIGINT,
    IN p_Monto DECIMAL(10, 2),
    IN p_BancoID SMALLINT,
    IN p_CanalID SMALLINT,
    IN p_FotoVoucher VARCHAR(150)
)
BEGIN
	-- Variable para almacenar el ID del cliente
	DECLARE v_ClienteID BIGINT;
	   
    -- Verificar que el nuevo monto no sea menor que cero
   IF p_Monto <= 0 THEN
        SELECT 'El monto de la recarga debe ser mayor a cero.' AS msg_info, -1 AS msg_code;
   ELSE
	
	   -- Obtener el ID del cliente por el PlayerID
	   SELECT id INTO v_ClienteID FROM clientes WHERE player_id = p_PlayerID;

	   -- Realizar la recarga
	   INSERT INTO recargas (monto, fecha_hora, foto_voucher, cliente_id, usuario_id, banco_id, canal_id)
	   VALUES (p_Monto, NOW(), p_FotoVoucher,  v_ClienteID, p_UsuarioID, p_BancoID, p_CanalID);
	    
	   -- Registrar la operación en el log de auditoría
	   INSERT INTO auditoria_logs (tabla_afectada, operacion, fecha_hora, usuario_id)
	   VALUES ('recargas', 'INSERT', NOW(), p_UsuarioID);
	    
	   -- Actualizar el saldo del cliente
	   UPDATE clientes SET saldo = saldo + p_Monto WHERE id =  v_ClienteID;
	   
    	SELECT 'Recarga realizada exitosamente.' AS msg_info, 1 AS msg_code;
    	
   END IF;
END //

DELIMITER  ;

DELIMITER //

CREATE PROCEDURE actualizar_recarga(
	IN p_RecargaID BIGINT,
   IN p_NuevoUsuarioID BIGINT,
   IN p_NuevoMonto DECIMAL(10, 2),
   IN p_NuevoBancoID SMALLINT,
   IN p_NuevoCanalID SMALLINT
)
BEGIN
	DECLARE v_SaldoActual DECIMAL(10, 2);
	DECLARE v_Diferencia DECIMAL(10, 2);
   DECLARE v_RecargaExiste INT;
   DECLARE v_ClienteID BIGINT;

   -- Verificar que el nuevo monto sea mayor que cero
   IF p_NuevoMonto <= 0 THEN
      SELECT 'El monto de la recarga debe ser mayor a cero.' AS msg_info, -1 AS msg_code;
   ELSE
	   -- Verificar si la recarga existe
	   SELECT COUNT(*) INTO v_RecargaExiste FROM recargas WHERE id = p_RecargaID;
	    
	   IF v_RecargaExiste = 0 THEN
	      SELECT 'La recarga especificada no existe.' AS msg_info, -1 AS msg_code;
	   ELSE
		   -- Obtener el cliente_id de la recarga
		   SELECT cliente_id into v_ClienteID FROM recargas WHERE id = p_RecargaID;		
			
		   -- Obtener el saldo actual del cliente
		   SELECT saldo INTO v_SaldoActual FROM clientes WHERE id = v_ClienteID;
		
		   -- Calcular la diferencia entre el nuevo monto y el monto original
		   SELECT p_NuevoMonto - monto INTO v_Diferencia FROM recargas WHERE id = p_RecargaID;
		    
		   -- Actualizar los detalles de la recarga
		   UPDATE recargas 
		   SET 
				fecha_hora = NOW(),
				usuario_id = p_NuevoUsuarioID,
				banco_id = p_NuevoBancoID,
		      canal_id = p_NuevoCanalID
		   WHERE id = p_RecargaID;
		    
		   -- Validar que el saldo actual del cliente sea suficiente para cubrir la diferencia
		   IF v_SaldoActual >= ABS(v_Diferencia) THEN        
		      -- Actualizar el monto de la recarga
		      UPDATE recargas 
		      SET monto = p_NuevoMonto
		      WHERE id = p_RecargaID;
		        
		      -- Actualizar el saldo del cliente teniendo en cuenta la diferencia
		      IF v_Diferencia > 0 THEN
		         -- Si el nuevo monto es mayor que el monto original, sumar la diferencia al saldo actual
		         UPDATE clientes 
		         SET saldo = v_SaldoActual + v_Diferencia
		            WHERE id = v_ClienteID;
		      ELSE
		         -- Si el nuevo monto es menor o igual que el monto original, restar la diferencia al saldo actual
		         UPDATE clientes 
		         SET saldo = v_SaldoActual - ABS(v_Diferencia)
		         WHERE id = v_ClienteID;
		      END IF;    
		   END IF;    
		    
		  	INSERT INTO auditoria_logs (tabla_afectada, operacion, fecha_hora, usuario_id)
		   VALUES ('recargas', 'UPDATE', NOW(), p_NuevoUsuarioID);
		   
		   SELECT 'Recarga actualizada exitosamente.' AS msg_info, 1 AS msg_code;
		END IF;	   
	END IF;
END //

DELIMITER ;

DELIMITER //
CREATE PROCEDURE consultar_recargas_por_player_id(
    IN p_PlayerID VARCHAR(50)
)
BEGIN
    -- Variable para almacenar el ID del cliente
    DECLARE v_ClienteID BIGINT;

    -- Obtener el ID del cliente por el PlayerID
    SELECT id INTO v_ClienteID FROM clientes WHERE player_id = p_PlayerID;

    -- Verificar si se encontró el cliente
    IF v_ClienteID IS NOT NULL THEN
        -- Consultar las recargas del cliente
        SELECT r.id AS recarga_id, r.monto AS monto, r.fecha_hora AS fecha_hora, b.descripcion AS banco, c.nombre AS canal
        FROM recargas r
        INNER JOIN bancos b ON r.banco_id = b.id
        INNER JOIN canales_comunicacion c ON r.canal_id = c.id
        WHERE r.cliente_id = v_ClienteID;
    ELSE
        -- Si no se encontró el cliente, emitir un mensaje de error
        SELECT 'No se encontró un cliente con el player id proporcionado.' AS msg_info, -1 AS msg_code;
    END IF;
END //

DELIMITER ;

DELIMITER //

CREATE PROCEDURE listar_bancos()
BEGIN
    SELECT id, codigo, descripcion
    FROM bancos 
	 WHERE estado = "activo";
END //

DELIMITER ;

DELIMITER //

CREATE PROCEDURE listar_canales_comunicacion()
BEGIN
    SELECT id, nombre, estado
    FROM canales_comunicacion
	 WHERE estado = "activo";
END //

DELIMITER ;
