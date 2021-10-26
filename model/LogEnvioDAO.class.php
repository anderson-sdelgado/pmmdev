<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../dbutil/Conn.class.php');
/**
 * Description of LogEnvioDAO
 *
 * @author anderson
 */
class LogEnvioDAO extends Conn {
    //put your code here
    
    public function salvarDados($dados, $pagina, $versao, $base) {
        
        $this->Conn = parent::getConn($base);
        
        if (($versao >= 2.00) && ($versao < 3.00)) {
       
            $data = date("d/m/Y H:i:s");
            
            $sql = "INSERT INTO DADOS_MOBILE ("
                    . " DTHR "
                    . " , APLICATIVO "
                    . " , PAGINA "
                    . " , DADOS "
                    . " , DTHR_SERV "
                    . " ) "
                    . " VALUES ("
                    . " SYSDATE "
                    . " , 'PMM' "
                    . " , ?"
                    . " , ?"
                    . " , TO_DATE('". $data ."', 'DD/MM/YYYY HH24:MI:SS'))";

            $pagina = $pagina . '-' . $versao;
            
            $this->Create = $this->Conn->prepare($sql);
            $this->Create->bindParam(1, $pagina, PDO::PARAM_STR, 30);
            $this->Create->bindParam(2, $dados, PDO::PARAM_STR, 32000);
            $this->Create->execute();
        
        }
        else if($versao >= 3.00){
            
            $pos1 = strpos($dados, "_") + 1;
            $bolmm = substr($dados, 0, ($pos1 - 1));
            $jsonObjBoletim = json_decode($bolmm);
            $dadosBoletim = $jsonObjBoletim->boletim;
            
            $idEquip = 0;
            $flag = 0;
            
            if(($pagina == 'inserirbolabertommfert') || ($pagina == 'inserirbolfechadommfert')){
                
                foreach ($dadosBoletim as $bol) {

                    $idEquip = $bol->idEquipBolMMFert;
                    $result = $this->dadoAtual($idEquip, $base);
                    foreach ($result as $item) {
                        $flag = $item['FLAG_LOG_ENVIO'];
                    }

                }
                
            }
            
            if($flag == 1){
                
                $sql = "INSERT INTO PMM_LOG_ENVIO ("
                        . " EQUIP_ID "
                        . " , DTHR "
                        . " , PAGINA "
                        . " , DADOS "
                        . " ) "
                        . " VALUES ("
                        . " " . $idEquip
                        . " , SYSDATE "
                        . " , ? "
                        . " , ? "
                        . " )";

                $this->Create = $this->Conn->prepare($sql);
                $this->Create->bindParam(1, $pagina, PDO::PARAM_STR, 30);
                $this->Create->bindParam(2, $dados, PDO::PARAM_STR, 32000);
                $this->Create->execute();
                
            }
            
        }
        
    }
    
    public function dadoAtual($equip, $base) {

        $select = " SELECT "
                        . " A.VERSAO_NOVA "
                        . " , A.VERSAO_ATUAL "
                        . " , A.FLAG_LOG_ENVIO "
                        . " , A.FLAG_LOG_ERRO "
                    . " FROM "
                        . " PMM_ATUALIZACAO A "
                        . " , EQUIP E "
                    . " WHERE "
                        . " A.EQUIP_ID = E.NRO_EQUIP "
                        . " AND"
                        . " E.EQUIP_ID = " . $equip;

        $this->Conn = parent::getConn($base);
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
    }
    
}
