<?php

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;
use \Hcode\Mailer;



Class Category extends Model {


    public static function listAll()
    {

        $sqlData = new Sql();

        return $sqlData->select("SELECT * FROM tb_categories ORDER BY descategory");

    }

   public function save()

   {

    $sqlData = new Sql();

    $results = $sqlData->select("CALL sp_categories_save(:idcategory, :descategory)", array(
       ":idcategory"=>$this->getidcategory(),
       ":descategory"=>$this->getdescategory()
    ));

    $this->setData($results[0]);

   }

   public function get($idcategory) 
   {

        $sqlData = new Sql();

        $results = $sqlData->select("SELECT * FROM tb_categories WHERE idcategory = :idcategory", [
            ":idcategory"=>$idcategory

        ]);

        $this->setData($results[0]);

   }

   public function delete()

   {

    $sqlData = new Sql();

        $sqlData->query("DELETE FROM tb_categories WHERE idcategory = :idcategory", [
            ":idcategory"=>$this->getidcategory()

        ]);


   }
 
}

?>