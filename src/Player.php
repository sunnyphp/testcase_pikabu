<?php
declare(strict_types=1);

namespace App;

use App\CardsDeck\Card;
use Countable;

/**
 * Класс Player реализует игрока
 * @package App
 * @author Sunny
 */
class Player implements Countable
{
	/**
	 * Класс игры
	 * @var GameFool|null
	 */
	private $game = null;
	
	/**
	 * Имя игрока
	 * @var string
	 */
	private $name;
	
	/**
	 * Массив карт на руках игрока
	 * @var Card[]
	 */
	private $cards = [];
	
	/**
	 * Конструктор класса
	 * @param string $name Имя игрока
	 */
	public function __construct(string $name)
	{
		$this->name = $name;
	}
	
	/**
	 * Магический метод для возврата строки
	 * @return string
	 */
	public function __toString()
	{
		return sprintf('%s(%s)', $this->getName(), $this->getCards() ?: 'No cards');
	}
	
	/**
	 * Возвращает количество карт на руках игрока
	 * @inheritDoc
	 */
	public function count()
	{
		return count($this->cards);
	}
	
	/**
	 * Возвращает имя игрока
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}
	
	/**
	 * Устанавливаем текущую игру
	 * @param GameFool $game
	 * @return $this
	 */
	public function setGame(GameFool $game): Player
	{
		$this->game = $game;
		
		return $this;
	}
	
	/**
	 * Дает карту игроку
	 * @param Card ...$cards
	 * @return $this
	 */
	public function give(Card ...$cards): Player
	{
		$this->cards = array_merge($this->cards, $cards);
		
		return $this;
	}
	
	/**
	 * Сортировка карт в руках игрока
	 * @return $this
	 */
	public function sorting(): Player
	{
		uasort($this->cards, function(Card $a, Card $b){
			if ($a->isSuit($this->game->getTrump()) && !$b->isSuit($this->game->getTrump())) {
				return 1;
			} elseif ($b->isSuit($this->game->getTrump()) && !$a->isSuit($this->game->getTrump())) {
				return -1;
			} elseif ($a->getRank() === $b->getRank()) {
				return $a->isGreaterSuit($b) ? 1 : -1;
			} else {
				return $a->isGreaterRank($b) ? 1 : -1;
			}
		});
		
		return $this;
	}
	
	/**
	 * Возвращает карты очередного хода игрока (одну если это первый ход)
	 * @param string[] $ranks Ранги на столе, если нет - первый ход игрока
	 * @param bool $exceptTrump Кроме козырей (когда нужно отдать карты проигравшему)
	 * @return Card[]
	 */
	public function getTurnCards(array $ranks = [], bool $exceptTrump = false): array
	{
		// первый ход, берем самую младшую карту
		if ($ranks === []) {
			$single = array_shift($this->cards);
			return ($single ? [$single] : []);
		}
		
		// получаем самый старший козырь
		$trumpSuit = $this->game->getTrump()->getSuit();
		$trump = end($this->cards);
		if ($trump === false || $trump->getSuit() !== $trumpSuit) {
			$trump = null;
		}
		
		// не первый ход, пытаемся выбрать подходящую карту
		$cards = [];
		foreach ($this->cards as $k => $card) {
			if ($exceptTrump && $card->getSuit() === $trumpSuit) {
				// пропускаем козыри при подкидывании карт проигравшему
				continue;
			}
			
			if (in_array($card->getRank(), $ranks)) {
				// пропускаем самый старший козырь, если на руках больше одной карты
				if (!$exceptTrump && $card === $trump && count($this->cards) > 1) {
					continue;
				}
				
				// подходит, добавляем в массив на возврат
				unset($this->cards[$k]);
				$cards[] = $card;
			}
		}
		
		return $cards;
	}
	
	/**
	 * Возвращает карты которые бьют все переданные карты или пустой массив если нечем
	 * @param Card[] $tableCards
	 * @return Card[]
	 */
	public function getBeatCards(array $tableCards): array
	{
		$beatCards = [];
		foreach ($tableCards as $open) {
			$matched = $trump = null;
			foreach ($this->cards as $k => $card) {
				// проверяем ранг
				if ($card->isGreater($open)) {
					// карта подходит
					$matched = $card;
					break;
				}
				
				// первый попавшийся козырь, может пригодиться
				if (
					$trump === null && !in_array($card, $beatCards, true) &&
					$card->isSuit($this->game->getTrump()) && !$open->isSuit($this->game->getTrump())
				) {
					$trump = $card;
				}
			}
			
			if ($matched !== null) {
				// подходящая карта
				$beatCards[$open->get()] = $matched;
			} elseif ($trump !== null) {
				// бьем козырем
				$beatCards[$open->get()] = $trump;
			} else {
				// нечем бить
				return [];
			}
		}
		
		// удаляем из рук то, что используем на столе
		foreach ($beatCards as $card) {
			if (($key = array_search($card, $this->cards, true)) !== false) {
				unset($this->cards[$key]);
			}
		}
		
		return $beatCards;
	}
	
	/**
	 * Возвращает строку с картами игрока
	 * @return string
	 */
	public function getCards(): string
	{
		return implode(',', $this->cards);
	}
}
