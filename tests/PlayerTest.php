<?php
declare(strict_types=1);

namespace Tests;

use App\Player;
use Countable;
use PHPUnit\Framework\TestCase;

/**
 * Класс PlayerTest
 * @package Tests
 * @author Sunny
 */
class PlayerTest extends TestCase
{
	/**
	 * Тестирование основ
	 */
	public function testBasics()
	{
		$t = new Player('a');
		
		$this->assertSame('a', $t->getName());
		$this->assertSame('', $t->getCards());
		
		$this->assertSame('a(No cards)', (string)$t);
		$this->assertInstanceOf(Countable::class, $t);
		$this->assertSame(0, count($t));
	}
}
