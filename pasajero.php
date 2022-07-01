<?php

class Pasajero
{
    private $rDocumento;
    private $pNombre;
    private $pApellido;
    private $pTelefono;
    private $objViaje; //delegacion de viaje
    private $mensajeoperacion;

    public function __construct()
    {
        $this->rDocumento = "";
        $this->pNombre = "";
        $this->pApellido = "";
        $this->pTelefono = "";
        $this->objViaje = "";
    }

    public function cargar($rdocumento, $pnombre, $papellido, $ptelefono, $objviaje)
    {
        $this->setRDocumento($rdocumento);
        $this->setPNombre($pnombre);
        $this->setPApellido($papellido);
        $this->setPTelefono($ptelefono);
        $this->setObjViaje($objviaje);
    }

    public function getRDocumento()
    {
        return $this->rDocumento;
    }

    public function setRDocumento($rDocumento)
    {
        $this->rDocumento = $rDocumento;
    }

    public function getPNombre()
    {
        return $this->pNombre;
    }

    public function setPNombre($pNombre)
    {
        $this->pNombre = $pNombre;
    }

    public function getPApellido()
    {
        return $this->pApellido;
    }

    public function setPApellido($pApellido)
    {
        $this->pApellido = $pApellido;
    }

    public function getPTelefono()
    {
        return $this->pTelefono;
    }

    public function setPTelefono($pTelefono)
    {
        $this->pTelefono = $pTelefono;
    }

    public function getObjViaje()
    {
        return $this->objViaje;
    }

    public function setObjViaje($objViaje)
    {
        $this->objViaje = $objViaje;
    }
    public function getMensajeoperacion()
    {
        return $this->mensajeoperacion;
    }

    public function setMensajeoperacion($mensajeoperacion)
    {
        $this->mensajeoperacion = $mensajeoperacion;
    }

    /**
     * Recupera los datos de una persona por $rNumEmpleado
     * @param int $rNumEmpleado
     * @return true en caso de encontrar los datos, false en caso contrario 
     */

    public function Buscar($rDocumento)
    {
        $base = new BaseDatos();
        $consulta = "SELECT * FROM pasajero WHERE rDocumento =" . $rDocumento;
        $respuesta = null;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($pasajero = $base->Registro()) {
                    $this->setRDocumento($rDocumento);
                    $this->setPNombre($pasajero['pnombre']);
                    $this->setPApellido($pasajero['papellido']);
                    $this->setPTelefono($pasajero['ptelefono']);
                    $objViaje = new Viaje();
                    $objViaje->Buscar($pasajero['idviaje']);
                    $this->setObjViaje($objViaje);
                    $respuesta = true;
                }
            } else {
                $respuesta = false;
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $respuesta = false;
            $this->setmensajeoperacion($base->getError());
        }
        return $respuesta;
    }

    public function listar($condicion)
    {
        $arregloPasajero = null;
        $base = new BaseDatos();
        $consultaPasajero = "SELECT * FROM pasajero ";
        if ($condicion != "") {
            $consultaPasajero .= ' WHERE ' . $condicion;
        }
        $consultaPasajero .= " ORDER BY rdocumento ";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaPasajero)) {
                $arregloPasajero = array();
                while ($pasajero = $base->Registro()) {
                    $objPasajero = new Pasajero();
                    $objPasajero->buscar($pasajero['rdocumento']);
                    array_push($arregloPasajero, $objPasajero);
                }
            } else {
                $arregloPasajero = false;
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $arregloPasajero = false;
            $this->setmensajeoperacion($base->getError());
        }
        return $arregloPasajero;
    }


    public function ingresar()
    {
        $baseDeDatos = new BaseDatos();
        $respuesta = null;
        $consulta = "INSERT INTO pasajero (rdocumento, pnombre, papellido, ptelefono, idviaje) VALUES (" . $this->getRDocumento() . ",'" . $this->getPNombre() . "','" . $this->getPApellido() . "','" . $this->getPTelefono() . "','" . $this->getObjViaje()->getIdViaje() . "')";
        if ($baseDeDatos->iniciar()) {
            if ($baseDeDatos->ejecutar($consulta)) {
                $respuesta = true;
            } else {
                $respuesta = false;
                $this->setMensajeOperacion($baseDeDatos->getError());
            }
        } else {
            $respuesta = false;
            $this->setMensajeOperacion($baseDeDatos->getError());
        }
        return $respuesta;
    }

    public function modificar()
    {
        $baseDeDatos = new BaseDatos();
        $respuesta = null;
        $consultaModificacion = "UPDATE pasajero SET rdocumento = ".$this->getRDocumento().",
        pnombre = '" . $this->getPNombre() . "', papellido = '" . $this->getPApellido() . "', ptelefono = '" . $this->getPTelefono(). "', idviaje = '" . $this->getObjViaje()->getIdViaje() . "' WHERE rdocumento = " . $this->getRDocumento();
        if ($baseDeDatos->iniciar()) {
            if ($baseDeDatos->ejecutar($consultaModificacion)) {
                $respuesta = true;
            } else {
                $respuesta = false;
                $this->setmensajeoperacion($baseDeDatos->getError());
            }
        } else {
            $respuesta = false;
            $this->setmensajeoperacion($baseDeDatos->getError());
        }
        return $respuesta;
    }

    public function eliminar()
    {
        $baseDeDatos = new BaseDatos();
        $respuesta = null;
        if ($baseDeDatos->iniciar()) {
            $consultaBorrar = "DELETE FROM pasajero WHERE rdocumento = " . $this->getRDocumento();
            if ($baseDeDatos->ejecutar($consultaBorrar)) {
                $respuesta = true;
            } else {
                $respuesta = false;
                $this->setMensajeOperacion($baseDeDatos->getError());
            }
        } else {
            $respuesta = false;
            $this->setMensajeOperacion($baseDeDatos->getError());
        }
        return $respuesta;
    }

    public function __toString()
    {
        $info = "El nombre del pasajero es: {$this->getPNombre()}\n".
        "El apellido del pasajero es: {$this->getPApellido()}\n".
        "El documento del pasajero es: {$this->getRDocumento()}\n".
        "El codigo del viaje es: {$this->getObjViaje()->getIdViaje()}\n".
        "El telefono del pasajero es: {$this->getPTelefono()}";
        return $info;
    }
}
