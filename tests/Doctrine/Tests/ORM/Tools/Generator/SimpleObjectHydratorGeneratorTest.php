<?php

namespace Doctrine\Tests\ORM\Tools\Generator;

use Doctrine\Tests\OrmFunctionalTestCase;
use Doctrine\ORM\Tools\Generator\SimpleObjectHydratorGenerator;
use Doctrine\ORM\Query\ResultSetMapping;

class SimpleObjectHydratorGeneratorTest extends OrmFunctionalTestCase
{
    /**
     * @test
     */
    public function testGenerateHydrator()
    {
        $generator = new SimpleObjectHydratorGenerator(
            $this->_em->getConnection()->getDatabasePlatform(),
            $this->_em->getMetadataFactory()
        );

        $rsm = new ResultSetMapping();
        $rsm->addEntityResult('Doctrine\Tests\Models\CMS\CmsUser', 'r');
        $rsm->addFieldResult('r', 'username0', 'username');
        $rsm->addFieldResult('r', 'name1', 'name');

        $rsm = $this->_em->getUnitOfWork()->getEntityPersister('Doctrine\Tests\Models\CMS\CmsAddress')->getResultSetMapping();

        $generator->generate($rsm);
    }
}
