<?php
declare(strict_types=1);

namespace App\GameFool;

/**
 * Класс Log реализует логгер матча
 * @package App\GameFool
 * @author Sunny
 */
class Log
{
	/**
	 * Лог
	 * @var array
	 */
	private $log = [];
	
	/**
	 * Очищает лог
	 * @return $this
	 */
	public function flush(): Log
	{
		$this->log = [];
		
		return $this;
	}
	
	/**
	 * Добавляет запись в лог
	 * @param string $key
	 * @param $value
	 * @return $this
	 */
	public function add(string $key, $value): Log
	{
		$key = trim($key, ': ');
		
		if (is_array($value)) {
			// required php 7.4 with fn($v) => $pad.trim($v) :'(
			$pad = str_repeat(' ', strlen($key) + 2);
			$value = array_map(function($v) use($pad){
				return $pad.trim($v);
			}, $value);
			
			$value = trim(implode(PHP_EOL, $value));
		} else {
			$value = trim((string)$value);
		}
		
		// добавляем в лог
		$this->log[] = $key.': '.$value;
		
		return $this;
	}
	
	/**
	 * Добавляет разделительную строку в лог
	 * @return $this
	 */
	public function addSpacer(): Log
	{
		$this->log[] = '';
		
		return $this;
	}
	
	/**
	 * Возвращает лог в виде строки
	 * @return string
	 */
	public function get(): string
	{
		return implode(PHP_EOL, $this->log);
	}
}
