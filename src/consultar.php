<?php

include_once './database/DB.php';

class Consultar
{

   private $valor_total, $valor_entrada, $quantidade_parcelas, $primeiro_dia_cobranca, $periodicidade, $response;

   public function __construct($request)
   {

      $db = new DB();
      http_response_code(200);
      echo json_encode($db->getItemById($request['id']));
      exit;
   }
}

new Consultar($request);
