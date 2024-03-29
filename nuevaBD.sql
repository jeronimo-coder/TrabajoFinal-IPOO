CREATE DATABASE bdviajes; 

CREATE TABLE empresa(
    idempresa bigint,
    enombre varchar(150),
    edireccion varchar(150),
    PRIMARY KEY (idempresa)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE responsable (
    rnumeroempleado bigint,
    rnumerolicencia bigint,
	rnombre varchar(150), 
    rapellido  varchar(150), 
    PRIMARY KEY (rnumeroempleado)
    )ENGINE=InnoDB DEFAULT CHARSET=utf8;
	
CREATE TABLE viaje (
    idviaje bigint,
	vdestino varchar(150),
    vcantmaxpasajeros int,
	idempresa bigint,
    rnumeroempleado bigint,
    vimporte float,
    tipoAsiento varchar(150), /*primera clase o no, semicama o cama*/
    idayvuelta varchar(150), /*si no*/
    PRIMARY KEY (idviaje),
    FOREIGN KEY (idempresa) REFERENCES empresa (idempresa),
	FOREIGN KEY (rnumeroempleado) REFERENCES responsable (rnumeroempleado)
    ON UPDATE CASCADE
    ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	
CREATE TABLE pasajero (
    rdocumento varchar(15),
    pnombre varchar(150), 
    papellido varchar(150), 
	ptelefono int, 
	idviaje bigint,
    PRIMARY KEY (rdocumento),
	FOREIGN KEY (idviaje) REFERENCES viaje (idviaje)	
    )ENGINE=InnoDB DEFAULT CHARSET=utf8; 
 