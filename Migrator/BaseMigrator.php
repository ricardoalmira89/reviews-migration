<?php

require_once __DIR__.'/../Db.php';

class BaseMigrator
{

    protected $db;

    public $insertedIds = [];
    protected $syncMap = [];
    private $syncPath = __DIR__.'/../var/';

    public function __construct()
    {
        $this->db = new Db();
    }

    public function query($sql, $schema = 'old'){
        return $this->db->query($sql, $schema);
    }

    public function insert($sql, $schema = 'new'){
        return $this->db->insert($sql, $schema);
    }

    public function getStrValue($data, $key, $failReturn = 'NULL'){
        $elem = \Alm\AlmArray::get($data, $key);
        $elem = ($elem !== null) ? sprintf("\"%s\"", $elem) : $failReturn;

        return $elem;
    }

    protected function saveSyncMap($entity){

        if (!file_exists($this->syncPath.$this->db->getSource())) {
            mkdir($this->syncPath.$this->db->getSource(), 0777);
        } else {
            //exist
        }

        \Alm\AlmArray::saveToFile($this->syncMap, $this->syncPath.$this->db->getSource().'/'.$entity);
    }

    protected function loadSyncMap($entity){
        $this->syncMap = \Alm\AlmArray::loadFromFile($this->syncPath.$this->db->getSource().'/'.$entity);
    }

    protected function oldIdToNewId($old){

        foreach ($this->syncMap as $key => $item){
            if ($item['old'] == $old)
                return $item['new'];
        }

        return null;
    }

    public function oldIdToNewIdOther($old, $entity){

        $map = \Alm\AlmArray::loadFromFile($this->syncPath.$this->db->getSource().'/'.$entity);

        foreach ($map as $key => $item){
            if ($item['old'] == $old)
                return $item['new'];
        }

        return null;
    }

    private function configSources($options = []){

        \Alm\AlmValidator::validate($options, array(
            'source' => 'req',
            'destination' => 'req'
        ));

        $this->db->setSource($options['source']);
        $this->db->setDestination($options['destination']);

    }

    protected function migrate($options = []){
        $this->configSources($options);
    }

}