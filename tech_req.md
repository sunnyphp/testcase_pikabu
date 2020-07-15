### Задание

Необходимо написать на php классах небольшую упрощенную реализацию народной карточной игры «Дурак».
Задача программы — рассчитать одну партию игры между виртуальными игроками.
Ваша задача — написать необходимые php классы игры «Дурак» для работы следующей программы:

```php
echo (new GameFool())
(new Player('Rick'))
(new Player('Morty'))
(new Player('Summer'))
(new CardsDeck(rand(1, 0xffff)))
();
```
Данная программа выводит имя игрока, который оказался «дураком», либо дефис (-), если ничья.

### Правила игры

Ниже перечислены правила, которые во многом похожи на классическую карточную игру «Дурак», но с некоторыми упрощениями. Для понимания как должна работать программа мы подготовили страницу, которая генерирует случайную партию и выводит лог игры (какое случайно число колоды, какой козырь, кто на кого ходит и какие карты у него в руках, кто проиграл):

[Лог случайной партии игры](tech_log.md)

Вы можете использовать этот лог для отладки вашей программы, так как при одинаковом случайном числе и наборе игроков программы должны генерировать одинаковую партию.

Список правил:

1. Используется колода из 36 карт;
2. Игроков может быть от 2 до 4;
3. Изначально колода отсортирована по масти в следующем порядке ♠, ♥, ♣, ♦. Для каждой масти карты упорядочены по достоинству, от 6 до туза;
4. Перед началом игры колода сортируется заданным случайным числом: выполняется 1000 итераций, в каждой итерации из колоды берется карта с порядковым номер n = (random + iterator * 2) mod 36 (где random - случайное переданное число, а iterator - номер итерации сортировки 0…999) и перемещается в начало колоды;
5. После сортировки колоды выполняется 6 итераций раздачи карт из начала массива колоды: для каждого игрока, в порядке их добавления в игру, достается карта из начала колоды (начало массива). Таким образом после 6 итераций у каждого игрока должно быть 6 карт в руках;
6. На протяжении всей игры карты в руках игроков должны быть сортированы. Вначале идут карты всех не козырных мастей, сортированные по достоинству и по масти (порядок: пика, крест, бубен, червей), затем козыри, также сортированные по достоинству;
7. Следом достается из начала массива колоды карта-козырь. Эта карта возвращается в колоду, но в самый конец, чтобы быть забранной последней;
8. Первым ходит игрок, который первый был добавлен в игру (в примере кода выше это Rick);
9. Ход выполняется на следующего игрока по порядку их добавления в игру, циклически;
10. В ходе участвуют только два игрока,нападающий и отбивающийся. Другие игроки не могут подкидывать карты;
11. Ход всегда начинается с самой младшей карты любой масти, кроме козыря. Если в руках не осталось карт, кроме козырей, то ход начинается с самого младшего козыря;
12. Продолжать ход необходимо картами с достоинствами, которые использовались за ход. Например, если за ход был использован валет червы, то нападающий может продолжить ход, например, с валет бубен. Однако нельзя продолжать ход самой старшей козырной картой в руках, если в руках более одной карты. Например, если за ход использовались тузы, а в руках нападающего есть несколько карт, среди которых есть козырный туз, то им продолжать ход нельзя. Выбирать карту для продолжения хода нужно самую младшую в руке (карты отсортированы в руке, см п.6);
13. Отбиваться необходимо старшей картой той же масти. Выбирается самая младшая из возможных карт для отбивания. Если нет подходящих карт той же масти, то отбиваться нужно самой младшей козырной картой;
14. Если отбиваться нечем больше, то отбивающий забирает все карты, что были использованы за ход. Также, у нападающего забираются все карты такого же достоинства, что были использованы за ход, кроме козырей. Иными словами, если козырь черва, за ход использовалась карта 10 пика и на руках нападающего есть ещё 10 черва и 10 бубен, то 10 бубен переходит в руки отбивающегося.
15. Если игрок проиграл ход (забрал карты хода), то он пропускает свой ход и нападающим становится следующий по списку игрок;
16. После каждого хода игроки берут недостающие количество карт из начала массива колоды. Вначале пополняет руку картами нападающий, затем обороняющийся и затем все остальные по порядку их следования;
17. Если в руках игрока не осталось карт и в колоде пусто, то этот игрок выходит из игры;
18. Игра завершается, когда остается один игрок с картами в руках или не остается игроков (ничья).

### Технические требования

1. Ваш лучший код на php7.3
2. Все классы должны быть в одном файле;
3. Без использования библиотек.