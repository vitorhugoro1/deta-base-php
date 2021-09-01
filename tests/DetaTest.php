<?php

namespace VitorHugoRo\Tests;

use PHPUnit\Framework\TestCase;
use VitorHugoRo\Deta\Deta;
use VitorHugoRo\Deta\DetaRequest;
use VitorHugoRo\Deta\Item;
use VitorHugoRo\Deta\Responses\QueryResponse;

class DetaTest extends TestCase
{
    public function testCanGetItemsFromBase()
    {
        /** @var DetaRequest|\PHPUnit\Framework\MockObject\MockObject */
        $detaRequesterMock = $this->createMock(DetaRequest::class);

        $detaRequesterMock->expects($this->once())
            ->method('request')
            ->willReturn([
                'paging' => [
                    'size' => 2,
                    'last' => null
                ],
                'items' => [
                    [
                        'key' => mt_rand(),
                        'field1' => 1
                    ],
                    [
                        'key' => mt_rand(),
                        'field1' => 2
                    ],
                ]
            ]);

        $service = new Deta(
            "include an key here",
            "include an key here",
            "items",
            $detaRequesterMock
        );

        $service->setBaseName(rand());

        $response = $service->fetch();

        $this->assertInstanceOf(QueryResponse::class, $response);

        $this->assertInstanceOf(Item::class, $response->first());

        $this->assertContains(1, $response->first()->getBody());

        $this->assertCount(2, $response->items());
    }

    /** @test */
    public function canInsertOnBase()
    {
        /** @var DetaRequest|\PHPUnit\Framework\MockObject\MockObject */
        $detaRequesterMock = $this->createMock(DetaRequest::class);

        $baseName = rand();

        $detaRequesterMock->expects($this->once())
            ->method('request')
            ->with(
                $this->equalTo("POST"),
                $this->equalTo("{$baseName}/items")
            )
            ->willReturn([
                'key' => 1,
                'field1' => '123'
            ]);

        $service = new Deta(
            "include an key here",
            "include an key here",
            "items",
            $detaRequesterMock
        );

        $service->setBaseName($baseName);

        $inputData = [
            'key' => 1,
            'field1' => '123'
        ];

        $response = $service->insert($inputData);

        $this->assertInstanceOf(Item::class, $response);

        $this->assertNotNull($response->getKey());
        $this->assertEquals(['field1' => '123'], $response->getBody());
    }

    /** @test */
    public function canUpdateAnItem()
    {
        /** @var DetaRequest|\PHPUnit\Framework\MockObject\MockObject */
        $detaRequesterMock = $this->createMock(DetaRequest::class);

        $detaRequesterMock->expects($this->any())
            ->method('request')
            ->willReturn(
                [
                    'key' => 1,
                    'set' => [
                        'setField' => '321'
                    ],
                    'increment' => [
                        'incrementField' => 1
                    ],
                    'append' => [
                        'appendField' => ['something']
                    ],
                    'prepend' => [
                        'prependField' => ['items']
                    ],
                    'delete' => [
                        'deleteField'
                    ],
                ],
                [
                    'key' => 1,
                    'setField' => '321',
                    'incrementField' => 2,
                    'appendField' => ['item', 'something'],
                    'prependField' => ['items', 'some', 'items']
                ]
            );

        $service = new Deta(
            "include an key here",
            "include an key here",
            "items",
            $detaRequesterMock
        );

        $service->setBaseName(rand());

        $updatedItem = $service->update(
            1,
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
        /** @var DetaRequest|\PHPUnit\Framework\MockObject\MockObject */
        $detaRequesterMock = $this->createMock(DetaRequest::class);

        $baseName = rand();

        $detaRequesterMock->expects($this->any())
            ->method('request')
            ->willReturn(
                [
                    'paging' => [
                        'size' => 2,
                        'last' => null
                    ],
                    'items' => [
                        [
                            'key' => 1,
                            'field1' => 1
                        ],
                        [
                            'key' => mt_rand(),
                            'field1' => 2
                        ],
                    ]
                ],
                [
                    'key' => 1,
                    'field1' => 1
                ]
            );

        $service = new Deta(
            "include an key here",
            "include an key here",
            "items",
            $detaRequesterMock
        );

        $service->setBaseName($baseName);

        $item = $service->fetch()->first();

        $gettedItem = $service->get($item->getKey());

        $this->assertEquals($item, $gettedItem);
    }

    /** @test */
    public function canDeleteItem()
    {
        /** @var DetaRequest|\PHPUnit\Framework\MockObject\MockObject */
        $detaRequesterMock = $this->createMock(DetaRequest::class);

        $detaRequesterMock->expects($this->once())
            ->method('request')
            ->willReturn([
                'key' => "1"
            ]);

        $service = new Deta(
            "include an key here",
            "include an key here",
            "items",
            $detaRequesterMock
        );

        $service->setBaseName(rand());

        $deleted = $service->delete("1");

        $this->assertTrue($deleted);
    }
}
