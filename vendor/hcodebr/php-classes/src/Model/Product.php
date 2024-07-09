<?php 

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;
use \Hcode\Mailer;


class Product extends Model{	

	public static function listAll(){

		$sqlData = new Sql();

		return $sqlData->select("SELECT * FROM tb_products ORDER BY desproduct");
	}

	public static function checkList($list){

		foreach ($list as &$row) {
			
			$p = new Product();

			$p->setData($row);

			$row = $p->getValues();

		}

		return $list;

	}

	public function save(){

		$sqlData = new Sql();

		$results = $sqlData->select("CALL sp_products_save(:idproduct, :desproduct, :vlprice, :vlwidth, :vlheight, :vllength, :vlweight, :desurl)", array(
				":idproduct"=>$this->getidproduct(),
				":desproduct"=>$this->getdesproduct(),
				":vlprice"=>$this->getvlprice(),
				":vlwidth"=>$this->getvlwidth(),
				":vlheight"=>$this->getvlheight(),
				":vllength"=>$this->getvllength(),
				":vlweight"=>$this->getvlweight(),
				":desurl"=>$this->getdesurl()
				
		));

		$this->setData($results[0]);

	
	}

	public function get($idproduct){

		$sqlData = new Sql();

		$results = $sqlData->select("SELECT * FROM tb_products WHERE idproduct = :idproduct", [
			':idproduct'=>$idproduct

		]);

		$this->setData($results[0]);
	}

	public function delete(){

		$sqlData = new Sql();

		$sqlData->query("DELETE FROM tb_products WHERE idproduct = :idproduct", [
			':idproduct'=>$this->getidproduct()

		]);

		
	}

	public function checkPhoto(){

		if (file_exists(
			$_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR. 
			"res" . DIRECTORY_SEPARATOR. 
			"site" . DIRECTORY_SEPARATOR.
			"img" . DIRECTORY_SEPARATOR.
			"products" . DIRECTORY_SEPARATOR.
			$this->getidproduct() . ".jpg"
		)){

			$url =  "/res/site/img/products/" . $this->getidproduct() . ".jpg";

		}else{

			$url =  "/res/site/img/product.jpg";

		}

		return $this->setdesphoto($url);

	}

	public function getValues(){

		$this->checkPhoto();

		$values = parent::getValues();

		return $values;

	}

	public function setPhoto($file){

		$extension = explode('.', $file['name']); 		// pra ver onde tem ponto no arquivo e depois daquilo conseguir detectar o tipo do arquivo pra poder converter todos para jpg

		$extension = end($extension);

		switch ($extension){

			case "jpg":
			case "jpeg":
					$image = imagecreatefromjpeg($file["tmp_name"]);	//tmp_name é o nome temporário do arquivo
				break;

			case "gif":
					$image = imagecreatefromgif($file["tmp_name"]);
				break;

			case "png":
                $image = imagecreatefrompng($file['tmp_name']);
                $new_im = imagecreatetruecolor(imagesx($image), imagesy($image));
                $white = imagecolorallocate($new_im, 255, 255, 255);
                imagefill($new_im, 0, 0, $white);
                imagealphablending($new_im, true);
                imagecopy($new_im, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
                imagedestroy($image);
                $image = $new_im;
				break;
		}

		$dist = $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR. 
			"res" .DIRECTORY_SEPARATOR. 
			"site" .DIRECTORY_SEPARATOR.
			"img" .DIRECTORY_SEPARATOR.
			"products" .DIRECTORY_SEPARATOR.
			$this->getidproduct().".jpg";

		imagejpeg($image, $dist);

		imagedestroy($image);

		$this->checkPhoto();

	}

	public function getFromURL($desurl){

		$sqlData = new Sql();

		$rows = $sqlData->select("SELECT * FROM tb_products WHERE desurl = :desurl LIMIT 1", [
			':desurl'=>$desurl
		]);

		$this->setData($rows[0]);

	}

	public function getCategories(){

		$sqlData = new Sql();

		return $sqlData->select("
				SELECT * FROM tb_categories a 
				INNER JOIN tb_productscategories b ON a.idcategory = b.idcategory 
				WHERE b.idproduct = :idproduct
			", [
					':idproduct'=>$this->getidproduct()
			]);
	}
	
	
}


 ?>