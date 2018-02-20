<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class CnfConexionDB
{var $host;
 var $db;
 var $user;
 var $pass;

 function CnfConexionDB($host,$db,$user,$pass)
    {$this->host=$host;
     $this->db=$db;
     $this->user=$user;
     $this->pass=$pass;
    }

}

class ConexionDBMySql
{private $cnfMySql;
 private $cnn;
 private $error;
 private $rs;

 function ConexionDBMySql(CnfConexionDB $cnf)
    {$this->cnfMySql=$cnf;
     $this->error="";
     $this->cnn = mysql_connect($cnf->host, $cnf->user, $cnf->pass); //mysql_connect("localhost", "root", "cocoliso");
     if(!$this->cnn)
        {$this->error="No se puede conectar a la base de datos MySql";
        }
     mysql_select_db($cnf->db, $this->cnn);
    }

 public function consultar($consultaSELECT)
    {$this->rs=null;
     $this->rs=mysql_query($consultaSELECT, $this->cnn);
     return $this->rs;
    }

 public function nroRegsRetornados($rs)
    {$nroregs=mysql_num_rows($rs);
     return $nroregs;
    }

 public function siguienteRegistro($rs)
    {return mysql_fetch_array($rs);
    }

 public function resultado($rs, $fila, $columna)
    {return mysql_result($rs, $fila, $columna);
    }

 public function actualizar($consultaINSERTUPDATE)
    {//$this->rs=null;
     //$this->rs=mysql_query($consultaINSERTUPDATE, $this->cnn);
     //$nroregs=mysql_affected_rows($this->rs);
     //return $nroregs;
     $sw=mysql_query($consultaINSERTUPDATE, $this->cnn);
     return $sw;
    }

 public function cerrar()
    {mysql_close($this->cnn);
    }

}

 $cnnMySql = new ConexionDBMySql( new CnfConexionDB("localhost","hermes3001","root","") );
 //$cnnMySql = new ConexionDBMySql( new CnfConexionDB("localhost","hermes","roger","rruiz01") );

?>





