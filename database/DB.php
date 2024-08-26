<?php

define('DB_FILENAME', 'db.json');
define('DB_DIRNAME', 'storage');


class DB
{

   private $filename, $data, $lastId;
   public function __construct()
   {
      $this->filename = DB_DIRNAME . '/' . DB_FILENAME;
      $this->load();
   }

   private function load()
   {
      if (file_exists($this->filename)) {
         $jsonString = file_get_contents($this->filename);
         $this->data = json_decode($jsonString, true);

         if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Erro ao decodificar JSON: " . json_last_error_msg());
         }
      } else {
         mkdir(DB_DIRNAME, 0777, true);
         file_put_contents($this->filename, '[]');
         $this->load();
      }

      $this->lastId = $this->getLastId();
   }

   private function save()
   {
      $jsonString = json_encode($this->data, JSON_PRETTY_PRINT);
      file_put_contents($this->filename, $jsonString);
   }

   private function getNextId()
   {
      return $this->lastId + 1;
   }

   private function getLastId()
   {
      $lastId = 0;
      foreach ($this->data as $item) {
         if (isset($item['id']) && $item['id'] > $lastId) {
            $lastId = $item['id'];
         }
      }
      return $lastId;
   }

   public function addItem($item)
   {
      $item['id'] = $this->getNextId();
      $this->data[] = $item;
      $this->lastId = $item['id'];
      $this->save();
      return $item['id'];
   }

   public function getItemById($id)
   {
      foreach ($this->data as $item) {
         if (isset($item['id']) && $item['id'] == $id) {
            return $item;
         }
      }
      return ['error:' => 'Registro não encontrado'];
   }
}
