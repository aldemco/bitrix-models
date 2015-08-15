<?php

namespace Arrilot\Tests\BitrixModels;

use Arrilot\BitrixModels\Queries\SectionQuery;
use Arrilot\Tests\BitrixModels\Stubs\TestSection;
use Mockery as m;

class SectionQueryTest extends TestCase
{
    public function tearDown()
    {
        m::close();
    }

    /**
     * Create testing object.
     *
     * @param $bxObject
     *
     * @return SectionQuery
     */
    protected function createQuery($bxObject)
    {
        TestSection::$bxObject = m::mock('object');

        return new SectionQuery($bxObject, 'Arrilot\Tests\BitrixModels\Stubs\TestSection');
    }

    public function testGetListWithScopes()
    {
        $bxObject = m::mock('object');
        TestSection::$bxObject = $bxObject;
        $bxObject->shouldReceive('getList')->with(
            ['SORT' => 'ASC'],
            ['NAME'=>'John', 'ACTIVE'=>'Y', 'IBLOCK_ID'=>1],
            false,
            ['ID','NAME'],
            false
        )->once()->andReturn(m::self());
        $bxObject->shouldReceive('fetch')->andReturn(['ID' => 1, 'NAME' => 'foo'], ['ID' => 2, 'NAME' => 'bar'], false);

        $query = $this->createQuery($bxObject);
        $items = $query->sort(['SORT' => 'ASC'])->filter(['NAME' => 'John'])->active()->select('ID', 'NAME')->getList();

        $expected = [
            ['ID' => 1, 'NAME' => 'foo'],
            ['ID' => 2, 'NAME' => 'bar'],
        ];
        foreach ($items as $k => $item) {
            $this->assertSame($expected[$k], $item->toArray());
        }
    }
}
