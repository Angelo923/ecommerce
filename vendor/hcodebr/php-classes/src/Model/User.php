<?php

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;

Class User extends Model {

    const SESSION = "User";
    
    public static function login($login, $password) 
    {

        $sqlData = new Sql();

        $results = $sqlData->select("SELECT * FROM  tb_users WHERE deslogin = :LOGIN", array(
            ":LOGIN"=>$login
        ));

        if (count($results) === 0) 
        {
            throw new \Exception("Usuário inexistente ou senha inválida", 1);
            
        }

        $data = $results[0];

        if (password_verify($password, $data["despassword"]) === true) 
        {

            $user = new User();

            $user->setData($data);

            $_SESSION[User::SESSION] = $user->getValues();

            return $user;

        }else {

            throw new \Exception("Usuário inexistente ou senha inválida", 1);  
        }
        
    }

    public static function verifyLogin($inadmin = true)
    {

        if (
            !isset($_SESSION[User::SESSION])
            ||
            !$_SESSION[User::SESSION]
            ||
            !(int)$_SESSION[User::SESSION]["iduser"] > 0
            ||
            (bool)$_SESSION[User::SESSION]["inadmin"] !== $inadmin
        ) {

            header("Location: /admin/login");
            exit;

        }
    }

    public static function logout()
    {

        $_SESSION[User::SESSION] = NULL;

    }

    public static function listAll()
    {

        $sqlData = new Sql();

        return $sqlData->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) ORDER BY b.desperson");

    }

    public function save() 
    {

         $sqlData = new Sql();

         $results = $sqlData->select("CALL sp_users_save(:desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
            ":desperson"=>$this->getdesperson(),
            ":deslogin"=>$this->getdeslogin(),
            ":despassword"=>$this->getdespassword(),
            ":desemail"=>$this->getdesemail(),
            ":nrphone"=>$this->getnrphone(),
            ":inadmin"=>$this->getinadmin()
         ));

         $this->setData($results[0]);

    }

    public function get($iduser)
    {


        $sqlData = new Sql();

        $results = $sqlData->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) WHERE a.iduser = :iduser", array(
            ":iduser"=>$iduser
        ));

        $this->setData($results[0]);
    }

    public function update()

    {

        $sqlData = new Sql();

         $results = $sqlData->select("CALL sp_usersupdate_save(:iduser, :desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
            ":iduser"=>$this->getiduser(),
            ":desperson"=>$this->getdesperson(),
            ":deslogin"=>$this->getdeslogin(),
            ":despassword"=>$this->getdespassword(),
            ":desemail"=>$this->getdesemail(),
            ":nrphone"=>$this->getnrphone(),
            ":inadmin"=>$this->getinadmin()
         ));

         $this->setData($results[0]);

    }

    public function delete()
    {

        $sqlData = new Sql();

        $sqlData->query("CAll sp_users_delete(:iduser)", array(
            ":iduser"=>$this->getiduser()
        ));


    }


    public static function getForgot($email) 
    {
        $sqlData = new Sql();

        $results = $sqlData->select("SELECT * FROM tb_persons aINNER JOIN tb_users b USING (idperson) WHERE a.desemail = :email", array(
            ":email"=>$email
        ));

        if (count($results) === 0)
        {

            throw new \Exception("Não foi possível recuperar a senha.", 1);
            
        }
        else 
        {
        
        }

    }

}

?>