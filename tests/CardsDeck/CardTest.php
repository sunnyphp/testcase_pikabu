<?php
declare(strict_types=1);

namespace Tests\CardsDeck;

use App\CardsDeck\Card;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Класс CardTest
 * @package Tests\CardsDeck
 * @author Sunny
 */
class CardTest extends TestCase
{
	/**
	 * Тестирование основ
	 */
	public function testBasics()
	{
		$t1 = new Card(Card::RANK_6, Card::SUIT_PEAK);
		$t2 = new Card(Card::RANK_7, Card::SUIT_PEAK);
		$t3 = new Card(Card::RANK_7, Card::SUIT_HEART);
		
		$this->assertSame(Card::RANK_6, $t1->getRank());
		$this->assertSame(Card::SUIT_PEAK, $t1->getSuit());
		$this->assertSame(Card::RANK_6.Card::SUIT_PEAK, $t1->get());
		
		// соответствие рубашек
		$this->assertTrue($t1->isSuit($t2));
		$this->assertTrue($t2->isSuit($t1));
		$this->assertFalse($t1->isSuit(null));
		$this->assertFalse($t2->isSuit(null));
		
		// ранг больше-меньше
		$this->assertTrue($t1->isLessRank($t2));
		$this->assertTrue($t1->isLessRank($t3));
		$this->assertFalse($t1->isGreaterRank($t2));
		$this->assertFalse($t1->isGreaterRank($t3));
		$this->assertFalse($t2->isLessRank($t1));
		$this->assertFalse($t3->isLessRank($t1));
		$this->assertTrue($t2->isGreaterRank($t1));
		$this->assertTrue($t3->isGreaterRank($t1));
		
		// соответствие рубашки и ранги больше-меньше
		$this->assertTrue($t1->isLess($t2));
		$this->assertFalse($t1->isLess($t3));
		$this->assertFalse($t1->isGreater($t2));
		$this->assertFalse($t1->isGreater($t3));
		$this->assertFalse($t2->isLess($t1));
		$this->assertFalse($t3->isLess($t1));
		$this->assertTrue($t2->isGreater($t1));
		$this->assertFalse($t3->isGreater($t1));
	}
	
	/**
	 * Тест исключения: некорректный ранг карты
	 */
	public function testException1()
	{
		$this->expectException(Exception::class);
		
		new Card('abc', Card::SUIT_PEAK);
	}
	
	/**
	 * Тест исключения: некорректная рубашка карты
	 */
	public function testException2()
	{
		$this->expectException(Exception::class);
		
		new Card(Card::RANK_A, 'abc');
	}
}
