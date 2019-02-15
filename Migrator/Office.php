<?php
/**
 * Created by PhpStorm.
 * User: dvelop
 * Date: 14/02/19
 * Time: 12:15 PM
 */

require_once 'BaseMigrator.php';

use Alm\AlmArray;

class Office extends BaseMigrator
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

        $data = $this->query('select * from office', 'old');
        foreach ($data as $item){
            $this->insertData($item, $options['company_id']);
        }

        $this->setNexts($options['company_id']);

        $this->saveSyncMap('office');
    }

    private function insertData($data, $companyId){

        $sql = sprintf('INSERT INTO `office` VALUES (NULL,NULL,%s,NULL,%s,"%s","%s","%s","%s",%s,%s,1)',
                $companyId,
                AlmArray::get($data, 'head'),
                AlmArray::get($data, 'name'),
                AlmArray::get($data, 'address'),
                AlmArray::get($data, 'contact'),
                AlmArray::get($data, 'status'),
                $this->getStrValue($data, 'penalized_at'),
                $this->getStrValue($data, 'head_at')
            );

       $result = $this->insert($sql, 'new');
       $this->insertedIds[] = $this->db->lastId();

       $this->syncMap[] = array(
           'old' => $data['id'],
           'new' => $this->db->lastId()
       );

       dump(sprintf('Migrando office: %s', $data['name']));
       return $result;
    }

    private function setNexts(){

        $data = $this->query('select * from office', 'old');
        foreach ($data as $item){
            $sql = sprintf('update office set next_id = %s where id = %s', $this->oldIdToNewId($item['next_id']), $this->oldIdToNewId($item['id']));
            $this->insert($sql);
        }
    }

}