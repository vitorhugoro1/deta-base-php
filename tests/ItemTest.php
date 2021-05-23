<?php

namespace VitorHugoRo\Tests;

use PHPUnit\Framework\TestCase;
use VitorHugoRo\Deta\Exceptions\RequiredItemFieldException;
use VitorHugoRo\Deta\Item;

class ItemTest extends TestCase
{
    /** @test */
    public function canBuildFromResponse()
    {
        $item = Item::fromResponse([
            'key' => '321',
            'field1' => '123'
        ]);

        $this->assertEquals('321', $item->getKey());

        $this->assertArrayHasKey('field1', $item->getBody());
        $this->assertContains('123', $item->getBody());
    }

    /** @test */
    public function keyIsRequiredField()
    {
        $this->expectException(RequiredItemFieldException::class);
        $this->expectExceptionMessage("Not found field key on response.");

        Item::fromResponse([
            'field1' => '123'
        ]);
    }
}
