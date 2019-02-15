<?php
/**
 * Created by PhpStorm.
 * User: dvelop
 * Date: 14/02/19
 * Time: 12:15 PM
 */

require_once 'BaseMigrator.php';

use Alm\AlmArray;

class Tag extends BaseMigrator
{

    public function __construct()
    {
        parent::__construct();
    }

    public function migrate($options = []){

        parent::migrate($options);

        $data = $this->query('select * from tag_usuario_review', 'old');
        foreach ($data as $item){
            $this->insertData($item);
        }

    }

    private function insertData($data){

        $reviewId = $this->oldIdToNewIdOther($data['review_id'], 'review');
        $userId = $this->oldIdToNewIdOther($data['user_id'], 'user');

        $sql = sprintf('INSERT INTO `tag_usuario_review` VALUES (%s,%s)',
                $reviewId,
                $userId
            );

       $result = $this->insert($sql, 'new');
       $this->insertedIds[] = $this->db->lastId();

       dump(sprintf('Migrando tag: %s', $data['user_id']));
       return $result;
    }

}