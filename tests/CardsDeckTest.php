<?php
declare(strict_types=1);

namespace Tests;

use App\CardsDeck;
use Countable;
use PHPUnit\Framework\TestCase;

/**
 * Класс CardsDeckTest
 * @package Tests
 * @author Sunny
 */
class CardsDeckTest extends TestCase
{
	/**
	 * Тестирование основ
	 */
	public function testBasics()
	{
		$seed = mt_rand(100, 999);
		$t = new CardsDeck($seed);
		
		$this->assertSame($t->getSeed(), $seed);
		$this->assertNull($t->take());
		$this->assertNull($t->takeTrump());
		
		$this->assertSame('', (string)$t);
		$this->assertInstanceOf(Countable::class, $t);
		$this->assertSame(0, count($t));
	}
}
