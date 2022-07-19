<?php
namespace App\Model;

use App\Core\Sql;
use App\Core\QueryBuilder;

class Checkout extends Sql
{
    protected $id;
    protected $id_user;
    protected $createdAt;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return null|int
     */
    public function getLastInsertId(): ?int
    {
        return $this->last_insert_id;
    }
    
    /**
     * @param int $id_user
     */
    public function setIdUser(int $id_user): void
    {
        $this->id_user = $id_user;
    }

    /**
     * @return null|int
     */
    public function getIdUser(): ?int
    {
        return $this->id_user;
    }

    /**
     * @return null|int
     */
    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    //  /**
    //  * @return null|int
    //  */
    // public function getIdProduct(): ?int
    // {
    //     return $this->id_product;
    // }

    // /**
    //  * @param int $id_product
    //  */
    // public function setIdProduct(int $id_product): void
    // {
    //     $this->id_product = $id_product;
    // }

    public function setCheckout(){

        $this->setIdUser($_SESSION["auth"]) ;

    }

    public function getCheckoutForm(): array
    {   //Formulaire un peu inutile, mais c'est surtout pour le futur token CSRF
        return [
            "config"=>[
                "method"=>"POST",
                "action"=>"checkout",
                "submit"=>"Acheter"
            ],
            'inputs'=>[
                "user_id"=>[
                    "type"=>"hidden",
                    "required"=>true,
                    "value" => $_SESSION['auth']
                ]
            ]
        ];
    }

    public function getAllCheckout()
    {
        $checkouts = $this->getAll(['id','id_user','createdAt']);

        return $checkouts;
    }

}