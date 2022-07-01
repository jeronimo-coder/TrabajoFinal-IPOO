<?php

class Empresa
{
    private $idEmpresa;
    private $eNombre;
    private $eDireccion;
    private $mensajeOperacion;

    public function __construct()
    {
        $this->idEmpresa = "";
        $this->eNombre = "";
        $this->eDireccion = "";
    }

    public function cargar($idEmpresa, $eNombre, $eDireccion)
    {
        $this->setIdEmpresa($idEmpresa);
        $this->setENombre($eNombre);
        $this->setEDireccion($eDireccion);
    }


    public function getIdEmpresa()
    {
        return $this->idEmpresa;
    }

    public function setIdEmpresa($idEmpresa)
    {
        $this->idEmpresa = $idEmpresa;
    }

    public function getENombre()
    {
        return $this->eNombre;
    }

    public function setENombre($eNombre)
    {
        $this->eNombre = $eNombre;
    }

    public function getEDireccion()
    {
        return $this->eDireccion;
    }

    public function setEDireccion($eDireccion)
    {
        $this->eDireccion = $eDireccion;
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
     * Recupera los datos de una persona por idempresa
     * @param int $idempresa
     * @return true en caso de encontrar los datos, false en caso contrario 
     */

    public function Buscar($idempresa)
    {
        $base = new BaseDatos();
        $consulta = "SELECT * FROM empresa WHERE idempresa=" . $idempresa;
        $respuesta = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($empresa = $base->Registro()) {
                    $this->setIdEmpresa($idempresa);
                    $this->setENombre($empresa['enombre']);
                    $this->setEDireccion($empresa['edireccion']);
                    $respuesta = true;
                }
            } else {
                $respuesta = false;
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $respuesta = false;
            $this->setMensajeOperacion($base->getError());
        }
        return $respuesta;
    }

    public function listar($condicion)
    {
        $arregloEmpresa = null;
        $base = new BaseDatos();
        $consultaEmpresa = "SELECT * FROM empresa ";
        if ($condicion != "") {
            $consultaEmpresa .= ' WHERE ' . $condicion;
        }
        $consultaEmpresa .= " ORDER BY idempresa ";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaEmpresa)) {
                $arregloEmpresa = array();
                while ($empresa = $base->Registro()) {

                    $objEmpresa = new Empresa();
                    $objEmpresa->Buscar($empresa['idempresa']);
                    array_push($arregloEmpresa, $objEmpresa);
                }
            } else {
                $arregloEmpresa = false;
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $arregloEmpresa = false;
            $this->setMensajeOperacion($base->getError());
        }
        return $arregloEmpresa;
    }

    /** Funcion para ingresar informacion de la empresa */

    public function ingresar()
    {
        $baseDeDatos = new BaseDatos();
        $respuesta = null;
        $consulta = "INSERT INTO empresa (idempresa, enombre, edireccion) VALUES ('". $this->getIdEmpresa(). "','" . $this->getENombre() . "','" . $this->getEDireccion() . "')";
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

    /** Función que nos permite modificar datos de la empresa */

    public function modificar()
    {
        $respuesta = null;
        $baseDeDatos = new BaseDatos();
        $consultaModificacion = "UPDATE empresa SET idempresa = " . $this->getIdEmpresa() . ", 
        enombre = '" . $this->getENombre() . "', 
        edireccion ='" . $this->getEDireccion() . "' WHERE idempresa = " . $this->getIdEmpresa();
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

    /** Nos permite eliminar el objeto de la base de datos */
    public function eliminar()
    {
        $baseDeDatos = new BaseDatos();
        $respuesta = null;
        if ($baseDeDatos->iniciar()) {
            $consultaBorrar = "DELETE FROM empresa WHERE idempresa = " . $this->getIdEmpresa();
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
        $info = "Id empresa: {$this->getIdEmpresa()} \n" .
            "Nombre: {$this->getENombre()}\n" .
            "Direccion: {$this->getEDireccion()}";
        return $info;
    }
}
