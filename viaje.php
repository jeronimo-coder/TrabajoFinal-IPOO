<?php

class Viaje
{
    private $idViaje;
    private $vDestino;
    private $vCantMaxPasajeros;
    private $colPasajeros;
    private $objEmpresa; // delegacion de empresa
    private $objEmpleado; // delegacion de responsable
    private $vImporte;
    private $tipoAsiento; // primera clase o no, semicama o cama
    private $idaYvuelta; // si no
    private $mensajeOperacion;

    public function __construct()
    {
        $this->idViaje = "";
        $this->vDestino = "";
        $this->vCantMaxPasajeros = "";
        $this->colPasajeros = [];
        $this->empresa = "";
        $this->rNumEmpleado = "";
        $this->vImporte = "";
        $this->tipoAsiento = "";
        $this->idaYvuelta = "";
    }

    public function cargar($idviaje, $vdestino, $vcantmaxpasajeros, $objEmpresa, $objResponsable, $vimporte, $tipoasiento, $idayvuelta)
    {
        $this->setIdViaje($idviaje);
        $this->setVDestino($vdestino);
        $this->setVCantMaxPasajeros($vcantmaxpasajeros);
        $this->setObjEmpresa($objEmpresa);
        $this->setObjEmpleado($objResponsable);
        $this->setVImporte($vimporte);
        $this->setTipoAsiento($tipoasiento);
        $this->setIdaYvuelta($idayvuelta);
    }

    public function getIdViaje()
    {
        return $this->idViaje;
    }

    public function setIdViaje($idViaje)
    {
        $this->idViaje = $idViaje;
    }

    public function getVDestino()
    {
        return $this->vDestino;
    }

    public function setVDestino($vDestino)
    {
        $this->vDestino = $vDestino;
    }

    public function getVCantMaxPasajeros()
    {
        return $this->vCantMaxPasajeros;
    }

    public function setVCantMaxPasajeros($vCantMaxPasajeros)
    {
        $this->vCantMaxPasajeros = $vCantMaxPasajeros;
    }

    public function getColPasajeros()
    {
        return $this->colPasajeros;
    }

    public function setColPasajeros($colPasajeros)
    {
        $this->colPasajeros = $colPasajeros;
    }

    public function getObjEmpresa()
    {
        return $this->objEmpresa;
    }

    public function setObjEmpresa($empresa)
    {
        $this->objEmpresa = $empresa;
    }

    public function getObjEmpleado()
    {
        return $this->objEmpleado;
    }

    public function setObjEmpleado($objEmpleado)
    {
        $this->objEmpleado = $objEmpleado;
    }

    public function getVImporte()
    {
        return $this->vImporte;
    }

    public function setVImporte($vImporte)
    {
        $this->vImporte = $vImporte;
    }

    public function getTipoAsiento()
    {
        return $this->tipoAsiento;
    }

    public function setTipoAsiento($tipoAsiento)
    {
        $this->tipoAsiento = $tipoAsiento;
    }

    public function getIdaYvuelta()
    {
        return $this->idaYvuelta;
    }

    public function setIdaYvuelta($idaYvuelta)
    {
        $this->idaYvuelta = $idaYvuelta;
    }

    public function getMensajeOperacion()
    {
        return $this->mensajeOperacion;
    }

    public function setMensajeOperacion($mensajeOperacion)
    {
        $this->mensajeOperacion = $mensajeOperacion;
    }

    /**
     * Recupera los datos de una persona por $rNumEmpleado
     * @param int $rNumEmpleado
     * @return true en caso de encontrar los datos, false en caso contrario 
     */

    public function Buscar($idViaje)
    {
        $baseDeDatos = new BaseDatos();
        $consulta = "SELECT * FROM viaje WHERE idviaje =" . $idViaje;
        $respuesta = null;
        if ($baseDeDatos->Iniciar()) {
            if ($baseDeDatos->Ejecutar($consulta)) {
                if ($viaje = $baseDeDatos->Registro()) {
                    $objResponsable = new Responsable();
                    $objEmpresa = new Empresa();
                    $objResponsable->buscar($viaje['rnumeroempleado']);
                    $objEmpresa->buscar($viaje['idempresa']);
                    $this->setIdViaje($idViaje);
                    $this->setVDestino($viaje['vdestino']);
                    $this->setVCantMaxPasajeros($viaje['vcantmaxpasajeros']);
                    $this->setObjEmpresa($objEmpresa);
                    $this->setObjEmpleado($objResponsable);
                    $this->setVImporte($viaje['vimporte']);
                    $this->setTipoAsiento($viaje['tipoAsiento']);
                    $this->setIdaYvuelta($viaje['idayvuelta']);
                    $respuesta = true;
                }
            } else {
                $this->setMensajeOperacion($baseDeDatos->getError());
                $respuesta = false;
            }
        } else {
            $this->setMensajeOperacion($baseDeDatos->getError());
            $respuesta = false;
        }
        return $respuesta;
    }

    public function listar($condicion)
    {
        $arregloViaje = [];
        $baseDeDatos = new BaseDatos();
        $consultaViaje = "SELECT * FROM viaje ";
        if ($condicion != "") {
            $consultaViaje = $consultaViaje . ' where ' . $condicion;
        }
        $consultaViaje .= " order by rnumeroempleado ";
        if ($baseDeDatos->Iniciar()) {
            if ($baseDeDatos->Ejecutar($consultaViaje)) {
                while ($viaje = $baseDeDatos->Registro()) {
                    $objViaje = new Viaje();
                    $objViaje->Buscar($viaje['idviaje']);
                    array_push($arregloViaje, $objViaje);
                }
            } else {
                $this->setMensajeOperacion($baseDeDatos->getError());
            }
        } else {
            $this->setMensajeOperacion($baseDeDatos->getError());
        }
        return $arregloViaje;
    }

    public function ingresar()
    {
        $baseDeDatos = new BaseDatos();
        $respuesta = null;
        $consulta = "INSERT INTO viaje (idviaje, vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado, vimporte, tipoAsiento, idayvuelta) 
        VALUES ('". $this->getIdViaje() ."','".$this->getVDestino()."',".$this->getVCantMaxPasajeros().",".$this->getObjEmpresa()->getIdEmpresa().",".$this->getObjEmpleado()->getRNumEmpleado().",".$this->getVImporte().",'".$this->getTipoAsiento()."','".$this->getIdaYvuelta()."')";
        if ($baseDeDatos->iniciar()) {
            if ($baseDeDatos->ejecutar($consulta)) {
                $respuesta = true;
            } else {
                $this->setMensajeOperacion($baseDeDatos->getError());
                $respuesta = false;
            }
        } else {
            $this->setmensajeoperacion($baseDeDatos->getError());
            $respuesta = false;
        }
        return $respuesta;
    }

    public function modificar()
    {
        $baseDeDatos = new BaseDatos();
        $respuesta = null;
        $consultaModificacion = "UPDATE viaje SET vdestino = '" . $this->getVDestino() . "', vcantmaxpasajeros = '" . $this->getVCantMaxPasajeros() . "', idempresa = '" . $this->getObjEmpresa()->getIdEmpresa() . "', rnumeroempleado = '" . $this->getObjEmpleado()->getRNumEmpleado() . "', vimporte = '" . $this->getVImporte() . "', tipoAsiento = '" . $this->getTipoAsiento() . "', idayvuelta = '" . $this->getIdaYvuelta() . "' WHERE idviaje = " . $this->getIdViaje();
        if ($baseDeDatos->iniciar()) {
            if ($baseDeDatos->ejecutar($consultaModificacion)) {
                $respuesta = true;
            } else {
                $this->setmensajeoperacion($baseDeDatos->getError());
                $respuesta = false;
            }
        } else {
            $this->setmensajeoperacion($baseDeDatos->getError());
            $respuesta = false;
        }
        return $respuesta;
    }

    public function eliminar()
    {
        $baseDeDatos = new BaseDatos();
        $respuesta = null;
        if ($baseDeDatos->iniciar()) {
            $consultaBorrar = "DELETE FROM viaje WHERE idviaje = " . $this->getIdViaje();
            if ($baseDeDatos->ejecutar($consultaBorrar)) {
                $respuesta = true;
            } else {
                $this->setMensajeOperacion($baseDeDatos->getError());
                $respuesta = false;
            }
        } else {
            $this->setMensajeOperacion($baseDeDatos->getError());
            $respuesta = false;
        }
        return $respuesta;
    }

    /** Obtenemos a los pasajeros de un viaje en especifico  */

    public function obtenerPasajeros()
    {
        $baseDeDatos = new BaseDatos();
        $respuesta = null;
        $consulta = "idViaje = " . $this->getIdViaje();
        if ($baseDeDatos->iniciar()) {
            $objPasajero = new Pasajero();
            $arrayObjPersona = $objPasajero->listar($consulta);
            if (is_array($arrayObjPersona)) {
                $this->setColPasajeros($arrayObjPersona);
                $respuesta = true;
            } else {
                $this->setMensajeOperacion($baseDeDatos->getError());
                $respuesta = false;
            }
        } else {
            $this->setMensajeOperacion($baseDeDatos->getError());
            $respuesta = false;
        }
        return $respuesta;
    }

    /** Chequeamos si hay pasajes disponibles  */

    public function PasajesDisponible()
    {
        $this->obtenerPasajeros();
        $arrayObjPasajero = $this->getColPasajeros();
        if (count($arrayObjPasajero) < $this->getVCantMaxPasajeros()) {
            $verificacion = true;
        } else {
            $verificacion = false;
        }
        return $verificacion;
    }

    public function __toString()
    {
        $info = "El codigo del viaje es: {$this->getIdViaje()}\n" .
            "El destino del viaje es:  {$this->getVDestino()}\n".
            "La cantidad maxima de pasajeros es:  {$this->getVCantMaxPasajeros()}\n" .
            "El importe del viaje es:  {$this->getVImporte()}\n" .
            "El tipo de asiento del viaje es: {$this->getTipoAsiento()}\n" .
            "El viaje es de ida y vuelta: {$this->getIdaYvuelta()}\n" .
            "El ID de la empresa es: " . " {$this->getObjEmpresa()->getIdEmpresa()}\n" .
            "Los datos del responsable del viaje son: " . " {$this->getObjEmpleado()->getRNumEmpleado()}";
        return $info;
    }
}
