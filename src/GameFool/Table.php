<?php
declare(strict_types=1);

namespace App\GameFool;

use App\CardsDeck\Card;
use Exception;

/**
 * Класс Table реализует логику работы с картами на столе
 * @package App\GameFool
 * @author Sunny
 */
class Table
{
	/**
	 * Козырь
	 * @var Card
	 */
	private $trump;
	
	/**
	 * Карты на столе
	 * @var Card[]
	 */
	private $open = [];
	
	/**
	 * Побитые/закрытые карты
	 * @var Card[]
	 */
	private $closed = [];
	
	/**
	 * Конструктор класса
	 * @param Card $trump Козырь
	 */
	public function __construct(Card $trump)
	{
		$this->trump = $trump;
	}
	
	/**
	 * Добавляет (подкидывает) карты на стол (их необходимо побить/закрыть)
	 * @param Card ...$cards
	 * @return $this
	 */
	public function addOpen(Card ...$cards): Table
	{
		foreach ($cards as $card) {
			$this->open[$card->get()] = $card;
		}
		
		return $this;
	}
	
	/**
	 * Возвращает список карт на столе
	 * @return Card[]
	 */
	public function getOpen(): array
	{
		return $this->open;
	}
	
	/**
	 * Возвращает все ранги на столе
	 * @return string[]
	 */
	public function getRanks(): array
	{
		$ranks = [];
		
		foreach ($this->open + $this->closed as $card) {
			$rank = $card->getRank();
			if (!in_array($rank, $ranks)) {
				$ranks[] = $rank;
			}
		}
		
		return $ranks;
	}
	
	/**
	 * Добавляет карты которыми бьются/закрываются карты на столе
	 * @param Card[] $assoc Ключ - Card::_toString() на столе, значение - карта бьющая карту на столе
	 * @return $this
	 * @throws Exception
	 */
	public function addBeat($assoc): Table
	{
		foreach ($assoc as $cardName => $beat) {
			// получаем карту на столе
			$card = ($this->open[$cardName] ?? null);
			if ($card === null) {
				throw new Exception('Нет карты на столе: '.$cardName);
			}
			
			// проверяем возможность её закрытия
			$success = false;
			if ($beat->isGreater($card)) {
				// подходящая карта
				$success = true;
			} elseif ($beat->isSuit($this->trump)) {
				// заходим с козырей
				$success = true;
			}
			
			// проверяем игрока на вшивость
			if (!$success) {
				throw new Exception('Попытка обмануть: '.$beat->get().' не бьет '.$cardName);
			}
			
			// переносим в побитые/закрытые
			$this->closed[$cardName] = $card;
			$this->closed[$beat->get()] = $beat;
			unset($this->open[$cardName]);
		}
		
		return $this;
	}
	
	/**
	 * Возвращает карты на столе (включая уже битые), т.к. игрок не может побить открытые карты.
	 * Игрок при этом должен запросить остальные карты того же ранга (кроме козырей).
	 * @return Card[]
	 */
	public function cantBeat(): array
	{
		$cards = array_values($this->open + $this->closed);
		
		$this->open = $this->closed = [];
		
		return $cards;
	}
	
	/**
	 * Очистка стола после битвы между игроками
	 * @return $this
	 * @throws Exception
	 */
	public function clear(): Table
	{
		$this->closed = [];
		
		if ($this->open !== []) {
			throw new Exception('На столе есть не битые карты: '.implode(', ', $this->open));
		}
		
		return $this;
	}
}
