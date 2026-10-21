<?php
/**
 * One-off: fill "Politika e privatësisë" (Privacy Policy) and "Kushtet e përdorimit"
 * (Terms and Conditions) from the German originals on mimatest.net
 * (/datenschutzerklaerung/ and /agb/), translated into Albanian (page content) and
 * English (stored as TranslatePress translations, editable in Translate Page afterwards).
 *
 *   wp --user=admin eval-file /scripts/legal-pages.php pages        # 1. write the Albanian pages
 *   (visit /en/privacy-policy/ and /en/terms-and-conditions/)
 *   wp --user=admin eval-file /scripts/legal-pages.php dictionary   # 2. store the English
 *
 * Each block: [ type, Albanian, English ]. Types: h2, h3, h4, p, pb (bold paragraph),
 * ul (Albanian and English are arrays of items). Inline markup is limited to <a> and <br>,
 * which is where TranslatePress splits a block into separate strings.
 */

$dpo    = '<a href="mailto:datenschutz@mimatest.net">datenschutz@mimatest.net</a>';
$post   = '<a href="mailto:post@mimatest.net">post@mimatest.net</a>';
$db_mail = '<a href="mailto:post@darmbulanz.net">post@darmbulanz.net</a>';

// Sentences that repeat across the privacy sections.
$s = array(
	'presentation' => array( 'Ky përpunim shërben për prezantimin publik të kompanisë sonë.', 'This processing serves the public presentation of our company.' ),
	'enquiry_data' => array( 'Në këtë kuadër përpunojmë përmbajtjen e kërkesave që na vini në dispozicion, të dhënat tuaja të përdoruesit dhe datën e komunikimit si kategori të dhënash që lidhen me ju.', 'In doing so, we process the content of the enquiries you provide, your user data and the date of communication as categories of data relating to you.' ),
	'basis_b'      => array( 'Baza ligjore është zbatimi i masave të nevojshme parakontraktore dhe përmbushja e kontratave sipas nenit 6(1)(b) të GDPR-së.', 'The legal basis is the implementation of necessary pre-contractual measures and the performance of contracts pursuant to Art. 6(1)(b) GDPR.' ),
	'need_enquiry' => array( 'Përpunimi i të dhënave të mësipërme që lidhen me ju është i nevojshëm për trajtimin e kërkesës suaj. Nëse nuk na i vini në dispozicion këto të dhëna, nuk mund ta trajtojmë kërkesën tuaj.', 'The processing of the above data relating to you is necessary to handle your enquiry. If you do not provide us with this data, we cannot handle your enquiry.' ),
	'usa'          => array( 'Të dhënat e mësipërme që lidhen me ju transferohen në SHBA, si vend i tretë me vendim për përshtatshmëri sipas nenit 45 të GDPR-së.', 'The above data relating to you is transferred to the USA as a third country with an adequacy decision pursuant to Art. 45 GDPR.' ),
	'withdraw'     => array( 'Keni të drejtë ta tërhiqni pëlqimin tuaj në çdo kohë, me efekt për të ardhmen. Ligjshmëria e përpunimit të kryer në bazë të pëlqimit tuaj deri në tërheqjen e tij nuk cenohet.', 'You have the right to withdraw your consent at any time with effect for the future. The lawfulness of the processing carried out on the basis of your consent until its withdrawal is not affected.' ),
	'withdraw_mail'=> array( "Pëlqimin tuaj për këtë veprimtari përpunimi mund ta tërhiqni në çdo kohë me email në {$dpo}.", "You can withdraw your consent to this processing activity at any time by email to {$dpo}." ),
	'object'       => array( 'Sipas nenit 21 të GDPR-së, keni të drejtë të kundërshtoni përpunimin e përshkruar më sipër të të dhënave që lidhen me ju, nëse ka arsye që burojnë nga situata juaj e veçantë ose nëse kundërshtimi juaj drejtohet kundër marketingut të drejtpërdrejtë.', 'Pursuant to Art. 21 GDPR, you have the right to object to the processing of data relating to you described above if there are grounds arising from your particular situation or if your objection is directed against direct marketing.' ),
	'consent_keep' => array( 'Të dhënat ruhen deri në tërheqjen e pëlqimit tuaj. Një regjistër të fshirjeve e ruajmë për tre vjet.', 'The data is stored until you withdraw your consent. We keep a deletion log for three years.' ),
	'joint_info'   => array( 'Sipas kësaj marrëveshjeje, kompania merr përsipër përmbushjen e detyrimeve të transparencës dhe ju mund të informoheni këtu mbi përpunimin e të dhënave tuaja personale:', 'Under this agreement, the company assumes responsibility for fulfilling the transparency obligations, and you can find information about the processing of your personal data here:' ),
	'keep_5y'      => array( 'Me një llogari të identifikuar, të dhënat ruhen derisa përdoruesi t’i fshijë ato brenda llogarisë ose të fshijë të gjithë llogarinë, si dhe 5 vjet për cookies teknikisht të nevojshme. Pa një llogari të identifikuar, kohëzgjatja e ruajtjes është 5 vjet për cookies teknikisht të nevojshme.', 'With a logged-in account, the data is stored until the user deletes it within the account or deletes the entire account, and for 5 years for technically necessary cookies. Without a logged-in account, the storage period is 5 years for technically necessary cookies.' ),
);
$sub = function ( $n, $sq, $en ) { return array( 'pb', "{$n} {$sq}", "{$n} {$en}" ); };
$to  = function ( $sq_recipient, $en_recipient ) {
	return array( 'p', "Të dhënat e mësipërme që lidhen me ju u transmetohen {$sq_recipient}.", "The above data relating to you is transmitted to {$en_recipient}." );
};
$link = function ( $url ) { return '<a href="' . $url . '" target="_blank" rel="noopener">' . $url . '</a>'; };

$meta_sq = 'Meta Platforms Ireland Limited, 4 Grand Canal Place, Grand Canal Harbour, Dublin 2, Irlandë, dhe Meta Platforms, Inc., 1 Hacker Way, Menlo Park, California 94025, SHBA';
$meta_en = 'Meta Platforms Ireland Limited, 4 Grand Canal Place, Grand Canal Harbour, Dublin 2, Ireland, and Meta Platforms, Inc., 1 Hacker Way, Menlo Park, California 94025, USA';

// A social media presence section (Facebook, Instagram, LinkedIn, Pinterest, TikTok, Spotify).
$presence = function ( $n, $sq_title, $en_title, $keep, $recipients, $joint = null, $basis = null ) use ( $s, $sub ) {
	$blocks = array(
		array( 'h4', "{$n}. {$sq_title}", "{$n}. {$en_title}" ),
		$sub( "{$n}.1.", 'Qëllimet', 'Purposes' ), array( 'p', $s['presentation'][0], $s['presentation'][1] ),
		$sub( "{$n}.2.", 'Llojet e të dhënave', 'Types of data' ), array( 'p', $s['enquiry_data'][0], $s['enquiry_data'][1] ),
		$sub( "{$n}.3.", 'Baza ligjore', 'Legal basis' ), $basis ? $basis : array( 'p', $s['basis_b'][0], $s['basis_b'][1] ),
		$sub( "{$n}.4.", 'Domosdoshmëria', 'Necessity' ), array( 'p', $s['need_enquiry'][0], $s['need_enquiry'][1] ),
		$sub( "{$n}.5.", 'Kohëzgjatja e ruajtjes', 'Storage period' ), $keep,
		$sub( "{$n}.6.", 'Marrësit', 'Recipients' ),
	);
	$blocks = array_merge( $blocks, $recipients );
	if ( $joint ) {
		$blocks[] = $sub( "{$n}.7.", 'Informacion i mëtejshëm mbi përgjegjësinë e përbashkët', 'Further information on joint controllership' );
		$blocks   = array_merge( $blocks, $joint );
	}
	return $blocks;
};
$joint = function ( $sq_party, $en_party, $agreement, $info ) use ( $s, $link ) {
	return array(
		array( 'p', "Me {$sq_party} kemi lidhur një marrëveshje për përgjegjësinë e përbashkët: " . $link( $agreement ) . '.', "We have concluded a joint controllership agreement with {$en_party}: " . $link( $agreement ) . '.' ),
		array( 'p', $s['joint_info'][0] . ' ' . $link( $info ) . '.', $s['joint_info'][1] . ' ' . $link( $info ) . '.' ),
	);
};

/* ------------------------------------------------------------------ Privacy policy */

$privacy = array_merge(
	array(
		array( 'h2', 'Njoftim për mbrojtjen e të dhënave personale', 'Privacy notice' ),
		array( 'p', 'Të nderuar vizitorë të kësaj faqeje interneti,', 'Dear visitor to this website,' ),
		array( 'p', 'mbrojtja e të dhënave tuaja personale është një çështje e rëndësishme jo vetëm për ju, por edhe për ne, përgjegjësit e faqes së internetit „Mimatest.net“ (në vijim „ne“, „neve“). E vlerësojmë shumë besimin tuaj se i trajtojmë të dhënat tuaja personale me kujdes dhe në përputhje me ligjin. Të dhënat tuaja trajtohen prej nesh në mënyrë konfidenciale.', 'the protection of your personal data is an important concern not only for you, but also for us, the persons responsible for the website “Mimatest.net” (hereinafter “we”, “us”). We greatly appreciate your trust that we handle your personal data conscientiously and in compliance with the law. Your data is treated confidentially by us.' ),
		array( 'p', 'Me këtë njoftim për mbrojtjen e të dhënave nuk duam vetëm të përmbushim detyrimin tonë ligjor sipas neneve 13 dhe 14 të GDPR-së, por edhe t’ju shpjegojmë në mënyrë të kuptueshme se cilat të dhëna personale mblidhen kur vizitoni faqen tonë të internetit dhe si i trajtojmë ato.', 'With this privacy notice we not only want to fulfil our legal obligation under Articles 13 and 14 GDPR, but also to explain to you in an understandable way which personal data is collected when you visit our website and how we handle it.' ),
		array( 'h4', 'Përgjegjësi për përpunimin', 'Controller' ),
		array( 'p', 'Klaudia Sheko', 'Klaudia Sheko' ),
		array( 'h4', 'Të dhënat e kontaktit të zyrtarit për mbrojtjen e të dhënave', 'Contact details of the data protection officer' ),
		array( 'p', $dpo, $dpo ),

		array( 'h3', 'Përdorimi i faqes së internetit', 'Use of the website' ),
		array( 'p', 'Në këtë seksion mësoni se cilat nga të dhënat tuaja përpunojmë për t’ju vënë në dispozicion faqen tonë të internetit.', 'In this section you will learn which of your data we process in order to provide you with our website.' ),

		array( 'h4', '1. Vënia në dispozicion e faqes sonë të internetit', '1. Provision of our website' ),
		$sub( '1.1.', 'Qëllimet', 'Purposes' ), array( 'p', $s['presentation'][0], $s['presentation'][1] ),
		$sub( '1.2.', 'Llojet e të dhënave', 'Types of data' ),
		array( 'p', 'Në këtë kuadër përpunojmë të dhëna lidhjeje (të dhëna aksesi dhe të dhëna të pajisjes fundore) si kategori të dhënash që lidhen me ju.', 'In doing so, we process connection data (access data and end-device data) as categories of data relating to you.' ),
		$sub( '1.3.', 'Baza ligjore', 'Legal basis' ), array( 'p', $s['basis_b'][0], $s['basis_b'][1] ),
		$sub( '1.4.', 'Domosdoshmëria', 'Necessity' ),
		array( 'p', 'Përpunimi i të dhënave të mësipërme që lidhen me ju është i nevojshëm që të mund të kontaktoni me ne. Nëse nuk na i vini në dispozicion këto të dhëna, nuk mund t’jua shfaqim faqen tonë të internetit.', 'The processing of the above data relating to you is necessary for you to contact us. If you do not provide us with this data, we cannot display our website to you.' ),
		$sub( '1.5.', 'Kohëzgjatja e ruajtjes', 'Storage period' ),
		array( 'p', 'Të dhënat ruhen për kohëzgjatjen e seancës së shfletuesit.', 'The data is stored for the duration of the browser session.' ),
		array( 'p', 'Kur jeni të identifikuar me një llogari Google, YouTube i ruan të dhënat derisa përdoruesi t’i fshijë ato brenda llogarisë ose të fshijë të gjithë llogarinë Google, si dhe dy vjet për cookies teknikisht të nevojshme. Pa një llogari Google të identifikuar, të dhënat ruhen derisa të fshihen historiku i kërkimeve dhe historiku i shikimeve në YouTube, si dhe dy vjet për cookies teknikisht të nevojshme.', 'When you are logged in to a Google account, YouTube stores the data until the user deletes it within the account or deletes the entire Google account, and for two years for technically necessary cookies. Without a logged-in Google account, the data is stored until the YouTube search history and watch history are deleted, and for two years for technically necessary cookies.' ),

		array( 'h4', '2. Siguria e faqes së internetit', '2. Website security' ),
		$sub( '2.1.', 'Qëllimet', 'Purposes' ),
		array( 'p', 'Ky përpunim shërben për të garantuar funksionimin pa ndërprerje të faqes së internetit.', 'This processing serves to ensure the trouble-free operation of the website.' ),
		$sub( '2.2.', 'Llojet e të dhënave', 'Types of data' ),
		array( 'p', 'Në këtë kuadër përpunojmë të dhëna lidhjeje (të dhëna aksesi dhe të dhëna të pajisjes fundore) dhe informacionin e sistemit tuaj si kategori të dhënash që lidhen me ju.', 'In doing so, we process connection data (access data and end-device data) and your system information as categories of data relating to you.' ),
		$sub( '2.3.', 'Baza ligjore', 'Legal basis' ),
		array( 'p', 'Baza ligjore është neni 6(1)(f) i GDPR-së. Interesi ynë legjitim është garantimi i funksionimit pa ndërprerje të faqes së internetit.', 'The legal basis is Art. 6(1)(f) GDPR. Our legitimate interest is ensuring the trouble-free operation of the website.' ),
		$sub( '2.4.', 'Kohëzgjatja e ruajtjes', 'Storage period' ),
		array( 'p', 'Kohëzgjatja e ruajtjes është 30 ditë.', 'The storage period is 30 days.' ),
		$sub( '2.5.', 'E drejta juaj për kundërshtim', 'Your right to object' ),
		array( 'p', $s['object'][0], $s['object'][1] ),
		array( 'p', "Të drejtën e kundërshtimit mund ta ushtroni duke dërguar një email në {$dpo}.", "You can exercise your right to object by sending an email to {$dpo}." ),

		array( 'h4', '3. Menaxhimi i pëlqimeve', '3. Consent management' ),
		$sub( '3.1.', 'Qëllimet', 'Purposes' ),
		array( 'p', 'Ky përpunim shërben për administrimin dhe dokumentimin e pëlqimeve tuaja.', 'This processing serves to manage and document your consents.' ),
		$sub( '3.2.', 'Llojet e të dhënave', 'Types of data' ),
		array( 'p', 'Në këtë kuadër përpunojmë të dhëna pëlqimi si të dhëna që lidhen me ju.', 'In doing so, we process consent data as data relating to you.' ),
		$sub( '3.3.', 'Baza ligjore', 'Legal basis' ),
		array( 'p', 'Baza ligjore është detyrimi ynë për të provuar pëlqimin sipas nenit 6(1)(c) të GDPR-së, në lidhje me nenin 5(2) dhe nenin 7(1) të GDPR-së.', 'The legal basis is our obligation to provide evidence pursuant to Art. 6(1)(c) GDPR in conjunction with Art. 5(2) and Art. 7(1) GDPR.' ),
		$sub( '3.4.', 'Kohëzgjatja e ruajtjes', 'Storage period' ), array( 'p', $s['consent_keep'][0], $s['consent_keep'][1] ),

		array( 'h3', 'Kontakti', 'Contact' ),
		array( 'p', 'Në këtë kapitull mësoni se si mund të kontaktoni me ne dhe cilat nga të dhënat tuaja përpunojmë kur na dërgoni një kërkesë.', 'In this chapter you will learn how you can contact us and which of your data we process when you send us an enquiry.' ),
		array( 'h4', '4. Kontakti me telefon, email dhe chat', '4. Contact by telephone, email and chat' ),
		array( 'p', 'Darmbulanz GmbH<br>Otto-Schiedt-Str. 39<br>04179 Leipzig<br>Gjermani', 'Darmbulanz GmbH<br>Otto-Schiedt-Str. 39<br>04179 Leipzig<br>Germany' ),
		array( 'p', 'Përfaqësuar nga:<br>Z. Paul M. Wötzel', 'Represented by:<br>Mr Paul M. Wötzel' ),
		array( 'p', "Kontakti:<br>Telefon: +49 (0) 172 2691064 (WhatsApp, Signal, Telegram)<br>Email: {$post}", "Contact:<br>Phone: +49 (0) 172 2691064 (WhatsApp, Signal, Telegram)<br>Email: {$post}" ),

		array( 'h3', 'Marketingu', 'Marketing' ),
		array( 'p', 'Lexoni në këtë seksion se cilat nga të dhënat tuaja përdorim kur klikoni mbi reklamat tona. Analiza e sjelljes suaj si përdorues na ndihmon të përmirësojmë reklamat tona, ofertën tonë dhe faqen e internetit. Nëse jeni abonuar në buletinin tonë informativ dhe klikoni mbi lidhjet drejt artikujve tanë, ju shpjegojmë cilat nga të dhënat tuaja përdoren në këtë rast.', 'In this section you can read which of your data we use when you click on our advertisements. Analysing your user behaviour helps us to improve our advertisements, our offer and the website. If you have subscribed to our newsletter and click on links to our articles, we explain which of your data is used in the process.' ),
		array( 'h4', '5. Analiza e sjelljes së përdoruesve', '5. User behaviour analysis' ),
		$sub( '5.1.', 'Qëllimet', 'Purposes' ),
		array( 'p', 'Ky përpunim shërben për analizën e sjelljes së përdoruesve.', 'This processing serves to analyse user behaviour.' ),
		$sub( '5.2.', 'Llojet e të dhënave', 'Types of data' ),
		array( 'p', 'Në këtë kuadër përpunojmë të dhënat tuaja të lidhjes (të dhëna aksesi dhe të pajisjes fundore), të dhënat tuaja të përdorimit, të dhëna për ndërveprimin tuaj me reklamat tona, të dhënat tuaja demografike, identifikuesin tuaj unik të reklamave dhe identifikuesin tuaj unik të pajisjes si kategori të dhënash që lidhen me ju.', 'In doing so, we process your connection data (access and end-device data), your usage data, data on your interaction with our advertisements, your demographic data, your unique advertising ID and your unique device ID as categories of data relating to you.' ),
		$sub( '5.3.', 'Baza ligjore', 'Legal basis' ),
		array( 'p', 'Baza ligjore është pëlqimi juaj i shprehur për analizën e marketingut sipas nenit 6(1)(a) të GDPR-së.', 'The legal basis is your express consent to the marketing analysis pursuant to Art. 6(1)(a) GDPR.' ),
		$sub( '5.4.', 'Kohëzgjatja e ruajtjes', 'Storage period' ),
		array( 'p', 'Kohëzgjatja e ruajtjes nga Mimatest GmbH për këtë përpunim të të dhënave të mësipërme që lidhen me ju është:', 'The storage period of Mimatest GmbH for this processing of the above data relating to you is:' ),
		array( 'ul',
			array( 'për Google Analytics 14 muaj (të dhënat demografike 2 muaj),', 'për gjurmimin e Google Ads 24 muaj,', 'për Meta Pixel 24 muaj,', 'për Meta for Business 6 muaj,', 'për Meta Ads Manager 24 muaj,', 'për Microsoft Advertising 180 ditë,', 'për Pinterest Advertising 6 muaj,', 'për Spotify Ads 180 ditë,', 'për Twitter Advertising 6 muaj,', 'dhe për LinkedIn Insight Tag 180 ditë.' ),
			array( 'for Google Analytics 14 months (demographic data 2 months),', 'for Google Ads tracking 24 months,', 'for Meta Pixel 24 months,', 'for Meta for Business 6 months,', 'for Meta Ads Manager 24 months,', 'for Microsoft Advertising 180 days,', 'for Pinterest Advertising 6 months,', 'for Spotify Ads 180 days,', 'for Twitter Advertising 6 months,', 'and for LinkedIn Insight Tag 180 days.' ),
		),
		$sub( '5.5.', 'Marrësit', 'Recipients' ),
		$to( 'Google LLC, 1600 Amphitheatre Pkwy., Mountain View, CA 94043, SHBA', 'Google LLC, 1600 Amphitheatre Pkwy., Mountain View, CA 94043, USA' ),
		$to( $meta_sq, $meta_en ),
		$to( 'Microsoft Corporation, One Microsoft Way, Redmond, WA 98052-6399, SHBA', 'Microsoft Corporation, One Microsoft Way, Redmond, WA 98052-6399, USA' ),
		$to( 'Pinterest Europe Ltd., Palmerston House, 2nd Floor, Fenian Street, Dublin 2, Irlandë', 'Pinterest Europe Ltd., Palmerston House, 2nd Floor, Fenian Street, Dublin 2, Ireland' ),
		$to( 'Spotify AB, Regeringsgatan 19, 111 53 Stockholm, Suedi, dhe Spotify USA Inc., 150 Greenwich Street, Floor 62, New York, NY 10007, SHBA', 'Spotify AB, Regeringsgatan 19, 111 53 Stockholm, Sweden, and Spotify USA Inc., 150 Greenwich Street, Floor 62, New York, NY 10007, USA' ),
		$to( 'Twitter International Company, One Cumberland Place, Fenian Street, Dublin 2, D02 AX07, Irlandë, dhe Twitter Inc., 1355 Market Street, Suite 900, San Francisco, CA 94103, SHBA', 'Twitter International Company, One Cumberland Place, Fenian Street, Dublin 2, D02 AX07, Ireland, and Twitter Inc., 1355 Market Street, Suite 900, San Francisco, CA 94103, USA' ),
		$to( 'LinkedIn Ireland Unlimited Company, Wilton Place, Dublin 2, Irlandë, dhe LinkedIn Corporation, 1000 West Maude Avenue, Sunnyvale, CA 94085, SHBA', 'LinkedIn Ireland Unlimited Company, Wilton Place, Dublin 2, Ireland, and LinkedIn Corporation, 1000 West Maude Avenue, Sunnyvale, CA 94085, USA' ),
		array( 'p', $s['usa'][0], $s['usa'][1] ),
		// The original numbers this "8.6."; it belongs to section 5.
		$sub( '5.6.', 'E drejta juaj për tërheqjen e pëlqimit', 'Your right to withdraw your consent' ),
		array( 'p', $s['withdraw'][0], $s['withdraw'][1] ),
		array( 'p', $s['withdraw_mail'][0], $s['withdraw_mail'][1] ),

		array( 'h4', '6. Abonimi në buletinin tonë informativ dhe analiza e sjelljes së përdoruesve', '6. Newsletter subscription and user behaviour analysis' ),
		$sub( '6.1.', 'Qëllimet', 'Purposes' ),
		array( 'p', 'Ky përpunim shërben për marketingun dhe analizën e sjelljes së përdoruesve.', 'This processing serves marketing and user behaviour analysis.' ),
		$sub( '6.2.', 'Llojet e të dhënave', 'Types of data' ),
		array( 'p', 'Në këtë kuadër përpunojmë adresën tuaj të email-it, të dhënat tuaja të aksesit, të dhëna për sjelljen tuaj ndaj email-eve, të dhënat e pajisjes suaj fundore, të dhënat tuaja të lidhjes, të dhënat tuaja të pëlqimit dhe numrin e rasteve që keni paraqitur në aplikacion si kategori të dhënash që lidhen me ju.', 'In doing so, we process your email address, your access data, email user behaviour data, your end-device data, your connection data, your consent data and the number of cases you have submitted in the app as categories of data relating to you.' ),
		$sub( '6.3.', 'Baza ligjore', 'Legal basis' ),
		array( 'p', 'Baza ligjore për abonimin në buletinin tonë informativ është pëlqimi juaj i shprehur për abonimin sipas nenit 6(1)(a) të GDPR-së.', 'The legal basis for subscribing to our newsletter is your express consent to the newsletter subscription pursuant to Art. 6(1)(a) GDPR.' ),
		array( 'p', 'Baza ligjore për analizën e sjelljes së përdoruesve është neni 6(1)(f) i GDPR-së. Interesi ynë legjitim është analiza e marketingut.', 'The legal basis for the user behaviour analysis is Art. 6(1)(f) GDPR. Our legitimate interest is marketing analysis.' ),
		$sub( '6.4.', 'Kohëzgjatja e ruajtjes', 'Storage period' ), array( 'p', $s['consent_keep'][0], $s['consent_keep'][1] ),
		$sub( '6.5.', 'E drejta juaj për tërheqjen e pëlqimit (abonimi në buletinin tonë informativ)', 'Your right to withdraw your consent (newsletter subscription)' ),
		array( 'p', $s['withdraw'][0], $s['withdraw'][1] ),
		array( 'p', 'Pëlqimin tuaj për këtë veprimtari përpunimi mund ta tërhiqni në çdo kohë përmes lidhjes së çabonimit në fund të çdo buletini informativ.', 'You can withdraw your consent to this processing activity at any time via the unsubscribe link at the end of each newsletter.' ),
		array( 'p', $s['withdraw_mail'][0], $s['withdraw_mail'][1] ),
		$sub( '6.6.', 'E drejta juaj për kundërshtim (analiza e sjelljes së përdoruesve)', 'Your right to object (user behaviour analysis)' ),
		array( 'p', $s['object'][0], $s['object'][1] ),
		array( 'p', "Të drejtën e kundërshtimit mund ta ushtroni në çdo kohë duke dërguar një email në {$dpo}.", "You can exercise your right to object at any time by sending an email to {$dpo}." ),

		array( 'h3', 'Prania në rrjetet sociale', 'Social media presences' ),
		array( 'p', 'Në këtë seksion kemi renditur platformat ku jemi të pranishëm ne, d.m.th. Mimatest GmbH, si dhe cilat nga të dhënat tuaja përpunohen kur vizitoni faqen e Mimatest brenda platformës përkatëse.', 'In this section we have listed the platforms on which we, i.e. Mimatest GmbH, are represented and which of your data is processed when you visit the Mimatest page on the respective platform.' ),
	),
	$presence( 7, 'Prania në Facebook', 'Facebook presence', array( 'p', $s['keep_5y'][0], $s['keep_5y'][1] ),
		array( $to( $meta_sq, $meta_en ), array( 'p', $s['usa'][0], $s['usa'][1] ) ),
		$joint( 'Meta Platforms Ireland Limited, 4 Grand Canal Place, Grand Canal Harbour, Dublin 2, Irlandë,', 'Meta Platforms Ireland Limited, 4 Grand Canal Place, Grand Canal Harbour, Dublin 2, Ireland', 'https://www.facebook.com/legal/terms/page_controller_addendum', 'https://www.facebook.com/legal/terms/information_about_page_insights_data' ) ),
	$presence( 8, 'Prania në Instagram', 'Instagram presence', array( 'p', $s['keep_5y'][0], $s['keep_5y'][1] ),
		array( $to( $meta_sq, $meta_en ), array( 'p', $s['usa'][0], $s['usa'][1] ) ),
		$joint( 'Meta Platforms Ireland Limited, 4 Grand Canal Place, Grand Canal Harbour, Dublin 2, Irlandë,', 'Meta Platforms Ireland Limited, 4 Grand Canal Place, Grand Canal Harbour, Dublin 2, Ireland', 'https://www.facebook.com/legal/terms/page_controller_addendum', 'https://www.facebook.com/legal/terms/information_about_page_insights_data' ) ),
	$presence( 9, 'Prania në LinkedIn', 'LinkedIn presence',
		array( 'p', 'Me një llogari të identifikuar, të dhënat ruhen derisa përdoruesi të fshijë të gjithë llogarinë, ndërsa cookies teknikisht të nevojshme ruhen derisa të fshihen nga përdoruesi ose nga shfletuesi. Pa një llogari të identifikuar, cookies teknikisht të nevojshme ruhen derisa personi i interesuar t’i fshijë ato në shfletues.', 'With a logged-in account, the data is stored until the user deletes the entire account, and technically necessary cookies are stored until they are deleted by the user or the browser. Without a logged-in account, technically necessary cookies are stored until the data subject deletes them in the browser.' ),
		array( $to( 'LinkedIn Ireland Unlimited Company, Gardner House, Wilton Plaza, Wilton Place, Dublin 2, Irlandë, dhe LinkedIn Corporation, 1000 West Maude Avenue, Sunnyvale, CA 94085, SHBA', 'LinkedIn Ireland Unlimited Company, Gardner House, Wilton Plaza, Wilton Place, Dublin 2, Ireland, and LinkedIn Corporation, 1000 West Maude Avenue, Sunnyvale, CA 94085, USA' ), array( 'p', $s['usa'][0], $s['usa'][1] ) ),
		$joint( 'LinkedIn Ireland Unlimited Company, Gardner House, Wilton Plaza, Wilton Place, Dublin 2, Irlandë,', 'LinkedIn Ireland Unlimited Company, Gardner House, Wilton Plaza, Wilton Place, Dublin 2, Ireland', 'https://legal.linkedin.com/pages-joint-controller-addendum', 'https://legal.linkedin.com/pages-joint-controller-addendum' ) ),
	$presence( 10, 'Prania në Pinterest', 'Pinterest presence',
		array( 'p', 'Të dhënat ruhen derisa përdoruesi t’i fshijë ato brenda llogarisë ose të fshijë të gjithë llogarinë; cookies e seancës ruhen deri në mbylljen e seancës së shfletuesit, ndërsa cookies e përhershme derisa personi i interesuar t’i fshijë të dhënat në shfletues.', 'The data is stored until the user deletes it within the account or deletes the entire account; session cookies are stored until the browser session is closed, and permanent cookies until the data subject deletes the data in the browser.' ),
		array( $to( 'Pinterest Europe Ltd., Palmerston House, 2nd Floor, Fenian Street, Dublin 2, Irlandë', 'Pinterest Europe Ltd., Palmerston House, 2nd Floor, Fenian Street, Dublin 2, Ireland' ), array( 'p', $s['usa'][0], $s['usa'][1] ) ) ),
	$presence( 11, 'Prania në TikTok', 'TikTok presence',
		array( 'p', 'Kohëzgjatja e ruajtjes është 18 muaj.', 'The storage period is 18 months.' ),
		array(
			$to( 'TikTok Information Technologies UK, 125 Kingsway, London, WC2B 6NH, Mbretëria e Bashkuar, dhe TikTok Technology Limited, 10 Earlsfort Terrace, Dublin, D02 T380, Irlandë', 'TikTok Information Technologies UK, 125 Kingsway, London, WC2B 6NH, United Kingdom, and TikTok Technology Limited, 10 Earlsfort Terrace, Dublin, D02 T380, Ireland' ),
			array( 'p', 'Të dhënat e mësipërme që lidhen me ju transferohen në Mbretërinë e Bashkuar dhe në Korenë e Jugut, si vende të treta me vendim për përshtatshmëri sipas nenit 45 të GDPR-së.', 'The above data relating to you is transferred to the United Kingdom and South Korea as third countries with an adequacy decision pursuant to Art. 45 GDPR.' ),
			// The original names datenschutz@dermanostic.com here (another company); replaced with Mimatest's privacy address.
			array( 'p', "Të dhënat tuaja transferohen në vendet e treta të mëposhtme pa vendim për përshtatshmëri: SHBA, Singapor dhe Rusi. Baza ligjore për transferimin e të dhënave tuaja në këto vende të treta janë klauzolat standarde kontraktuale sipas nenit 46 të GDPR-së. Kompania ofron garanci të përshtatshme për mbrojtjen e të dhënave, të cilat mund t’i shihni me kërkesë në {$dpo}.", "Your data is transferred to the following third countries without an adequacy decision: USA, Singapore and Russia. The legal basis for transferring your data to these third countries is standard contractual clauses pursuant to Art. 46 GDPR. The company provides appropriate safeguards for data protection, which you can view on request at {$dpo}." ),
		),
		$joint( 'TikTok Information Technologies UK, 125 Kingsway, London, WC2B 6NH, Mbretëria e Bashkuar, dhe TikTok Technology Limited, 10 Earlsfort Terrace, Dublin, D02 T380, Irlandë,', 'TikTok Information Technologies UK, 125 Kingsway, London, WC2B 6NH, United Kingdom, and TikTok Technology Limited, 10 Earlsfort Terrace, Dublin, D02 T380, Ireland', 'https://ads.tiktok.com/i18n/official/policy/jurisdiction-specific-terms', 'https://ads.tiktok.com/i18n/official/policy/jurisdiction-specific-terms' ),
		array( 'p', 'Baza ligjore e Mimatest GmbH është zbatimi i masave të nevojshme parakontraktore dhe përmbushja e kontratave sipas nenit 6(1)(b) të GDPR-së.', 'The legal basis of Mimatest GmbH is the implementation of necessary pre-contractual measures and the performance of contracts pursuant to Art. 6(1)(b) GDPR.' ) ),
	$presence( 12, 'Prania në Spotify', 'Spotify presence',
		array( 'p', 'Me një llogari të identifikuar, të dhënat ruhen derisa përdoruesi t’i fshijë ato brenda llogarisë ose të fshijë të gjithë llogarinë, ndërsa cookies teknikisht të nevojshme ruhen derisa të fshihen nga përdoruesi ose nga shfletuesi. Pa një llogari të identifikuar, cookies teknikisht të nevojshme ruhen derisa të fshihen nga përdoruesi ose nga shfletuesi.', 'With a logged-in account, the data is stored until the user deletes it within the account or deletes the entire account, and technically necessary cookies are stored until they are deleted by the user or the browser. Without a logged-in account, technically necessary cookies are stored until they are deleted by the user or the browser.' ),
		array( $to( 'Spotify AB, Regeringsgatan 19, 111 53 Stockholm, Suedi, dhe Spotify USA Inc., 150 Greenwich Street, Floor 62, New York, NY 10007, SHBA', 'Spotify AB, Regeringsgatan 19, 111 53 Stockholm, Sweden, and Spotify USA Inc., 150 Greenwich Street, Floor 62, New York, NY 10007, USA' ), array( 'p', $s['usa'][0], $s['usa'][1] ) ) ),
	array(
		array( 'h4', '13. Kanali në YouTube', '13. YouTube channel' ),
		$sub( '13.1.', 'Qëllimet', 'Purposes' ), array( 'p', $s['presentation'][0], $s['presentation'][1] ),
		$sub( '13.2.', 'Llojet e të dhënave', 'Types of data' ),
		array( 'p', 'Në këtë kuadër përpunojmë kategoritë e mëposhtme të të dhënave që lidhen me ju: përmbajtje audio dhe audiovizuale.', 'In doing so, we process the following categories of data relating to you: audio and audiovisual content.' ),
		$sub( '13.3.', 'Baza ligjore', 'Legal basis' ), array( 'p', $s['basis_b'][0], $s['basis_b'][1] ),
		$sub( '13.4.', 'Domosdoshmëria', 'Necessity' ), array( 'p', $s['need_enquiry'][0], $s['need_enquiry'][1] ),
		$sub( '13.5.', 'Kohëzgjatja e ruajtjes', 'Storage period' ),
		array( 'p', 'Të dhënat ruhen deri në fshirjen e kanalit në YouTube.', 'The data is stored until the YouTube channel is deleted.' ),
		$sub( '13.6.', 'Marrësit', 'Recipients' ),
		$to( 'Alphabet, Inc., 901 Cherry Ave., San Bruno, CA 94066, SHBA', 'Alphabet, Inc., 901 Cherry Ave., San Bruno, CA 94066, USA' ),
		array( 'p', $s['usa'][0], $s['usa'][1] ),

		array( 'h3', 'Të drejtat e subjekteve të të dhënave', 'Data subject rights' ),
		array( 'p', 'Legjislacioni për mbrojtjen e të dhënave ju jep juve, si subjekt i të dhënave, të drejta mbi mënyrën se si trajtohen të dhënat që lidhen me ju. Ne ju garantojmë zbatimin e të drejtave që keni ndaj nesh si subjekt i të dhënave. Më shumë për këtë mësoni në këtë seksion.', 'Data protection law grants you, as a data subject, rights regarding the handling of data relating to you. We guarantee the implementation of the data subject rights you have towards us. You can find out more in this section.' ),
		array( 'h4', '14. Të drejtat tuaja si subjekt i të dhënave', '14. Your rights as a data subject' ),
		array( 'p', 'Ndaj nesh keni të drejtat e mëposhtme në lidhje me të dhënat personale që ju përkasin:', 'You have the following rights towards us with regard to the personal data concerning you:' ),
		array( 'ul',
			array( 'e drejta e informimit dhe e marrjes së një kopjeje të të dhënave tuaja,', 'e drejta e korrigjimit,', 'e drejta e fshirjes dhe e drejta për t’u harruar,', 'e drejta e kufizimit të përpunimit,', 'e drejta e kundërshtimit të përpunimit,', 'e drejta e transferueshmërisë së të dhënave.' ),
			array( 'the right of access and to receive a copy of your data,', 'the right to rectification,', 'the right to erasure and to be forgotten,', 'the right to restriction of processing,', 'the right to object to processing,', 'the right to data portability.' ),
		),
		array( 'p', 'Përveç kësaj, keni të drejtë të paraqitni një ankesë pranë një autoriteti mbikëqyrës për mbrojtjen e të dhënave në lidhje me përpunimin e të dhënave tuaja personale nga ana jonë.', 'You also have the right to lodge a complaint with a data protection supervisory authority about our processing of your personal data.' ),
		array( 'h4', '15. Menaxhimi i kërkesave të subjekteve të të dhënave', '15. Management of data subject requests' ),
		$sub( '15.1.', 'Qëllimet', 'Purposes' ),
		array( 'p', 'Ky përpunim shërben për trajtimin e të drejtave të subjekteve të të dhënave në përputhje me legjislacionin për mbrojtjen e të dhënave.', 'This processing serves to handle data subject rights in compliance with data protection law.' ),
		$sub( '15.2.', 'Llojet e të dhënave', 'Types of data' ),
		array( 'p', 'Në këtë kuadër përpunojmë të gjitha kategoritë e të dhënave si kategori të dhënash që lidhen me ju.', 'In doing so, we process all categories of data as categories of data relating to you.' ),
		$sub( '15.3.', 'Baza ligjore', 'Legal basis' ),
		array( 'p', 'Baza ligjore e Mimatest GmbH është neni 6(1)(c) i GDPR-së, në lidhje me Kapitullin III të GDPR-së.', 'The legal basis of Mimatest GmbH is Art. 6(1)(c) GDPR in conjunction with Chapter III GDPR.' ),
		$sub( '15.4.', 'Domosdoshmëria', 'Necessity' ),
		array( 'p', 'Përpunimi i të dhënave të mësipërme që lidhen me ju është i nevojshëm për përmbushjen e detyrimeve tona ligjore.', 'The processing of the above data relating to you is necessary to fulfil our legal obligations.' ),
		$sub( '15.5.', 'Kohëzgjatja e ruajtjes', 'Storage period' ),
		array( 'p', 'Kohëzgjatja e ruajtjes nga Mimatest GmbH është tre vjet.', 'The storage period of Mimatest GmbH is three years.' ),
		$sub( '15.6.', 'Marrësit', 'Recipients' ),
		// The original names the external officer as "XXXXXXXXXXXXXXX" (placeholder); only the address is kept.
		array( 'p', "Të dhënat e mësipërme që lidhen me ju i transmetohen zyrtarit tonë të jashtëm për mbrojtjen e të dhënave: {$dpo}.", "The above data relating to you is transmitted to our external data protection officer: {$dpo}." ),
	)
);

/* ------------------------------------------------------------------ Terms and conditions */

$line = '________________________________________';
$terms = array(
	array( 'h2', 'Kushtet e Përgjithshme', 'General Terms and Conditions' ),
	array( 'pb', 'Darmbulanz GmbH', 'Darmbulanz GmbH' ),
	array( 'p', 'Ofruesi: Darmbulanz GmbH, Otto-Schmiedt-Str. 39, 04179 Leipzig', 'Provider: Darmbulanz GmbH, Otto-Schmiedt-Str. 39, 04179 Leipzig' ),
	array( 'p', "Drejtues ekzekutivë: Dr. Martin Thoma dhe Paul M. Wötzel<br>Gjykata e regjistrit: Leipzig, HRB 45476<br>Email: {$db_mail}", "Managing directors: Dr. Martin Thoma and Paul M. Wötzel<br>Register court: Leipzig, HRB 45476<br>Email: {$db_mail}" ),

	array( 'h3', 'Parathënie', 'Preamble' ),
	array( 'p', 'Për lexueshmëri më të mirë, nuk përdoren njëkohësisht disa forma gjuhësore. Të gjitha emërtimet e personave për klientët vlejnë njësoj për të gjitha gjinitë.', 'For better readability, several language forms are not used simultaneously. All personal designations for customers apply equally to all genders.' ),

	array( 'h3', '§ 1 Fusha e zbatimit dhe gjuha e kontratës', '§ 1 Scope and language of contract' ),
	array( 'p', '1. Këto Kushte të Përgjithshme vlejnë për:', '1. These Terms and Conditions apply to:' ),
	array( 'ul',
		array( 'shitjen, testimin dhe vlerësimin e testit me gjak të tharë Mimatest® (në vijim „Produkti“),', 'paketat e këshillimit për të ushqyerit, si dhe ndërmjetësimin dhe shitjen e suplementeve ushqimore („Produktet“),', 'funksionimin dhe përdorimin e faqeve të internetit mimatest.net, yourtestresults.net, darmbulanz.net, darmbulanz.de, darmbulanz.at dhe darmbulanz.ch (në vijim së bashku „Faqet e internetit“).' ),
		array( 'the sale, testing and evaluation of the Mimatest® dried blood test (hereinafter “Product”),', 'nutritional counselling packages as well as the brokering and sale of dietary supplements (“Products”),', 'the operation and use of the websites mimatest.net, yourtestresults.net, darmbulanz.net, darmbulanz.de, darmbulanz.at and darmbulanz.ch (hereinafter together “Websites”).' ),
	),
	array( 'p', '2. Kushtet e ndryshme ose shtesë të klientit nuk zbatohen, përveç rastit kur Darmbulanz GmbH pranon shprehimisht me shkrim vlefshmërinë e tyre.', '2. Deviating or supplementary terms of the customer shall not apply unless Darmbulanz GmbH expressly agrees to their validity in writing.' ),
	array( 'p', '3. Duke përdorur shërbimet tona ose duke blerë Produktin, klienti i pranon pa kufizim këto Kushte të Përgjithshme.', '3. By using our services or purchasing the Product, the customer accepts these Terms and Conditions without restriction.' ),
	array( 'p', '4. Gjuha e kontratës është ekskluzivisht gjermanishtja. Çdo përkthim shërben vetëm për informim; në rast dyshimi vlen teksti në gjermanisht.', '4. The language of the contract is exclusively German. Any translations are for information purposes only; in case of doubt, the German text shall prevail.' ),
	array( 'p', '5. Këto Kushte të Përgjithshme nuk vlejnë për platformën e komunitetit „social.darmbulanz.net“, e cila administrohet nga shoqata Dioscurides e.V. dhe për të cilën vlejnë kushte të veçanta.', '5. These Terms and Conditions do not apply to the community platform “social.darmbulanz.net”, which is operated by the association Dioscurides e.V. and is subject to separate terms and conditions.' ),

	array( 'h3', '§ 2 Oferta dhe lidhja e kontratës', '§ 2 Offer and conclusion of contract' ),
	array( 'p', '1. Paraqitja e produkteve dhe shërbimeve në faqet tona të internetit nuk përbën ofertë ligjërisht detyruese, por një ftesë jo detyruese për të bërë porosi (invitatio ad offerendum).', '1. The presentation of the products and services on our websites does not constitute a legally binding offer, but a non-binding invitation to place an order (invitatio ad offerendum).' ),
	array( 'p', '2. Procesi i porosisë ndahet si më poshtë:', '2. The ordering process is structured as follows:' ),
	array( 'ul',
		array( '(1) Vendosja e produktit në shportë', '(2) Futja e të dhënave të porosisë dhe të adresës, si dhe zgjedhja e mënyrës së dërgimit dhe të pagesës', '(3) Kontrolli i përmbledhjes së porosisë dhe, nëse nevojitet, korrigjimi i saj', '(4) Dërgimi i porosisë detyruese duke klikuar „Bëje Porosinë“' ),
		array( '(1) Add the product to the shopping cart', '(2) Enter order and address details and choose the shipping and payment method', '(3) Check the order summary and correct it if necessary', '(4) Submit the binding order by clicking “Place Order”' ),
	),
	array( 'p', '3. Menjëherë pas dërgimit të porosisë, merrni me email një konfirmim automatik të marrjes, i cili nuk përbën ende pranim. Kontrata e shitjes lidhet vetëm kur Darmbulanz GmbH e pranon porosinë me një konfirmim të veçantë porosie me email ose kur e dërgon produktin. Nëse është e nevojshme, ju lutemi kontrolloni edhe dosjen e postës së padëshiruar (spam).', '3. Immediately after submitting the order, you will receive an automatic confirmation of receipt by email, which does not yet constitute acceptance. A purchase contract is only concluded when Darmbulanz GmbH accepts the order with a separate order confirmation by email or ships the product. Please check your spam folder if necessary.' ),
	array( 'p', '4. Deri në klikimin përfundimtar mbi „Bëje Porosinë“, të gjitha të dhënat mund të korrigjohen në çdo kohë përmes funksionit të redaktimit në faqen e përmbledhjes, ose porosia mund të anulohet duke mbyllur dritaren e shfletuesit.', '4. Until the final click on “Place Order”, all details can be corrected at any time using the edit function on the summary page, or the order can be cancelled by closing the browser window.' ),
	array( 'p', '5. Vendi i përmbushjes është selia e Darmbulanz GmbH në Leipzig.', '5. The place of performance is the registered office of Darmbulanz GmbH in Leipzig.' ),

	array( 'h3', '§ 3 Përdorimi i Mimatest® (testi me gjak të tharë)', '§ 3 Use of Mimatest® (dried blood test)' ),
	array( 'p', '1. Klienti merr përsipër ta përdorë testin me gjak të tharë Mimatest® vetëm sipas udhëzimeve të bashkëngjitura dhe përshkrimeve të vëna në dispozicion në faqet e internetit.', '1. The customer undertakes to use the Mimatest® dried blood test only in accordance with the enclosed instructions and the descriptions provided on the websites.' ),
	array( 'p', '2. Për vlerësimin, klienti e dërgon mostrën e marrë në kutinë postare të treguar nga Darmbulanz GmbH. Rezultatet mund të shihen në formë digjitale në faqen e internetit yourtestresults.net.', '2. For the evaluation, the customer sends the collected sample to the post office box specified by Darmbulanz GmbH. The results can be accessed digitally on the website yourtestresults.net.' ),
	array( 'p', '3. Vlerësimi i testeve kryhet sipas nivelit aktual të shkencës dhe teknologjisë. Darmbulanz GmbH nuk mban përgjegjësi për rezultate të gabuara ose të paplota që shkaktohen nga marrja e pasaktë e mostrës ose nga keqpërdorimi i testit.', '3. The tests are evaluated according to the current state of science and technology. Darmbulanz GmbH accepts no liability for incorrect or incomplete results caused by improper sample collection or misuse of the test.' ),
	array( 'p', '4. Kohëzgjatja e vlerësimit është zakonisht deri në 3 javë nga mbërritja e mostrës në kutinë postare, plus kohën e dërgesës. Në raste të veçanta, kjo kohëzgjatje mund të tejkalohet.', '4. The evaluation usually takes up to 3 weeks from receipt of the sample at the post office box, plus shipping time. In individual cases, this period may be exceeded.' ),

	array( 'h3', '§ 4 Çmimet dhe procesi i pagesës', '§ 4 Prices and payment processing' ),
	array( 'p', '1. Të gjitha çmimet e treguara janë çmime përfundimtare në euro, përfshirë TVSH-në ligjore. Kostot e dërgesës tregohen veçmas në përshkrimin e produktit dhe në përmbledhjen e porosisë.', '1. All prices stated are final prices in euros including statutory VAT. Shipping costs are shown separately in the product description and the order summary.' ),
	array( 'p', '2. Çmimi i blerjes duhet paguar në momentin e dërgimit të porosisë. Pagesa mund të kryhet me mënyrat e mëposhtme:', '2. The purchase price is due when the order is placed. Payment can be made using the following methods:' ),
	array( 'ul', array( 'transfertë bankare SEPA', 'Klarna – pagesë e menjëhershme' ), array( 'SEPA bank transfer', 'Klarna instant payment' ) ),
	array( 'p', '3. Në rast të kthimit të një debitimi për shkak të të dhënave të pasakta ose mungesës së fondeve në llogari, tarifat që rezultojnë i mbulon klienti.', '3. In the event of a chargeback due to incorrect details or insufficient funds, the customer shall bear the resulting fees.' ),
	array( 'p', '4. Darmbulanz GmbH ka të drejtë t’i transferojë kërkesat që rrjedhin nga kjo marrëdhënie biznesi. Për faturimin dhe arkëtimin e kërkesave, të dhënat e nevojshme (emri, adresa, numri i faturës, shuma) u përcillen ofruesve të autorizuar të shërbimeve të pagesave.', '4. Darmbulanz GmbH is entitled to assign claims arising from this business relationship. For invoicing and the processing of claims, the necessary data (name, address, invoice number, amount) is passed on to commissioned payment service providers.' ),

	array( 'h3', '§ 5 Detyrimet e klientit', '§ 5 Obligations of the customer' ),
	array( 'p', '1. Klienti është i detyruar të japë të dhëna të vërteta dhe të plota gjatë porosisë dhe përdorimit të testit.', '1. The customer is obliged to provide truthful and complete information when ordering and using the test.' ),
	array( 'p', '2. Klienti merr përsipër të mos i interpretojë rezultatet e testit pa u konsultuar me profesionistë mjekësorë dhe të mos marrë vendime mjekësore vetëm mbi këtë bazë.', '2. The customer undertakes not to interpret the test results without consulting medical professionals and not to make medical decisions on this basis alone.' ),
	array( 'p', '3. Keqpërdorimi i faqeve të internetit, veçanërisht përmes futjes së të dhënave të rreme ose të paplota, është i ndaluar.', '3. Misuse of the websites, in particular by entering false or incomplete data, is prohibited.' ),
	array( 'p', '4. Të dhënat e hyrjes në llogaritë e klientëve duhet të trajtohen në mënyrë konfidenciale. Në rast dyshimi për akses të paautorizuar, Darmbulanz GmbH duhet të njoftohet menjëherë.', '4. Login details for customer accounts must be treated confidentially. If unauthorised access is suspected, Darmbulanz GmbH must be informed immediately.' ),

	array( 'h3', '§ 6 Rezervimi i pronësisë', '§ 6 Retention of title' ),
	array( 'p', 'Malli i dorëzuar mbetet pronë e Darmbulanz GmbH deri në pagesën e plotë. Për konsumatorët, ushtrimi i rezervimit të pronësisë kërkon tërheqjen paraprake nga kontrata.', 'The goods delivered remain the property of Darmbulanz GmbH until paid in full. In the case of consumers, asserting the retention of title requires prior withdrawal from the contract.' ),

	array( 'h3', '§ 7 Garancia ligjore', '§ 7 Warranty' ),
	array( 'p', '1. Nëse malli i dorëzuar ose shërbimi i këshillimit ka defekte, klienti gëzon të drejtat ligjore të garancisë (përmbushje plotësuese, tërheqje nga kontrata, ulje e çmimit).', '1. If the goods delivered or the counselling service are defective, the customer is entitled to the statutory warranty rights (subsequent performance, withdrawal, price reduction).' ),
	array( 'p', '2. Afati i garancisë është dy vjet nga marrja e mallit. Për defektet e fshehura me dashje, parashkrimi rregullohet sipas dispozitave të përgjithshme ligjore.', '2. The warranty period is two years from receipt of the goods. Defects that were fraudulently concealed become time-barred in accordance with the general statutory provisions.' ),

	array( 'h3', '§ 8 Përgjegjësia', '§ 8 Liability' ),
	array( 'p', '1. Darmbulanz GmbH mban përgjegjësi të pakufizuar për dashjen dhe pakujdesinë e rëndë, si dhe për dëmtimin e jetës, të trupit dhe të shëndetit. Në rast pakujdesie të lehtë, Darmbulanz GmbH mban përgjegjësi vetëm për shkeljen e detyrimeve thelbësore kontraktuale, përmbushja e të cilave e bën të mundur zbatimin e rregullt të kontratës, dhe vetëm deri në masën e dëmit të parashikueshëm e tipik për kontratën. Përgjegjësia sipas Ligjit për përgjegjësinë për produktet nuk cenohet.', '1. Darmbulanz GmbH shall be liable without limitation for intent and gross negligence and in the event of injury to life, body and health. In the case of slight negligence, Darmbulanz GmbH shall only be liable for the breach of essential contractual obligations, the fulfilment of which makes the proper performance of the contract possible in the first place, and only up to the amount of the foreseeable damage typical for the contract. Liability under the Product Liability Act remains unaffected.' ),
	array( 'p', '2. Darmbulanz GmbH nuk mban përgjegjësi për dëmet që shkaktohen nga përdorimi i pasaktë i produktit ose i faqeve të internetit.', '2. Darmbulanz GmbH is not liable for damage caused by improper use of the product or the websites.' ),
	array( 'p', '3. Darmbulanz GmbH nuk mban përgjegjësi për interpretimin mjekësor të rezultateve të testit. Kjo është përgjegjësi e klientit dhe, sipas rastit, e profesionistëve që ai konsulton.', '3. Darmbulanz GmbH is not liable for the medical interpretation of the test results. This is the responsibility of the customer and, where applicable, the professionals consulted.' ),
	array( 'p', '4. Duke pasur parasysh kushtet teknike të internetit, nuk jepet asnjë garanci për disponueshmërinë e vazhdueshme të faqeve të internetit dhe të sistemeve online.', '4. In view of the technical conditions of the internet, no guarantee is given for the constant availability of the websites and online systems.' ),

	array( 'h3', '§ 9 E drejta e autorit dhe të drejtat e markës', '§ 9 Copyright and trademark rights' ),
	array( 'p', '1. Të gjitha përmbajtjet e faqeve të internetit, përfshirë tekstet, imazhet dhe paraqitjet e tjera, mbrohen nga e drejta e autorit. Riprodhimi, përpunimi, shpërndarja dhe çdo lloj shfrytëzimi kërkojnë pëlqimin me shkrim të Darmbulanz GmbH.', '1. All content on the websites, including texts, images and other representations, is protected by copyright. Reproduction, editing, distribution and any kind of exploitation require the written consent of Darmbulanz GmbH.' ),
	array( 'p', '2. „Darmbulanz®“ është markë e regjistruar e Manidad AG, Bern. Darmbulanz GmbH e përdor atë me licencë. Duke përdorur testin ose faqet e internetit, klienti nuk fiton asnjë të drejtë mbi procedurat ose përmbajtjet që qëndrojnë në themel të tyre.', '2. “Darmbulanz®” is a registered trademark of Manidad AG, Bern. It is used by Darmbulanz GmbH under licence. By using the test or the websites, the customer does not acquire any rights to the underlying procedures or content.' ),
	array( 'p', '3. Të drejtat e autorit mbi procedurën e vlerësimit mbi të cilën bazohet ofrimi i shërbimit u përkasin ekskluzivisht dhënësve përkatës të teknologjisë, të cilët i kanë dhënë Darmbulanz-it, në mënyrë ligjërisht detyruese, të drejtën e përdorimit të kësaj procedure në kuadër të një marrëveshjeje licence.', '3. The copyrights to the evaluation procedure on which the service is based belong exclusively to the respective technology providers, who have granted Darmbulanz the legally binding right to use this procedure under a licence agreement.' ),
	array( 'p', '4. Të drejtat e markës mbi emërtimin dhe logon JANISER® i përkasin ekskluzivisht Manidad AG. Kjo i ka dhënë me kontratë Darmbulanz®-it përdorimin reklamues të markës, përfshirë logon, si dhe shitjen e produkteve dhe shërbimeve përkatëse në rajonin DACH (Gjermani, Austri, Zvicër).', '4. The trademark rights to the name and logo JANISER® belong exclusively to Manidad AG, which has contractually granted Darmbulanz® the right to use the trademark, including the logo, for advertising purposes and to distribute the associated products and services in the DACH region (Germany, Austria, Switzerland).' ),

	array( 'h3', '§ 10 Mbrojtja e të dhënave', '§ 10 Data protection' ),
	array( 'p', '1. Darmbulanz GmbH i përpunon të dhënat personale të klientit vetëm për zbatimin e kontratës (dorëzimi, pagesa, komunikimi me klientin) dhe në kuadër të detyrimeve ligjore. Çdo përdorim tjetër për qëllime reklamimi ose analize bëhet vetëm në bazë të një pëlqimi të shprehur, i cili mund të tërhiqet në çdo kohë.', '1. Darmbulanz GmbH processes the customer’s personal data solely for the performance of the contract (delivery, payment, customer communication) and within the scope of legal obligations. Any further use for advertising or analysis purposes only takes place on the basis of express consent, which can be withdrawn at any time.' ),
	array( 'p', '2. Informacion të detajuar mbi përpunimin e të dhënave personale gjeni në <a href="/privacy-policy/">Politikën e privatësisë</a> në faqet e internetit.', '2. Detailed information on the processing of personal data can be found in the <a href="/privacy-policy/">Privacy Policy</a> on the websites.' ),
	array( 'p', '3. Të dhënat në një llogari ekzistuese klienti mbeten të ruajtura derisa klienti ta fshijë vetë llogarinë. Për porositë si mysafir, ruajtja bëhet vetëm në kuadër të detyrimeve tatimore dhe tregtare për ruajtjen e dokumenteve. Ndryshimet e adresës mund t’i bëni vetë pasi të identifikoheni te „Llogaria ime“.', '3. Data in an existing customer account remains stored until the customer deletes the account. For guest orders, data is only stored within the scope of retention obligations under tax and commercial law. Address changes can be made yourself after logging in under “My account”.' ),

	array( 'h3', '§ 11 E drejta e tërheqjes', '§ 11 Right of withdrawal' ),
	array( 'p', 'Klientët gëzojnë të drejtën ligjore të tërheqjes sipas udhëzimit për tërheqjen më poshtë. Konsumator është çdo person fizik që lidh një veprim juridik për qëllime që kryesisht nuk lidhen as me veprimtarinë e tij tregtare, as me veprimtarinë e tij profesionale të pavarur. Hollësitë gjenden në shtojcë.', 'Customers have a statutory right of withdrawal in accordance with the cancellation policy below. A consumer is any natural person who enters into a legal transaction for purposes that are predominantly neither commercial nor self-employed professional activities. Details can be found in the annex.' ),

	array( 'h3', '§ 12 Dispozitat përfundimtare', '§ 12 Final provisions' ),
	array( 'p', '1. Zbatohet ligji i Republikës Federale të Gjermanisë, duke përjashtuar Konventën e OKB-së për shitjen ndërkombëtare të mallrave (CISG). Për klientët me vendbanim të zakonshëm në një shtet anëtar të BE-së, dispozitat detyruese mbrojtëse të shtetit përkatës të vendbanimit nuk cenohen.', '1. The law of the Federal Republic of Germany shall apply, excluding the UN Convention on Contracts for the International Sale of Goods (CISG). For customers whose habitual residence is in an EU member state, the mandatory protective provisions of the respective country of residence remain unaffected.' ),
	array( 'p', '2. Vendi i juridiksionit për të gjitha mosmarrëveshjet me tregtarë, persona juridikë të së drejtës publike ose fonde të veçanta publike është Leipzig. Ndaj klientëve, Leipzig vlen si vend juridiksioni nëse klienti e ka zhvendosur vendbanimin jashtë vendit pas lidhjes së kontratës ose nëse vendbanimi i tij nuk dihet në momentin e ngritjes së padisë.', '2. The place of jurisdiction for all disputes with merchants, legal entities under public law or special funds under public law is Leipzig. With regard to customers, Leipzig shall be the place of jurisdiction if the customer has moved their place of residence abroad after conclusion of the contract or if their place of residence is unknown at the time the action is filed.' ),
	array( 'p', '3. Ndryshimet dhe plotësimet e këtyre Kushteve të Përgjithshme kërkojnë formën e shkruar. Nëse ndonjë dispozitë e veçantë është e pavlefshme, pjesa tjetër e kontratës mbetet e vlefshme; në vend të dispozitës së pavlefshme zbatohet rregullimi ligjor.', '3. Amendments and additions to these Terms and Conditions must be made in writing. Should individual provisions be invalid, the remainder of the contract shall remain valid; the invalid provision shall be replaced by the statutory provision.' ),
	array( 'p', '4. Tekstin e plotë të kontratës, përfshirë këto Kushte të Përgjithshme, jua dërgojmë me email bashkë me konfirmimin e porosisë. Ne nuk e ruajmë atë përtej kësaj.', '4. We will send you the full text of the contract, including these Terms and Conditions, by email with the order confirmation. We do not store it beyond this.' ),
	array( 'p', '5. Në përputhje me § 36 të Ligjit gjerman për zgjidhjen e mosmarrëveshjeve të konsumatorëve (VSBG), ju njoftojmë se nuk jemi të detyruar të marrim pjesë në një procedurë jashtëgjyqësore për zgjidhjen e mosmarrëveshjeve para një organi pajtimi për konsumatorët. Platforma e BE-së për zgjidhjen online të mosmarrëveshjeve gjendet në: ' . $link( 'https://ec.europa.eu/consumers/odr' ) . '. Adresa jonë e email-it: <a href="mailto:info@darmbulanz.net">info@darmbulanz.net</a>', '5. In accordance with § 36 of the German Consumer Dispute Resolution Act (VSBG), we point out that we are not obliged to participate in out-of-court dispute resolution proceedings before a consumer arbitration board. The EU platform for online dispute resolution is available at: ' . $link( 'https://ec.europa.eu/consumers/odr' ) . '. Our email address: <a href="mailto:info@darmbulanz.net">info@darmbulanz.net</a>' ),

	array( 'h3', 'Shtojcë: Informacion për klientin, udhëzimi për tërheqjen dhe formulari i tërheqjes', 'Annex: Customer information, cancellation policy and withdrawal form' ),
	array( 'h4', 'Udhëzimi për tërheqjen', 'Cancellation policy' ),
	array( 'p', 'Klientët kanë të drejtë të tërhiqen nga kjo kontratë brenda katërmbëdhjetë ditëve pa dhënë arsye. Afati i tërheqjes është katërmbëdhjetë ditë nga dita kur ju ose një palë e tretë e caktuar nga ju (që nuk është transportuesi) keni marrë në zotërim mallin.', 'Customers have the right to withdraw from this contract within fourteen days without giving any reason. The withdrawal period is fourteen days from the day on which you or a third party named by you (who is not the carrier) took possession of the goods.' ),
	array( 'p', 'Për të ushtruar të drejtën tuaj të tërheqjes, na njoftoni për vendimin tuaj për t’u tërhequr me një deklaratë të qartë (letër ose email):', 'To exercise your right of withdrawal, you must inform us of your decision to withdraw by means of a clear statement (letter or email):' ),
	array( 'p', "Darmbulanz GmbH<br>Otto-Schmiedt-Str. 39<br>04179 Leipzig<br>Email: {$db_mail}", "Darmbulanz GmbH<br>Otto-Schmiedt-Str. 39<br>04179 Leipzig<br>Email: {$db_mail}" ),
	array( 'p', 'Për këtë mund të përdorni formularin model të tërheqjes më poshtë, por përdorimi i tij nuk është i detyrueshëm. Për respektimin e afatit mjafton që deklarata të dërgohet në kohë.', 'You may use the model withdrawal form below for this purpose, but this is not mandatory. To meet the withdrawal deadline, it is sufficient to send the declaration in good time.' ),
	array( 'p', 'Shënim: Kitet Mimatest (testet me gjak të tharë) të dëmtuara, të ndotura ose të hapura, si dhe materialet harxhuese të hapura, nuk mund të rimbursohen.', 'Note: Damaged, soiled or opened Mimatest kits (dried blood tests) and opened consumables cannot be refunded.' ),
	array( 'h4', 'Pasojat e tërheqjes', 'Consequences of withdrawal' ),
	array( 'p', 'Në rast tërheqjeje të vlefshme, ju rimbursojmë menjëherë të gjitha pagesat që keni kryer, përfshirë kostot standarde të dërgesës, dhe jo më vonë se katërmbëdhjetë ditë nga marrja e deklaratës suaj të tërheqjes. Rimbursimi bëhet me të njëjtën mënyrë pagese që keni përdorur për transaksionin fillestar; për këtë nuk ju ngarkohet asnjë tarifë. Mund ta mbajmë rimbursimin derisa ta kemi marrë mallin mbrapsht ose derisa të keni provuar se e keni dërguar atë mbrapsht.', 'If you withdraw effectively, we will reimburse all payments you have made, including standard delivery costs, without delay and at the latest within fourteen days of receiving your notice of withdrawal. Repayment will be made using the same means of payment that you used for the original transaction; you will not be charged any fees for this. We may withhold repayment until we have received the goods back or you have provided proof that you have returned them.' ),
	array( 'p', 'Malli duhet kthyer menjëherë, dhe jo më vonë se katërmbëdhjetë ditë pas deklaratës suaj të tërheqjes, në adresën e mëposhtme:', 'The goods must be returned without delay and at the latest within fourteen days of your notice of withdrawal to the following address:' ),
	array( 'p', 'Darmbulanz GmbH, Otto-Schmiedt-Str. 39, 04179 Leipzig', 'Darmbulanz GmbH, Otto-Schmiedt-Str. 39, 04179 Leipzig' ),
	array( 'p', 'Kostot e drejtpërdrejta të kthimit i mbuloni ju. Ju lutemi, nëse është e mundur, përdorni paketimin origjinal.', 'You will bear the direct costs of returning the goods. Please use the original packaging if possible.' ),
	array( 'h4', 'Formulari model i tërheqjes', 'Model withdrawal form' ),
	array( 'p', '(Ju lutemi plotësojeni dhe dërgojeni vetëm nëse dëshironi të tërhiqeni nga kontrata.)', '(Please complete and return this form only if you wish to withdraw from the contract.)' ),
	array( 'p', 'Për: Darmbulanz GmbH, Otto-Schmiedt-Str. 39, 04179 Leipzig / info@darmbulanz.de', 'To: Darmbulanz GmbH, Otto-Schmiedt-Str. 39, 04179 Leipzig / info@darmbulanz.de' ),
	array( 'p', 'Me anë të kësaj, unë/ne (*) tërhiqem/tërhiqemi (*) nga kontrata e lidhur prej meje/nesh (*) për blerjen e mallit të mëposhtëm:', 'I/We (*) hereby withdraw from the contract concluded by me/us (*) for the purchase of the following goods:' ),
	array( 'p', "Produkti:<br>{$line}", "Product:<br>{$line}" ),
	array( 'p', "Porositur më:<br>{$line}", "Ordered on:<br>{$line}" ),
	array( 'p', "Paguar më:<br>{$line}", "Paid on:<br>{$line}" ),
	array( 'p', "Marrë më:<br>{$line}", "Received on:<br>{$line}" ),
	array( 'p', "Emri dhe adresa e konsumatorit/konsumatorëve:<br>{$line}<br>{$line}", "Name and address of the consumer(s):<br>{$line}<br>{$line}" ),
	array( 'p', "Data, vendi dhe nënshkrimi (vetëm për tërheqjen në letër):<br>{$line}", "Date, place and signature (only for withdrawal on paper):<br>{$line}" ),
	array( 'p', '(*) Fshini atë që nuk vlen.', '(*) Delete as applicable.' ),
	array( 'p', "Darmbulanz GmbH · Otto-Schmiedt-Str. 39 · 04179 Leipzig · {$db_mail}<br>Gjykata e regjistrit: Leipzig, HRB 45476 · Drejtues: Dr. Martin Thoma &amp; Paul M. Wötzel", "Darmbulanz GmbH · Otto-Schmiedt-Str. 39 · 04179 Leipzig · {$db_mail}<br>Register court: Leipzig, HRB 45476 · Managing directors: Dr. Martin Thoma &amp; Paul M. Wötzel" ),
);

$pages = array(
	3  => $privacy, // Politika e privatësisë / Privacy Policy
	10 => $terms,   // Kushtet e përdorimit / Terms and Conditions
);

/* ------------------------------------------------------------------ helpers */

function legal_block_markup( $b ) {
	list( $type, $sq ) = $b;
	switch ( $type ) {
		case 'h2':
		case 'h3':
		case 'h4':
			$level = (int) substr( $type, 1 );
			$attrs = 2 === $level ? '' : ' {"level":' . $level . '}';
			return "<!-- wp:heading{$attrs} -->\n<{$type} class=\"wp-block-heading\">{$sq}</{$type}>\n<!-- /wp:heading -->";
		case 'pb':
			return "<!-- wp:paragraph -->\n<p><strong>{$sq}</strong></p>\n<!-- /wp:paragraph -->";
		case 'ul':
			$items = '';
			foreach ( $sq as $item ) {
				$items .= "<!-- wp:list-item -->\n<li>{$item}</li>\n<!-- /wp:list-item -->\n";
			}
			return "<!-- wp:list -->\n<ul class=\"wp-block-list\">{$items}</ul>\n<!-- /wp:list -->";
		default:
			return "<!-- wp:paragraph -->\n<p>{$sq}</p>\n<!-- /wp:paragraph -->";
	}
}

// TranslatePress stores each run of text between tags as its own string.
function legal_segments( $html ) {
	$parts = preg_split( '/<[^>]+>/', $html );
	$out   = array();
	foreach ( $parts as $part ) {
		$part = trim( html_entity_decode( $part, ENT_QUOTES | ENT_HTML5, 'UTF-8' ) );
		if ( '' !== $part && preg_match( '/\p{L}/u', $part ) ) {
			$out[] = $part;
		}
	}
	return $out;
}

$mode = isset( $args[0] ) ? $args[0] : '';

if ( 'pages' === $mode ) {
	foreach ( $pages as $id => $blocks ) {
		$content = implode( "\n\n", array_map( 'legal_block_markup', $blocks ) );
		wp_update_post( array( 'ID' => $id, 'post_content' => wp_slash( $content ), 'post_status' => 'publish' ) );
		WP_CLI::log( get_the_title( $id ) . ': ' . count( $blocks ) . ' blocks' );
	}
	WP_CLI::success( 'Albanian pages written.' );

} elseif ( 'dictionary' === $mode ) {
	global $wpdb;
	$table = $wpdb->prefix . 'trp_dictionary_sq_en_us';
	$norm  = function ( $t ) {
		$t = html_entity_decode( wp_strip_all_tags( $t ), ENT_QUOTES | ENT_HTML5, 'UTF-8' );
		$t = str_replace( array( '’', '‘' ), "'", $t );
		return trim( preg_replace( '/\s+/u', ' ', $t ) );
	};

	// Albanian segment → English segment, block by block.
	$map = array();
	foreach ( $pages as $blocks ) {
		foreach ( $blocks as $b ) {
			$sq_parts = 'ul' === $b[0] ? $b[1] : array( $b[1] );
			$en_parts = 'ul' === $b[0] ? $b[2] : array( $b[2] );
			foreach ( $sq_parts as $i => $sq_html ) {
				$sq_seg = legal_segments( $sq_html );
				$en_seg = legal_segments( $en_parts[ $i ] );
				if ( count( $sq_seg ) !== count( $en_seg ) ) {
					WP_CLI::error( "Albanian/English don't line up:\n  " . implode( ' | ', $sq_seg ) . "\n  " . implode( ' | ', $en_seg ) );
				}
				foreach ( $sq_seg as $k => $seg ) {
					if ( $seg !== $en_seg[ $k ] ) {
						$map[ $norm( $seg ) ] = array( $seg, $en_seg[ $k ] );
					}
				}
			}
		}
	}

	$fill = function () use ( $wpdb, $table, $norm, $map ) {
		$done = array();
		foreach ( $wpdb->get_results( "SELECT id, original FROM {$table}" ) as $row ) {
			$key = $norm( $row->original );
			if ( isset( $map[ $key ] ) ) {
				$wpdb->update( $table, array( 'translated' => $map[ $key ][1], 'status' => 2 ), array( 'id' => $row->id ) );
				$done[ $key ] = true;
			}
		}
		return $done;
	};
	$done   = $fill();
	$unseen = array();
	foreach ( $map as $key => $pair ) {
		if ( ! isset( $done[ $key ] ) ) {
			$unseen[] = $pair[0];
		}
	}
	if ( $unseen ) {
		TRP_Translate_Press::get_trp_instance()->get_component( 'query' )->insert_strings( $unseen, 'en_US' );
		$done = $fill();
	}
	WP_CLI::success( 'English translations saved: ' . count( $done ) . ' of ' . count( $map ) . ' strings.' );

} else {
	WP_CLI::error( 'Usage: wp eval-file legal-pages.php pages|dictionary' );
}
