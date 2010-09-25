<?php

namespace Doctrine\Tests\ORM\Tools;

require_once __DIR__ . '/../../TestInit.php';

class DisconnectedClassMetadataFactoryTest extends \Doctrine\Tests\OrmTestCase
{
    public function testSettingNamespace()
    {
        $cmf = new \Doctrine\ORM\Tools\DisconnectedClassMetadataFactory($this->_getTestEntityManager());
        
        $class = $cmf->getMetadataFor('Doctrine\Tests\Models\CMS\CmsUser');
        $this->assertType('Doctrine\ORM\Mapping\ClassMetadataInfo', $class);

        $this->assertEquals('Doctrine\Tests\Models\CMS', $class->namespace);
    }
}