<?php

include_once './database/DB.php';

define("TIPOS_PERIODICIDADE", ['mensal' => 30, 'semanal' => 7]);

class Carne
{

   private $valor_total, $valor_entrada, $quantidade_parcelas, $primeiro_dia_cobranca, $periodicidade, $response;

   public function __construct($request)
   {
      $this->atribuirDados($request);
      $this->validaDados($request);
      $this->geraCarne();
      $this->gravaCarne();
      http_response_code(200);
      echo json_encode($this->response);
      die();
   }


   private function validaDados($dados): void
   {
      $erro[] = $this->valor_total <= 0 ? 'O valor total deve ser maior que zero.' : null;
      $erro[] = $this->valor_entrada >= $this->valor_total ? 'O valor da entrada deve ser menor que valor total.' : null;
      $erro[] = $this->quantidade_parcelas <= 0 ? 'A quantidade de parcelas deve ser maior que zero.' : null;
      $erro[] = !strtotime($this->primeiro_dia_cobranca) ? 'O primeiro dia da cobrança deve ser uma data.' : null;
      $erro[] = !array_key_exists($this->periodicidade, TIPOS_PERIODICIDADE) ? 'A periodicidade deve ser mensal ou semanal.' : null;
      $erro = array_filter($erro);

      if (count($erro) > 0) {
         http_response_code(400);
         $this->response['error'] = 'Parâmetros inválidos: ' . implode(', ', $erro);
         echo json_encode($this->response);
         exit;
      }
   }

   private function atribuirDados($dados): void
   {
      extract($dados);
      $this->valor_total = $valor_total;
      $this->valor_entrada = $valor_entrada > 0.00 ? $valor_entrada : null;
      $this->quantidade_parcelas = $quantidade_parcelas;
      $this->primeiro_dia_cobranca = $primeiro_dia_cobranca;
      $this->periodicidade = $periodicidade;
   }

   private function geraCarne()
   {

      $this->response = [
         "total" => number_format($this->valor_total, 2, '.', ''),
         "valor_entrada" => number_format($this->valor_entrada, 2, '.', ''),
         "parcelas" => $this->getListaParcelas(),
         "entrada" => isset($this->valor_entrada)
      ];
   }

   private function getListaParcelas(): array
   {
      $liquido = $this->valor_total - $this->valor_entrada;
      $valorParcela = floor($liquido / $this->quantidade_parcelas * 100) / 100;
      $resto = round($liquido - ($valorParcela * $this->quantidade_parcelas), 2);

      $i = 0;
      $listaParcelas['parcelas'] = [];

      while ($i < $this->quantidade_parcelas) {
         $listaParcelas['parcelas'][] = [
            "data_vencimento" => date('Y-m-d', strtotime($this->primeiro_dia_cobranca . "+" . $i * TIPOS_PERIODICIDADE[$this->periodicidade] . " days")),
            'valor' => $valorParcela + $resto,
            "numero" => ++$i
         ];

         $resto = 0;
      }

      return $listaParcelas;
   }

   private function gravaCarne()
   {
      $db = new DB();
      $this->response['id'] = $db->addItem($this->response);
   }
}

new Carne($request);
