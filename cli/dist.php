<?php
declare(strict_types=1);

// подключаем автозагрузку
require_once __DIR__.'/../vendor/autoload.php';

// данные для записи в файл
$bufferHeader = sprintf('<?php
declare(strict_types=1);

/**
 * @auto-generated @ %s
 * @author Sunny
 */

', date('r'));
$bufferFooter = PHP_EOL;

// получаем используемые файлы
$directory = new RecursiveDirectoryIterator(__DIR__ . '/../src/');
/** @var SplFileInfo[] $iterator */
$iterator = new RecursiveIteratorIterator($directory);
foreach ($iterator as $info) {
	if ($info->getExtension() === 'php') {
		// получаем данные файла
		$file = file($info->getPathname());
		$path = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $info->getPathname());
		if (($pos = stripos($path, 'src')) !== false) {
			$path = substr($path, $pos);
		}
		if (!is_array($file)) {
			printf("Skipped: %s\n", $path);
			continue;
		}
		
		$isPhpDoc = false;
		foreach ($file as $k => $line) {
			if (strpos($line, '/**') === 0) {
				// отрезаем все что дальше
				$bufferFooter .= '// '.str_repeat('=', 50).PHP_EOL;
				$bufferFooter .= '// File path: '.$path.PHP_EOL;
				$bufferFooter .= '// '.str_repeat('=', 50).PHP_EOL.PHP_EOL;
				$bufferFooter .= trim(implode('', array_slice($file, $k))).PHP_EOL.PHP_EOL;
				break;
			}
		}
		printf("Done: %s\n", $path);
	}
}

// пишем в файл
$distFilePath = __DIR__.'/../dist/dist.php';
$a = file_put_contents($distFilePath, $bufferHeader.$bufferFooter);

// сообщаем
printf("Done %u bytes, file: %s\n", $a, $distFilePath);
