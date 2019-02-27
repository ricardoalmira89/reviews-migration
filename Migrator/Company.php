<?php
/**
 * Created by PhpStorm.
 * User: dvelop
 * Date: 14/02/19
 * Time: 12:15 PM
 */

require_once 'BaseMigrator.php';

use Alm\AlmArray;

class Company extends BaseMigrator
{

    public function __construct()
    {
        parent::__construct();
    }

    public function migrate($options = []){

        parent::migrate($options);

        $data = $this->query('select * from company', 'old');

        foreach ($data as $item){
            $this->insertData($item);
        }

        $this->saveSyncMap('company');

    }

    public function getId(){
        return $this->insertedIds[0];
    }

    private function insertData($data)
    {

        $localId = $this->getLocalOwnerId($data['user_id']);

        $sql = sprintf('INSERT INTO `company` (id,name,created_at,owner_id,status) VALUES (NULL,"%s","%s",%s,%s)',
            AlmArray::get($data, 'name'),
            AlmArray::get($data, 'created_at'),
            $localId,
            1
        );

        $result = $this->insert($sql, 'new');
        $this->insertedIds[] = $this->db->lastId();

        $this->syncMap[] = array(
            'old' => $data['id'],
            'new' => $this->db->lastId()
        );

        dump(sprintf('Migrando company: %s', $data['name']));

        return $result;
    }

    private function getLocalOwnerId($user_id){

        $sql = 'select username from usuario where id = '.$user_id;
        $username = $this->query($sql)[0]['username'];


        $sql = sprintf('select id from usuario where username = "%s"', $username);
        $localId = (integer)$this->query($sql, 'new')[0]['id'];

        return $localId;
    }

}