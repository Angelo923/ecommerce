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

    Category::updateFile();

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

        Category::updateFile();

   }

   public static function updateFile()
   {

        $categories = Category::listAll();

        $html = [];

        foreach ($categories as $row) {
            array_push($html, '<li><a href="/categories/'.$row['idcategory'].'">'.$row['descategory'].'</a></li>');
        }

        file_put_contents($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "categories-menu.html", implode('', $html));



   }

   public function getProducts($related = true)
   {

    $sqlData = new Sql();

    if ($related === true) {

        return $sqlData->select("SELECT * FROM tb_products WHERE idproduct IN (
            SELECT a.idproduct
            FROM tb_products a
            INNER JOIN tb_categoriesproducts b ON a.idproduct = b.idproduct
            WHERE b.idcategory = :idcategory
            );
        ", [
            ':idcategory'=>$this->getidcategory()
        ]);
    
    } else {

        return $sqlData->select("SELECT * FROM tb_products WHERE idproduct NOT IN (
            SELECT a.idproduct
            FROM tb_products a
            INNER JOIN tb_categoriesproducts b ON a.idproduct = b.idproduct
            WHERE b.idcategory = :idcategory
            );
        ", [
            ':idcategory'=>$this->getidcategory()
        ]);
    

    } 


   }

   public function addProduct(Product $product) 
   {
        $sqlData = new Sql();

        $sqlData->query("INSERT INTO tb_categoriesproducts(idcategory, idproduct) VALUES (:idcategory, :idproduct)", [
            ':idcategory'=>$this->getidcategory(),
            ':idproduct'=>$product->getidproduct()
        ]);
   }


   public function removeProduct(Product $product) 
   {
        $sqlData = new Sql();

        $sqlData->query("DELETE FROM tb_categoriesproducts WHERE idcategory = :idcategory AND idproduct = :idproduct", [
            ':idcategory'=>$this->getidcategory(),
            ':idproduct'=>$product->getidproduct()
        ]);
   }



}


?>