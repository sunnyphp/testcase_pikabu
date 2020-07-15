### Лог случайной партии игры «Дурак»

На этой странице показывается лог работы эталонной программы для тестового задания по вакансии php-разработчика. Лог содержит информацию о каждом раунде игры, кто на кого ходит, какие карты в руках, кто какие карты выкладывает и берет, кто в итоге стал «дураком».
С помощью этого лога участники испытания смогут сверять логику работы их программы и эталонной.

```text
Deck random: 60493
Trump: 6♦
Rick: 6♣,6♥,8♥,10♣,В♥,Т♣
Morty: 7♠,9♠,10♥,В♠,К♠,Т♥
Summer: 9♣,К♣,8♦,10♦,Д♦,Т♦

01: Rick(6♣,6♥,8♥,10♣,В♥,Т♣) vs Morty(7♠,9♠,10♥,В♠,К♠,Т♥)
    Rick --> 6♣
    Rick --> 6♥
    Morty <-- 6♣
    Morty <-- 6♥
    (deck) Rick + В♣
    (deck) Rick + 7♣

02: Summer(9♣,К♣,8♦,10♦,Д♦,Т♦) vs Rick(7♣,8♥,10♣,В♣,В♥,Т♣)
    Summer --> 9♣
    10♣ <-- Rick
    Summer --> 10♦
    Rick <-- 9♣
    Rick <-- 10♣
    Rick <-- 10♦
    (deck) Summer + Д♥
    (deck) Summer + 6♠

03: Morty(6♣,6♥,7♠,9♠,10♥,В♠,К♠,Т♥) vs Summer(6♠,Д♥,К♣,8♦,Д♦,Т♦)
    Morty --> 6♣
    К♣ <-- Summer
    Morty --> 6♥
    Д♥ <-- Summer
    Morty --> К♠
    8♦ <-- Summer
    (deck) Morty + 8♠
    (deck) Summer + 10♠
    (deck) Summer + Д♠
    (deck) Summer + Т♠

04: Summer(6♠,10♠,Д♠,Т♠,Д♦,Т♦) vs Rick(7♣,8♥,9♣,10♣,В♣,В♥,Т♣,10♦)
    Summer --> 6♠
    10♦ <-- Rick
    Summer --> 10♠
    Rick <-- 6♠
    Rick <-- 10♦
    Rick <-- 10♠
    (deck) Summer + 7♥
    (deck) Summer + 9♥

05: Morty(7♠,8♠,9♠,10♥,В♠,Т♥) vs Summer(7♥,9♥,Д♠,Т♠,Д♦,Т♦)
    Morty --> 7♠
    Д♠ <-- Summer
    (deck) Morty + К♥
    (deck) Summer + 8♣

06: Summer(7♥,8♣,9♥,Т♠,Д♦,Т♦) vs Rick(6♠,7♣,8♥,9♣,10♠,10♣,В♣,В♥,Т♣,10♦)
    Summer --> 7♥
    8♥ <-- Rick
    Summer --> 8♣
    9♣ <-- Rick
    Summer --> 9♥
    В♥ <-- Rick
    (deck) Summer + Д♣
    (deck) Summer + 7♦
    (deck) Summer + 9♦

07: Rick(6♠,7♣,10♠,10♣,В♣,Т♣,10♦) vs Morty(8♠,9♠,10♥,В♠,К♥,Т♥)
    Rick --> 6♠
    8♠ <-- Morty
    (deck) Morty + В♦

08: Morty(9♠,10♥,В♠,К♥,Т♥,В♦) vs Summer(Д♣,Т♠,7♦,9♦,Д♦,Т♦)
    Morty --> 9♠
    Т♠ <-- Summer
    Morty --> Т♥
    7♦ <-- Summer
    (deck) Morty + К♦
    (deck) Morty + 6♦

09: Summer(Д♣,9♦,Д♦,Т♦) vs Rick(7♣,10♠,10♣,В♣,Т♣,10♦)
    Summer --> Д♣
    Т♣ <-- Rick
    Summer --> Д♦
    Rick <-- Д♣
    Rick <-- Т♣
    Rick <-- Д♦

10: Morty(10♥,В♠,К♥,6♦,В♦,К♦) vs Summer(9♦,Т♦)
    Morty --> 10♥
    9♦ <-- Summer

11: Summer(Т♦) vs Rick(7♣,10♠,10♣,В♣,Д♣,Т♣,10♦,Д♦)
    Summer --> Т♦
    Rick <-- Т♦

12: Morty(В♠,К♥,6♦,В♦,К♦) vs Rick(7♣,10♠,10♣,В♣,Д♣,Т♣,10♦,Д♦,Т♦)
    Morty --> В♠
    10♦ <-- Rick
    Morty --> В♦
    Д♦ <-- Rick

13: Rick(7♣,10♠,10♣,В♣,Д♣,Т♣,Т♦) vs Morty(К♥,6♦,К♦)
    Rick --> 7♣
    6♦ <-- Morty

14: Morty(К♥,К♦) vs Rick(10♠,10♣,В♣,Д♣,Т♣,Т♦)
    Morty --> К♥
    Т♦ <-- Rick
    Morty --> К♦
    Rick <-- К♥
    Rick <-- Т♦
    Rick <-- К♦

Fool: Rick
```
