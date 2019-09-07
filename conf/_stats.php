<?php
/**
 * This is b2evolution's stats config file.
 *
 * @deprecated TODO: It holds now just things that should be move around due to hitlog refactoring.
 *
 * This file sets how b2evolution will log hits and stats
 * Last significant changes to this file: version 1.6
 *
 * @package conf
 */
if( !defined('EVO_CONFIG_LOADED') ) die( 'Please, do not access this page directly.' );


/**
 * Self referers that should not be considered as "real" referers in stats.
 * This should typically include this site and maybe other subdomains of this site.
 *
 * The following substrings will be looked up in the referer http header
 * in order to identify referers to hide in the logs.
 *
 * The string must start within the 12 FIRST CHARS of the referer or it will be ignored.
 * note: http://abc.com is already 14 chars. 12 for safety.
 *
 * WARNING: you should *NOT* use a slash at the end of simple domain names, as
 * older Netscape browsers will not send these. For example you should list
 * http://www.example.com instead of http://www.example.com/ .
 *
 * @todo move to admin interface (T_basedomains list editor), but use for upgrading
 * @todo handle multiple blog roots.
 *
 * @global array
 */
$self_referer_list = array(
	'://'.$basehost,			// This line will match all pages from the host of your $baseurl
	'://www.'.$basehost,		// This line will also match www.you_base_host because any "www." will have been stripped away from your basehost
	'http://localhost',
	'http://127.0.0.1',
);


/**
 * Speciallist: referrers that should not be considered as "real" referers in stats.
 * This should typically include stat services, online email services, online aggregators, etc.
 *
 * The following substrings will be looked up in the referer http header
 * in order to identify referers to hide in the logs
 *
 * THIS IS NOT FOR SPAM! Use the Antispam features in the admin section to control spam!
 *
 * The string must start within the 12 FIRST CHARS of the referer or it will be ignored.
 * note: http://abc.com is already 14 chars. 12 for safety.
 *
 * WARNING: you should *NOT* use a slash at the end of simple domain names, as
 * older Netscape browsers will not send these. For example you should list
 * http://www.example.com instead of http://www.example.com/ .
 *
 * @todo move to admin interface (T_basedomains list editor), but use for upgrading
 *
 * @global array
 */
$SpecialList = array(
	// webmails
	'.mail.yahoo.com/',
	'//mail.google.com/',
	'webmail.aol.com/',
	// stat services
	'sitemeter.com/',
	'Mediatoolkitbot',
	// aggregators
	'bloglines.com/',
	// caches
	'/search?q=cache:',		// Google cache
	// redirectors
	'googlealert.com/',
	'facebook.com/externalhit_uatext.php/',
	'Go-http-client',
	'TrendsmapResolver',
	'Twitterbot',
	// site status services
	'host-tracker.com',
	'uptimerobot.com',
	'/cron_exec.php',
	// Generate page snapshots
	'BingPreview',
	// Services	
	'Dispatch',		       // Paypal
	'autodiscover',
	'Slack-ImgProxy',	   // https://user-agents.net/bots/slack-imgproxy

	// Unknown
	'python-requests',
	// add your own...
);


/**
 * UserAgent identifiers for logging/statistics
 *
 * The following substrings will be looked up in the user_agent http header
 *
 * 'type' aggregator currently gets only used to "translate" user agent strings.
 * An aggregator hit gets detected by accessing the feed.
 *
 * @global array $user_agents
 */
$user_agents = array(
	// Robots:
	1000 => array('robot', 'Googlebot', 'Google (Googlebot)' ), // removed slash in order to also match "Googlebot-Image", "Googlebot-Mobile", "Googlebot-Sitemaps"
	1001 => array('robot', 'Slurp/', 'Inktomi (Slurp)' ),
	1002 => array('robot', 'Yahoo! Slurp', 'Yahoo (Slurp)' ), // removed ; to also match "Yahoo! Slurp China"
	1003 => array('robot', 'msnbot', 'MSN Search (msnbot)' ), // removed slash in order to also match "msnbot-media"
	1004 => array('robot', 'Frontier/', 'Userland (Frontier)' ),
	1005 => array('robot', 'ping.blo.gs/', 'blo.gs' ),
	1006 => array('robot', 'organica/', 'Organica' ),
	1007 => array('robot', 'Blogosphere/', 'Blogosphere' ),
	1008 => array('robot', 'blogging ecosystem crawler', 'Blogging ecosystem'),
	1009 => array('robot', 'FAST-WebCrawler/', 'Fast' ),			// http://fast.no/support/crawler.asp
	1010 => array('robot', 'timboBot/', 'Breaking Blogs (timboBot)' ),
	1011 => array('robot', 'NITLE Blog Spider/', 'NITLE' ),
	1012 => array('robot', 'The World as a Blog ', 'The World as a Blog' ),
	1013 => array('robot', 'daypopbot/ ', 'DayPop' ),
	1014 => array('robot', 'Bitacle bot/', 'Bitacle' ),
	1015 => array('robot', 'Sphere Scout', 'Sphere Scout' ),
	1016 => array('robot', 'Gigabot/', 'Gigablast (Gigabot)' ),
	1017 => array('robot', 'Yandex', 'Yandex' ),
	1018 => array('robot', 'Mail.RU/', 'Mail.Ru' ),
	1019 => array('robot', 'Baiduspider', 'Baidu spider' ),
	1020 => array('robot', 'infometrics-bot', 'Infometrics Bot' ),
	1021 => array('robot', 'DotBot/', 'DotBot' ),
	1022 => array('robot', 'Twiceler-', 'Cuil (Twiceler)' ),
	1023 => array('robot', 'discobot/', 'Discovery Engine' ),
	1024 => array('robot', 'Speedy Spider', 'Entireweb (Speedy Spider)' ),
	1025 => array('robot', 'monit/', 'Monit'),
	1026 => array('robot', 'Sogou web spider', 'Sogou'),
	1027 => array('robot', 'Tagoobot/', 'Tagoobot'),
	1028 => array('robot', 'MJ12bot/', 'Majestic-12'),
	1029 => array('robot', 'ia_archiver', 'Alexa crawler'),
	1030 => array('robot', 'KaloogaBot', 'Kalooga'),
	1031 => array('robot', 'Flexum/', 'Flexum'),
	1032 => array('robot', 'OOZBOT/', 'OOZBOT'),
	1033 => array('robot', 'ApptusBot', 'Apptus'),
	1034 => array('robot', 'Purebot', 'Pure Search'),
	1035 => array('robot', 'Sosospider', 'Sosospider'),
	1036 => array('robot', 'TopBlogsInfo', 'TopBlogsInfo'),
	1037 => array('robot', 'spbot/', 'SEOprofiler'),
	1038 => array('robot', 'StackRambler', 'Rambler' ),
	1039 => array('robot', 'AportWorm', 'Aport.ru' ),
	1040 => array('robot', 'ScoutJet', 'ScoutJet' ),
	1041 => array('robot', 'bingbot/', 'Bing' ),
	1042 => array('robot', 'Nigma.ru/', 'Nigma.ru' ),
	1043 => array('robot', 'ichiro/', 'Ichiro' ),
	1044 => array('robot', 'YoudaoBot/', 'Youdao' ),
	1045 => array('robot', 'Sogou web spider/', 'Sogou web spider' ),
	1046 => array('robot', 'findfiles.net', 'findfiles.net' ),
	1047 => array('robot', 'SiteBot/', 'SiteBot' ),
	1048 => array('robot', 'Nutch-', 'Apache Nutch' ),
	1049 => array('robot', 'DoCoMo/', 'DoCoMo' ),
	1050 => array('robot', 'findlinks/', 'FindLinks' ),
	1051 => array('robot', 'MLBot', 'MLBot' ),
	1052 => array('robot', 'facebookexternalhit', 'Facebook' ),
	1053 => array('robot', ' oBot/', 'IBM Bot' ),
	1054 => array('robot', 'GarlikCrawler/', 'Garlik' ),
	1055 => array('robot', 'Yeti/', 'Naver' ),
	1056 => array('robot', 'TurnitinBot/', 'Turnitin' ),
	1057 => array('robot', 'NerdByNature.Bot', 'NerdByNature' ),
	1058 => array('robot', 'SeznamBot/', 'SeznamBot' ),
	1059 => array('robot', 'Nymesis/', 'Nymesis' ),
	1060 => array('robot', 'YodaoBot/', 'YodaoBot' ),
	1061 => array('robot', 'Exabot/', 'Exabot' ),
	1062 => array('robot', 'AhrefsBot/', 'AhrefsBot' ),
	1063 => array('robot', 'SISTRIX Crawler', 'SISTRIX' ),
	1064 => array('robot', 'AcoonBot/', 'AcoonBot' ),
	1065 => array('robot', 'VoilaBot', 'VoilaBot' ),
	1066 => array('robot', 'SiteExplorer', 'SiteExplorer' ),
	1067 => array('robot', 'IstellaBot/', 'IstellaBot' ),
	1068 => array('robot', 'exb.de/crawler', 'ExB Language Crawler' ),
	1069 => array('robot', 'SemrushBot', 'SemrushBot' ),
	
	// Robots 2019:
	1070 => array('robot', 'UptimeRobot', 'Uptime' ),
	1071 => array('robot', 'Qwantify', 'Qwant' ),
	1072 => array('robot', 'BingPreview', 'BingPreview' ),
	1073 => array('robot', 'Nimbostratus-Bot', 'Nimbostratus' ),
	1074 => array('robot', 'ips-agent', 'ips-agent' ),
	1075 => array('robot', 'DuckDuckBot-Https', 'DuckDuckGo' ),
	1076 => array('robot', 'LivelapBot', 'LivelapBot' ),
	1077 => array('robot', 'Alexibot', 'Alexibot' ),
	1078 => array('robot', 'asterias', 'asterias' ),
	1079 => array('robot', 'Black.Hole', 'Black.Hole' ),
	1080 => array('robot', 'BlackWidow', 'BlackWidow' ),	
	1081 => array('robot', 'BlowFish', 'BlowFish' ),
	1082 => array('robot', 'BotALot', 'BotALot' ),
	1083 => array('robot', 'BuiltBotTough', 'BuiltBotTough' ),
	1084 => array('robot', 'Bullseye', 'Bullseye' ),
	1085 => array('robot', 'BunnySlippers', 'BunnySlippers' ),
	1086 => array('robot', 'Cegbfeieh', 'Cegbfeieh' ),
	1087 => array('robot', 'CheeseBot', 'CheeseBot' ),
	1090 => array('robot', 'CherryPicker', 'CherryPicker' ),
	1091 => array('robot', 'ChinaClaw', 'ChinaClaw' ),
	1093 => array('robot', 'cosmos', 'cosmos' ),
	1094 => array('robot', 'Crescent', 'Crescent' ),
	1095 => array('robot', 'Custo', 'Custo' ),
	1096 => array('robot', 'DISCo', 'DISCo' ),
	1097 => array('robot', 'DittoSpyder', 'DittoSpyder' ),
	1098 => array('robot', 'eCatch', 'eCatch' ),
	1099 => array('robot', 'EirGrabber', 'EirGrabber' ),
	1100 => array('robot', 'EroCrawler', 'EroCrawler' ),
	1101 => array('robot', 'EyeNetIE', 'EyeNetIE' ),
	1102 => array('robot', 'FlashGet', 'FlashGet' ),
	1103 => array('robot', 'Foobot', 'Foobot' ),
	1104 => array('robot', 'FrontPage', 'FrontPage' ),
	1105 => array('robot', 'GetRight', 'GetRight' ),
	1106 => array('robot', 'GetWeb!', 'GetWeb' ),
	1107 => array('robot', 'Go-Ahead-Got-It', 'Go-Ahead-Got-It' ),
	1108 => array('robot', 'Go!Zilla', 'Go!Zilla' ),
	1109 => array('robot', 'GrabNet', 'GrabNet' ),
	1110 => array('robot', 'Grafula', 'Grafula' ),
	1111 => array('robot', 'hloader', 'hloader' ),
	1112 => array('robot', 'HMView', 'HMView' ),
	1113 => array('robot', 'httplib', 'httplib' ),	
	1114 => array('robot', 'humanlinks', 'humanlinks' ),
	1115 => array('robot', 'InfoNaviRobot', 'InfoNaviRobot' ),
	1116 => array('robot', 'InterGET', 'InterGET' ),
	1117 => array('robot', 'JennyBot', 'JennyBot' ),
	1118 => array('robot', 'JetCar', 'JetCar' ),
	1119 => array('robot', 'Kenjin.Spider', 'Kenjin' ),	
	1120 => array('robot', 'Keyword.Density', 'Keyword.Density' ),
	1121 => array('robot', 'LexiBot', 'LexiBot' ),
	1122 => array('robot', 'LinkextractorPro', 'LinkextractorPro' ),
	1123 => array('robot', 'LinkWalker', 'LinkWalker' ),
	1124 => array('robot', 'lwp-trivial', 'lwp-trivial' ),		
	1125 => array('robot', 'Mata.Hari', 'Mata.Hari' ),
	1126 => array('robot', 'Microsoft.URL', 'Microsoft.URL' ),
	1127 => array('robot', 'MIIxpc', 'MIIxpc' ),	
	1128 => array('robot', 'Mister.PiX', 'Mister.PiX' ),
	1129 => array('robot', 'moget', 'moget' ),
	1130 => array('robot', 'Vampire', 'Vampire' ),
	1131 => array('robot', 'Navroad', 'Navroad' ),
	1132 => array('robot', 'NearSite', 'NearSite' ),
	1133 => array('robot', 'NetAnts', 'NetAnts' ),
	1134 => array('robot', 'NetMechanic', 'NetMechanic' ),		
	1135 => array('robot', 'NetSpider', 'NetSpider' ),
	1136 => array('robot', 'NetZIP', 'NetZIP' ),	
	1137 => array('robot', 'NICErsPRO', 'NICErsPRO' ),	
	1138 => array('robot', 'NPBot', 'NPBot' ),
	1139 => array('robot', 'Octopus', 'Octopus' ),
	1140 => array('robot', 'Openfind', 'Openfind' ),	
	1141 => array('robot', 'PageGrabber', 'PageGrabber' ),
	1142 => array('robot', 'pavuk', 'pavuk' ),
	1143 => array('robot', 'pcBrowser', 'pcBrowser' ),
	1144 => array('robot', 'ProPowerBot', 'ProPowerBot' ),	
	1145 => array('robot', 'ProWebWalker', 'ProWebWalker' ),	
	1146 => array('robot', 'ReGet', 'ReGet' ),
	1147 => array('robot', 'SlySearch', 'SlySearch' ),
	1148 => array('robot', 'SpankBot', 'SpankBot' ),
	1149 => array('robot', 'RepoMonkey', 'RepoMonkey' ),
	1150 => array('robot', 'spanner', 'spanner' ),
	1151 => array('robot', 'SuperBot', 'SuperBot' ),
	1152 => array('robot', 'SuperHTTP', 'SuperHTTP' ),	
	1153 => array('robot', 'Surfbot', 'Surfbot' ),
	1154 => array('robot', 'suzuran', 'suzuran' ),	
	1155 => array('robot', 'Szukacz', 'Szukacz' ),
	1156 => array('robot', 'tAkeOut', 'tAkeOut' ),
	1157 => array('robot', 'Teleport', 'Teleport' ),	
	1158 => array('robot', 'Telesoft', 'Telesoft' ),
	1159 => array('robot', 'TheNomad', 'TheNomad' ),
	1160 => array('robot', 'TightTwatBot', 'TightTwatBot' ),
	1161 => array('robot', 'Titan', 'Titan' ),	
	1162 => array('robot', 'True_Robot', 'True_Robot' ),	
	1163 => array('robot', 'turingos', 'turingos' ),
	1164 => array('robot', 'VoidEYE', 'VoidEYE' ),	
	1165 => array('robot', 'WebAuto', 'WebAuto' ),
	1166 => array('robot', 'WebBandit', 'WebBandit' ),
	1167 => array('robot', 'WebCopier', 'WebCopier' ),	
	1168 => array('robot', 'WebEnhancer', 'WebEnhancer' ),
	1169 => array('robot', 'WebFetch', 'WebFetch' ),
	1170 => array('robot', 'WebLeacher', 'WebLeacher' ),
	1171 => array('robot', 'WebmasterWorldForumBot', 'WorldForumBot' ),		
	1172 => array('robot', 'WebReaper', 'WebReaper' ),	
	1173 => array('robot', 'WebSauger', 'WebSauger' ),
	1174 => array('robot', 'Quester', 'Quester' ),	
	1175 => array('robot', 'WebStripper', 'WebStripper' ),
	1176 => array('robot', 'WebWhacker', 'WebWhacker' ),
	1177 => array('robot', 'WebZip', 'WebZip' ),	
	1178 => array('robot', 'Wget', 'Wget' ),
	1179 => array('robot', 'Widow', 'Widow' ),
	1180 => array('robot', 'WWWOFFLE', 'WWWOFFLE' ),
	1181 => array('robot', 'Zeus', 'Zeus' ),
	1182 => array('robot', 'Teleport', 'Teleport' ),

	// Unknown robots:
	5000 => array('robot', 'psycheclone', 'Psycheclone' ),
	5001 => array('robot', 'Go-http-client', 'Go-Client' ),
	5002 => array('robot', 'EmailCollector', 'EmailCollector' ),
	5003 => array('robot', 'EmailSiphon', 'EmailSiphon' ),
	5004 => array('robot', 'EmailWolf', 'EmailWolf' ),
	5005 => array('robot', 'BackDoorBot', 'BackDoorBot' ),
	5006 => array('robot', 'HTTrack', 'HTTrack' ),
	5007 => array('robot', 'ExtractorPro', 'ExtractorPro' ),
	5008 => array('robot', 'CopyRightCheck', 'CopyRightCheck' ),
	5009 => array('robot', 'larbin', 'larbin' ),
	5010 => array('robot', 'LeechFTP', 'LeechFTP' ),
	5011 => array('robot', 'SiteSnagger', 'SiteSnagger' ),
	5012 => array('robot', 'SmartDownload', 'SmartDownload' ),
	5013 => array('robot', 'WebEMailExtrac', 'WebEMailExtrac' ),
	5014 => array('robot', 'Amazon', 'Amazon' ),
	
	// Aggregators:
	10000 => array('aggregator', 'AppleSyndication/', 'Safari RSS (AppleSyndication)' ),
	10001 => array('aggregator', 'Feedreader', 'Feedreader' ),
	10002 => array('aggregator', 'Syndirella/', 'Syndirella' ),
	10003 => array('aggregator', 'rssSearch Harvester/', 'rssSearch Harvester' ),
	10004 => array('aggregator', 'Newz Crawler',	'Newz Crawler' ),
	10005 => array('aggregator', 'MagpieRSS/', 'Magpie RSS' ),
	10006 => array('aggregator', 'CoologFeedSpider', 'CoologFeedSpider' ),
	10007 => array('aggregator', 'Pompos/', 'Pompos' ),
	10008 => array('aggregator', 'SharpReader/', 'SharpReader'),
	10009 => array('aggregator', 'Straw ', 'Straw'),
	10010 => array('aggregator', 'YandexBlog', 'YandexBlog'),
	10011 => array('aggregator', ' Planet/', 'Planet Feed Reader'),
	10012 => array('aggregator', 'UniversalFeedParser/', 'Universal Feed Parser'),
);

/* Set user devices */
// MOBILE
$mobile_user_devices = array(
	'iphone'   => '(iphone|ipod)',
	'android'  => 'android.*mobile',
	'blkberry' => 'blackberry',
	'winphone' => 'windows phone os',
	'wince'    => 'windows ce; (iemobile|ppc|smartphone)',
	'palm'     => '(avantgo|blazer|elaine|hiptop|palm|plucker|xiino)',
	'gendvice' => '(kindle|mobile|mmp|midp|pocket|psp|symbian|smartphone|treo|up.browser|up.link|vodafone|wap|opera mini)'
);

// TABLET
$tablet_user_devices = array(
	'ipad'     => '(ipad)',
	'andrtab'  => 'android(?!.*mobile)',
	'berrytab' => 'rim tablet os',
);

// PC
$pc_user_devices = array(
	'win311'   => 'win16',
	'win95'    => '(windows 95)|(win95)|(windows_95)',
	'win98'    => '(windows 98)|(win98)',
	'win2000'  => '(windows nt 5.0)|(windows 2000)',
	'winxp'    => '(windows nt 5.1)|(windows XP)',
	'win2003'  => '(windows nt 5.2)',
	'winvista' => '(windows nt 6.0)',
	'win7'     => '(windows nt 6.1)',
	'winnt40'  => '(windows nt 4.0)|(winnt4.0)|(winnt)|(windows nt)',
	'winme'    => '(windows me)|(win 9x 4.90)',
	'openbsd'  => 'openbsd',
	'sunos'    => 'sunos',
	'linux'    => '(linux)|(x11)',
	'ubuntu'   => 'ubuntu',
	'macosx'   => 'mac os x',
	'macos'    => '(mac_powerpc)|(macintosh)',
	'qnx'      => 'qnx',
	'beos'     => 'beos',
	'os2'      => 'os/2'
);

$user_devices = array_merge(
	$tablet_user_devices,
	$mobile_user_devices,
	$pc_user_devices
);

$user_devices_color = array(
	// Mobile
	'iphone'   => 'd8c1a1',
	'ipad'     => 'c5aa8c',
	'andrtab'  => 'cdba9c',
	'android'  => 'e0caa5',
	'berrytab' => 'b29575',
	'blkberry' => 'baa286',
	'winphone' => 'ceb28b',
	'wince'    => 'e4d6b9',
	'palm'     => 'c8ac84',
	'gendvice' => 'e6d4bf',
	// PC
	'win311'   => 'CCCCCC',
	'win95'    => '676767',
	'win98'    => 'ABABAB',
	'win2000'  => '898989',
	'winxp'    => 'DEDEDE',
	'win2003'  => 'A3A3A3',
	'winvista' => 'EEEEEE',
	'win7'     => '999999',
	'winnt40'  => 'B9B9B9',
	'winme'    => '7F7F7F',
	'openbsd'  => 'AFAFAF',
	'sunos'    => '808080',
	'linux'    => 'E0E0E0',
	'ubuntu'   => 'B4B4B4',
	'macosx'   => '9F9F9F',
	'macos'    => 'F0F0F0',
	'qnx'      => 'D0D0D0',
	'beos'     => '8F8F8F',
	'os2'      => 'C0C0C0'
	);

$referer_type_array = array (
	'0'       => 'All',
	'search'  => 'Search',
	'referer' => 'Referer',
	'direct'  => 'Direct',
	'self'    => 'Self',
	'special' => 'Special',
	'spam'    => 'Spam',
	'admin'   => 'Admin'
	);

$referer_type_color = array(
	'session' => '006699',
	'search'  => '0099FF',
	'special' => 'ff00ff',
	'referer' => '00CCFF',
	'direct'  => '00FFCC',
	'spam'    => 'FF0000',
	'self'    => '00FF99',
	'admin'   => '999999',
	'ajax'    => '339966',
	);

$agent_type_array = array (
	'0'       => 'All',
	'robot'   => 'Robot',
	'browser' => 'Browser',
	'unknown' => 'Unknown',
	);

$agent_type_color = array(
	'rss'     => 'FF6600',
	'robot'   => 'FF9900',
	'browser' => 'FFCC00',
	'unknown' => 'cccccc'
);

$hit_type_array = array (
	'0'        => 'All',
	'rss'      => 'RSS',
	'standard' => 'Standard',
	'ajax'     => 'AJAX',
	'service'  => 'Service',
	'admin'    => 'Admin',
	'api'      => 'API'
	);

$hit_type_color = array(
	'standard'         => 'FFBB00',
	'service'          => '6699CC',
	'rss'              => 'FF6600',
	'ajax'             => '339966',
	'admin'            => 'AAE0E0',
	'standard_robot'   => 'FF9900',
	'standard_browser' => 'FFCC00',
	'api'              => '5BC0DE',
	'unknown'          => 'CCCCCC',
);

$hit_method_color = array(
	'GET'    => '000000',
	'POST'   => 'FFBB00',
	'PUT'    => 'ff00ff',
	'DELETE' => 'FF0000',
	'HEAD'   => '00CCFF',
);

$user_gender_color = array(
	'women_active'       => '990066',
	'women_notactive'    => 'c72290',
	'women_closed'       => 'ff66cc',
	'men_active'         => '003399',
	'men_notactive'      => '3268d4',
	'men_closed'         => '6699ff',
	'nogender_active'    => '666666',
	'nogender_notactive' => '999999',
	'nogender_closed'    => 'cccccc'
);

$activity_type_color = array(
	'users'     => 'FF9900',
	'posts'    => '6699CC',
	'comments' => '5BC0DE'
);

?>
