Näotuvastuse rakendus
=====================

Näotuvastuse rakendus on Ron Tõnnisoni poolt loodud rakendus Opuse proovitöö eesmärgil.

Rakendus laeb alla etteantud veebilehelt kõik pildid, mis ta leiab ning otsib igalt pildilt nägu. Kui rakendus tuvastab mõnel pildil näo, siis ta salvestab selle pildi URLi andmebaasi. Kui rakendus on lõpetanud kõikidelt piltidelt otsimise, siis ta kustutab kõi allalaetud pildid ära. Igal hetkel on võimalik lasta rakendusel kuvada kõik pildid, mis on andmebaasi salvestatud. Pilte saab järjest kuvada ka siis, kui rakendus parasjagu mõnelt veebilehelt allalaetud pilte alles kontrollib, kuid sel juhul ei anna rakendus teada, millal ta on lõpetanud kõikide piltide läbivaatamise.


NÕUDED
------

Nõuded antud rakenduse kasutamiseks on, et veebiserver toetaks PHP'd.


INSTALLEERIMINE
---------------

Kopeerida kogu kaust või kõik kaustas olevad failid/kaustad veebiserveri kausta või localhosti.
Juhul kui kasutada localhosti ja kopeerida kogu kaust, näeb veebiaadress välja selline:

~~~http://localhost/rakendus
~~~

Seejärel logida sisse oma andmebaasiserverisse ning luua uus andmebaas või kasutada juba olemasolevat ja importida sinna fail nimega 'images.sql'.
Importimise asemel võib luua ka uue tabeli, selleks kasutada järgnevat MySQL lausendit:

´´´MySQL
CREATE TABLE images (id int(11) AUTO_INCREMENT, url varchar(255), PRIMARY KEY (id));
´´´


KONFIGUREERIMINE
----------------

### Andmebaas

Redigeeri faili nimega 'db.php' lisades sinna tõesed andmed, nagu näiteks:

´´´PHP
$config = array(
    'username' => 'root',
    'password' => ''
);
´´´

ning

´´´PHP
$conn = new PDO('mysql:host=localhost;dbname=imgdb',
			            $config['username'],
			            $config['password']
			            );
´´´

** NB! ** Pea meeles, et andmebaas ei tekki iseenesest ning enne tabeli loomist on vaja ka andmebaas luua või valida mõni olemasolev.
