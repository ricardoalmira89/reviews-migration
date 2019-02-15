<?php
/**
 * Created by PhpStorm.
 * User: dvelop
 * Date: 14/02/19
 * Time: 12:15 PM
 */

require_once 'BaseMigrator.php';

use Alm\AlmArray;

class Template extends BaseMigrator
{

    public function __construct()
    {
        parent::__construct();
    }

    public function migrate($companyId){

        $elements = array(
            sprintf('insert into template values (NULL, "%s", "%s", %s, %s )',
                "First",
                "Hi, :customer: Thank you for choosing :company:. Could you take 30 seconds to leave us a review using the link below? Thanks :link:",
                1,
                $companyId),
            sprintf('insert into template values (NULL, "%s", "%s", %s, %s )',
                "Second",
                "Hi :customer:, Thank you for choosing :company:. Just wanted to remind you to leave us a review on Google or Facebook using the link below when you get a second. It would really help us out, thanks again! :link:",
                2,
                $companyId),
            sprintf('insert into template values (NULL, "%s", "%s", %s, %s )',
                "Third",
                "Hi :customer: Thank you again for choosing :company:. This is the last time we will reach out to you. When you get a second please leave us a review using the link below, thank you! :link:",
                3,
                $companyId)
        );

        foreach ($elements as $item){
            $this->insert($item);
        }

        dump('Migrando templates');
    }

   

}