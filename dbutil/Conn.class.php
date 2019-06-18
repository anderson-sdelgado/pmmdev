<?php

/**
 * Conn.class [ CONEXÃO ]
 * Classe abstrata de conexão. Padrão SingleTon.
 * Retorna um objeto PDO pelo método estático getConn();
 * 
 * @copyright (c) 2013, Robson V. Leite UPINSIDE TECNOLOGIA
 */
class Conn {

    /** @var PDO */
    private static $Connect = null;

    /**
     * Conecta com o banco de dados com o pattern singleton.
     * Retorna um objeto PDO!
     */
    private static function Conectar() {
        try {

            $BD = 2;

            if (self::$Connect == null) {
                if ($BD == 1) {
                    $tns = "  (DESCRIPTION = (ENABLE = BROKEN)(FAILOVER = ON)(LOAD_BALANCE = YES)
                            (ADDRESS = (PROTOCOL = TCP)(HOST = stafe-scan)(PORT = 1521))
                            (CONNECT_DATA =
                              (SERVER = DEDICATED)
                              (SERVICE_NAME = STAFE)
                              (FAILOVER_MODE =
                                (TYPE = SELECT)
                                (METHOD = BASIC)
                                (RETRIES = 180)
                                (DELAY = 5)
                               )
                            )
                          )";
                } elseif ($BD == 2) {
                    $tns = "  (DESCRIPTION = (ENABLE = BROKEN)(FAILOVER = ON)(LOAD_BALANCE = YES)
                            (ADDRESS = (PROTOCOL = TCP)(HOST = stafe-scan)(PORT = 1521))
                            (CONNECT_DATA =
                              (SERVER = DEDICATED)
                              (SERVICE_NAME = STAFEQA)
                              (FAILOVER_MODE =
                                (TYPE = SELECT)
                                (METHOD = BASIC)
                                (RETRIES = 180)
                                (DELAY = 5)
                               )
                            )
                          )";
                } elseif ($BD == 3) {
                    $tns = "  (DESCRIPTION = (ENABLE = BROKEN)(FAILOVER = ON)(LOAD_BALANCE = YES)
                            (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.2.15)(PORT = 1521))
                            (CONNECT_DATA =
                              (SERVER = DEDICATED)
                              (SERVICE_NAME = STAFEDEV)
                              (FAILOVER_MODE =
                                (TYPE = SELECT)
                                (METHOD = BASIC)
                                (RETRIES = 180)
                                (DELAY = 5)
                               )
                            )
                          )";
                }

                self::$Connect = new PDO("oci:dbname=" . $tns . ';charset=utf8', 'INTERFACE', 'FGBNY946');
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
