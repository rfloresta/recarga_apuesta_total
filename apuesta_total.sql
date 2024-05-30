-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS apuesta_total;

-- Utilizar la base de datos recién creada
USE apuesta_total;

CREATE TABLE clientes(
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_id VARCHAR(50) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    saldo DECIMAL(10, 2) DEFAULT 0,
    INDEX(id)
);

-- Tabla de roles
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    estado ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo'
    INDEX(id)
);

-- Tabla de usuarios (usuarios)
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(100) NOT NULL,
    contrasena VARCHAR(100) NOT NULL,
    rol_id INT,
    FOREIGN KEY (rol_id) REFERENCES roles(id)
);

-- Tabla de canal de comunicación
CREATE TABLE canales_comunicacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
    INDEX(id)
);

-- Tabla de bancos
CREATE TABLE bancos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    estado ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo'
    INDEX(id)
);


CREATE TABLE recargas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    monto DECIMAL(10, 2) NOT NULL,
    fecha_hora DATETIME NOT NULL,
    foto_voucher BLOB,
    client_id INT,
    banco_id INT,
    canal_id INT,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    FOREIGN KEY (banco_id) REFERENCES bancos(id),
    FOREIGN KEY (canal_id) REFERENCES canales_comunicacion (id),
    INDEX(cliente_id),
    INDEX(banco_id),
    INDEX(canal_id)
);


DELIMITER //

CREATE PROCEDURE RealizarRecarga(
    IN p_ClienteID INT,
    IN p_Monto DECIMAL(10, 2),
    IN p_banco_id INT,
    IN p_canal_id INT,
    IN p_foto_voucher BLOB
)
BEGIN
    -- Verificar que el nuevo monto no sea menor que cero
    IF p_monto <= 0 THEN
        SELECT 'El monto de la recarga debe ser mayor a cero.' AS mensaje;
        LEAVE;
    END IF;

    INSERT INTO recargas (monto, fecha_hora, foto_voucher, client_id, banco_id, canal_id)
    VALUES (p_monto, NOW(), p_foto_voucher,  p_cliente_id, p_banco_id, p_canal_id);
    
    -- Actualizar el saldo del cliente
    UPDATE clientes SET saldo = saldo + p_monto WHERE id =  p_cliente_id;
END //

DELIMITER ;

DELIMITER //

CREATE PROCEDURE ActualizarRecarga(
    IN p_RecargaID INT,
    IN p_NuevoMonto DECIMAL(10, 2),
    IN p_NuevoBancoID INT,
    IN p_NuevoCanalID INT,
    IN p_NuevaFotoVoucher BLOB
)
BEGIN
    DECLARE v_SaldoActual DECIMAL(10, 2);
    DECLARE v_Diferencia DECIMAL(10, 2);
    DECLARE v_RecargaExiste INT;
    DECLARE v_ClienteID INT;

    -- Verificar que el nuevo monto sea mayor que cero
    IF p_NuevoMonto <= 0 THEN
        SELECT 'El nuevo monto de la recarga debe ser mayor a cero.' AS mensaje;
        LEAVE;
    END IF;

    -- Verificar si la recarga existe
    SELECT COUNT(*) INTO v_RecargaExiste FROM recargas WHERE id = p_RecargaID;
    
    IF v_RecargaExiste = 0 THEN
        SELECT 'La recarga especificada no existe.' AS mensaje;
        LEAVE;
    END IF;

    -- Obtener el cliente_id de la recarga
    SELECT cliente_id into v_ClienteID FROM recargas WHERE id = p_RecargaID

    -- Obtener el saldo actual del cliente
    SELECT saldo INTO v_SaldoActual FROM clientes WHERE id = v_ClienteID;

    -- Calcular la diferencia entre el nuevo monto y el monto original
    SELECT p_NuevoMonto - monto INTO v_Diferencia FROM recargas WHERE id = p_RecargaID;
    
    -- Actualizar los detalles de la recarga
    UPDATE recargas 
    SET banco_id = p_NuevoBancoID,
        canal_id = p_NuevoCanalID,
        foto_voucher = p_NuevaFotoVoucher
    WHERE id = p_RecargaID;

    -- Validar que el saldo actual del cliente sea suficiente para cubrir la diferencia
    IF v_SaldoActual >= ABS(v_Diferencia) THEN        
        -- Actualizar el monto de la recarga
        UPDATE recargas 
        SET monto = p_NuevoMonto,
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
    
    SELECT 'Recarga actualizada exitosamente.' AS mensaje;

END //

DELIMITER ;


DELIMITER //

CREATE PROCEDURE ConsultarRecargasPorPlayerID(
    IN p_PlayerID VARCHAR(50)
)
BEGIN
    -- Variable para almacenar el ID del cliente
    DECLARE v_ClienteID INT;

    -- Obtener el ID del cliente por el PlayerID
    SELECT id INTO v_ClienteID FROM clientes WHERE player_id = p_PlayerID;

    -- Verificar si se encontró el cliente
    IF v_ClienteID IS NOT NULL THEN
        -- Consultar las recargas del cliente
        SELECT r.id AS RecargaID, r.monto AS Monto, r.fecha_hora AS FechaHora, b.nombre AS Banco, c.nombre AS Canal
        FROM recargas r
        INNER JOIN bancos b ON r.banco_id = b.id
        INNER JOIN canales_comunicacion c ON r.canal_id = c.id
        WHERE r.cliente_id = v_ClienteID;
    ELSE
        -- Si no se encontró el cliente, emitir un mensaje de error
        SELECT 'No se encontró un cliente con el PlayerID proporcionado.' AS mensaje;
    END IF;
END //

DELIMITER ;
