<?php
/**
 * Created by PhpStorm.
 * User: dvelop
 * Date: 14/02/19
 * Time: 12:15 PM
 */

require_once 'BaseMigrator.php';

use Alm\AlmArray;

class Configuration extends BaseMigrator
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

        $data = $this->query('select * from configuration', 'old');
        foreach ($data as $item){
            $this->insertData($item, $options['company_id']);
        }

        $this->saveSyncMap('configuration');
    }

    private function insertData($data, $companyId){

        $sql = sprintf('INSERT INTO `configuration` VALUES (NULL,%s,%s,%s,%s,%s)',
                $this->getStrValue($data, '_name'),
                $this->getStrValue($data, '_value'),
                $this->getStrValue($data, 'created_at'),
                $this->getStrValue($data, 'updated_at'),
                $companyId
            );

       $result = $this->insert($sql, 'new');
       $this->insertedIds[] = $this->db->lastId();

       dump(sprintf('Migrando configuration: %s', $data['_name']));
       return $result;
    }

}