<?php

namespace VitorHugoRo\Tests;

use PHPUnit\Framework\TestCase;
use VitorHugoRo\Deta\Deta;
use VitorHugoRo\Deta\Item;
use VitorHugoRo\Deta\Responses\QueryResponse;

class DetaTest extends TestCase
{
    protected Deta $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new Deta(
            "include an key here",
            "include an key here",
            "items"
        );
    }

    /** @test */
    public function canGetItemsFromBase()
    {
        $temporaryBase = rand();

        $this->service->setBaseName($temporaryBase);

        foreach (range(1, 3) as $i) {
            $this->service->insert([
                'field1' => $i
            ]);
        }

        $response = $this->service->fetch();

        $this->assertInstanceOf(QueryResponse::class, $response);

        $this->assertInstanceOf(Item::class, $response->first());

        // $this->assertContains(1, $response->first()->getBody());

        $this->assertCount(3, $response->items());

        $this->service->setBaseName('items');
    }

    /** @test */
    public function canInsertOnBase()
    {
        $response = $this->service->insert([
            'field1' => '123'
        ]);

        $this->assertInstanceOf(Item::class, $response);

        $this->assertNotNull($response->getKey());
        $this->assertEquals(['field1' => '123'], $response->getBody());
    }

    /** @test */
    public function canUpdateAnItem()
    {
        $item = $this->service->insert([
            'setField' => '123',
            'incrementField' => 1,
            'appendField' => ['item'],
            'prependField' => ['some', 'items'],
            'deleteField' => 'something'
        ]);

        $updatedItem = $this->service->update(
            $item->getKey(),
            [
                'setField' => '321'
            ],
            [
                'incrementField' => 1
            ],
            [
                'appendField' => ['something']
            ],
            [
                'prependField' => ['items']
            ],
            [
                'deleteField'
            ]
        );

        $updatedBody = $updatedItem->getBody();

        $this->assertArrayNotHasKey('deleteField', $updatedBody);

        $this->assertEquals('321', $updatedBody['setField']);
        $this->assertEquals(2, $updatedBody['incrementField']);
        $this->assertEquals(['items', 'some', 'items'], $updatedBody['prependField']);
        $this->assertEquals(['item', 'something'], $updatedBody['appendField']);
    }

    /** @test */
    public function canSeeItem()
    {
        $item = $this->service->fetch()->first();

        $gettedItem = $this->service->get($item->getKey());

        $this->assertEquals($item, $gettedItem);
    }

    /** @test */
    public function canDeleteItem()
    {
        $item = $this->service->fetch()->first();

        $deleted = $this->service->delete($item->getKey());

        $this->assertTrue($deleted);
    }
}
