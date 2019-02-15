<?php
/**
 * Created by PhpStorm.
 * User: dvelop
 * Date: 14/02/19
 * Time: 12:15 PM
 */

require_once 'BaseMigrator.php';

use Alm\AlmArray;

class Customer extends BaseMigrator
{

    public function __construct()
    {
        parent::__construct();
    }

    public function migrate($options = []){

        parent::migrate($options);

        \Alm\AlmValidator::validate($options, array(
            'company_id' => 'req'
        ));

        $data = $this->query('select * from customer', 'old');
        foreach ($data as $item){
            $this->insertData($item, $options['company_id']);
        }

        $this->saveSyncMap('customer');
    }

    private function insertData($data, $companyId){

        $sql = sprintf('INSERT INTO `customer` VALUES (NULL,%s,%s,%s,%s)',
                $companyId,
                $this->getStrValue($data, 'name'),
                $this->getStrValue($data, 'email'),
                $this->getStrValue($data, 'cell')
            );

       $result = $this->insert($sql, 'new');
       $this->insertedIds[] = $this->db->lastId();

       $this->syncMap[] = array(
           'old' => $data['id'],
           'new' => $this->db->lastId()
       );

       dump(sprintf('Migrando customer: %s', $data['name']));
       return $result;
    }

}