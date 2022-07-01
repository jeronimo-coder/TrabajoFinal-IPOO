<?php
/* IMPORTANTE !!!!  Clase para (PHP 5, PHP 7)*/

class BaseDatos {
    private $HOSTNAME;
    private $BASEDATOS;
    private $USUARIO;
    private $CLAVE;
    private $CONEXION;
    private $QUERY;
    private $RESULT;
    private $ERROR;
    /**
     * Constructor de la clase que inicia ls variables instancias de la clase
     * vinculadas a la coneccion con el Servidor de BD
     */
    public function __construct(){
        $this->HOSTNAME = "127.0.0.1";
        $this->BASEDATOS = "bdviajes";
        $this->USUARIO = "root";
        $this->CLAVE="";
        $this->RESULT=0;
        $this->QUERY="";
        $this->ERROR="";
    }

    public function getHOSTNAME(){
        return $this->HOSTNAME;
    }

    public function setHOSTNAME($HOSTNAME){
        $this->HOSTNAME = $HOSTNAME;
    }

    public function getBASEDATOS(){
        return $this->BASEDATOS;
    }

    public function setBASEDATOS($BASEDATOS){
        $this->BASEDATOS = $BASEDATOS;
    }

    public function getUSUARIO(){
        return $this->USUARIO;
    }

    public function setUSUARIO($USUARIO){
        $this->USUARIO = $USUARIO;
    }

    public function getCLAVE(){
        return $this->CLAVE;
    }

    public function setCLAVE($CLAVE){
        $this->CLAVE = $CLAVE;
    }

    public function getCONEXION(){
        return $this->CONEXION;
    }

    public function setCONEXION($CONEXION){
        $this->CONEXION = $CONEXION;
    }

    public function getQUERY(){
        return $this->QUERY;
    }

    public function setQUERY($QUERY){
        $this->QUERY = $QUERY;
    }

    public function getRESULT(){
        return $this->RESULT;
    }

    public function setRESULT($RESULT){
        $this->RESULT = $RESULT;
    }

    public function getERROR(){
        return $this->ERROR;
    }

    public function setERROR($ERROR){
        $this->ERROR = $ERROR;
    }

    /** Iniciamos la conexion con la base de datos 
     * @return bool
    */

    public function iniciar()
    {
        $respuesta = false;
        $conexion = mysqli_connect($this->HOSTNAME, $this->USUARIO, $this->CLAVE, $this->BASEDATOS);
        if($conexion){
            if(mysqli_select_db($conexion, $this->BASEDATOS)){
                $this->CONEXION = $conexion;
                unset($this->QUERY);
                unset($this->ERROR);
                $respuesta = true;
            } else {
                $this->ERROR = mysqli_errno($conexion). ": " . mysqli_error($conexion);
            }
        } else {
            $this->ERROR = mysqli_errno($conexion). ": " . mysqli_error($conexion);
        }
        return $respuesta;
    }

    /** Funcion que se encargar de ejecutar una consulta en la base de datos
     * recibe por parametro la consulta
     * @param string $consulta
     * @return bool
     */

    public function ejecutar($consulta)
    {
        $respuesta = false;
        unset($this->ERROR);
        $this->QUERY = $consulta;
        if($this->RESULT = mysqli_query($this->CONEXION, $consulta)){
            $respuesta = true;
        } else{
            $this->ERROR = mysqli_errno($this->CONEXION). ": ". mysqli_error($this->CONEXION);
        }
        return $respuesta;
    }

    /**
     * Devuelve un registro retornado por la ejecucion de una consulta
     * el puntero se despleza al siguiente registro de la consulta
     *
     * @return boolean
     */

    public function registro()
    {
        $respuesta = null;
        if($this->RESULT){
            unset($this->ERROR);
            if($temp = mysqli_fetch_assoc($this->RESULT)){
                $respuesta = $temp;
            } else{
                mysqli_free_result($this->RESULT);
            }
        } else{
            $this->ERROR = mysqli_errno($this->CONEXION) . ": " . mysqli_error($this->CONEXION);
        }
        return $respuesta;
    }
    
    public function devuelveIDInsercion($consulta){
        $verificacion = null;
        unset($this->ERROR);
        if($this->setRESULT(mysqli_query($this->getCONEXION(), $consulta))){
            $id = mysqli_insert_id();
            $verificacion = $id;
        }else{
            $this->setERROR(mysqli_errno($this->getCONEXION()).":".mysqli_error($this->getCONEXION()));
        }
        return $verificacion;
    }
}
   
?>