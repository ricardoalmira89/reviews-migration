<?php
/**
 * Created by PhpStorm.
 * User: dvelop
 * Date: 14/02/19
 * Time: 12:00 PM
 */

require_once 'vendor/autoload.php';

class Db
{

    public $link;

    private $config = array(
        'old' => [
            'user' => 'root',
            'password' => 'S1nchr0ny',
            'host' => 'vpc-rds-prod.csxf0hqn0tzd.us-east-1.rds.amazonaws.com',
            'db' => 'miracle'
        ],
        'new' => [
            'user' => 'root',
            'password' => 'S1nchr0ny',
            'host' => '3.86.220.36',
            'db' => 'migration'
        ]
    );

    public function __construct($options = [])
    {

    }

    public function query($sql, $schema = 'old'){
        $config = $this->config[$schema];
        $this->link = mysqli_connect($config['host'], $config['user'], $config['password'], $config['db']);

        $data = mysqli_query($this->link, $sql);

        return mysqli_fetch_all($data, 1) ;
    }

    public function insert($sql, $schema = 'new'){
        $config = $this->config[$schema];
        $this->link = mysqli_connect($config['host'], $config['user'], $config['password'], $config['db']);

        $result = mysqli_query($this->link, $sql);

        if (!$result){
            dump(array(
                'error' => mysqli_error($this->link),
                'query' => $sql,
                'schema' => $schema
            ));
        }


        return $result;
    }

    public function lastId(){
        return mysqli_insert_id($this->link);
    }

    public function setSource($source){
        $this->config['old']['db'] = $source;
    }

    public function getSource(){
        return $this->config['old']['db'];
    }

    public function setDestination($source){
        $this->config['new']['db'] = $source;
    }

    public function getDestination(){
        return $this->config['new']['db'];
    }

}