<?php
    namespace app\models;
    use \PDO;

    if(file_exists(__DIR__."/../../config/server.php")){
        require_once __DIR__."/../../config/server.php";
    }
    class mainModel{
        private $server = DB_SERVER;
        private $db = DB_NAME;
        private $user = DB_USER;
        private $pass = DB_PASS;

        protected function conectar(){
            $conexion = new PDO("mysql:host=".$this->server.";
            dbname=".$this->db, $this->user, $this->pass);
            $conexion->exec("SET CHARACTER SET utf8");
            return $conexion;
        }

        protected function ejecutarConsulta($consulta){
            $sql=$this->conectar()->prepare($consulta);
            $sql->execute();
            return $sql;
        }

        public function limpiarCadena($cadena){
            $palabras=["<script>","</script>","<script src","<script type=","SELECT * FROM","SELECT "," SELECT ","DELETE FROM","INSERT INTO","DROP TABLE","DROP DATABASE","TRUNCATE TABLE","SHOW TABLES","SHOW DATABASES","<?php","?>","--","^","<",">","==","=",";","::"];

            $cadena=trim($cadena);
            $cadena=stripslashes($cadena);

            foreach($palabras as $palabra){
                $cadena=str_ireplace($palabra, "",$cadena);

            }

            $cadena=trim($cadena);
            $cadena=stripslashes($cadena);

            return $cadena;
        }

        protected function verificarDatos($filtro, $cadena){
            if(preg_match("/^".$filtro."$/", $cadena)){
                return false;
            }else{
                return true;
            }
        }


        protected function guardarDatos($tabla, $datos){

            $queary="INSERT INTO $tabla (";

            $C = 0;
            foreach($datos as $clave){
                if($C>=1){ $queary.=","; }
                $queary.= $clave["campo_nombre"];
                $C++;
            }

            $queary.=") VALUES(";

            $C = 0;
            foreach($datos as $clave){
                if($C>=1){ $queary.=","; }
                $queary.= $clave["campo_marcador"];
                $C++;

            }

            $queary.=")";
            $sql=$this->conectar()->prepare($queary);

            foreach($datos as $clave){
                $sql->bindParam($clave["campo_marcador"],$clave["campo_valor"]);
            }

            $sql->execute();

            return $sql;
        }


        public function seleccionarDatos($tipo, $tabla, $campo, $id){
            $tipo=$this->limpiarCadena($tipo);
            $tipo=$this->limpiarCadena($tabla);
            $tipo=$this->limpiarCadena($campo);
            $tipo=$this->limpiarCadena($id);

            if($tipo="Unico"){
                $sql=$this->conectar()->prepare("SELECT * FROM $tabla WHERE $campo=:
                ID");
                $sql->bindParam(":ID",$id);
            }elseif($tipo == "Normal"){
                $sql=$this->conectar()->prepare("SELECT $campo FROM $tabla");
            }

            $sql->execute();

            return $sql;
        }

        protected function actualizarDatos($tabla, $datos,$condicion){
           
            $queary="UPDATE $tabla SET ";

            $C = 0;
            foreach($datos as $clave){
                if($C>=1){ $queary.=","; }
                $queary.= $clave["campo_nombre"]."=".$clave["campo_marcador"];
                $C++;
            }

            $queary.=" WHERE ".$condicion["condicion_campo"]."=".$condicion["condicion_marcador"];
            
            $sql=$this->conectar()->prepare($queary);

            foreach($datos as $clave){
                $sql->bindParam($clave["campo_marcador"],$clave["campo_valor"]);
            }

            $sql->bindParam($condicion["condicion_marcador"],$condicion["condicion_valor"]);

            $sql->execute();

            return $sql;
        }

        protected function eliminarRegistro($tabla,$campo,$id){

            $sql=$this->conectar()->prepare("DELETE FROM $tabla WHERE $campo=:id");
            $sql->bindParam(":id",$id);
            $sql->execute();

            return $sql;
        }

        protected function paginadorTablas($pagina,$numero_paginas,$url,$botones){
            $tabla='<nav class="pagination is-centered is-rounded" role="navigation" aria-label="pagination">';

            if($pagina<=1){
	            $tabla.='
	            <a class="pagination-previous is-disabled" disabled >Anterior</a>
	            <ul class="pagination-list">
	            ';
	        }else{
	            $tabla.='
	            <a class="pagination-previous" href="'.$url.($pagina-1).'/">Anterior</a>
	            <ul class="pagination-list">
	                <li><a class="pagination-link" href="'.$url.'1/">1</a></li>
	                <li><span class="pagination-ellipsis">&hellip;</span></li>
	            ';
	        }

            




        }
        
    }
?>