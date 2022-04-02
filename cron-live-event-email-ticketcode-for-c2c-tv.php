<?php
include_once('web-config.php'); 
include_once('smtp_1.php'); 
include_once('includes/classes/DBQuery.php');
$objDBQuery = new DBQuery();

include_once('includes/functions/common.php'); 

// This cron job is fired every 1 minite 

$whereArr = array();
$appCode = '3e909131cbb1a1f308183c838bc005d7';
$arrSelectDbFields = array('appCode', 'appName');	

$appInfoArray = $objDBQuery->getRecord(0, $arrSelectDbFields, 'tbl_apps', array('appCode' => $appCode, 'status' => 'A'));
if (is_array($appInfoArray) && !empty($appInfoArray))
{

	$arrSelectDbFlds4Stream = array('streamCode', 'streamTitle', 'streamUrl', 'eventStDateTime', 'timezoneOffset', 'streamImg', 'streamdescription', 'staring', 'streamTrailerUrl');
	$whereCls = "appCode_FK = '$appCode' AND status = 'A' AND eventStDateTime != ''";
	$appStreamsInfoArr = $objDBQuery->getRecord(0, $arrSelectDbFlds4Stream, 'tbl_streams', $whereCls, '', '', 'eventStDateTime', 'ASC');
	
	if (!empty($appStreamsInfoArr) && is_array($appStreamsInfoArr))
	{
		//$timezoneOffset = $appStreamsInfoArr[$j]['timezoneOffset'];
		
		$nunOfRows = count($appStreamsInfoArr);
		for ($i = 0; $i < $nunOfRows; $i++)
		{
			$eventStDateTime = $appStreamsInfoArr[$i]['eventStDateTime'];
			$timezoneOffset = $appStreamsInfoArr[$i]['timezoneOffset'];
			$eventStDateTime = convertDateTimeSpecificTimeZone($eventStDateTime, $timezoneOffset);
			$eventUnixTime = unixtime64($eventStDateTime);

			// This Logic for 24 hrs before email shot
			$arrCurrentDate = getExpiredDate('1 days');
			$arrCurrentDate2 = getExpiredDate('1 days +1 minutes +30 seconds');		
			
			// This Logic for 15 minites before email shot
			$arrCurrentDateFor15 = getExpiredDate('+15 minutes');
			$arrCurrentDate2For15 = getExpiredDate('+15 minutes +30 seconds');	

			$emailAbbr = '';
			$emailAbbr = 'app-buy-live-event-ticket';
			if ($eventUnixTime >= $arrCurrentDate['unixtimeExtended'] && $eventUnixTime <= $arrCurrentDate2['unixtimeExtended'])
			{
				echo "24 hrs before";
				$emailAbbr = 'app-remainder-before-1';
			}
			else if ($eventUnixTime >= $arrCurrentDateFor15['unixtimeExtended'] && $eventUnixTime <= $arrCurrentDate2For15['unixtimeExtended'])
			{
			
				echo "15 Minites before";
				$emailAbbr = 'app-remainder-before-2';
			}

			if ($emailAbbr != '')
			{
			
				$whereCls = "subjectAbbr = '$emailAbbr' AND appCode_FK = '$appCode'";				

				$emailFormatInfoArr = $objDBQuery->getRecord(0, '*', 'tbl_email_format', $whereCls);
				
				//$emailFormatInfoArr = $objDBQuery->getEmailFormat(1, $appCode, $emailAbbr);
				
				$streamCode = $appStreamsInfoArr[$i]['streamCode'];
				$eventOrStreamTitle = $appStreamsInfoArr[$i]['streamTitle'];
				
				
				list($eStDate, $eStTime) = explode(" ",  $appStreamsInfoArr[$i]['eventStDateTime'], 2);
				
				$tOffset = str_replace('_', ' ', $timezoneOffset);
				$timing = $eStDate;
				$eventTime = $eStTime .' '.$tOffset;
				
				$ticketInfoArr = $objDBQuery->getRecord(0, array('ticketCode', 'buyerUserCode_FK', 'streamCode_FK', 'type', 'buyInformation'), 'tbl_ticket_codes', array('streamCode_FK' => $streamCode, 'type' => 'L'), '', '', '', '');
				
				$subM = 'Important information for tomorrow’s Chords2Cure Virtual Concert';
				$mailB = "<p style='font-size: 18px; font-family: sans-serif; color:black'><b>IT'S SHOWTIME!  The Chords2Cure Virtual Concert is TOMORROW, September 26th -- ARE YOU READY TO ROCK?</b></p>
<p style='font-size: 14px; font-family: sans-serif'>
If you are viewing the broadcast in North America (5:30pm PDT), please use this link: <a href='https://concert.chords2cure.tv'  target='_blank'>www.concert.chords2cure.tv</a> <br>
If you are viewing the broadcast in Europe (5:30pm GMT + 2 -- a/k/a 8:30am PDT), please use this link: <a href='https://econcert.chords2cure.tv'  target='_blank'>www.econcert.chords2cure.tv</a> <br><br>

Attached you will find a <b>QUICK-CONNECT GUIDE</b> to help navigate accessing the event.  If you have ANY problems, please contact Technical Assistance at 805-317-4608 who will be on standby starting one hour <b>prior to the event</b>.
<br><br>
<b>LOS ANGELES RESIDENTS:  NOT SURE WHAT TO EAT WHILE WATCHING THE SHOW?</b>  <bR><Br>

Chords2Cure is partnering with <b>MENDOCINO FARMS</b> to help you solve that problem!  Simply place your order @ <a href='https://urldefense.proofpoint.com/v2/url?u=http-3A__order.MendocinoFarms.Com&d=DwMFaQ&c=fP4tf--1dS0biCFlB0saz0I0kjO5v7-GLPtvShAo4cc&r=myRjDGAL-aQU1iGsiWInsWBuNc7PqMlJ-n7O_di-y7E&m=9VESGkqjSaWztc9mVqOERgNQNKaYrmffvzDK1SpjAN0&s=u90jlIpIQFU46XzQhRFdFC1h_0eEahg4b8RUb1JI0mY&e=' target='_blank'>order.MendocinoFarms.com</a> and enter the code <b><font color='00c7fd'>CHORDS2CURE926</font></b> at checkout and Mendocino Farms will donate 20% to C2C in honor of Childhood Cancer Awareness Month.  <b><u>Coupon code is valid all day</u></b>!
<br><br>
This offer is valid for online ordering only; delivery and pick-up. Participating locations: Brentwood, Santa Monica, Sherman Oaks, Studio City, 3rd & Fairfax, West Hollywood, Westlake Village and Marina Del Rey. Third-party delivery service orders are not eligible for this promotion.  Other discounts, coupons or offers are not valid in conjunction with this code.  Tax, tip and delivery fee are not part of the donation.</p>
";

				//ivan@swigmedia.com
			//	sendEmail('ivan@swigmedia.com', $subM, $mailB, 'no-reply@chords2cure.tv', 'no-reply@chords2cure.tv', 'HTML');	
				if (is_array($ticketInfoArr) && !empty($ticketInfoArr))
				{
					$numOfRows2 = count($ticketInfoArr);
					$srn = 0;
					for ($j = 0; $j < $numOfRows2; $j++)
					{
						$userCode = $ticketInfoArr[$j]['buyerUserCode_FK'];
						$ticketCode = $ticketInfoArr[$j]['ticketCode'];
						$buyInfo = 	$ticketInfoArr[$j]['buyInformation'];

						$userInfoArr = $objDBQuery->getRecord(0, array('username', 'fname', 'lname', 'email', 'name', 'createdOn'), 'tbl_registered_users', array('userCode' => $userCode), '', '', '', '');
						$email = $userInfoArr[0]['email']; 
						$createdOnDate = $userInfoArr[0]['createdOn'];
						
						
						if($email == "vkvia6@gmail.com" ||
							$email == "Richard@rstearns.com" || 
							$email == "2475991@washoeschools.org" || 
							$email == "alivela@aol.com" || 
							$email == "dhara.amish@gmail.com" || 
							$email == "kimlevin@me.com" || 
							$email == "amandapegula@gmail.com" || 
							$email == "five.whites23@gmail.com" || 
							$email == "Tiffanyjhughes@gmail.com" || 
							$email == "Stephanie.Whisler@Yahoo.com" || 
							$email == "Manda65432@gmail.com" || 
							$email == "Nicolefailla@gmail.com" || 
							$email == "Rosebud2314@yahoo.com" || 
							$email == "sbukinik@mac.com" || 
							$email == "dhara.amish@gmail.com" || 
							$email == "Karmamgood@gmail.com" || 
							$email == "pvalvensleben@mac.com" || 
							$email == "dweep.neha@gmail.com" || 
							$email == "amanda@halo-heart.com" || 
							$email == "tmassin@me.com" || 
							$email == "Mandi@holmesmc.com" || 
							$email == "edencohen@escapeartists.com" || 
							$email == "johanna@bright.com" || 
							$email == "nicolehorton@me.com" || 
							$email == "Jfarina@mednet.ucla.edu" || 
							$email == "rachelfrenchweber@me.com" || 
							$email == "richardwenk1@mac.com" || 
							$email == "luisafg@att.net" || 
							$email == "davidweinrot@gmail.com" || 
							$email == "alocke@xrds.org" || 
							$email == "aabowitz@gmail.com" || 
							$email == "lanylippman@yahoo.com" || 
							$email == "boyz7@mac.com" || 
							$email == "staciehausner@yahoo.com" || 
							$email == "georgegeorges2u@hotmail.com" || 
							$email == "andre_slaughter@spe.sony.com" || 
							$email == "carrieneus@gmail.com" || 
							$email == "brian.ma@lamillcoffee.com" || 
							$email == "janewangbeck@gmail.com" || 
							$email == "jennifersolomon@me.com" || 
							$email == "niuha@kupelian.net" || 
							$email == "kahalpert@gmail.com" || 
							$email == "qpdolldebi@gmail.com" || 
							$email == "gary@caffeluxxe.com" || 
							$email == "memrosenberg@yahoo.com" || 
							$email == "stacikoondel@verizon.net" || 
							$email == "smcdiarmid@mednet.ucla.edu" || 
							$email == "stisch14@gmail.com" || 
							$email == "lynnesheridan@gmail.com" || 
							$email == "carrie@summitla.com" || 
							$email == "jodyfurie@gmail.com" || 
							$email == "seretean@me.com" || 
							$email == "sdearscript@gmail.com" || 
							$email == "mattimore@ikahanmedia.com" || 
							$email == "paula.b.press@gmail.com" || 
							$email == "mirnaduriel@gmail.com" || 
							$email == "danielle.stokdyk@gmail.com" || 
							$email == "Berti@coldplay.com" || 
							$email == "sbesi@cox.net" || 
							$email == "harley@harleypasternak.com" || 
							$email == "alissagrayson@icloud.com" || 
							$email == "merygrace11@hotmail.com" || 
							$email == "bruce@newbergfamily.net" || 
							$email == "timhilsimon@me.com" || 
							$email == "info@vitaperfetta.com" || 
							$email == "daryl@offerfamily.com" || 
							$email == "mdritti@gmail.com" || 
							$email == "Liaseabold@gmail.com" || 
							$email == "karenteicher3@gmail.com" || 
							$email == "Sullivan_Linda@smc.edu" || 
							$email == "SoniaKBorris@gmail.com" || 
							$email == "LisaDAndreaSchwartz@gmail.com" || 
							$email == "mimison12@aol.com" || 
							$email == "hilmihallaj@gmail.com" || 
							$email == "gourmeletas@gmail.com" || 
							$email == "tfaigus@gmail.com" || 
							$email == "lisa@lisakingdesign.com" || 
							$email == "andrewlicht@gmail.com" || 
							$email == "daniel@stonexstone.com" || 
							$email == "wglickman@mac.com" || 
							$email == "sptmoritz@aol.com" || 
							$email == "narena26@icloud.com" || 
							$email == "jenschiff@gmail.com" || 
							$email == "allison@kaplanwright.com" || 
							$email == "susie@ikahanmedia.com" || 
							$email == "ffox68@gmail.com" || 
							$email == "greggsilver22@gmail.com" || 
							$email == "onedrd1@yahoo.com" || 
							$email == "hassangaledary@icloud.com" || 
							$email == "Nfederman@mednet.ucla.edu" || 
							$email == "julieablock@gmail.com" || 
							$email == "kelly@craigandkelly.com" || 
							$email == "rodliber@me.com" || 
							$email == "russell@pixelmediaca.com" || 
							$email == "holeike@me.com" || 
							$email == "tracy@therappaports.com" || 
							$email == "lrbrownson@earthlink.net" || 
							$email == "dzwelling@gmail.com" || 
							$email == "rvernick@mednet.ucla.edu" || 
							$email == "rockwell.alexandra@gmail.com" || 
							$email == "rteselle@yahoo.com" || 
							$email == "CALKINS99@GMAIL.COM" || 
							$email == "agores@gores.com" || 
							$email == "belden4@mac.com" || 
							$email == "ritarayrn09@gmail.com" || 
							$email == "rswindler@mendocinofarms.com" || 
							$email == "jack4brindle@gmail.com" || 
							$email == "kitnolanmusic@gmail.com" || 
							$email == "jenkduran@me.com" || 
							$email == "gdunkel@mednet.ucla.edu" || 
							$email == "lindameadow@gmail.com" || 
							$email == "rudolph_robert@smc.edu" || 
							$email == "dennis@jnlstainless.com" || 
							$email == "ah@andellinc.com" || 
							$email == "RODNEYLIBER@GMAIL.COM" || 
							$email == "brookmanmichelle@icloud.com" || 
							$email == "mbdean72@gmail.com" || 
							$email == "itstine@icloud.com" || 
							$email == "mary@marykayne.com" || 
							$email == "marynayar@gmail.com" || 
							$email == "Larryklein1@me.com" || 
							$email == "efaraut@gmail.com" || 
							$email == "grace.s21@k12.xrds.org" || 
							$email == "jamesrs@aol.com" || 
							$email == "joke365@gmail.com" || 
							$email == "sharon.orrange@med.usc.edu" || 
							$email == "hblynne@hotmail.com" || 
							$email == "seisenstadt@mac.com" || 
							$email == "richard.stearn@compass.com" || 
							$email == "lizbliss4@gmail.com" || 
							$email == "steve.berman@umusic.com" || 
							$email == "jmilken@hotmail.com" || 
							$email == "alexlee777@gmail.com" || 
							$email == "chrisfurie@aol.com" || 
							$email == "jsporter1129@gmail.com" || 
							$email == "radavidow@netscape.net" || 
							$email == "mgallagher@xytechsystems.com" || 
							$email == "rajraygo@icloud.com" || 
							$email == "queencamryn@gmail.com" || 
							$email == "espenswish@gmail.com" || 
							$email == "donnalynn33@me.com" || 
							$email == "karen@razpr.com" || 
							$email == "lauriepdubin@gmail.com" || 
							$email == "iweinrot@yahoo.com" || 
							$email == "stevedellerson@gmail.com" || 
							$email == "gom1944@pacbell.net" || 
							$email == "jzh@corcoran.com" || 
							$email == "debbiefisher@earthlink.net" || 
							$email == "tracilee66@yahoo.com" || 
							$email == "andmers@aol.com" || 
							$email == "craig@lipseyappraisal.com" || 
							$email == "kevingore128@gmail.com" || 
							$email == "bunnyflip@mac.com" || 
							$email == "cece.asst@gmail.com" || 
							$email == "stacyjd@me.com" || 
							$email == "jndiamond@me.com" || 
							$email == "mary.meadow@gmail.com" || 
							$email == "crpolstein@aol.com" || 
							$email == "Jewlzfahn@icloud.com" || 
							$email == "katjaemcke@me.com" || 
							$email == "michaeljayrichter@gmail.com" || 
							$email == "robinliawhitaker@gmail.com" || 
							$email == "alex@klmvision.com" || 
							$email == "friedlanderamy@gmail.com" || 
							$email == "Chivexp@me.com" || 
							$email == "fGladden@xrds.org" || 
							$email == "fracey_glynn@spe.sony.com" || 
							$email == "squashy00@mac.com" || 
							$email == "aloc@dneg.com" || 
							$email == "cmcwolff@gmail.com" || 
							$email == "khrich15@yahoo.com" || 
							$email == "mahtabh@msn.com" || 
							$email == "carrie.richman@icloud.com" || 
							$email == "jaugsberger@edenrockmedia.com" || 
							$email == "southcove@comcast.net" || 
							$email == "karieklp@cox.net" || 
							$email == "pschlessel@filmdistrict.com" || 
							$email == "pattygiftos@yahoo.com" || 
							$email == "margaretmeenaghan@gmail.com" || 
							$email == "asteiner@jagsconsulting.com" || 
							$email == "Skipw010@gmail.com" || 
							$email == "Frankilove@gmail.com" || 
							$email == "kpanunzio@yahoo.com" || 
							$email == "kelsykarter@gmail.com" || 
							$email == "leslie.helmer@ogletree.com" || 
							$email == "adalsemer@gmail.com" || 
							$email == "dhara.agileinceptors@gmail.com" || 
							$email == "maggie@bandolierstyle.com" || 
							$email == "Gbgolfer@hotmail.com" || 
							$email == "jlaurent@xrds.org" || 
							$email == "helenricw@comcast.net" || 
							$email == "michael.richter@lazard.com" || 
							$email == "lwend1@hotmail.com" || 
							$email == "kaipegula@gmail.com" || 
							$email == "news@klyce.com" || 
							$email == "meryl_emmerton@spe.sony.com" || 
							$email == "danielle@sunfare.com" || 
							$email == "mccarroll122@gmail.com" || 
							$email == "jslmusic@mac.com" || 
							$email == "p.markle@verison.net" || 
							$email == "randerson@xrds.org" || 
							$email == "laurenrojany@mac.com" || 
							$email == "kerishiotani@yahoo.com" || 
							$email == "am.rosenbaum@me.com" || 
							$email == "d@gmail.com" ||  
							$email == "Namasteeee@aol.com" ||  
							$email == "jennifer90077@gmail.com" ||  
							$email == "ajvernet@gmail.com" ||  
							$email == "iamalisonsherman@gmail.com" ||  
							$email == "karenfurie@yahoo.com" ||  
							$email == "Loanne@comcast.net" ||  
							$email == "marleen.meyers@nyumc.org" ||  
							$email == "wendy@wendykram.com" ||  
							$email == "malibututors@gmail.com" ||  
							$email == "danazeve@gmail.com" ||  
							$email == "melpaul1@earthlink.net" ||  
							$email == "kjacobs27@gmail.com" ||  
							$email == "mikensheba@cox.net" ||  
							$email == "susan_zuckerman@msn.com" ||  
							$email == "annaoetker@gmx.net" ||  
							$email == "courtneymdouglas@icloud.com" ||  
							$email == "jessicafurei@gmail.com" ||  
							$email == "mersedehmy@yahoo.com" ||  
							$email == "pranavpopat@gmail.com" ||  
							$email == "gayleshea@hotmail.com" ||  
							$email == "joeloshapiro@yahoo.com" ||  
							$email == "sandinomia@gmail.com" ||  
							$email == "ewinthrop@hl.com" ||  
							$email == "matttoronto75@gmail.com" ||  
							$email == "anouk@accenteventplanning.com" ||  
							$email == "jamie_stevens@spe.sony.com" ||  
							$email == "tracey_glynn@spe.sony.com" ||  
							$email == "Walter@wbmandell.com" ||  
							$email == "paul@moosetoys.com" ||  
							$email == "lindsayralbert@me.com" ||  
							$email == "Thealpins@gmail.com" ||  
							$email == "kellyperey@yahoo.com" ||  
							$email == "sscharfman@aol.com" ||  
							$email == "darren.dworkin@cshs.org" ||  
							$email == "w.v.wrede@googlemail.com" ||  
							$email == "hillaryrs@aol.com" ||  
							$email == "angeliked@gmail.com" ||  
							$email == "namit@dneg.com" ||  
							$email == "galencrew@aol.com" ||  
							$email == "kidswimteacher@yahoo.com" ||  
							$email == "adrian@acflowershop.com" ||  
							$email == "lisajlicht5@gmail.com" ||  
							$email == "Shana.Gutovich@myriadpictures.com" ||  
							$email == "mweiman@mednet.ucla.edu" ||  
							$email == "dougjoe@gmail.com" ||  
							$email == "Alexilaffoon@gmail.com" ||  
							$email == "janbirchphoto@gmail.com" ||  
							$email == "annlevit@me.com" ||  
							$email == "okmeca@yahoo.com" ||  
							$email == "jason@powell-electric.com" ||  
							$email == "lauriecappello@mac.com" ||  
							$email == "michelepweeger@gmail.com" ||  
							$email == "tammyfine@gmail.com" ||  
							$email == "arnon_manor@spe.sony.com" ||  
							$email == "Carroll@carrollcasting.com" ||  
							$email == "getbeyondfit@gmail.com" ||  
							$email == "acolloffcasting@gmail.com" ||  
							$email == "janandthegirls@yahoo.com" ||  
							$email == "attldk@comcast.net" ||  
							$email == "richardfoos2002@yahoo.com" ||  
							$email == "michael_morgenthal@spe.sony.com" ||  
							$email == "lheisz@gmail.com" ||  
							$email == "therese@thereselotman.com" ||  
							$email == "tibbets.daniel@gmail.com" ||  
							$email == "lindafurie@icloud.com" ||  
							$email == "Romymcfarland@hotmail.com" ||  
							$email == "emitis3@yahoo.com" ||  
							$email == "eric_torres@spe.sony.com" ||  
							$email == "gold.steph@gmail.com" ||  
							$email == "zoe.rogovon@showtime.net" ||  
							$email == "grich528@aol.com" ||  
							$email == "jayneebeckman@gmail.com" ||  
							$email == "julyeis23@gmail.com" ||  
							$email == "pkgiffin@aol.com" ||  
							$email == "award2@me.com" ||  
							$email == "albalou@aol.com" ||  
							$email == "sheldon@budgetmortgagecorp.com" ||  
							$email == "yasi@email.com" ||  
							$email == "led@jaywalker.pictures" ||  
							$email == "michael.weisberg@ms.com" ||  
							$email == "alexandrafurie@gmail.com" ||  
							$email == "jeff@thekivecompany.com" ||  
							$email == "chloenia15@icloud.com" ||  
							$email == "arabellaezralow@icloud.com" ||  
							$email == "debiharris13@gmail.com" ||  
							$email == "suaza@fpa.com" ||  
							$email == "rorygreen@gmail.com" ||  
							$email == "superflykc@gmail.com" ||  
							$email == "VALCABRERA67@GMAIL.COM" ||  
							$email == "tracybrody70@gmail.com" ||  
							$email == "abbykohl@mac.com" ||  
							$email == "stacy.valner@gmail.com" ||  
							$email == "dcohn@medb.ucla.edu" ||  
							$email == "Robbiebfox@gmail.com" ||  
							$email == "jayandjackie@sbcglobal.net" ||  
							$email == "design@inakloss.com" ||  
							$email == "pipthegreat@gmail.com" ||  
							$email == "Mollyharrigan123@gmail.com" ||  
							$email == "codybluemedia@gmail.com" ||  
							$email == "ryan.gagerman@gmail.com" ||  
							$email == "lenaleit@aol.com" ||  
							$email == "ingrid.totalcare@gmail.com" ||  
							$email == "lisa@hireoptions.com" ||  
							$email == "calvinviolet@yahoo.com" ||  
							$email == "cathy@mackeysandrich.com" ||  
							$email == "josh@yourhalf.com" ||  
							$email == "rhaineschlagel@gmail.com" ||  
							$email == "groslnd@comcast.net" ||  
							$email == "a_astrachan@onyxpartners.com" ||  
							$email == "Jessicaisles@yahoo.co.uk" ||  
							$email == "peter@portaviabh.com" ||  
							$email == "darcykp@mac.com" ||  
							$email == "mwcurtis@mwcurtis.com" ||  
							$email == "alisonkal@aol.com" ||  
							$email == "nino@miergallery.com" ||  
							$email == "kaicole@msn.com" ||  
							$email == "peri@gilpin.com" ||  
							$email == "claudiamharrison@yahoo.com" ||  
							$email == "kkerwin@mac.com" ||  
							$email == "ZoeBurke22@marlborough.org" ||  
							$email == "chrissy@houseofblus.com" ||  
							$email == "jennifermeder@gmail.com" ||  
							$email == "mariecapitti@gmail.com" ||  
							$email == "Deanna@sgc.bz" ||  
							$email == "Dorif@aol.com" ||  
							$email == "Ellatruweston@gmail.com" ||  
							$email == "kathyfranklin90@gmail.com" ||  
							$email == "Jodi@weingartenfamily.com" ||  
							$email == "mrsteele@mednet.ucla.edu" ||  
							$email == "dawmusic@yahoo.com" ||  
							$email == "erica@west.net" ||  
							$email == "smhurley@verizon.net" ||  
							$email == "rosannec@mac.com" ||  
							$email == "Juliereisz@sbcglobal.net" ||  
							$email == "fisher.ishino@gmail.com" ||  
							$email == "cgianni@xrds.org" ||  
							$email == "bella.w22@k12.xrds.org" ||  
							$email == "Kjglw@yahoo.com" ||  
							$email == "h2obetty@socal.rr.com" ||  
							$email == "Kimcburger@gmail.com" ||  
							$email == "sharondegreiff@gmail.com" ||  
							$email == "lori@loribregman.com" ||  
							$email == "pollyeshel@earthlink.net" ||  
							$email == "Todd_Black@spe.sony.com" ||  
							$email == "Peternorman@aol.com" ||  
							$email == "amuth333@gmail.com" ||  
							$email == "samarbloomingdale@gmail.com" ||  
							$email == "larecruit@aol.com" ||  
							$email == "rkennedy@moxiefirecracker.com" ||  
							$email == "sandygjohnston287@yahoo.com" ||  
							$email == "annabiederman@yahoo.com" ||  
							$email == "Janabezdekt@gmail.com" ||  
							$email == "Carla.pennington@cbsparamount.com" ||  
							$email == "awardell@firstrepublic.com" ||  
							$email == "Frkrueger@me.com" ||  
							$email == "ginabina74@me.com" ||  
							$email == "lf@lindseyfoard.com" ||  
							$email == "shilpa@nayali.la" ||  
							$email == "dana.zeve@gmail.com" ||  
							$email == "christina@chillbywill.com" ||  
							$email == "Beate.chee@mac.com" ||  
							$email == "Kirachow@yahoo.com" ||  
							$email == "Antonattac@me.com" ||  
							$email == "ptsullivan@comcast.net" ||  
							$email == "csolton@reliancefi.com" ||  
							$email == "Lindsayalbert@me.com" ||  
							$email == "wyzcrkrs@pacbell.net" ||  
							$email == "Memcke@mac.com" ||  
							$email == "Fcopeland@mac.com" ||  
							$email == "Beatrice@knigge.de" ||  
							$email == "alisongarb@yahoo.com" ||  
							$email == "bfldesign1@comcast.net" ||  
							$email == "H.easton@yahoo.com" ||  
							$email == "olivia@oliviacorwin.com" ||  
							$email == "nettibode@me.com" ||  
							$email == "Carolhoyt@mac.com" ||  
							$email == "Schroeder-werthmann@t-online.de" ||  
							$email == "Randi@randisinger.com" ||  
							$email == "ecampbellnyc@gmail.com" ||  
							$email == "Rocio.kissling@thinklatino.net" ||  
							$email == "Astrid@bscher.net" ||  
							$email == "Dave@neumancpa.com" ||  
							$email == "zefarrell@gmail.com" ||  
							$email == "Billodowd@dolphinentertainment.net" ||  
							$email == "Marylg@mac.com" ||  
							$email == "Mmoayedi@verizon.net" ||  
							$email == "fay.vahdani@luxehc.com" ||  
							$email == "Jenniferkell@verizon.net" ||  
							$email == "Joemcadams@me.com" ||  
							$email == "nshore@fortress.com" ||  
							$email == "jendshore@gmail.com" ||  
							$email == "Malimento@xrds.org" ||  
							$email == "jmitchs3@aol.com" ||  
							$email == "Iengel@ultimed.org" ||  
							$email == "linda2@gmail.com" ||  
							$email == "nikkig1@roadrunner.com" ||  
							$email == "Drkmbeckmann@gmail.com" ||  
							$email == "dana.cataldi@thepartnerstrust.com" ||  
							$email == "Lee@drleehausner.com" ||  
							$email == "kgibson@equilibra.us" ||
							$email == "dan@srfr.com" ||  
							$email == "styles.by.alexandra@gmail.com" ||  
							$email == "carolynneuner@icloud.com" ||  
							$email == "jennyfritz70@hotmail.com" ||  
							$email == "mgeibelson@robinskaplan.com" ||  
							$email == "rodgarcia59@live.com" ||  
							$email == "Melinda@melindamoore.con" ||  
							$email == "Alex@fullBRANCH.com" ||  
							$email == "Lili@liliradu.com" ||  
							$email == "jacksonblackburn93@gmail.com" ||  
							$email == "Kroehne@getonair.net" ||  
							$email == "lisabwoods@yahoo.com" ||  
							$email == "julie@stickybesocks.com" ||  
							$email == "janiewood1@mac.com" ||  
							$email == "watari@usc.edu" ||  
							$email == "Taugsberger@edenrockmedia.com" ||  
							$email == "Kwite6@me.com" ||  
							$email == "melissa_weiss_98@yahoo.com" ||  
							$email == "tracyrossie@gmail.com" ||  
							$email == "michelle@michelle-wolf.com" ||  
							$email == "t.grayson2@verizon.net" ||  
							$email == "reggiefisher2@gmail.com" ||  
							$email == "ilysebronte@gmail.com" ||  
							$email == "Zrct55@aol.com" ||  
							$email == "Ciaomerle@mullinenterprises.com" ||  
							$email == "Sandraitkoff@gmail.com" ||  
							$email == "Tabuinla@aol.com" ||  
							$email == "shirink14@gmail.com" ||  
							$email == "Kirk.damico@myriadpictures.com" ||  
							$email == "Yaratabet@me.com" ||  
							$email == "kcgilbertortho@gmail.com" ||  
							$email == "OhK2C@aol.com" ||  
							$email == "Mwengel@aol.com" ||  
							$email == "joelshapiro@yahoo.com" ||  
							$email == "Fini0189@aol.com" ||  
							$email == "nancypardo@me.com" ||  
							$email == "Taraellison1@gmail.com" ||  
							$email == "Annchristin@gmail.com" ||  
							$email == "Anna.borja@jugueton.com.sv" ||  
							$email == "sundher@aol.com" ||  
							$email == "annkathrinseif@gmail.com" ||  
							$email == "caroltaubman@gmail.com" ||  
							$email == "picklessorell@aol.com" ||  
							$email == "Ellros7@yahoo.com" ||  
							$email == "Bethhealy@sbcglobal.net" ||  
							$email == "lenny.khazan@gmail.com" ||  
							$email == "Bholden@newtheme.net" ||  
							$email == "lovekeepsmewishing@gmail.com" ||  
							$email == "nicolegumpert@icloud.com" ||  
							$email == "rms940@gmail.com" ||  
							$email == "Soyon777@gmail.com" ||  
							$email == "Thkloss@yahoo.com" ||  
							$email == "mel.wanger@verizon.net" ||  
							$email == "uhmeer@yahoo.com" ||  
							$email == "stanauklang@gmail.com" ||  
							$email == "Shollycul@aol.com" ||  
							$email == "mikestocksdale@yahoo.com" ||  
							$email == "filmdog@gmail.com" ||  
							$email == "Tschachotin@gmail.com" ||  
							$email == "oortega2149@yahoo.com" ||  
							$email == "hgores@yahoo.com" ||  
							$email == "mpjoport@mac.com" ||  
							$email == "hdriordan@gmail.com" ||  
							$email == "barbarasneider8@gmail.com" ||  
							$email == "timm.oberwelland@mac.com" ||  
							$email == "angela@glistenproductions.com" ||  
							$email == "andreanj@optonline.net" ||  
							$email == "cvonwrede@yahoo.de" ||  
							$email == "Info@sweetfin.com" ||  
							$email == "eburka@streetsense.com" ||  
							$email == "garciasheinbaum@gmail.com" ||  
							$email == "mpegs3@aol.com" ||  
							$email == "Hherzsprung@googlemail.com" ||  
							$email == "macrina.wang@yale.edu" ||  
							$email == "strongandkindla@gmail.com" ||  
							$email == "blakekamper@gmail.com" ||  
							$email == "Heatherh@heatherhartt.com" ||  
							$email == "Angelarichardson@libello.com" ||  
							$email == "bronte411@gmail.com" ||  
							$email == "Zinapistor@gmail.com" ||  
							$email == "alison@outfithome.com" ||  
							$email == "Jsbelzberg@gmail.com" ||  
							$email == "Bhausner@yahoo.com" ||  
							$email == "Risebarbakow1@gmail.com" ||  
							$email == "Marte.have@googlemail.com" ||  
							$email == "Aparker@xrds.org" ||  
							$email == "cfrankel@me.com" ||  
							$email == "Claudia@harmer.de" ||  
							$email == "J.rusnak@screencraft.de" ||  
							$email == "Tamtammalibu@icloud.com" ||  
							$email == "cbfeingold980@gmail.com" ||  
							$email == "skalfa@comcast.net" ||  
							$email == "michelle.horwitch@gmail.com" ||  
							$email == "Theflyprof@gmail.com" ||  
							$email == "maureene1@gmail.com" ||  
							$email == "nogacohen614@gmail.com" ||  
							$email == "Pkawa@hotmail.com" ||  
							$email == "julees@gmail.com" ||  
							$email == "c.mckenna@ajacf.org" ||  
							$email == "Jenniferklein1969@gmail.com" ||  
							$email == "mason@madhappy.com" ||  
							$email == "sdg1865@gmail.com" ||  
							$email == "yaelas@gmail.com" ||  
							$email == "Edelstone@comcast.net" ||  
							$email == "frankilovetribe@gmail.com" ||  
							$email == "coleylaffoon@gmail.com" ||  
							$email == "Ginamccloskey@yahoo.com" ||  
							$email == "gtstina@yahoo.com" ||  
							$email == "jill@jillroberts.com" ||  
							$email == "Bh@brigitta-horvath.com" ||  
							$email == "Maria@davant-Garde.com" ||  
							$email == "merchriskai@mac.com" ||  
							$email == "laronzon@icloud.com" ||  
							$email == "dweis34358@aol.com" ||  
							$email == "abtroester@gmail.com" ||  
							$email == "Whatsupchuck@icloud.com" ||  
							$email == "testuser@gmail.com" ||  
							$email == "testuser@gmail.com" ||  
							$email == "himanshuray.ray@gmail.com" ||  
							$email == "AmZy211@yahoo.com" ||  
							$email == "juliebenco@gmail.com" ||  
							$email == "mariecjacobson@gmail.com" ||  
							$email == "Ymatlof@hwhmf.com" ||  
							$email == "trdiablo@gmail.com" ||  
							$email == "SSimon@xrds.org" ||  
							$email == "Astondominquez27@gmail.com" ||  
							$email == "amandasrichmond@yahoo.com" ||  
							$email == "Lilycataldi@yahoo.com" ||  
							$email == "Danielvoll@mac.com" ||  
							$email == "Nmcs4s@aol.com" ||  
							$email == "blahblahblonde@gmail.com" ||  
							$email == "Jodyhope13@gmail.com" ||  
							$email == "Polalu@yahoo.com" ||  
							$email == "Raymu1963@yahoo.com.mx" ||  
							$email == "Aleccume@gmail.com" ||  
							$email == "matt@levincreative.com" ||  
							$email == "Ale600@me.com" ||  
							$email == "Nstrogoff@icloud.com" ||  
							$email == "meganshortz11@gmail.com" ||  
							$email == "pollyeshel@gmail.com" ||  
							$email == "amy_gc64@yahoo.com" ||  
							$email == "jon@jmgcapital.com" ||  
							$email == "Jerzy1216@gmail.com" ||  
							$email == "Mitchellmeditation@gmail.com" ||  
							$email == "Veep555@aol.com" ||  
							$email == "ctlady929@gmail.com" ||  
							$email == "amzy.patel@gmail.com" ||  
							$email == "redlotus013@yahoo.com" ||  
							$email == "jkleinert@jenniferkleinert.com" ||  
							$email == "dd@gmail.com" ||  
							$email == "dhara.nivu@gmail.com" ||  
							$email == "alex@iKahanmedia.com" ||  
							$email == "asherryboys@gmail.com" ||  
							$email == "lking@xrds.org" ||  
							$email == "milljacq@me.com" ||  
							$email == "msmichellenader@gmail.com" ||  
							$email == "sweetbetty30@aol.com" ||  
							$email == "milljacq@me.com" ||  
							$email == "drgulas@gmail.com" ||  
							$email == "sopoadams@gmail.com" ||  
							$email == "Msannac007@yahoo.com" ||  
							$email == "Juliebes10@hotmail.com" ||  
							$email == "danielle@bbdproperties.com" ||  
							$email == "lee@bergstein.tv" ||  
							$email == "Libertyr@me.com" ||  
							$email == "Cachibaier@yahoo.com" ||  
							$email == "tgiftos@nacllc.com" ||  
							$email == "bergy329@mac.com" ||  
							$email == "jakers@bright.net" ||  
							$email == "katherine.kkbrum@gmail.com" ||  
							$email == "sarah@theaibels.com" ||  
							$email == "julielevi2@gmail.com" ||  
							$email == "april@elementadvertising.com" ||  
							$email == "darren@cuttingedgegroup.com" ||  
							$email == "Anouk3@mac.com" ||  
							$email == "amon.joanie@gmail.com" ||  
							$email == "dani28@me.com" ||  
							$email == "sayoko@teitelco.com" ||  
							$email == "bezdekt@gmail.com" ||  
							$email == "Zach.Ellenberg@gmail.com" ||  
							$email == "Kathybina@aol.com" ||  
							$email == "kb.savannah@gmail.com" ||  
							$email == "Sfnrouz@gmail.com" ||  
							$email == "kamitchell@mac.com" ||  
							$email == "rvenick@mednet.ucla.edu" ||  
							$email == "Andreaslutske@yahoo.com" ||  
							$email == "mdrcampos@gmail.com" ||  
							$email == "srice@gmrfamilylaw.com" ||  
							$email == "Kristamontagna@me.com" ||  
							$email == "hhalwani@me.com" ||  
							$email == "Judeguillemot@gmail.com" ||  
							$email == "fifi.monstie@gmail.com" ||  
							$email == "Kathybina@aol.com" ||  
							$email == "clmacavinta@gmail.com" ||  
							$email == "mikeahekoyan@gmail.com" ||  
							$email == "Mandy@smtmgt.com" ||  
							$email == "trishaandryan@gmail.com" ||  
							$email == "dianeronnau@gmail.com" ||  
							$email == "pattyvaughn@sbcglobal.net" ||  
							$email == "gene@machnet.com" ||  
							$email == "sharoncarr67@yahoo.com" ||  
							$email == "Ninatarnay@hotmail.com"  
							) ///$email == "vkvia6@gmail.com"  
						{
						}
						else 
						{ 
							$srn++;
							
							//if($email == "SSimon@xrds.org")
							//{

						
							//echo print_r($email);
							//print_r($email);
						
							$arrEmailInfo = prepareEmailFormat(array(0, 0), $emailFormatInfoArr, $objDBQuery, $appCode, $userInfoArr[0]['name'], '', '', '', $eventOrStreamTitle, $ticketCode, $timing, $eventTime);
							//print_r($arrEmailInfo['strBody']);
							//echo $srn." ".$email." ". $ticketCode." ".$userCode." ".$createdOnDate." - ".$buyInfo;
							//
							echo "<br>";
							//echo $email;
							//$email = "vkvia6@gmail.com";
							sendEmail($email, $subM, $mailB, $arrEmailInfo['appFromEmailTxt'], $arrEmailInfo['appNoreplyEmail'], 'HTML');	
							//sendEmail($email, $arrEmailInfo['emailSubject'], $arrEmailInfo['strBody'], $arrEmailInfo['appFromEmailTxt'], $arrEmailInfo['appNoreplyEmail'], 'HTML');	
							//}
						}
					}
				
				}
			}			

		}
		
	}
}
echo "Swig Manager Cron Job Fired on ".date('d-m-Y H:i:s')."\n ";

//205, 201
?>