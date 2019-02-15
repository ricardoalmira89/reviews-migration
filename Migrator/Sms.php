<?php
/**
 * Created by PhpStorm.
 * User: dvelop
 * Date: 14/02/19
 * Time: 12:15 PM
 */

require_once 'BaseMigrator.php';

use Alm\AlmArray;

class Sms extends BaseMigrator
{

    public function __construct()
    {
        parent::__construct();
    }

    public function migrate($options = []){

        parent::migrate($options);

        $data = $this->query('select * from sms', 'old');
        foreach ($data as $item){
            $this->insertData($item);
        }

        $this->saveSyncMap('sms');
    }

    private function insertData($data){

        $customerId = $this->oldIdToNewIdOther($data['customer_id'], 'customer');
        $senderId = $this->oldIdToNewIdOther($data['sender_id'], 'user');
        $review_id = $this->oldIdToNewIdOther($data['review_id'], 'review');

        if (!$review_id)
            $review_id = 'NULL';

        if (!$senderId)
            $senderId = 'NULL';

        $sql = sprintf('INSERT INTO `sms` VALUES (NULL,%s,%s,%s,%s,%s,%s,%s,%s,%s)',
                $customerId,
                $this->getStrValue($data, 'body'),
                $this->getStrValue($data, 'date_sent'),
                $senderId,
                $this->getStrValue($data, 'cell'),
                $this->getStrValue($data, 'email'),
                $this->getStrValue($data, 'reviewed_at'),
                $review_id,
                $this->getStrValue($data, 'auto')
            );

       $result = $this->insert($sql, 'new');
       $this->insertedIds[] = $this->db->lastId();

       $this->syncMap[] = array(
           'old' => $data['id'],
           'new' => $this->db->lastId()
       );

       dump(sprintf('Migrando sms: %s', $data['body']));
       return $result;
    }

}