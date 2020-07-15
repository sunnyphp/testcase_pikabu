<?php
declare(strict_types=1);

namespace Tests;

use App\CardsDeck;
use App\GameFool;
use App\Player;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Класс GameFoolTest
 * @package Tests
 * @author Sunny
 */
class GameFoolTest extends TestCase
{
	/**
	 * Тестирование основ
	 */
	public function testBasics()
	{
		// выполняем
		$result = (new GameFool())
		(new Player('Rick'))
		(new Player('Morty'))
		(new Player('Summer'))
		(new CardsDeck(24227))
		();
		
		// сверяем с эталоном
		$this->assertSame('Rick', $result);
	}
	
	/**
	 * Тест исключения: меньше двух игроков
	 */
	public function testException1()
	{
		$this->expectException(Exception::class);
		
		(new GameFool)
			->addPlayer(new Player('a'))
			->setDeck(new CardsDeck(1))
			->run()
		;
	}
	
	/**
	 * Тест исключения: больше четырех игроков
	 */
	public function testException2()
	{
		$this->expectException(Exception::class);
		
		(new GameFool)
			->addPlayer(new Player('a'))
			->addPlayer(new Player('b'))
			->addPlayer(new Player('c'))
			->addPlayer(new Player('d'))
			->addPlayer(new Player('e'))
			->setDeck(new CardsDeck(1))
			->run()
		;
	}
	
	/**
	 * Тест исключения: нет колоды карт
	 */
	public function testException3()
	{
		$this->expectException(Exception::class);
		
		(new GameFool)
			->addPlayer(new Player('a'))
			->addPlayer(new Player('b'))
			->run()
		;
	}
}
