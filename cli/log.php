<?php
declare(strict_types=1);

// подключаем автозагрузку
require_once __DIR__ . '/../vendor/autoload.php';

// получаем используемые файлы
use App\CardsDeck;
use App\GameFool;

// получаем лог
$game = (new GameFool)
	//->createPlayer('Rick')
	->createPlayer('Morty')
	//->createPlayer('Summer')
	//->createPlayer('Fry')
	//->createPlayer('Bender')
	->createPlayer('Leela')
	//->setDeck(new CardsDeck(24227))
	->setDeck(new CardsDeck(16597))
;
$game->run();

if (!headers_sent()) {
	header('Content-Type: text/plain; charset=utf-8');
}

echo $game->log->get();
