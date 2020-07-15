<?php
declare(strict_types=1);

namespace Tests\GameFool;

use App\CardsDeck\Card;
use App\GameFool\Table;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Класс TableTest
 * @package Tests\GameFool
 * @author Sunny
 */
class TableTest extends TestCase
{
	/**
	 * Козырь
	 * @var Card|null
	 */
	private $trump = null;
	
	/**
	 * @inheritDoc
	 */
	protected function setUp(): void
	{
		$this->trump = new Card(Card::RANK_A, Card::SUIT_HEART);
	}
	
	/**
	 * Тестирование основ
	 */
	public function testBasics()
	{
		$t = new Table($this->trump);
		
		$c1 = new Card(Card::RANK_6, Card::SUIT_DIAMOND);
		$c2 = new Card(Card::RANK_6, Card::SUIT_PEAK);
		$c3 = new Card(Card::RANK_6, Card::SUIT_CLOVER);
		$b1 = new Card(Card::RANK_7, Card::SUIT_DIAMOND);
		$b2 = new Card(Card::RANK_6, Card::SUIT_HEART);
		
		$this->assertSame([], $t->getOpen());
		$this->assertSame([], $t->getRanks());
		$this->assertSame([], $t->cantBeat());
		
		// не можем побить, забираем карту на столе
		$t->addOpen($c1);
		$this->assertSame([$c1->get() => $c1, ], $t->getOpen());
		$this->assertSame([Card::RANK_6, ], $t->getRanks());
		$this->assertSame([$c1, ], $t->cantBeat());
		$this->assertSame([], $t->getOpen());
		$this->assertSame([], $t->getRanks());
		
		// можем побить в этот раз
		$t->addOpen($c1);
		$this->assertSame([$c1->get() => $c1, ], $t->getOpen());
		$this->assertSame([Card::RANK_6, ], $t->getRanks());
		$t->addBeat([$c1->get() => $b1, ]);
		$this->assertSame([], $t->getOpen());
		$this->assertSame([Card::RANK_6, Card::RANK_7, ], $t->getRanks());
		
		// подкидываем еще пару
		$t->addOpen($c2, $c3);
		$this->assertSame([$c2->get() => $c2, $c3->get() => $c3, ], $t->getOpen());
		$this->assertSame([Card::RANK_6, Card::RANK_7, ], $t->getRanks());
		
		// бьем первую
		$t->addBeat([$c2->get() => $b2, ]);
		$this->assertSame([$c3->get() => $c3, ], $t->getOpen());
		$this->assertSame([Card::RANK_6, Card::RANK_7, ], $t->getRanks());
		
		// вторую не можем, забираем все что на столе
		$this->assertEqualsCanonicalizing([$c1, $c2, $c3, $b1, $b2, ], $t->cantBeat());
		$this->assertSame([], $t->getOpen());
		$this->assertSame([], $t->getRanks());
	}
	
	/**
	 * Тест исключения: на столе нет переданной карты для битья/закрытия
	 */
	public function testException1()
	{
		$this->expectException(Exception::class);
		
		(new Table($this->trump))->addBeat([
			$this->trump->get()	=> $this->trump,
		]);
	}
	
	/**
	 * Тест исключения: попытка обмануть
	 */
	public function testException2()
	{
		$this->expectException(Exception::class);
		
		$d = new Card(Card::RANK_A, Card::SUIT_DIAMOND);
		
		(new Table($this->trump))
			->addOpen($d)
			->addBeat([
				$d->get()	=> new Card(Card::RANK_K, $d->getSuit()),
			])
		;
	}
}
