<?php
/**
 * Created by PhpStorm.
 * User: dvelop
 * Date: 14/02/19
 * Time: 12:15 PM
 */

require_once 'BaseMigrator.php';

use Alm\AlmArray;

class Reviews extends BaseMigrator
{

    public function __construct()
    {
        parent::__construct();
    }

    public function migrate($options = []){

        parent::migrate($options);

        $data = $this->query('select * from review', 'old');
        foreach ($data as $item){
            $this->insertData($item);
        }

        $this->saveSyncMap('review');
    }

    private function insertData($data){

        $rsId = $this->oldIdToNewIdOther($data['review_site_id'], 'review_site');

        $comment = preg_replace('/\"/', '\\"', $data['comment']);

        $sql = sprintf('INSERT INTO `review` VALUES (NULL,%s,%s,%s,%s,%s,%s,%s,%s)',
                $rsId,
                $this->getStrValue($data, 'customer'),
                $this->getStrValue($data, 'punctuation'),
                "\"".$comment."\"",
                $this->getStrValue($data, 'created_at'),
                $this->getStrValue($data, 'hash'),
                $this->getStrValue($data, 'reply'),
                $this->getStrValue($data, 'rid')
            );

       $result = $this->insert($sql, 'new');
       $this->insertedIds[] = $this->db->lastId();

       $this->syncMap[] = array(
           'old' => $data['id'],
           'new' => $this->db->lastId()
       );

       dump(sprintf('Migrando review: %s', $data['comment']));
       return $result;
    }

}