<?php
/**
 * Created by PhpStorm.
 * User: dvelop
 * Date: 14/02/19
 * Time: 12:15 PM
 */

require_once 'BaseMigrator.php';

use Alm\AlmArray;

class ReviewSite extends BaseMigrator
{

    public function __construct()
    {
        parent::__construct();
    }

    public function migrate($options = []){

        parent::migrate($options);

        $data = $this->query('select * from review_site', 'old');
        foreach ($data as $item){
            $this->insertData($item);
        }

        $this->saveSyncMap('review_site');
    }

    private function insertData($data){

        $officeId = $this->oldIdToNewIdOther($data['office_id'], 'office');

        $sql = sprintf('INSERT INTO `review_site` VALUES (NULL,%s,%s,%s,%s,%s,%s)',
                $this->getStrValue($data, 'url'),
                $this->getStrValue($data, 'updated_at'),
                $this->getStrValue($data, 'platform_id'),
                $officeId,
                $this->getStrValue($data, 'created_at'),
                $this->getStrValue($data, 'page_id')
            );

       $result = $this->insert($sql, 'new');
       $this->insertedIds[] = $this->db->lastId();

       $this->syncMap[] = array(
           'old' => $data['id'],
           'new' => $this->db->lastId()
       );

       dump(sprintf('Migrando review_site: %s', $data['page_id']));
       return $result;
    }

}