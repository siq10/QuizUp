# QuizUp - licență


## 1.1 Abstract
Această lucrare are scopul de a prezenta etapele dezvoltării aplicației web „QuizUp”. Rolul principal al acesteia este de a facilita acumularea de cunoștințe tehnice de către studenți, în vederea obținerii unor rezultate mai bune la materiile studiate la Facultatea de Informatică Iași. Aplicația se constituie din două părți: REST API la nivel de server și aplicație Vue la nivel de client.

Implementarea pornește de la un set de întrebări de tip grilă, stocate într-o bază de date de tip **MySQL**. Utilizatorii logați pot folosi token-uri pentru a “cumpăra” întrebări; în urma acestui proces, întrebările respective vor deveni disponibile pentru răspuns. În urma acumulării unui număr de răspunsuri corecte, utilizatorii pot să contribuie la setul de întrebări existent. Există mai multe moduri prin care utilizatorii pot obține token-uri, dintre care amintim: răspunsuri corecte la intrebări,”contribuția” la setul de întrebări existent, “cumpărarea” întrebărilor proprii de către alți utilizatori.

Aplicația oferă, de asemenea, funcționalitatea unui joc multiplayer de tip quiz, în care câștigătorul este cel care răspunde cu succes la un număr de întrebări în timpul cel mai scurt. Miza jocului este stabilită anterior de comun acord (un număr fix de token-uri).

Printre tehnologiile folosite amintim framework-ul  **Laravel** pe partea de server, respectiv **VueJS** pe partea de client. Comunicarea este realizată prin protocolul HTTP, aderând la modelul arhitectural REST. În cadrul componentei de joc a aplicației, informațiile se transmit în timp real prin intermediul protocolului WebSocket. Este folosit, în acest sens, frameworkul **Socket.IO**.

Am ales să realizez această aplicație întrucât consider că pot oferi o modalitate ușoară de a ințelege noțiuni abstracte și de a verifica nivelul de cunoștințe acumulat de utilizator, fiind cunoscut faptul că învățarea prin joc este foarte eficientă.