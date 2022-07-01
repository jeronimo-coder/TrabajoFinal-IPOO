<?php

use LDAP\Result;

include_once('baseDatos.php');
include_once('empresa.php');
include_once('responsable.php');
include_once('viaje.php');
include_once('pasajero.php');


function solicitarNumeroEntre($min, $max)
{
    //int $numero
    $numero = trim(fgets(STDIN));
    while (!is_int($numero) && !($numero >= $min && $numero <= $max)) {
        echo "Debe ingresar un número entre " . $min . " y " . $max . ": ";
        $numero = trim(fgets(STDIN));
    }
    return $numero;
}

function menuPrincipal()
{
    echo "======================== \n";
    echo "1) Mostrar \n";
    echo "2) Ingresar \n";
    echo "3) Modificar \n";
    echo "4) Eliminar \n";
    echo "5) Salir \n";
    echo "Qué opción desea ingresar?: \n";
    $numVal1 = 1;
    $numVal2 = 5;
    $eleccion = solicitarNumeroEntre($numVal1, $numVal2);
    return $eleccion;
}

function menuMostrar()
{
    echo "======================== \n";
    echo "1) Mostrar viajes \n";
    echo "2) Mostrar pasajeros\n";
    echo "3) Mostrar responsables\n";
    echo "4) Mostrar empresas\n";
    echo "5) Volver \n";
    echo "Qué opción desea ingresar?: \n";
    $numVal1 = 1;
    $numVal2 = 5;
    $eleccion = solicitarNumeroEntre($numVal1, $numVal2);
    return $eleccion;
}

function menuIngresar()
{
    echo "======================== \n";
    echo "1) Ingresar viaje \n";
    echo "2) Ingresar pasajero\n";
    echo "3) Ingresar responsable\n";
    echo "4) Ingresar empresa\n";
    echo "5) Volver \n";
    echo "Qué opción desea ingresar?: \n";
    $numVal1 = 1;
    $numVal2 = 5;
    $eleccion = solicitarNumeroEntre($numVal1, $numVal2);
    return $eleccion;
}

function menuModificar()
{
    echo "======================== \n";
    echo "1) Modificar viaje \n";
    echo "2) Modificar pasajero\n";
    echo "3) Modificar responsable\n";
    echo "4) Modificar empresa\n";
    echo "5) Volver \n";
    echo "Qué opción desea ingresar?: \n";
    $numVal1 = 1;
    $numVal2 = 5;
    $eleccion = solicitarNumeroEntre($numVal1, $numVal2);
    return $eleccion;
}

function menuEliminar()
{
    echo "======================== \n";
    echo "1) Eliminar viaje \n";
    echo "2) Eliminar pasajero\n";
    echo "3) Eliminar responsable\n";
    echo "4) Eliminar empresa\n";
    echo "5) Volver \n";
    echo "Qué opción desea ingresar?: \n";
    $numVal1 = 1;
    $numVal2 = 5;
    $eleccion = solicitarNumeroEntre($numVal1, $numVal2);
    return $eleccion;
}

function obtenerEmpresa()
{
    echo "Estas son las empresas de viajes que hay actualmente, con cual desea iniciar un nuevo viaje?: (Seleccione el id) \n";
    mostrarEmpresas();
    $empresa = new Empresa();
    $cantEmpresas = count($empresa->listar(""));
    if($cantEmpresas == 0){
        $empresa = false;
    }else{
        $eleccion = solicitarNumeroEntre(0, ($cantEmpresas - 1));
        $empresa->Buscar($eleccion);
    }
   
    return $empresa;
}
function obtenerResponsable()
{
    echo "Estos son los responsables actuales: (Selecione el numero de empleado) \n";
    mostrarResponsables();
    $responsable = new Responsable();
    $cantResponsables = count($responsable->listar(""));
    if ($cantResponsables == 0) {
        $responsable = false;
    } else {
        $eleccion = solicitarNumeroEntre(0, ($cantResponsables -1));
        $responsable->Buscar($eleccion);
    }
    return $responsable;
}

function obtenerViaje()
{
    echo "Estos son los viajes que hay actualmente: (seleccione por ID)\n";
    mostrarViajes();
    $viaje = new Viaje();
    $cantViajes = count($viaje->listar(""));
    if ($cantViajes == 0) {
        $viaje = false;
    } else {
        $eleccion = solicitarNumeroEntre(0, ($cantViajes -1));
        $viaje->Buscar($eleccion);
    }
    return $viaje;
}

/* function obtenerPasajero(){
    echo "Estos son los pasajeros que hay actualmente: (seleccione por id)\n";
    mostrarViajes();
    $decision = intval(trim(fgets(STDIN)));
    $pasajero = new Viaje();
    $pasajero->Buscar($decision);
    return $pasajero;   
} */

function crearViaje()
{
    $empresaElegida = obtenerEmpresa();
    $responsableElegido = obtenerResponsable();
    echo "Ingrese el destino del viaje: ";
    $destino = trim(fgets(STDIN));
    echo "Ingrese la cantidad max de pasajeros: ";
    $cantMax = intval(trim(fgets(STDIN)));
    echo "Ingrese si el viaje de solo de (ida) o (ida y vuelta): ";
    $idayvuelta = trim(fgets(STDIN));
    echo "Ingrese el importe del viaje: ";
    $importe = floatval(trim(fgets(STDIN)));
    echo "Ingrese el tipo de asiento (primera clase) o (asiento estandar): ";
    $tipoAsiento = trim(fgets(STDIN));
    $objViaje = new Viaje();

    $objViaje->cargar(count($objViaje->listar("")), $destino, $cantMax, $empresaElegida, $responsableElegido, $importe, $tipoAsiento, $idayvuelta);
    if (verificarViaje($objViaje)) {
        echo "Ya existe un viaje a ese destino. \n";
    } else {
        $respuesta = $objViaje->ingresar();
        if ($respuesta) {
            echo "Viaje ingresado! \n";
        } else {
            echo "No se pudo ingresar el viaje. Error: " . $objViaje->getMensajeOperacion();
        }
    }
}


function verificarViaje($objViajeNuevo)
{
    $objViaje = new Viaje();
    $arrayObjViaje = $objViaje->listar("");
    $i = 0;
    $mismoViaje = false;
    while (!$mismoViaje && ($i < count($arrayObjViaje))) {
        if (strtolower($arrayObjViaje[$i]->getVDestino()) == strtolower($objViajeNuevo->getVDestino())) {
            $mismoViaje = true;
        } else {
            $i++;
        }
    }
    return $mismoViaje;
}

function crearResponsable()
{
    echo "Ingrese el nombre del responsable: ";
    $nombre = trim(fgets(STDIN));
    echo "Ingrese el apellido del responsable: ";
    $apellido = trim(fgets(STDIN));
    echo "Ingrese el numero de licencia del responsable: ";
    $numeroLicencia = intval(trim(fgets(STDIN)));
    $objResponsable = new Responsable();
    $objResponsable->cargar(count($objResponsable->listar("")), $numeroLicencia, $nombre, $apellido);
    $respuesta = $objResponsable->ingresar();
    if ($respuesta) {
        echo "El responsable se ingreso correctamente \n";
    } else {
        echo "No se pudo ingresar el responsable \n. Error." . $objResponsable->getMensajeOperacion();
        $objResponsable = null;
    }
}

function crearPasajero($objViaje)
{
    echo "Ingrese el nombre del pasajero: ";
    $nombre = trim(fgets(STDIN));
    echo "Ingrese el apellido del pasajero: ";
    $apellido = trim(fgets(STDIN));
    echo "Ingrese el DNI del pasajero: ";
    $dniPasajero = trim(fgets(STDIN));
    echo "Ingrese el telefono: ";
    $telefono = intval(trim(fgets(STDIN)));
    $objPasajero = new Pasajero();
    $respuesta = $objPasajero->Buscar($dniPasajero);
    if ($respuesta) {
        echo "Este pasajero ya esta en un viaje. No se pudo ingresar \n";
        echo "======================== \n";
        echo "Desea cambiarlo al viaje que elegio recientemente?: (si) (no) ";
        $decision = trim(fgets(STDIN));
        if ($decision == "si") {
            $objPasajero->setObjViaje($objViaje);
            $objPasajero->modificar();
        }
        $objPasajero = null;
    } else {
        $objPasajero->cargar($dniPasajero, $nombre, $apellido, $telefono, $objViaje);
        $resp = $objPasajero->ingresar();
        if ($resp) {
            echo "Cargado exitosamente! \n";
        } else {
            echo "No se pudo ingresar el pasajero al viaje. Error: " . $objPasajero->getMensajeoperacion() . "\n";
        }
    }
}


function crearEmpresa()
{
    echo 'Ingrese el nombre de la empresa: ';
    $nombreEmpresa = trim(fgets(STDIN));
    echo 'Ingrese la direccion de la empresa: ';
    $direccionEmpresa = trim(fgets(STDIN));
    $objEmpresa = new Empresa();
    $objEmpresa->cargar(count($objEmpresa->listar("")), $nombreEmpresa, $direccionEmpresa);
    $respuesta = $objEmpresa->ingresar();
    if ($respuesta) {
        echo "La empresa fue ingresada correctamente! \n";
    } else {
        echo "La empresa no se pudo ingresar. Error" . $objEmpresa->getMensajeOperacion();
        $objEmpresa = null;
    }
}

function mostrarViajes()
{
    $respuesta = null;
    $objViaje = new Viaje();
    $arrayObjViaje = $objViaje->listar("");
    $cantViajes = count($objViaje->listar(""));
    if($cantViajes == 0){
        $respuesta = false;
    }else{
        echo "Los viajes son: \n";
        foreach ($arrayObjViaje as $viaje) {
            echo "============================\n";
            echo $viaje . "\n";
            echo "============================\n";
        }
        $respuesta = true;
    }
    return $respuesta;
    
}

function mostrarEmpresas()
{
    $respuesta = null;
    $objEmpresa = new Empresa();
    if(count($objEmpresa->listar("")) == 0){
        $respuesta = false;
    }else{
        $arrayObjEmpresa = $objEmpresa->listar("");
        echo "Las empresas son: \n";
        foreach ($arrayObjEmpresa as $empresa) {
            echo "============================\n";
            echo $empresa . "\n";
            echo "============================\n";
        }
        $respuesta = true;
    }
    
    return $respuesta;
}

function mostrarResponsables()
{
    $respuesta = null;
    $objResponsable = new Responsable();
    if(count($objResponsable->listar("")) == 0){
        $respuesta = false;
    }else{
        $arrayObjResponsable = $objResponsable->listar("");
        echo "Los responsables son: \n";
        foreach ($arrayObjResponsable as $responsable) {
            echo "============================\n";
            echo $responsable . "\n";
            echo "============================\n";
            $respuesta = true;
        }
    }
    return $respuesta;
}

function mostrarPasajeros($idViaje)
{
    $objPasajero = new Pasajero();
    $arrayObjPasajero = $objPasajero->listar("idviaje = " . $idViaje);
    echo "Los pasajeros del viaje {$idViaje} son: \n";
    foreach ($arrayObjPasajero as $pasajero) {
        echo "============================\n";
        echo $pasajero . "\n";
        echo "============================\n";
    }
}

function modificarResponsable($rNumResponsable)
{
    $responsable = new Responsable();
    $respuesta = $responsable->Buscar($rNumResponsable);
    if ($respuesta) {
        echo "Ingrese el numero de licencia: ";
        $numLicencia = intval(trim(fgets(STDIN)));
        echo "Ingrese el nombre: ";
        $nombre = trim(fgets(STDIN));
        echo "Ingrese el apellido: ";
        $apellido = trim(fgets(STDIN));
        $responsable->setRNumLicencia($numLicencia);
        $responsable->setRNombre($nombre);
        $responsable->setRApellido($apellido);
        $responsable->modificar();
        if ($responsable->modificar()) {
            echo "Modificado correctamente. \n";
            echo "======================== \n";
        } else {
            echo "No se pudo modificar \n";
            echo $responsable->getMensajeOperacion();
            echo "======================== \n";
        }
    } else {
        echo "El responsable elegido no existe \n";
    }
}

function modificarViaje($idviaje)
{
    $viaje = new Viaje();
    $respuesta = $viaje->buscar($idviaje);
    if ($respuesta) {
        $empresa = obtenerEmpresa();
        $responsable = obtenerResponsable();
        echo "Ingrese el destino: ";
        $destino = trim(fgets(STDIN));
        echo "Ingrese cantidad max de pasajeros: ";
        $cantMax = intval(trim(fgets(STDIN)));
        echo "Ingrese el importe: ";
        $importe = floatval(trim(fgets(STDIN)));
        echo "Ingrese el tipo de asiento: (primera clase) o (clase estandar) ";
        $tipoAsiento = trim(fgets(STDIN));
        echo "El viaje es (ida) o (ida y vuelta): ";
        $viajeDe = trim(fgets(STDIN));
        $viaje->setVDestino($destino);
        $viaje->setVCantMaxPasajeros($cantMax);
        $viaje->setVImporte($importe);
        $viaje->setTipoAsiento($tipoAsiento);
        $viaje->setIdaYvuelta($viajeDe);
        $viaje->setObjEmpresa($empresa);
        $viaje->setObjEmpleado($responsable);
        $viaje->modificar();
        if ($viaje->modificar()) {
            echo "Modificado correctamente. \n";
            echo "======================== \n";
        } else {
            echo "No se pudo modificar \n";
            echo $viaje->getMensajeOperacion();
            echo "======================== \n";
        }
    } else {
        echo "El viaje elegido no existe. \n";
    }
}

function modificarPasajero($rDocumento)
{
    $pasajero = new Pasajero();
    $respuesta = $pasajero->Buscar($rDocumento);
    if ($respuesta) {
        echo "Ingrese el nombre: ";
        $nombre = trim(fgets(STDIN));
        echo "Ingrese el apellido: ";
        $apellido = trim(fgets(STDIN));
        echo "Ingrese el numero de telefono: ";
        $telefono = intval(trim(fgets(STDIN)));
        $pasajero->setPNombre($nombre);
        $pasajero->setPApellido($apellido);
        $pasajero->setPTelefono($telefono);
        $pasajero->modificar();
    } else {
        echo "No se pudo modificar";
    }
    echo $pasajero->getMensajeoperacion();
}

function modificarEmpresa($idEmpresa)
{
    $empresa = new Empresa();
    $respuesta = $empresa->Buscar($idEmpresa);
    if ($respuesta) {
        echo "Ingrese el nombre de la empresa: ";
        $nombre = trim(fgets(STDIN));
        echo "Ingrese la direccion de la empresa: ";
        $direccion = trim(fgets(STDIN));
        $empresa->setENombre($nombre);
        $empresa->setEDireccion($direccion);
        $empresa->modificar();
        echo "Empresa modificada!\n";
    } else {
        echo "No se pudo modificar. No existe la empresa seleccionada.\n";
    }
    echo $empresa->getMensajeOperacion();
}

do {
    $iniciarMenuPrincipal = menuPrincipal();

    switch ($iniciarMenuPrincipal) {
        case 1:
            do {
                $menuMostrar = menuMostrar();
                if ($menuMostrar == 1) {
                    // MOSTRAR VIAJES
                    if(($mostrar = mostrarViajes()) == true){
                       /*  mostrarViajes(); */
                    }
                    
                }
                if ($menuMostrar == 2) {
                    // MOSTRAR PASAJEROS
                    
                    if(($mostrar = mostrarViajes()) == true){
                        /* mostrarViajes(); */
                        echo "Ingrese el ID del viaje del cual desea ver sus pasajeros: \n";
                        $id = trim(fgets(STDIN));
                        mostrarPasajeros($id);
                    }
                    
                }
                if ($menuMostrar == 3) {
                    // MOSTRAR RESPONSABLES
                    mostrarResponsables();
                }
                if ($menuMostrar == 4) {
                    // MOSTRAR EMPRESAS
                    mostrarEmpresas();
                }
            } while ($menuMostrar != 5);

            break;
        case 2:
            do {
                $menuIngresar =  menuIngresar();
                if ($menuIngresar == 1) {
                    // INGRESAR VIAJE A BD
                    crearViaje();
                } elseif ($menuIngresar == 2) {
                    // INGRESAR PASAJERO A UN VIAJE Y SU BD
                    $objViaje = obtenerViaje();
                    if($objViaje == false){
                        echo "No hay viajes donde ingresar un pasajero.\n";
                    }else{
                        crearPasajero($objViaje);
                    }
                    
                } elseif ($menuIngresar == 3) {
                    // INGRESAR RESPONSABLE A BD
                    crearResponsable();
                } elseif ($menuIngresar == 4) {
                    // INGRESAR UNA EMPRESA
                    crearEmpresa();
                }
            } while ($menuIngresar != 5);
            break;
        case 3:
            do {
                $menuModificar = menuModificar();
                if ($menuModificar == 1) {
                    // MODIFICAR VIAJE
                    $objviaje = obtenerViaje();
                    if($objviaje == false){
                        echo "No hay viaje que modificar.\n";
                    }else{
                        modificarViaje($objviaje->getIdViaje());
                    }
                    
                } elseif ($menuModificar == 2) {
                    //MODIFICAR PASAJERO
                    $cant = mostrarViajes();
                    if($cant == false){
                        echo "No hay pasajero que modificar.\n";
                    }else{
                        echo "Ingrese el ID del viaje del cual desea ver sus pasajeros. \n";
                        $id = trim(fgets(STDIN));
                        mostrarPasajeros($id);
                        echo "Seleccione el DNI del pasajero a modificar: \n";
                        $dni = trim(fgets(STDIN));
                        modificarPasajero($dni);
                        echo "Pasajero modificado!\n";
                    }
                    
                } elseif ($menuModificar == 3) {
                    // MODIFICAR RESPONSABLE
                    $responsable = mostrarResponsables();
                    if($responsable == false){
                        echo "No hay responsables que modificar.\n";
                    }else{
                        //mostrarResponsables();
                        echo "Ingrese el numero de empleado del Responsable a modificar: ";
                        $numero = intval(trim(fgets(STDIN)));
                        modificarResponsable($numero);
                    }
                    
                } elseif ($menuModificar == 4) {
                    // MODIFICAR EMPRESA
                    $objEmpresa = mostrarEmpresas();
                    if($objEmpresa == false){
                        echo "No hay empresa que modificar.\n";
                    }else{
                        //mostrarEmpresas();
                        echo "Seleccion el ID de una empresa: ";
                        $id = intval(trim(fgets(STDIN)));
                        modificarEmpresa($id);
                    }
                    
                }
            } while ($menuModificar != 5);
            break;
        case 4:
            do {
                $menuEliminar = menuEliminar();
                if ($menuEliminar == 1) {
                    //ELIMINAR VIAJE
                    $objViaje = obtenerViaje();
                    if ($objViaje == false) {
                        echo "No hay viajes que eliminar\n";
                    } else {
                        $respuesta = $objViaje->eliminar();
                        if ($respuesta) {
                            echo "Viaje eliminado.\n";
                            $objViaje = null;
                        } else {
                            echo "El viaje tiene pasajeros, no se puede eliminar. \n";
                        }
                    }
                } elseif ($menuEliminar == 2) {
                    //ELIMINAR PASAJERO
                    $objViaje = obtenerViaje();
                    if ($objViaje == false) {
                        echo "No hay pasajero que eliminar.\n";
                    } else {
                        mostrarPasajeros($objViaje->getIdViaje());
                        echo "Seleccione el DNI del pasajero a modificar: \n";
                        $dni = trim(fgets(STDIN));
                        $pasajero = new Pasajero();
                        $respuesta = $pasajero->Buscar($dni);
                        if ($respuesta) {
                            $pasajero->eliminar();
                            echo "Pasajero eliminado correctamente. \n";
                        } else {
                            echo "No se pudo eliminar al pasajero. \n";
                        }
                    }
                } elseif ($menuEliminar == 3) {
                    //ELIMINAR RESPONSABLE
                    $responsable = obtenerResponsable();
                    if ($responsable == false) {
                        echo "No hay responsables que eliminar.\n";
                    } else {
                        $respuesta = $responsable->eliminar();
                        if ($respuesta) {
                            echo "Responsable eliminado. \n";
                        } else {
                            echo "El responsable forma parte de algun viaje, no puede ser eliminado \n";
                        }
                    }
                } elseif ($menuEliminar == 4) {
                    //ELIMINAR EMPRESA
                    $empresa = obtenerEmpresa();
                    if($empresa == false){
                        echo "No hay empresas que eliminar.\n";
                    }else{
                        $respuesta = $empresa->eliminar();
                        if ($respuesta) {
                            echo "Empresa eliminada.\n";
                        } else {
                            echo "La empresa forma parte de algun viaje, no puede ser eliminada. \n";
                        }
                    }
                   
                }
            } while ($menuEliminar != 5);
    }
} while ($iniciarMenuPrincipal != 5);
