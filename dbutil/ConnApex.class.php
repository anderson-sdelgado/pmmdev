<?php

/**
 * Conn.class [ CONEXÃO ]
 * Classe abstrata de conexão. Padrão SingleTon.
 * Retorna um objeto PDO pelo método estático getConn();
 * 
 * @copyright (c) 2013, Robson V. Leite UPINSIDE TECNOLOGIA
 */
class ConnApex {

    /** @var PDO */
    private static $Connect = null;

    /**
     * Conecta com o banco de dados com o pattern singleton.
     * Retorna um objeto PDO!
     */
    private static function Conectar() {

        try {

            if (self::$Connect == null) {

                $tns = "(DESCRIPTION =
                            (ADDRESS = (PROTOCOL = TCP)(HOST = stafe-scan)(PORT = 1521))
                            (CONNECT_DATA =
                              (SERVER = DEDICATED)
                              (SERVICE_NAME = APEX)
                            )
                          )";

                self::$Connect = new PDO("oci:dbname=" . $tns . ';charset=utf8', 'STAFE', 'STA1553');

            }
        } catch (PDOException $e) {
            PHPErro($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine());
            die;
        }

        self::$Connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return self::$Connect;
    }

    protected static function getConn() {
        return self::Conectar();
    }

}
