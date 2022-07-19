<?php

namespace App\Core;
use App\Core\QueryBuilder;



abstract class Sql
{
    public $pdo;
    private $table;
    private $class;
    public $last_insert_id;

    public function __construct(string $table = NULL, string $class = NULL)
    {
        //Se connecter à la bdd
        //il faudra mettre en place le singleton
        try{
            $this->pdo = new \PDO( DBDRIVER.":host=".DBHOST.";port=".DBPORT.";dbname=".DBNAME
                ,DBUSER, DBPWD , [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING]);
        }catch (\Exception $e){
            die("Erreur SQL : ".$e->getMessage());
        }

        
        if(isset($class)){
            $this->class = $class;
        }

        //Si l'id n'est pas null alors on fait un update sinon on fait un insert
        $calledClassExploded = explode("\\",get_called_class());
        if(isset($table)){
            $this->table = strtolower(DBPREFIXE.($table));
        }else{
            $this->table = strtolower(DBPREFIXE.end($calledClassExploded));
        }



    }

    /**
     * @param int $id
     */
    public function setId(?int $id): object
    {
        $sql = "SELECT * FROM ".$this->table." WHERE id=".$id;
        $query = $this->pdo->query($sql);
        return $query->fetchObject(get_called_class());
    }

    public function save()
    {

        $columns = get_object_vars($this);
        $columns = array_diff_key($columns, get_class_vars(get_class()));

        if($this->getId() == null){
            $sql = "INSERT INTO ".$this->table." (".implode(",",array_keys($columns)).") 
            VALUES ( :".implode(",:",array_keys($columns)).")";
        }else{
            $update = [];
            foreach ($columns as $column=>$value)
            {
                $update[] = $column."=:".$column;
            }
            $sql = "UPDATE ".$this->table." SET ".implode(",",$update)." WHERE id=".$this->getId() ;

        }

        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute( $columns );
        $this->last_insert_id = $this->pdo->lastInsertId();
    }

    public function read()
    {
      
        $columns = get_object_vars($this);
        $columns = array_diff_key($columns, get_class_vars(get_class()));

        if($this->getId() == null){
            $sql = "SELECT * FROM students WHERE id = ?".$this->table." (".implode(",",array_keys($columns)).") 
            VALUES ( :".implode(",:",array_keys($columns)).")";
        }else{
            $update = [];
            foreach ($columns as $column=>$value)
            {
                $update[] = $column."=:".$column;
            }
            $sql = "UPDATE ".$this->table." SET ".implode(",",$update)." WHERE id=".$this->getId() ;

        }

    }
    
    public function delete(int $id = null): bool
    {
               
            if($id){
                $sql = "DELETE FROM ".$this->table." WHERE id = ?";
                $queryPrepared = $this->pdo->prepare($sql);
                $passed = $queryPrepared->execute( [$id] );
                return $passed; //Return true si requête reussie, sinon false
            }else{
                $sql = "DELETE FROM ".$this->table." WHERE id = ?";
                $queryPrepared = $this->pdo->prepare($sql);
                $passed = $queryPrepared->execute( [$this->id] );
                return $passed; //Return true si requête reussie, sinon false
            }
    
    }
  
    public function findByCustom(string $column,string $value)
    {
        $queryBuilder = new QueryBuilder();
        $sql = $queryBuilder
            ->select($this->table, ['*'])
            ->where($column, $value)
            ->limit(0, 1)
            ->getQuery();

            $query = $this->pdo->query($sql);
            
            if(isset($this->class)){
                return $query->fetchObject($this->class);
            }else{
                return $query->fetchObject(get_called_class());
            }
      
    }

    public function getAll($columnsToSelect = null)
    {
        $queryBuilder = new QueryBuilder();
        if($columnsToSelect != null && is_array($columnsToSelect)){ // verif if array
            $sql = $queryBuilder
            ->select($this->table, $columnsToSelect)
            ->getQuery();
        }else{
            $sql = $queryBuilder
            ->select($this->table, ['*'])
            ->getQuery();
        }
        
        $query = $this->pdo->query($sql);
       
        if($this->class){
            $resultQuery = $query->fetchAll($this->pdo::FETCH_CLASS,$this->class);
        }else{
            $resultQuery = $query->fetchAll($this->pdo::FETCH_CLASS,get_called_class());
        }
        // $objectsArray = [];
        // foreach($resultQuery as $result){
        //     $objectsArray[] = '';
        // }
        return $resultQuery;
       

    }

    public function getAllWhere($columnsToSelect = null, $whereArray)
    {
        $queryBuilder = new QueryBuilder();
        if($columnsToSelect != null && is_array($columnsToSelect)){ // verif if array
            $sql = $queryBuilder
            ->select($this->table, $columnsToSelect)
            ->where($whereArray[0], $whereArray[1])
            ->getQuery();
        }else{
            $sql = $queryBuilder
            ->select($this->table, ['*'])
            ->where($whereArray[0], $whereArray[1])
            ->getQuery();
        }
        
        $query = $this->pdo->query($sql);
       
        if($this->class){
            $resultQuery = $query->fetchAll($this->pdo::FETCH_CLASS,$this->class);
        }else{
            $resultQuery = $query->fetchAll($this->pdo::FETCH_CLASS,get_called_class());
        }
        // $objectsArray = [];
        // foreach($resultQuery as $result){
        //     $objectsArray[] = '';
        // }
        return $resultQuery;

    }

    public function getQuantityByDate(string $columnDate)
    {
        $sql = "SELECT DATE(" . $columnDate . ") as date, COUNT(*) as quantity FROM " . $this->table . " GROUP BY DATE(" . $columnDate . ");";
        
        $query = $this->pdo->query($sql);

        $resultQuery = $query->fetchAll();

        return $resultQuery;
    }

    //Function Find


//    public function findByMultipleCustom(string $column, $value)
//    {

//    }

}
