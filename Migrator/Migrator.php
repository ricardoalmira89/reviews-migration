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
    private $arg;

    public function __construct($arg)
    {
        $this->arg = $arg;
    }

    public function migrate(){

        $u = new User();
        $u->migrate();

        $c = new Company();
        $c->migrate();

        $cid = $c->getId();
        //$cid = 47;

        $u->assocCompany($cid);

        $o = new Office();
        $o->migrate($cid);

        $rs = new ReviewSite();
        $rs->migrate();

        $t = new Template();
        $t->migrate($cid);

        $dp = new DatoPlataforma();
        $dp->migrate($cid);


        $config = new Configuration();
        $config->migrate($cid);

        $config = new Customer();
        $config->migrate($cid);

        $config = new Reviews();
        $config->migrate();

        $config = new Sms();
        $config->migrate();

        $config = new BlackList();
        $config->migrate();

        $config = new Tag();
        $config->migrate();
    }

}