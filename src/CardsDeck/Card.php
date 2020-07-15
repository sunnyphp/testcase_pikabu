<?php
declare(strict_types=1);

namespace App\CardsDeck;

use Exception;

/**
 * Класс Card реализует карту
 * @link https://en.wikipedia.org/wiki/French_playing_cards
 * @package App\CardsDeck
 * @author Sunny
 */
class Card
{
	/**
	 * Рубашка: пика
	 * @var string
	 */
	public const SUIT_PEAK = '♠';
	
	/**
	 * Рубашка: сердце
	 * @var string
	 */
	public const SUIT_HEART = '♥';
	
	/**
	 * Рубашка: треф
	 * @var string
	 */
	public const SUIT_CLOVER = '♣';
	
	/**
	 * Рубашка: ромб
	 * @var string
	 */
	public const SUIT_DIAMOND = '♦';
	
	/**
	 * Ранг: шестерка
	 * @var string
	 */
	public const RANK_6 = '6';
	
	/**
	 * Ранг: семерка
	 * @var string
	 */
	public const RANK_7 = '7';
	
	/**
	 * Ранг: восьмерка
	 * @var string
	 */
	public const RANK_8 = '8';
	
	/**
	 * Ранг: девятка
	 * @var string
	 */
	public const RANK_9 = '9';
	
	/**
	 * Ранг: десятка
	 * @var string
	 */
	public const RANK_10 = '10';
	
	/**
	 * Ранг: валет (jack)
	 * @var string
	 */
	public const RANK_J = 'В';
	
	/**
	 * Ранг: королева (queen)
	 * @var string
	 */
	public const RANK_Q = 'Д';
	
	/**
	 * Ранг: король (king)
	 * @var string
	 */
	public const RANK_K = 'К';
	
	/**
	 * Ранг: туз (ace)
	 * @var string
	 */
	public const RANK_A = 'Т';
	
	/**
	 * Правильная последовательность для сортировки колоды
	 * @var int[]
	 */
	private static $deckSuits = [
		self::SUIT_PEAK,
		self::SUIT_HEART,
		self::SUIT_CLOVER,
		self::SUIT_DIAMOND,
	];
	
	/**
	 * Возможные рубашки и их вес
	 * @var int[]
	 */
	private static $suits = [
		self::SUIT_PEAK		=> 1,
		self::SUIT_CLOVER	=> 2,
		self::SUIT_DIAMOND	=> 3,
		self::SUIT_HEART	=> 4,
	];
	
	/**
	 * Возможные ранги и их вес
	 * @var int[]
	 */
	private static $ranks = [
		self::RANK_6	=> 1,
		self::RANK_7	=> 2,
		self::RANK_8	=> 3,
		self::RANK_9	=> 4,
		self::RANK_10	=> 5,
		self::RANK_J	=> 6,
		self::RANK_Q	=> 7,
		self::RANK_K	=> 8,
		self::RANK_A	=> 9,
	];
	
	/**
	 * Ранг
	 * @var string
	 */
	private $rank;
	
	/**
	 * Рубашка
	 * @var string
	 */
	private $suit;
	
	/**
	 * Конструктор класса
	 * @param string $rank Ранг
	 * @param string $suit Рубашка
	 * @throws Exception
	 */
	public function __construct(string $rank, string $suit)
	{
		// проверяем входящие данные
		if (!isset(self::$ranks[$rank]) || !isset(self::$suits[$suit])) {
			throw new Exception('Передан некорректный ранг или рубашка карты');
		}
		
		// устанавливаем
		$this->rank = $rank;
		$this->suit = $suit;
	}
	
	/**
	 * Магический метод для возврата строки
	 * @return string
	 */
	public function __toString()
	{
		return $this->get();
	}
	
	/**
	 * Возвращает True если текущая рубашка карты соответствует переданной и ранг текущей меньше
	 * @param Card|null $card
	 * @return bool
	 */
	public function isLess(?Card $card): bool
	{
		return $this->isSuit($card) && $this->isLessRank($card);
	}
	
	/**
	 * Возвращает True если текущая рубашка карты соответствует переданной и ранг текущей больше
	 * @param Card|null $card
	 * @return bool
	 */
	public function isGreater(?Card $card): bool
	{
		return $this->isSuit($card) && $this->isGreaterRank($card);
	}
	
	/**
	 * Возвращает строковое обозначение карты
	 * @return string
	 */
	public function get(): string
	{
		return $this->rank.$this->suit;
	}
	
	/**
	 * Возвращает массив рангов в правильной последовательности для сортировки колоды
	 * @return string[]
	 */
	public static function getDeckSuits(): array
	{
		return self::$deckSuits;
	}
	
	/**
	 * Возвращает массив рангов в правильной последовательности для сортировки игрока
	 * @return string[]
	 */
	public static function getRanks(): array
	{
		return array_keys(self::$ranks);
	}
	
	/**
	 * Возвращает True если ранг текущей карты меньше чем у переданной карты
	 * @param Card|null $card
	 * @return bool
	 */
	public function isLessRank(?Card $card): bool
	{
		return $card !== null && self::$ranks[$this->rank] < self::$ranks[$card->getRank()];
	}
	
	/**
	 * Возвращает True если ранг текущей карты больше чем у переданной карты
	 * @param Card|null $card
	 * @return bool
	 */
	public function isGreaterRank(?Card $card): bool
	{
		return $card !== null && self::$ranks[$this->rank] > self::$ranks[$card->getRank()];
	}
	
	/**
	 * Возвращает ранг
	 * @return string
	 */
	public function getRank(): string
	{
		return $this->rank;
	}
	
	/**
	 * Возвращает True если текущая карта имеет ту же рубашку что и переданная карта
	 * @param Card|null $card
	 * @return bool
	 */
	public function isSuit(?Card $card): bool
	{
		return $card !== null && $this->suit === $card->getSuit();
	}
	
	/**
	 * Возвращает True если рубашка текущей карты меньше чем у переданной карты
	 * @param Card|null $card
	 * @return bool
	 */
	public function isLessSuit(?Card $card): bool
	{
		return $card !== null && self::$suits[$this->suit] < self::$suits[$card->getSuit()];
	}
	
	/**
	 * Возвращает True если рубашка текущей карты больше чем у переданной карты
	 * @param Card|null $card
	 * @return bool
	 */
	public function isGreaterSuit(?Card $card): bool
	{
		return $card !== null && self::$suits[$this->suit] > self::$suits[$card->getSuit()];
	}
	
	/**
	 * Возвращает рубашку
	 * @return string
	 */
	public function getSuit(): string
	{
		return $this->suit;
	}
}
