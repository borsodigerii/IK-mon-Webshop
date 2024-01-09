Borsodi Gergő
ZIWUQ4

Webprogramozás - számonkérés

Kijelentem, hogy ez a megoldás a saját munkám. Nem másoltam vagy használtam harmadik féltől származó megoldásokat. Nem továbbítottam megoldást hallgatótársaimnak, és nem is tettem közzé. Nem használtam mesterséges intelligencia által generált kódot, kódrészletet. Az ELTE HKR 377/A. § értelmében, ha nem megengedett segédeszközt veszek igénybe, vagy más hallgatónak nem megengedett segítséget nyújtok, a tantárgyat nem teljesíthetem.

ELTE Hallgatói Követelményrendszer, IK kari különös rész, 377/A. §: "Az a hallgató, aki olyan tanulmányi teljesítménymérés (vizsga, zárthelyi, beadandó feladat) során, amelynek keretében számítógépes program vagy programmodul elkészítése a feladat, az oktató által meghatározottakon kívül más segédeszközt vesz igénybe, illetve más hallgatónak meg nem engedett segítséget nyújt, tanulmányi szabálytalanságot követ el, ezért az adott félévben a tantárgyat nem teljesítheti és a tantárgy kreditjét nem szerezheti meg."

### Minimálisan teljesítendő (enélkül nem fogadjuk el, 6 pont)
[x] `0.0 pont` Readme.md fájl: kitöltve, feltöltve
[x] `0.0 pont` Főoldal: megjelenik
[x] `1.0 pont` Főoldal: összes kártya listázása, pl. képekkel
[x] `1.0 pont` Főoldal: a kártya nevére kattintva a megfelelő kártya részletek oldalra jutunk
[x] `1.0 pont` Kártya részletek: Megjelenik a kártyán szereplő szörny neve, életereje, leírása és eleme
[x] `0.5 pont` Kártya részletek: Megjelenik a kártyához tartozó kép
[x] `0.5 pont` Kártya részletek: A kártyán szereplő szörny eleme szerint változik az oldalon egy vagy több elem színe vagy háttérszíne, pl.: Fire esetén piros, Lightning esetén sárga, stb.
[x] `2.0 pont` Admin: Új kártya létrehozása: hibakezelés, sikeres mentés


### Az alap feladatok (14 pont)
[x] `0.5 pont` Regisztrációs űrlap: Megfelelő elemeket tartalmazza
[x] `0.5 pont` Regisztrációs űrlap: Hibás esetek kezelése, hibaüzenet, állapottartás
[x] `0.5 pont` Regisztrációs űrlap: Sikeres regisztráció
[x] `0.5 pont` Bejelentkezés: Hibás esetek kezelése
[x] `0.5 pont` Bejelentkezés: Sikeres bejelentkezés
[x] `0.5 pont` Kijelentkezés
[x] `0.5 pont` Főoldal: Megjelenik a felhasználó neve és pénze
[x] `0.5 pont` Főoldal: A felhasználónévre kattintva a felhasználó részletei oldalra jutunk (EDIT: legördülő menü)
[x] `1.0 pont` Főoldal: Lehessen a kártyákat típus szerint szűrni.
[x] `0.5 pont` Felhasználó részletek: Megjelenik a felhasználó neve, e-mail címe, pénze
[x] `0.5 pont` Felhasználó részletek: Megjelennek a felhasználóhoz tartozó kártyák
[x] `2.0 pont` Felhasználó részletek: A felhasználó kártyái mellett megjelenik egy eladás gomb, amivel a felhasználó el tudja adni az adott kártyát, ekkor törlődik a kártyái közül az eladott lap és megkapja a kártya árának 90%-át. Az eladott lap visszakerül az ADMIN paklijába. (Azt hogy az eladás gombot hova helyezed és hogyan valósítod meg rád van bízva)
[x] `0.5 pont` Admin: Be lehet jelentkezni az admin felhasználó adataival
[x] `0.5 pont` Admin: Új kártya létrehozása csak Admin felhasználóval érhető el
[x] `0.5 pont` Főoldal: Ha be van jelentkezve jelenjen meg egy vásárlás gomb minden kártya alatt
[x] `1.5 pont` Főoldal (Vásárlás): Meg tudja venni a kártyát
[x] `0.5 pont` Főoldal (Vásárlás): Csak annyit tud venni amennyi pénze van
[x] `0.5 pont` Főoldal (Vásárlás): Legfeljebb 5 kártyája lehet
[x] `1.0 pont` Igényes kialakítás

### Plusz feladatok (max plusz 5 pont)
[x] `0.5 pont` Admin: Kártya módosítása: admin felhasználóval elérhető, még el nem adott kártyák esetén
[x] `0.5 pont` Admin: Kártya módosítása: hibakezelés, állapottartás, sikeres mentés
[ ] `1.0 pont` Főoldal: A főoldal egy gombra kattintva a nem admin felhasználók tudjanak venni egy véletlenszerű kártyát a pénzükből, egy random kártya ára lehet pl.: 50 coin.
[ ] `2.0 pont` Főoldal: A főoldalon egyszerre csak 9 kártya jelenjen meg, alattuk lehessen navigálni az oldalakon (oldalszámokkal, nyilakkal). Mindig az aktuális oldalszámnak megfelelő kártyák jelenjenek meg, minden oldalon a következő 9 kártya. A megoldáshoz használj AJAX-ot/fetch-et.
[ ] `1.0 pont` Csere 1. lépés: A főoldalon azoknál a kártyáknál, amely nem az adminnál és nálunk van jelenjen meg egy csere gomb, amire kattintva önhatalmúlag cserélhesse el ezen kártyára egy tetszőleges kártyáját a felhasználó. 
[ ] `1.0 pont` Csere 2. lépés: A csere ne azonnali és önhatalmú legyen, hanem a másik fél kapjon róla értesítést, és fogadhassa vagy utasíthassa el.
[ ] `1.0 pont` Csere 3. lépés: A cseréhez lehessen pénzt is hozzáadni bármely oldalra. Figyelj a negatív számokra!