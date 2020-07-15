<?php
declare(strict_types=1);

namespace App;

use App\CardsDeck\Card;
use Countable;
use Exception;

/**
 * Класс CardsDeck реализует колоду карт
 * @package App
 * @author Sunny
 */
class CardsDeck implements Countable
{
	/**
	 * Случайное число по которому определяется исход игры
	 * @var int
	 */
	private $seed;
	
	/**
	 * Массив карт в колоде
	 * @var Card[]
	 */
	private $collection = [];
	
	/**
	 * Конструктор класса
	 * @param int $seed
	 */
	public function __construct(int $seed)
	{
		$this->seed = $seed;
	}
	
	/**
	 * Возвращает количество оставшихся карт в колоде
	 * @inheritDoc
	 */
	public function count()
	{
		return count($this->collection);
	}
	
	/**
	 * Магический метод для возврата строки
	 * @return string
	 */
	public function __toString()
	{
		return implode(',', $this->collection);
	}
	
	/**
	 * Возвращает случайное число по которому определяется исход игры
	 * @return int
	 */
	public function getSeed(): int
	{
		return $this->seed;
	}
	
	/**
	 * Устанавливает случайное число по которому определяется исход игры
	 * @param int $seed
	 * @return CardsDeck
	 */
	public function setSeed(int $seed): CardsDeck
	{
		$this->seed = $seed;
		
		return $this;
	}
	
	/**
	 * Перемешивает колоду в соответствии со случайным числом
	 * @return $this
	 * @throws Exception
	 */
	public function shuffle(): CardsDeck
	{
		// заполняем эталонную колоду
		static $reference = [];
		if ($reference === []) {
			foreach (Card::getDeckSuits() as $suit) {
				foreach (Card::getRanks() as $rank) {
					$reference[] = new Card((string)$rank, (string)$suit);
				}
			}
		}
		
		// устанавливаем эталон
		$this->collection = $reference;
		
		// сортируем в соответствии со случайным числом
		for ($i=0, $c=count($reference); $i<1000; $i++) {
			$n = ($this->seed + $i * 2) % $c;
			$card = $this->collection[$n];
			unset($this->collection[$n]);
			array_unshift($this->collection, $card);
		}
		
		return $this;
	}
	
	/**
	 * Возвращает карту из начала колоды или NULL
	 * @return Card|null
	 */
	public function take(): ?Card
	{
		return array_shift($this->collection);
	}
	
	/**
	 * Возвращает козырь из начала колоды и перемещает его в конец колоды или NULL если нет карт
	 * @return Card|null
	 */
	public function takeTrump(): ?Card
	{
		if (($trump = $this->take()) !== null) {
			$this->collection[] = $trump;
		}
		
		return $trump;
	}
}
