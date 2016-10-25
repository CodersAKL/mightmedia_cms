Used hooks [![Stories in Ready](https://badge.waffle.io/CodersAKL/mightmedia_cms.png?label=ready&title=Ready)](http://waffle.io/CodersAKL/mightmedia_cms) [![Deployment status from dploy.io](http://mightmedia.dploy.io/badge/23779029942970/15244.png)](http://dploy.io)
==========
Please make hard link to the .git/hooks/pre-commit from file ROOT/version_hook.sh

    mklink /H ".git\hooks\post-commit" ".\hooks\post_commit_hook.sh"
    mklink /H ".git\hooks\pre-commit" ".\hooks\pre_commit_hook.sh"


mightmedia cms change log
==============

----------------------------------------------
MightMedia v1.46 | 9/13/2012 12:30 AM
Autoriai: zlotas, FDisk, p.dambrauskas
---
 * kosmetinis pataisymas. dievai/index.php prisijungimo submit buvo prilipęs prie input
 * kosmetinis pataisymas. default.css šalinam nereikalingas funkcijas
 * kosmetinis pataisymas. ol list style none
 * kosmetinis pataisymas. taisom klaidas
 * kosmetinis pataisymas. šalinam prisijungimą :D
 * kosmetinis pataisymas. Prisijungimas
 * kosmetinis pataisymas.
 * ini_set išmestas lauk
 * reikia testuot
 * upgrade iš v1.4 į v1.5 beveik paruošta.
 * naujienlaiškių dizaino karkasas, kaip ir baigta.
 * BUGFIX: Sesiju palaikymas tame paciame serveryje
 * BUGFIX: Virsutinis meniu per daug prilipes prie virsaus.
 * BUGFIX: Patogiau atsijungti is admin paneles
 * BUGFIX: nerodo avataro kai raso svecias.
 * BUGFIX: Atsijungus nuo admin paneles nebuvo unsetinamas User ID
 * BUGFIX: Install adresas()
 * įvedam truputi tvarkos
 * naujienlaiškių dizaino sprendimas...
 * "kažkas :)" neteisingai if'us parašęs buvo
 * "kažkas :)" sugadinęs užklausą buvo su dviem vienodais laukais.
 * rėksnių dėžės textarea maxialus simbolių skaičius - 300
 * instaliacijos pataisymai.
 * Editoriaus laukų pataisymai
 * textarea to editorius, kitur buvo pritaikyta editoriui.
 * if ( $kieno == 'galerija' ), buvo if ( $kieno = 'galerija' ) + viršuje esantis jquery buvo su klaidele
 * smulkmena: šaukykloje žinutės redagavimo įrankiai atsiranda tik užvedus pelę ant DIV, kurio klasė tr : tr2
 * Baigta su fotoalbumais.
 * Fixed #154 , padarytas administravimo puslapis, duk puslapyje viskas padaryta su jquery slidetogle
 * senų reitingų js - šalinam
 * fotogalerijos albumų atvaizdavimas. THE END - ???
 * tooltip color: #000;
 * stilių korekcija "padidintas plotis bei meniu 20px nuo viršaus"
 * blokai lygiavimas
 * dievai lygiavimas


----------------------------------------------
MightMedia v1.45 | 9/7/2012 1:25 AM
Autoriai: zlotas, p.dambrauskas, FDisk
---
 * install lygiavimas
 * javascript lygiavimas
 * priedai lygiavimas
 * stiliai lygiavimas
 * ROOT lygiavimas
 * lygiuojam puslapius
 * pataisytas: undefined, kategorijose, galerijoje sutvarkytas fancybox
 * kategorijų pataisymai + blokų administravimo pataisymai
 * Fixed #152
 * ir filtras patobuliuntas
 * pataisymai
 * Fixed #150
 * galerijos lokalizacija, nebuvo išvis aktyvavimo funkcijos.
 * kadangi iš url puslapiuodami ieškom 'p' BUG nes toks pats ir aktyvavimo buvo. p keičiam į priimti
 * prie news_limit klasė buvo select :D, blyn, pakeista į input.
 * admin prisijungimo dizaino pataisymai.
 * admin prisijungimo dizainas
 * pataisymas su install papke
 * nuorodų puslapiau update // dalis darbo
 * a. btn klasėje nereikia float:left; - iškraipo vaizdą., o nuorodos ir taip savaime lygiuojasi į eilę.
 * Galerijai kuriam paprastą foto albumų funkciją, dabar tik lokalizuota.
 * nereikalinga input klasė ant formos.
 * RSS BUG: Bandau pataisyti
 * rss nukreipimas
 * įvedam truputi tvarkos
 * BUGFIX: adresas()
 * įvedam truputi tvarkos
 * tooltip spalva.
 * naujas google lankomumo grafikas
 * naujas google lankomumo grafikas, keli css pataisymai
 * kategorijų paveiksliukų atnaujinimas
 * icons/flags - atnaujinimas
 * kategoriju selectboxai
 * nereikalingi failai
 * kategorija keičiam į fotoalbumą
 * kategoriju selectboxai
 * images papkės papildymas, korekcija
 * puslapiavimas


----------------------------------------------
MightMedia v1.44 | 9/5/2012 5:31 PM
Autoriai: zlotas, p.dambrauskas
---
 * Stiliai lietuviškai
 * kalbos papildymas
 * Stilius : default - orange
 * du default dizainai, pervadinti.
 * hide funkcija pakeista į lentele
 * tooltip BUG FIX : max-width: 300px;
 * galerijos stilius.
 * tvarkom papkę
 * buvo komentaro klaida, ne //, o /. Pridėtas siuntinio URL pateikimas.
 * `kom` set('taip','ne') COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'taip',
 * Dabar galima bus individualiai nustatyti 'kom' TAIP/NE
 * images papkės pataisymas
 * Filtras + puslapiavimas. Filtro nėra : pm/banai/balsavimas/... atrodo tik ten
 * AFTER `align`, ADD INDEX ( `rodyti` ) < Naujos MYSQL versijos neskaito šito. + pridėta galimybė išjungti komentarus galerijoje.
 * pašalinama nuoroda į stiliai/system.css, jis nereikalingas
 * editoriaus textarea klasė, editoriaus konkrečiam pločiui nustatyti
 * buvo blogas kelias iki .js failo + textarea klasė, editoriaus pločiui redaguoti. Ji slėpsis stiliai/system.css ir dizaino default.css
 * editoriaus funkcijos pataisymai, nes nauja nicedit versija.
 * install pataisymai
 * paneles to blokai
 * nurodom kelią iki install papkės
 * nustatom ROOT'ą
 * atnaujinta instaliavimo naujienos data.
 * atnaujinti sql upgrade failai, reikia test.
 * ištrinam favicon.ico, jis yra images papkėje ROOT'oj
 * faviconą traukiam iš images ROOT'os
 * setupo kalba, pataisytos nuorodos į img scr
 * taisom nuorodas ikonų
 * klaidos ir msg funkcijom.
 * kalbos papildymas, bei paneles failo su lang'inimas
 * Atnaujinam dievulėlius "ten, angelai gyvena, ant aukštų kalnų" citata iš Velnio Nuotakos
 * atnaujiname dievai katalogą
 * paneles keičiam į blokai, gana painiojimusi, tai panelės tai blokai, netvarkinga
 * images papkės tvarka, naujos ikonos, pratrinta flags papkė, bus padaryta papildomam atsisiuntimui.
 * atia atia colorbox, sveikas fancybox
 * atnaujinta javascript papkė
 * centriniai blokai, pakeisti include pavadinimai, atnaujinti failai
 * puslapiavimas
 * naujas default dizainas
 * stiliaus papkės atnaujinimas + naujas default dizainas
 * ROOT atnaujinimas + install papkė
 * ROOT atnaujinimas
 * puslapių atnaujinimas SQL_CAHE pašalinimas, papildomos funkcijos
 * Galerijos atnaujinimas, fancybox, nuoroda į nuotraukos puslapį.
 * Galerijos atnaujinimas - fancybox ir kt.
 * padariau filtravimo pradžią, išėmiau tą javascript filtrą, reik visur sudėt tokius filtrus administravime ir dar puslapiavimą lentelei padaryt.


----------------------------------------------
MightMedia v1.43 | 9/2/2012 10:12 PM
Autoriai: p.dambrauskas, zlotas, FDisk
---
 * Admin dizaino atnaujinimas
 * Prie failų galūnių pridėti failų formatus iš didžiųjų raidžių, kaip .PNG, .JPG.
 * RSS BUG: Bandau pataisyti
 * tiny_mce naujos versijos failai
 * Naujas editorius: ckeditor
 * Atnaujinti: tiny_mce, nicEdit editoriai bei pridėtas naujas: ckeditor
 * e-propaganda paveiksliuko pakeitimas bei pridėta nuoroda į e-propagandos puslapį, Redaguotas lang/lt.php
 * images/balsavimas/ nereikalingi paveikslėliai pašalinti
 * images/galerija nereikalingi lightbox GIF'ai pašalinti
 * images/levelis/ panaikinimas
 * kintamieji tarp {}
 * SQL_CACHE panaikinimas
 * AddThis Button pridėjimas
 * html klaida
 * pravalymas
 * pakeiciau atvaizdavima
 * nereik pagr pakete sito.
 * paneles aptvarkaiu kazkiek
 * Nereikalinga, per daug užklausų
 * Bandom firendly urls pataisyt
 * BUGFIX: 404 nerastu failu parsinimas
 * 113 eilutėje pridėtas autoriaus atvaizdavimas , virš datos.


----------------------------------------------
MightMedia v1.42 | 2/25/2011 9:18 PM
Autoriai: FDisk, p.dambrauskas
---
 * Beta fixai
 * Testas
 * Trinam nenaudojama
 * Apvalymas upgrade.php
 * Vertimai
 * BUGFIX: nera tikrinimo
 * BUGFIX: user-agent
 * Paskutinio apsilankymo bugas
 * Paskutinio apsilankymo bugas
 * Kesho valymas
 * Ijungtas keshavimas komentarams
 * Atnaujinta uzklausa
 * Straipsniu limitas toks kaip ir naujienu
 * BUGFIX: admin menu mose over
 * BUGFIX: Galerijos rusiavimas
 * Galerijos administravimo bugai
 * moderavimas
 * hyperiator išjungimo galimybė, stiliaus pataisymai
 * NEW: Share mygtukas
 * TODO: Reikalingas default css mygtukui (class: a2a_default_style)
 * BUGFIX: blogas paveiksliukas naujiem irasam atvaizduoti
 * BUGFIX: klaidingas URL
 * BUGFIX: moderatoriu puslapiui administravimo mygtuku pagrindinis css
 * BUGFIX: kad eitu vietoje puslapiu det ir nuorodas be http://
 * http:// ir http://www. autologout bugas.
 * Kategoriju cleanup
 * Image konfliktas


----------------------------------------------
MightMedia v1.41 | 9/10/2010 11:18 PM
Autoriai: FDisk, p.dambrauskas
---
 * Po truputi valome koda.
 * Default3 dizaino pataisymai
 * Admin css pataisymai
 * Diegimo proceduros mini pataisymai
 * user update bugfix
 * closed #122
 * bugfix: ban bad bots
 * http://www.cooliris.com/developer/tools/
 * upgrade
 * upgrade
 * bloodhound exploit - antivirus
 * fixed #121
 * skaitliukas kategorijų
 * nėra įrašų bugfix


----------------------------------------------
MightMedia v1.40 | 6/19/2010 8:29 AM
Autoriai: FDisk, p.dambrauskas
---
 * Save HTML - removed style
 * kazkas blogai?
 * upgrade ready to test :)
 * Daug smulki? bugfix?
 * Žodžių kėlimas į naują eilutę
 * fixed #116
 * Nuorodu info
 * truputis apsaugos nuo XSS ir bugo ištaisymas (mistiškas puslapio dingimas ištrynus jo "tėvą")
 * bugfix: nuorodos, siuntiniai
 * fixed #120
 * Nepavyko
 * Pataisymai
 * Testing
 * ka as zinau ko neveikia
 * Virus? atnaujinimas
 * tinymce fullscreen bugfix
 * admin CSS bugfixai
 * RSS nesuprantu kur klaida


----------------------------------------------
MightMedia v1.39 | 6/8/2010 7:52 PM
Autoriai: FDisk, p.dambrauskas
---
 * dizainas
 * dizaino pataisymas
 * bugfix: tinymce
 * class sticky
 * bugfix: tinymce
 * dėl komentarų
 * fixed #98
 * fixed #115
 * tinymce
 * bugfix: tinymce del š raidės
 * BUGFIX newsleter on SAFEMODE
 * BUGFIX dubliuojasi
 * escape(basename($_POST['pirminis'],'.php'))
 * BUGFIX kas naujo
 * fixed #114
 * fixed #113
 * fixed #117
 * fixed #111
 * fixed #112
 * fixed #109
 * fixed #108
 * Bug: banu administravime paima bloga komentara prie uzbaninto ip
 * Antivirus defs ir administravime kesho valymo mygtukas - nereikalingas kai keshavimas isjungtas ir atvirksciai
 * Naujienlaiskiai
 * Reik failu tikrinima pataisyt ir desinespaneles.php faile
 * kalbos.php


----------------------------------------------
MightMedia v1.38 | 5/24/2010 11:05 AM
Autoriai: FDisk, p.dambrauskas
---
 * Ping pong for the versioning number
 * Admin paneles inputu teksto spalva - patamsinam
 * Apklausa - pridedam keshavima apvalom koda. Pridedam usability
 * bugfix - startinis puslapis
 * forumo teisės
 * redagavimas
 * balsavimo archyvas
 * Nauja apklausa, TODO: archyvas, komentavimas, redagavimas, testavimas
 * Puslapio medis
 * uglymce
 * table sorter
 * biskiuka bugfixai del stiliaus
 * ar tirkai reikalingas float left ant formu?
 * bugfix: markitup - filemanager - blogas kelias
 * deafault dizaino pataisymai
 * Jei setup.php neistrintas - klaidos kodas
 * default zone
 * time zone
 * galerijos tiuningas
 * Setup pataisymai
 * pono F.... užmačia: galimybė pasirinkt redaktorių, CKeditor nedėjau, nes užima tiek pat, kiek tvs'as
 * setup išvaizda
 * upgrade išvaizda
 * multiple upload
 * Nauji virusai
 * Dizaina Default3 patvirtintu. Manau tinkamas - dar kada patiuninguosiu
 * Admin paneles tiuningas, nepatikimai veikia filemanageris - reik pratestuot skirtingom narsyklem
 * Papildytas atnaujinimas mysql lenteles
 * Kas naujo tiuningas
 * Grazinam config faila i 0kb
 * Patikslinti išsireiškimai
 * Galerijoje ignoruojam visus failus


----------------------------------------------
MightMedia v1.37 | 5/10/2010 7:36 PM
Autoriai: p.dambrauskas, FDisk
---
 * galerijos rikiavimo nustatymai
 * Apvalymas
 * galerija
 * Antivirusines pataisymai
 * Lygiuojame koda
 * meniu administravimo tiuningas
 * Dizainas admin paneles
 * Straipsniai - nebaigtas pagebreake
 * kad nebūtų tušti puslapiai
 * Admin naujas dizainas - pataisymai
 * Admin cp
 * Admin naujas dizainas
 * getip()
 * sticky news
 * v1.4 mysql lenteles EN
 * v1.4 mysql lenteles
 * Naujienos multilanguage
 * undefined $conf
 * Administravimo direktorijos keitimas - reikia pratestuoti
 * setup.php apvalymas
 * editorius
 * Nauja eilute htaccess failui, kad banai eitu is naujos eilutes
 * WYSIWYG perkėlimas į saugią zoną.
 * Truputis pataisymu susijusiu su daugiakalbyste
 * Padaryta: Plačiau galimybės
 * Ignoras ant betkokiu failu esanciu sandeliuke
 * Default dizaino pataisymai
 * Ignoras ant betkokiu failu esanciu siuntiniuose
 * htaccess leidziam failu tikrinima


----------------------------------------------
MightMedia v1.36 | 4/2/2010 4:50 PM
Autoriai: FDisk, p.dambrauskas
---
 * biskiuka bugfix filemanager
 * neveikia funkcija adresas(), gražina kelią iki ten, kur esi, tai netinka administravimui ir manageriui, suprantu, kodėl visi tvsai klausia kur instaliuotas TVS'AS, gal reiktų į configą tokį laukelį įkišt?
 * manageris, uploadina tik į siuntiniai/ , į sub direktorijas - ne, peržiūra neveikia  (nerodo img), peržiūros failo commitint neleidžia
 * antivirus
 * naujienlaiškiai
 * slaptažodžio priminimas
 * lang()
 * klaidos dėl tų lang()
 * Daugiakalbystes SQL
 * Daugiakalbyste. Truksta tik administravimo.
 * pločiai
 * siuntiniai
 * nuorodos + moderatoriaus puslapis
 * nuorodų puslapis, administravimas
 * subrykiavimas, admin pokalbiai, nukreipimas
 * Botu gaudyklė. Pataisyta admin CP lankomumo statistika.
 * Admin CP - submeniu zodziu lauzymas
 * pagrindinis.js neįkeltas buvo
 * atja prototype/scriptaculous
 * skelbimai?
 * forumas, panelės :)
 * Admin panelės meniu reorganizacija. Rezervuojam plotą mums. Svetainės struktūra perkeliame ir atvaizduojame kaip submeniu.
 * Naujienu tikrinimo tiuningas, admin paneles tiuningas,  galerijos redirectai


----------------------------------------------
MightMedia v1.35 | 3/14/2010 6:55 PM
Autoriai: p.dambrauskas, FDisk
---
 * input() ir dar kažkas
 * straipsniai
 * naujienos, jų kategorijos, administravimas pataisymai
 * furls
 * email keitimas, gimimo datos selected
 * forumo pritaikymas 1.4tai
 * Kategorijos bugfix
 * duko remontas
 * Galerija bugfix
 * šaukyklos aptvarkymas
 * nerastas pranešimas
 * pataisymų keletas
 * googlui patinka aiškūs title
 * url() + furls išjungimas
 * klaidos klaidelės, didelės ir mažos
 * Versijos tikrinimo configas
 * vaizdelis ir bėdelė dėl url()
 * Versijos tikrinimo - testas
 * jSON versijos tikrinimo bugfix
 * Versiju tikrinimas
 * MarkitUp preview
 * RSS feed markitup'ui. ShortTags buvo paliktas
 * friendly urls + tvarkelė
 * friendly urls dizaino meniu


----------------------------------------------
MightMedia v1.34 | 3/11/2010 3:14 PM
Autoriai: FDisk, p.dambrauskas
---
 * Isimu ir remonto puslapio lenteles virsutini borderi
 * mini bugfixai
 * atnajiname setup
 * Direktorijos pavadinimas sutapo su friendly urls
 * Galerija perkeliame i images/gallery
 * Privalomas folderis
 * Kas naujo
 * Patiuninguotas "kas naujo"
 * Nuorodu tikrinimui
 * friendly urls
 * tos su**ktos kategorijos
 * Slaptažodžio priminime pataisymas, kad įvedus betkokį emailą nesiųstų laiško, profilio redagavime draudimas palikti tuščią email laukelį
 * Bedos su nariu kategorijom ir editorium
 * editorius moderatoriams
 * truputis tiuningo
 * Admin panele - automatiskai uzkrauname modulio paveiksliuka defultini jei tokio nera
 * Format
 * superadminas dabar gali redaguoti ir kitus adminus
 * Pakeista galerijos isvaizda, pridetas redagavimas
 * gražesnis remontas, kiek funkcija, avatarai neužsikešuos (reik testuot)
 * peržiūros lang
 * url bb code
 * bugfix: balsavimas.php - 5 klausimo nebuvimas
 * bugfix: delete_cache()
 * meniu


----------------------------------------------
MightMedia v1.33 | 10/28/2009 8:41 AM
Autoriai: p.dambrauskas, FDisk
---
 * darbas
 * fixed #85
 * stilistika
 * ka pavadinimą normaliai rodytų
 * wrap(
 * Galimybė užkrauti betkokį puslapį (AJAX) - be dizaino
 * Input bugas.
 * Kalendorius su nuorodom į kas_naujo.php puslapį ir online.php tiuningas
 * fixed #82
 * oi bugfixas
 * comment #81
 * Pridėjau vėliavas
 * close #81
 * setupo dvikalbystė
 * BUGFIX - balsavimo tiuningas
 * data ir vertimas
 * test
 * Dokumentacija - bus gerai
 * closed #79
 * Pataisyta - galimybė įdėti daugiau balsavimų į vieną puslapį, ištaisytas baninimas po balsavimo, prijungti kalbų failai.
 * Geriau be animacijos ir geriau kai permatomas
 * Pridejau trukstama paveiksliuką


----------------------------------------------
MightMedia v1.32 | 9/5/2009 3:31 PM
Autoriai: p.dambrauskas, FDisk
---
 * ip
 * SEO urls 
 * @TODO
 * paiškos sistemoms
 * Apvalymas
 * fixed #75
 * fixed #62
 * fixed #77
 * paveiksliukas iskvieciamas su javascript pagalba.
 * fixed #78
 * kontaktas.php - pridėtas siuntėjo IP adresas
 * dėl vaizdo
 * avatarai2
 * avatarai
 * banai
 * vertimas
 * Ai niekas
 * googlui
 * dėl forumo sausainių
 * generated negali būti po </html>
 * validumas, išėmiau nereikalingą lentelę
 * fixed #73
 * fixed #72
 * fixed #71
 * bloga insert užklausa
 * fixed #61
 * fixed #69
 * fixed #68


----------------------------------------------
MightMedia v1.31 | 7/18/2009 12:10 PM
Autoriai: p.dambrauskas, FDisk
---
 * tarpas po labas
 * ne visuose hostinguose veikia, nežinau ar dėl to ką užkomentavau
 * duk
 * undefined
 * user_idas ?
 * neegzistuojantį pluginą išėmiau (InsertPicture)
 * blogas kelias į paveikslėlius buvo
 * Niu nezinau - man veikia nieko kaip ir nepakeiciau. Isbandziau ant kito kompo - viskas ok.
 * WYSIWYG editoriu perestroika
 * kalidos dėl lang
 * Ignorai


----------------------------------------------
MightMedia v1.30 | 6/28/2009 6:36 PM
Autoriai: FDisk, p.dambrauskas
---
 * php sintakse
 * Jquery Hint
 * php sintakse
 * favicon allow
 * HTML
 * Jquery Hint
 * perkeltas favicon i dizaino direktorija
 * prailgintas keshas
 * nzn kazkas
 * HTML valid
 * fixed #55
 * smulkmenos...
 * teisės
 * truputis validumo
 * adresas() gal dar patobulint reikėtų
 * buvo nevalidųs tie buttonai
 * timlink
 * komentarai
