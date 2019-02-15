<?php
/**
 * Created by PhpStorm.
 * User: dvelop
 * Date: 14/02/19
 * Time: 12:15 PM
 */

require_once 'BaseMigrator.php';

use Alm\AlmArray;

class BlackList extends BaseMigrator
{

    public function __construct()
    {
        parent::__construct();
    }

    public function migrate(){

        $data = $this->query('select * from blacklist', 'old');
        foreach ($data as $item){
            $this->insertData($item);
        }

        $this->saveSyncMap('blacklist');
    }

    private function insertData($data){

        $customerId = $this->oldIdToNewIdOther($data['customer_id'], 'customer');

        $sql = sprintf('INSERT INTO `blacklist` VALUES (NULL,%s,%s)',
                $customerId,
                $this->getStrValue($data, 'since')
            );

       $result = $this->insert($sql, 'new');
       $this->insertedIds[] = $this->db->lastId();

       $this->syncMap[] = array(
           'old' => $data['id'],
           'new' => $this->db->lastId()
       );

       dump(sprintf('Migrando blacklist: %s', $data['id']));
       return $result;
    }

}