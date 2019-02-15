<?php
/**
 * Created by PhpStorm.
 * User: dvelop
 * Date: 14/02/19
 * Time: 04:52 PM
 */

require_once 'User.php';
require_once 'Company.php';
require_once 'Office.php';
require_once 'ReviewSite.php';
require_once 'Template.php';
require_once 'DatoPlataforma.php';
require_once 'Configuration.php';
require_once 'Customer.php';
require_once 'Reviews.php';
require_once 'Sms.php';
require_once 'BlackList.php';
require_once 'Tag.php';

class Migrator
{
    private $options = [];

    public function __construct($options = [])
    {
        $this->options = $options;

        \Alm\AlmValidator::validate($options, array(
            'source' => 'req',
            'destination' => 'req'
        ));

    }

    public function migrate(){

        $u = new User();
        $u->migrate($this->options);

        $c = new Company();
        $c->migrate($this->options);

        $this->options['company_id'] = $c->getId();
        $u->assocCompany($c->getId());

        $o = new Office();
        $o->migrate($this->options);

        $rs = new ReviewSite();
        $rs->migrate($this->options);

        $t = new Template();
        $t->migrate($this->options);

        $dp = new DatoPlataforma();
        $dp->migrate($this->options);

        $config = new Configuration();
        $config->migrate($this->options);

        $config = new Customer();
        $config->migrate($this->options);

        $config = new Reviews();
        $config->migrate($this->options);

        $config = new Sms();
        $config->migrate($this->options);

        $config = new BlackList();
        $config->migrate($this->options);

        $config = new Tag();
        $config->migrate($this->options);
    }

}