<script>
/**
 * Accessible JavaScript Newsticker
 *
 * Copyright 2006 Wolfgang Bartelme
 * Bartelme Design - http://bartelme.at
 */
 
// Create namespace ------------------------------------------------------------
if (at == undefined) var at = {};
if (at.bartelme == undefined) at.bartelme = {};

// Newsticker class ------------------------------------------------------------
at.bartelme.newsticker = Class.create();
at.bartelme.newsticker.prototype = {
	initialize: function()
	{
		this.interval           = 15000;
		this.container          = $("newsticker");
		this.messages           = $A(this.container.getElementsByTagName("li"));
		this.number_of_messages = this.messages.length;
		if (this.number_of_messages == 0)
		{
			this.showError();
			return false;
		}
		this.current_message = 0;
		//this.current_message = Math.floor(Math.random()*this.number_of_messages);
		this.previous_message = null;
		
		// Create toggle button ------------------------------------------------
		this.toggle_button           = document.createElement("a");
		this.toggle_button.href      = "#";
		this.toggle_button.id        = "togglenewsticker";
		this.toggle_button.innerHTML = "[x]";
		//if (readCookie('newsticker') != 0) this.container.style.display = 'block';
		Event.observe(this.toggle_button, "click", this.toggle.bindAsEventListener(this), false);
		this.container.appendChild(this.toggle_button);

      // Display first message -----------------------------------------------
		this.hideMessages();
		this.showMessage();
		if (readCookie('newsticker') != 0) { Effect.BlindDown($("newsticker"),500); }
		// Install timer
		this.timer = setInterval(this.showMessage.bind(this), this.interval);
  	},
	showMessage: function()
	{
		Effect.Appear(this.messages[this.current_message]);
		setTimeout(this.fadeMessage.bind(this), this.interval-2000);
		if (this.current_message < this.number_of_messages-1)
		{
			this.previous_message = this.current_message;
			this.current_message  = this.current_message + 1;
		} else {
			this.current_message  = 0;
			this.previous_message = this.number_of_messages - 1;
		}
	},
	fadeMessage: function() { 
		Effect.Fade(this.messages[this.previous_message]); 
		//Effect.BlindUp(this.container, 1000); 
	},
	hideMessages: function() { this.messages.each(function(message) { Element.hide(message); }) },
	toggle: function() { Effect.BlindUp(this.container, 1000); createCookie('newsticker',0,1); },
	showError: function()
	{
		if (this.container.getElementsByTagName("ul").length == 0)
		{
			this.list = document.createElement("ul");
			this.container.appendChild(this.list);
		} else {
			this.list = this.container.getElementsByTagName("ul")[0];
		}
		this.errorMessage = document.createElement("li");
		this.errorMessage.className = "error";
		this.errorMessage.innerHTML = "Could not retrieve data";
		this.list.appendChild(this.errorMessage);
	}
}


Event.observe(window, "load", function(){new at.bartelme.newsticker()}, false);
	
</script>

<style>
#newsticker {
	background: #ffffaf;
	position: relative;
	min-height: 50px;
	border: 1px solid rgb(252, 244, 152);
}
#newsticker ul {
	/*border: 1px solid #fcf498;*/
	list-style: none;
	/*min-height: 100px;*/
	padding: 10px 15px;
	padding-right: 30px;
}
* html #newsticker ul {
	height: 100px;
	overflow: visible;
}
#newsticker li.error {
	color: #f00;
}
#newsticker #togglenewsticker {
	/*background: transparent url('http://bartelme.at/material/newsticker/newsticker.html') no-repeat 0 0;*/
	background: transparent url('data:image/gif;base64,R0lGODlhDgAOALMLAP//+///6///8v33sf//1///+f//z///v/35y/z0mP///////wAAAAAAAAAAAAAAACH5BAEAAAsALAAAAAAOAA4AAARGcMlDQimBHMmNUGAoGB0QngpAHh96ClSYICCShFVoD8McWqdeYnC6BBPDYkA37NFAGdnThmO5XhuD6arqtF4kzoJiwWg4EQA7') no-repeat 0 0;
	overflow: hidden;
	position: absolute;
	right: 10px;
	top: 12px;
	width: 14px;
	height: 14px;
	text-indent: 20px;
	outline: none;
}
* html #newsticker #togglenewsticker {
	right: 30px;
}
</style>

<?php

$text = <<< HTML
<div id="newsticker" style="display: none">
<ul> 
  <li>Leduko atšildymas burnoje sudegina 2,3 kalorijas.</li>  
  <li>Braškė yra netikras vaisius. Tai žiedsostis,apaugęs minkštimu. Tikrieji jos vaisiai-mažyčiai geltoni grūdeliai uogos paviršiuje.</li> 
  <li>Čiurliai - keisti paukščiai. Jie miega net skraidydami. <br /></li> 
  <li>Jaunas čiurlys gali skraidyti nenutūpdamas du metus.</li> 
  <li>Asilo akys išsidėstę taip, kad vienu metu jis gali matyti visas keturias savo kojas.</li> 
  <li>Uodas - tai gyvūnas, dėl kurio kaltės pasaulyje miršta daugiausiai žmonių.</li> 
  <li>Dramblio nėštumo periodas gali trukti iki 2 metų.</li> 
  <li>Amaras jau gimsta nėščias ir po 10 dienų pagimdo kitą.</li> 
  <li>Katinas - vienintelis naminis gyvūnas nepaminėtas Biblijoje.</li> 
  <li>Uodas turi 47 dantis.</li> 
  <li>Anties kvarksėjimas neturi aido. Ir niekas nežino kodėl.</li> 
  <li>Jūs sudeginate daugiau kalorijų miegodami, negu žiūrėdami televizorių.</li> 
  <li>Krevetės širdis yra galvoje</li> 
  <li>Naminės katės murkia 26 Hz dažniu, tai lygu dyzelinio variklio dažniui, dirbant tuščiąja eiga.</li> 
  <li>Zebras yra baltas gyvūnas su juodais dryžiais.</li> 
  <li>Tarakonas išgyvena 9 dienas be galvos ir po to nugaišta iš alkio.</li> 
  <li>Vieną valandą dėvint ausines, bakterijų kiekis ausyje padidėja apie 700 kartų.</li> 
  <li>70% merginų tiki, kad pirmoji meilė tęsis amžinai.</li> 
  <li>Pasakų knyga "Tūkstantis ir viena naktis" (originalas) prasideda žodžiais: "Aladinas buvo mažas kinietis berniukas".</li> 
  <li>Per visą gyvenimą moteris suvalgo apie 20 kg lūpdažio.</li> 
  <li>Špinatai nė vienu atomu geležies nepranoksta daugumos kitų maisto produktų. <br /></li> 
  <li>Jeigu jūrininkui Popajai sustiprėti labiausiai būtų reikėję geležies, jis verčiau būtų valgęs ne špinatus iš skardinės, o pačią skardinę.</li> 
  <li>Šokolade yra ne vien daug riebalų, cukraus ir kalorijų. Jis turi dar ir vitaminų A,B1,B2, geležies, kalcio, kalio bei fosforo, kai kurios jo rūšys - net daugiau negu obuolys, indelis jogurto arba porcija varškės.</li> 
  <li>Zigmundas Froidas turėjo liguistą baimę paparčiams.</li> 
  <li>Po ilgo darbo kompiuteriu pažiūrėjus į baltą tuščią popieriaus lapą, jis atrodys rožinis.</li> 
  <li>Nuo trisdešimties metų žmogus po truputį pradeda mažėti.</li> 
  <li>Seniausias pasaulyje kramtomosios gumos gabaliukas yra 9000 metų senumo.</li> 
  <li>Žmogaus šlaunies kaulai yra stipresni už betoną.</li> 
  <li>Rankų nagai auga 4 kartus greičiau negu kojų.</li> 
  <li>Moterys mirkčioja dvigubai daugiau už vyrus.</li> 
  <li>Suaugusio žmogaus smegenys sveria apie 1 kg 200 g.</li> 
  <li>1963 Randy Gardneris nemiegojęs išbuvo 264 valandas. Tai pasaulio rekordas.</li> 
  <li>Stipriausias kūno raumuo yra liežuvis.</li> 
  <li>Vyro nugaros smegenys yra 45 cm, o moters 43 cm ilgio.</li> 
  <li>Moters širdis plaka greičiau nei vyro.</li> 
  <li>Čiaudėjant žmogaus širdis sustoja.</li> 
  <li>Bendras žmogaus odos svoris yra maždaug 2,5 kg.</li> 
  <li>Šypsodamasis žmogus naudoja vidutiniškai 17 raumenų.</li> 
  <li>Susiraukdamas žmogus naudoja vidutiniškai 43 raumenis.</li> 
  <li>Dešinysis žmogaus plautis priima daugiau oro negu kairysis.</li> 
  <li>Fiziškai yra neįmanoma nusičiaudėti atsimerkus.</li> 
  <li>Žmogaus šonkauliai sujuda maždaug 5 milijonus kartų per metus (kvėpuojant).</li> 
  <li>Vyrai žagsi dažniau negu moterys.</li> 
  <li>Dauguma žmonių vorų bijo labiau nei mirties.</li> 
  <li>Ketvirtis žmogaus kaulų yra pėdose.</li> 
  <li>Kosmose astronautai negali verkti, nes nėra gravitacijos. Taigi ašaros negali bėgti.</li> 
  <li>Žmonės kalba vidutiniškai 120 žodžių per minutę greičiu.</li> 
  <li>Tarzanas reiškia "baltaodis".</li> 
  <li>Šešis kartus didesnė tikimybė, kad žaibas nutrenks vyrą, o ne moterį.</li> 
  <li>Eskimų kalboje yra 20 skirtingų žodžių sniegui pavadinti.</li> 
  <li>65 metų amerikietis prie televizoriaus būna praleidęs vidutiniškai 9 metus.</li> 
  <li>Elektros kėdę išrado dantistas.</li> 
  <li>Vidutiniškai per metus žmogus susapnuoja 1460 sapnus.</li> 
  <li>Leonardo da Vincis galėjo viena ranka rašyti ir tuo pačiu metu kita ranka tapyti.</li> 
  <li>Tik vienas Van Gogho paveikslas buvo parduotas jam pačiam būnant gyvam.</li> 
  <li>Žirkles išrado Leonardas da Vincis.</li> 
  <li>Iki Pizos bokšto viršūnės - 269 laipteliai.</li> 
  <li>Eifelio bokšte yra 1792 laipteliai.</li> 
  <li>Venera yra vienintelė planeta, kuri sukasi laikrodžio rodyklės kryptimi.</li> 
  <li>Šiuo metu labiausiai paplitęs vardas pasaulyje yra Mohammedas.</li> 
  <li>Kas ketvirtas amerikietis yra pasirodęs per televiziją.</li> 
  <li>Olimpiniame aukso medalyje privalo būti 92,5% sidabro.</li> 
  <li>Mėnesyje, kuris prasideda sekmadieniu, vienas penktadienis visada bus 13 dieną.</li> 
  <li>Tik 55% amerikiečių žino, kad saulė yra žvaigždė</li> 
  <li>Los Andžele automobilių yra daugiau nei žmonių.</li> 
  <li>Norint iki saulės nuvažiuoti automobiliu, reiktų 150 metų.</li> 
  <li>J.S. Bachas turėjo 20 vaikų - 7 su pirmąja žmona ir 13 - su antrąja.</li> 
  <li>Golfo kamuoliukas turi 336 duobutes.</li> 
  <li>Šeškai miega maždaug 20 valandų per parą.</li> 
  <li>Šiaurės elniai mėgsta bananus.</li> 
  <li>Dauguma dramblių sveria mažiau negu mėlynojo banginio liežuvis.</li> 
  <li>Blusa gali nušokti atstumą, 350 kartų viršijantį jos kūno ilgį. Tai tas pats, jei žmogus peršoktų futbolo stadioną.</li> 
  <li>Šamai turi daugiau nei 27 000 skonio receptorių.</li> 
  <li>Drugelių skonio receptoriai išdėstyti ant kojų.</li> 
  <li>Drambliai - vieninteliai gyvūnai, kurie nemoka šokinėti.</li> 
  <li>Baltieji lokiai - kairiarankiai.</li> 
  <li>Stručio akis yra didesnė už jo smegenis.</li> 
  <li>Omarų kraujas mėlynas.</li> 
  <li>Pasodinta kavos pupelė duoda vaisius tik po 5 metų.</li> 
  <li>Indiško lotoso sėklos išlieka daigios 300-400 metų.</li> 
  <li>Begemoto patelės pienas yra šviesiai rožinės spalvos.</li> 
  <li>Genys medį gali kapoti 20 kartų per sekundę dažniu.</li> 
  <li>Afrikos dramblys turi tik 4 dantis.</li> 
  <li>Šimpanzė išmoksta save atpažinti veidrodyje, o kitos beždžionės - ne.</li> 
  <li>Aštuonkojo akies vyzdys yra stačiakampio formos.</li> 
  <li>Delfinai miega atmerkę vieną akį.</li> 
  <li>Bitės turi 5 akis. Trys mažos akys yra bitės galvos viršuje, o dvi didelės - priekyje.</li> 
  <li>Bičių akys yra plaukuotos.</li> 
  <li>Nauji dantys rykliui gali išaugti per savaitę.</li> 
  <li>Taip pat kaip ir žmonės, katės ir šunys būna kairiarankiai arba dešiniarankiai (leteniai).</li> 
  <li>Kolibriai negali vaikščioti.</li> 
  <li>Prabudusios skruzdelės, panašiai kaip ir žmonės, rąžosi ir žiovauja.</li> 
  <li>Šokoladas žudo šunis. Šokoladas veikia šunų širdį ir nervų sistemą. 100 g šokolado nedideliam šuneliui gali būti mirtina dozė.</li> 
  <li>Aklas chameleonas vis tiek keičia spalvas.</li> 
  <li>Per dieną dramblys gali suėsti apie 200 kg šieno ir išgerti apie 230 l vandens.</li> 
  <li>Savo lizduose paukščiai nemiega. Čia jie gali tik snūstelti, bet miega jie kitose vietose.</li> 
  <li>Drugeliai gali skristi apie 30 km per valandą greičiu.</li> 
  <li>Šunys gali girdėti garsus, kurių negirdi žmogus.</li> 
  <li>Kiaulei pasižiūrėti į dangų nėra jokių fizinių galimybių.</li> 
  <li>Laumžirgio gyvenimo trukmė - 24 valandos.</li> 
  <li>Norint kietai išvirti stručio kiaušinį, reikia 4 valandų.</li> 
</ul>
</div>
HTML;

?>