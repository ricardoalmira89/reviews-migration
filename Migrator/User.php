<?php
/**
 * Created by PhpStorm.
 * User: dvelop
 * Date: 14/02/19
 * Time: 12:15 PM
 */

require_once 'BaseMigrator.php';

use Alm\AlmArray;

class User extends BaseMigrator
{

    public function __construct()
    {
        parent::__construct();
    }

    public function migrate($options = []){

        parent::migrate($options);

        $data = $this->query('select * from usuario', 'old');
        foreach ($data as $item){
            $this->insertData($item);
        }

        $this->saveSyncMap('user');
    }

    public function assocCompany($id){

        $this->deleteSupers();

        $sql = sprintf('update usuario set company_id = %s where id in (%s)', $id, implode(',', $this->insertedIds));
        $this->insert($sql);
    }

    private function insertData($data){

        $roles = $data['roles'];
        $roles = preg_replace('/\"/', '\\"', $roles);

        $sql = sprintf('INSERT INTO `usuario` VALUES (%s,%s,"%s","%s","%s","%s",%s,"%s","%s",%s,%s,%s,"%s","%s",%s,%s,%s,%s,"%s",%s, NULL)',
            "NULL",
              "NULL",
                AlmArray::get($data, 'username', 'NULL'),
                AlmArray::get($data, 'username_canonical', 'NULL'),
                AlmArray::get($data, 'email', 'NULL'),
                AlmArray::get($data, 'email_canonical', 'NULL'),
                AlmArray::get($data, 'enabled', 1),
                AlmArray::get($data, 'salt', 'NULL'),
                AlmArray::get($data, 'password', 'NULL'),
                AlmArray::get($data, 'last_login', 'NULL'),
                AlmArray::get($data, 'confirmation_token', 'NULL'),
                AlmArray::get($data, 'password_requested_at', 'NULL'),
                $roles,
                AlmArray::get($data, 'nombre', 'NULL'),
                AlmArray::get($data, 'office_id', 'NULL'),
                $this->getStrValue($data, 'phone'),
                AlmArray::get($data, 'status', 1),
                "NULL", //company id por ahora null,
                '{\"pending.auth\":1,\"inactive.account\":1,\"unanswered.reviews\":1,\"new.invite\":1,\"new.review\":1,\"new.bad.review\":1,\"monthly.leaderboard\":1,\"weekly.leaderboard\":1,\"monthly.performance\":1,\"weekly.performance\":1,\"tagged.review\":1,\"new.user\":1,\"admin.authorized\":1,\"dashboard.completed\":1}',
                1
            );


       $exist = $this->existUsuario($data['username'],$data['phone']);
       if ($exist){
           dump(sprintf('Usuario ya existe %s', $data['username']));
           return;
       }


       $result = $this->insert($sql, 'new');
       $this->insertedIds[] = $this->db->lastId();

        $this->syncMap[] = array(
            'old' => $data['id'],
            'new' => $this->db->lastId()
        );

       dump(sprintf('Migrando usuario: %s', $data['username']));

       return $result;
    }

    private function existUsuario($username, $phone){
        $sql = sprintf('select id, email from usuario where username = "%s" or phone = "%s"', $username, $phone);
        $data = $this->query($sql, 'new');

        if (count($data) > 0)
            return $data[0]['id'];

        return false;
    }

    private function deleteSupers(){

        $without = [];
        foreach ($this->insertedIds as $userId){
            if (!$this->isSuper($userId))
                $without[] = $userId;
        }

        $this->insertedIds = $without;
    }

    private function isSuper($id){
        $sql = sprintf('select id, email, roles from usuario where id = %s', $id);
        $data = $this->query($sql, 'new' );

        $role = AlmArray::get($data,'0:roles');
        preg_match('/SUPER/', $role, $matches);
        return count($matches) > 0;

    }


}