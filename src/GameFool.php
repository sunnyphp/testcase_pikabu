<?php
declare(strict_types=1);

namespace App;

use App\CardsDeck\Card;
use App\GameFool\Log;
use App\GameFool\Table;
use Exception;

/**
 * Класс GameFool реализует менеджера игры с игровой логикой
 * @property Log $log Логгер игры
 * @package App
 * @author Sunny
 */
class GameFool
{
	/**
	 * Количество карт на руках у игроков
	 * @var int
	 */
	private const CARD_ON_HAND = 6;
	
	/**
	 * Массив игроков
	 * @var Player[]
	 */
	private $players = [];
	
	/**
	 * Игровая колода
	 * @var CardsDeck|null
	 */
	private $deck = null;
	
	/**
	 * Текущий козырь
	 * @var Card|null
	 */
	private $trump = null;
	
	/**
	 * Магический метод вызова класса как функции
	 * @param Player|CardsDeck|null $arg
	 * @return $this|string
	 * @throws Exception
	 */
	public function __invoke($arg = null)
	{
		if ($arg instanceof Player) {
			// добавляем игрока
			$this->addPlayer($arg);
		} elseif ($arg instanceof CardsDeck) {
			// устанавливаем колоду
			$this->setDeck($arg);
		} else {
			// запуск игры
			return $this->run();
		}
		
		return $this;
	}
	
	/**
	 * Магический метод для возврата строки
	 * @return string
	 * @throws Exception
	 */
	public function __toString()
	{
		return $this->run();
	}
	
	/**
	 * Магический метод для возврата субклассов
	 * @param string $property
	 * @return object|null
	 */
	public function __get(string $property)
	{
		if ($property === 'log') {
			return $this->{$property} = new Log;
		}
		
		return null;
	}
	
	/**
	 * Добавляет игрока
	 * @param Player $player
	 * @return $this
	 */
	public function addPlayer(Player $player): GameFool
	{
		if (!in_array($player, $this->players)) {
			$this->players[] = $player->setGame($this);
		}
		
		return $this;
	}
	
	/**
	 * Создает игрока и добавляет его в игру
	 * @param string $name
	 * @return $this
	 */
	public function createPlayer(string $name): GameFool
	{
		$this->players[] = (new Player($name))->setGame($this);
		
		return $this;
	}
	
	/**
	 * Устанавливает колоду
	 * @param CardsDeck $deck
	 * @return $this
	 */
	public function setDeck(CardsDeck $deck): GameFool
	{
		$this->deck = $deck;
		
		return $this;
	}
	
	/**
	 * Возвращает текущий козырь или NULL
	 * @return Card|null
	 */
	public function getTrump(): ?Card
	{
		return $this->trump;
	}
	
	/**
	 * Возвращает текущего игрока или NULL если такового нет
	 * @return Player|null
	 */
	private function getCurrentPlayer(): ?Player
	{
		if (!($player = current($this->players))) {
			if (!($player = reset($this->players))) {
				$player = null;
			}
		}
		
		return $player;
	}
	
	/**
	 * Возвращает следующего игрока (циклично) или NULL если такового нет
	 * @return Player|null
	 */
	private function getNextPlayer(): ?Player
	{
		$player = null;
		
		if (!($player = next($this->players))) {
			if (!($player = reset($this->players))) {
				$player = null;
			}
		}
		
		return $player;
	}
	
	/**
	 * Выполняет игровую логику и возвращает результат
	 * @return string
	 * @throws Exception
	 */
	public function run(): string
	{
		// проверяем переменные
		$players = count($this->players);
		if ($players < 2 || $players > 4 || $this->deck === null) {
			throw new Exception('Неподходящее количество игроков или нет колоды для игры');
		}
		
		// мешаем колоду
		$this->deck->shuffle();
		
		// ничья
		$fool = '-';
		
		// раздача карт игрокам
		foreach ($this->players as $player) {
			for ($i=0; $i<self::CARD_ON_HAND; $i++) {
				$player->give($this->deck->take());
			}
		}
		
		// получаем козырь
		$this->trump = $this->deck->takeTrump();
		
		// сортируем карты игроков
		foreach ($this->players as $player) {
			$player->sorting();
		}
		
		// информация об игре
		$this->log
			->flush()
			->add('Deck random', $this->deck->getSeed())
			->add('Trump', $this->trump)
		;
		
		// информация об игроках
		foreach ($this->players as $player) {
			$this->log->add($player->getName(), $player->getCards());
		}
		
		// карты в колоде
		$this->log->add('Deck', (string)$this->deck);
		
		// игровой стол
		$table = new Table($this->trump);
		
		// начинаем игру!
		$this->log->addSpacer();
		$iteration = 0;
		$nextPlayerSkip = false;
		while (++$iteration) {
			$operations = [];
			
			// получаем текущего и следующего игрока
			$currentPlayer = $this->getCurrentPlayer();
			$nextPlayer = $this->getNextPlayer();
			if ($nextPlayerSkip) {
				// пропуск игрока
				$nextPlayerSkip = false;
				$iteration--;
				continue;
			} elseif ($nextPlayer === null || $nextPlayer === $currentPlayer) {
				// больше нет противников, текущий игрок оказался дураком
				if ($currentPlayer) {
					$fool = $currentPlayer->getName();
				}
				break;
			} elseif ($currentPlayer === null) {
				// нет игроков, цикл завершается
				break;
			}
			
			// лог кто против кого
			$operations[] = sprintf('%s vs %s', (string)$currentPlayer, (string)$nextPlayer);
			
			while (true) {
				// ход текущего игрока
				$cards = $currentPlayer->getTurnCards($table->getRanks());
				if ($cards === []) {
					// нечем ходить/подкидывать, завершаем цикл
					break;
				}
				
				// закидываем на стол, логируем
				$table->addOpen(...$cards);
				foreach ($cards as $card) {
					$operations[] = sprintf('%s --> %s', $currentPlayer->getName(), $card->get());
				}
				
				// пытаемся ответить
				$beats = $nextPlayer->getBeatCards($cards);
				if ($beats !== []) {
					// есть карты для закрытия карт на столе!
					$table->addBeat($beats);
					foreach ($beats as $card) {
						$operations[] = sprintf('%s <-- %s', $card->get(), $nextPlayer->getName());
					}
				} else {
					// не можем побить все карты, забираем со стола
					$nextPlayerSkip = true;
					$turnCards = $currentPlayer->getTurnCards($table->getRanks(), true);
					if ($turnCards !== []) {
						$table->addOpen(...$turnCards);
					}
					$tableCards = $table->cantBeat();
					$nextPlayer->give(...$tableCards)->sorting();
					foreach ($tableCards + $turnCards as $card) {
						$operations[] = sprintf('%s <-- %s', $nextPlayer->getName(), $card->get());
					}
					break;
				}
			}
			
			// проверяем необходимость добора карт
			foreach ([$currentPlayer, $nextPlayer] as $player) {
				/** @var Player $player */
				// добиваем карты на руках
				if (count($this->deck)) {
					for ($i=count($player); $i<self::CARD_ON_HAND; $i++) {
						if (($take = $this->deck->take()) !== null) {
							$player->give($take);
							$operations[] = sprintf('(deck) %s + %s', $player->getName(), $take->get());
						}
					}
				}
				
				// проверяем количество карт
				if (count($player) === 0) {
					// вышел из игры
					if (($key = array_search($player, $this->players, true)) !== false) {
						unset($this->players[$key]);
					}
				} else {
					// сортируем карты
					$player->sorting();
				}
			}
			
			// очищаем стол
			$table->clear();
			
			// добавляем запись в лог
			$this->log
				->add(str_pad((string)$iteration, 2, '0', STR_PAD_LEFT), $operations)
				->addSpacer()
			;
		}
		
		// объявляем результат
		$this->log->add('Fool', $fool);
		
		// возвращаем результат
		return $fool;
	}
}
