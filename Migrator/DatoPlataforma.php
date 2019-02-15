<?php
/**
 * Created by PhpStorm.
 * User: dvelop
 * Date: 14/02/19
 * Time: 12:15 PM
 */

require_once 'BaseMigrator.php';

use Alm\AlmArray;

class DatoPlataforma extends BaseMigrator
{

    public function __construct()
    {
        parent::__construct();
    }

    public function migrate($companyId){

        $data = $this->query('select * from dato_plataforma', 'old');
        foreach ($data as $item){
            $this->insertData($item, $companyId);
        }

        $this->saveSyncMap('dato_plataforma');
    }

    private function insertData($data){

       $userId = $this->oldIdToNewIdOther($data['user_id'], 'user');

       $sql = sprintf('INSERT INTO `dato_plataforma` VALUES (NULL,%s, %s, %s, %s, %s, %s)',
            $this->getStrValue($data, 'platform_id'),
            $this->getStrValue($data, 'nombre'),
            $this->getStrValue($data, 'valor'),
            $this->getStrValue($data, 'created_at'),
            $this->getStrValue($data, 'updated_at'),
            $userId
       );

       $result = $this->insert($sql, 'new');
       $this->insertedIds[] = $this->db->lastId();

       $this->syncMap[] = array(
           'old' => $data['id'],
           'new' => $this->db->lastId()
       );

       dump(sprintf('Migrando dato_plataforma: %s', $data['nombre']));
       return $result;
    }


}