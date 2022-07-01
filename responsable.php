<?php

class Responsable
{
    private $rNumEmpleado;
    private $rNumLicencia;
    private $rNombre;
    private $rApellido;
    private $mensajeOperacion;

    public function __construct()
    {
        $this->rNumEmpleado = "";
        $this->rNumLicencia = "";
        $this->rNombre = "";
        $this->rApellido = "";
    }

    public function cargar($rnumempleado, $rnumlicencia, $rnombre, $rapellido)
    {
        $this->setRNumEmpleado($rnumempleado);
        $this->setRNumLicencia($rnumlicencia);
        $this->setRNombre($rnombre);
        $this->setRApellido($rapellido);
    }

    public function getRNumEmpleado()
    {
        return $this->rNumEmpleado;
    }

    public function setRNumEmpleado($rNumEmpleado)
    {
        $this->rNumEmpleado = $rNumEmpleado;
    }

    public function getRNumLicencia()
    {
        return $this->rNumLicencia;
    }

    public function setRNumLicencia($rNumLicencia)
    {
        $this->rNumLicencia = $rNumLicencia;
    }

    public function getRNombre()
    {
        return $this->rNombre;
    }

    public function setRNombre($rNombre)
    {
        $this->rNombre = $rNombre;
    }

    public function getRApellido()
    {
        return $this->rApellido;
    }

    public function setRApellido($rApellido)
    {
        $this->rApellido = $rApellido;
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

    public function Buscar($rNumEmpleado)
    {
        $base = new BaseDatos();
        $consulta = "SELECT * FROM responsable WHERE rnumeroempleado =" . $rNumEmpleado;
        $respuesta = null;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($responsable = $base->Registro()) {
                    $this->setRNumEmpleado($rNumEmpleado);
                    $this->setRNumLicencia($responsable['rnumerolicencia']);
                    $this->setRNombre($responsable['rnombre']);
                    $this->setRApellido($responsable['rapellido']);
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
        $arregloResponsable = null;
        $base = new BaseDatos();
        $consultaResponsable = "SELECT * FROM responsable ";
        if ($condicion != "") {
            $consultaResponsable = $consultaResponsable . ' where ' . $condicion;
        }
        $consultaResponsable .= " order by rnumeroempleado ";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaResponsable)) {
                $arregloResponsable = array();
                while ($responsable = $base->Registro()) {
                    $objResponsable = new Responsable();
                    $objResponsable->Buscar($responsable['rnumeroempleado']);
                    array_push($arregloResponsable, $objResponsable);
                }
            } else {
                $arregloResponsable = false;
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $arregloResponsable = false;
            $this->setmensajeoperacion($base->getError());
        }
        return $arregloResponsable;
    }

    public function ingresar()
    {
        $baseDeDatos = new BaseDatos();
        $respuesta = null;
        $consulta = "INSERT INTO responsable (rnumeroempleado, rnumerolicencia, rnombre, rapellido) 
        VALUES ('". $this->getRNumEmpleado(). "','".$this->getRNumLicencia()."','".$this->getRNombre()."','".$this->getRApellido()."')";
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
        $consultaModificacion = "UPDATE responsable SET rnumerolicencia = '" . $this->getRNumLicencia() . "', rnombre = '" . $this->getRNombre() . "', rapellido = '" . $this->getRApellido() . "' WHERE rnumeroempleado = " . $this->getRNumEmpleado();
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
            $consultaBorrar = "DELETE FROM responsable WHERE rnumeroempleado = " . $this->getRNumEmpleado();
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
        $info = "Número empleado: {$this->getRNumEmpleado()} \n" .
            "Número licencia: {$this->getRNumLicencia()} \n" .
            "Nombre: {$this->getRnombre()} \n" .
            "Apellido: {$this->getRApellido()}";
        return $info;
    }
}
