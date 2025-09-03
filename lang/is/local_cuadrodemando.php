<?php
/**
 * Íslenskar textastrengjir fyrir Stjórnborð viðbótina
 *
 * @package    local_cuadrodemando
 * @author     Thorvaldur Konradsson
 * @version    1.0.0
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Stjórnborð';
$string['dashboard'] = 'Stjórnborð';
$string['dashboard:view'] = 'Skoða stjórnborð';
$string['dashboard:manage'] = 'Stjórna stjórnborði';

// Leiðsögustrengjir
$string['home'] = 'Heim';
$string['users'] = 'Notendur';
$string['courses'] = 'Námskeið';
$string['geography'] = 'Landafræði';

// Síðutitlar
$string['pagetitle'] = 'Stjórnborð - {$a}';
$string['welcometodashboard'] = 'Velkomin á stjórnborðið';

// Tölfræðistrengjir
$string['totalusers'] = 'Fjöldi notenda';
$string['totalcourses'] = 'Fjöldi námskeiða';
$string['totalenrollments'] = 'Fjöldi skráninga';
$string['activeusers'] = 'Virkir notendur';
$string['activeenrolments'] = 'Virk innritun';
$string['registeredusers'] = 'Skráðir notendur';
$string['uniqueaccesses'] = 'Einstök aðgangur';

// Villuskilaboð
$string['nopermission'] = 'Þú hefur ekki heimild til að skoða stjórnborðið';
$string['missingglobalvars'] = 'Nauðsynlegar altækar breytur vantar';

// Stillingar
$string['dashboardsettings'] = 'Stillingar stjórnborðs';
$string['enablecharts'] = 'Virkja línurit';
$string['enablecharts_desc'] = 'Virkja birtingu línurita á stjórnborðinu';
$string['refreshinterval'] = 'Uppfærslubil gagna';
$string['refreshinterval_desc'] = 'Hversu oft á að uppfæra gögn stjórnborðsins (í mínútum)';

// Brauðmylur
$string['breadcrumb:home'] = 'Heim';
$string['breadcrumb:users'] = 'Notendur';
$string['breadcrumb:courses'] = 'Námskeið';
$string['breadcrumb:geography'] = 'Landafræði';

// Tungumálaval
$string['language_selector'] = 'Tungumál:';
$string['lang_english'] = 'English';
$string['lang_spanish'] = 'Español';
$string['lang_icelandic'] = 'Íslenska';
$string['lang_catalan'] = 'Català';
$string['selectlanguage'] = 'Veldu tungumál';

// Villuskjár
$string['pagenotfound'] = 'Síða fannst ekki';
$string['pagenotfound_desc'] = 'Umbeðna síðan fannst ekki.';
$string['returntodashboard'] = 'Fara aftur á stjórnborð';

// Landafræðisíða
$string['geo'] = 'Landafræði';
$string['geo_instructions'] = 'Færðu músina yfir hvert fylki til að sjá gögnin og smelltu fyrir frekari upplýsingar';
$string['provinces_total'] = 'Samtals fylki';
$string['province_last_30_days'] = 'Gögn fylkis fyrir síðustu 30 daga';
$string['sessions_last_hour'] = 'Opnar lotur síðustu klukkustund';
$string['active_users_last_hour'] = 'Virkir notendur síðustu klukkustund';
$string['completions_last_month'] = 'Lokið námskeið síðasta mánuð';
$string['enrollments_last_month'] = 'Innritanir síðasta mánuð';
$string['registrations_last_month'] = 'Nýskráningar síðasta mánuð';
$string['deletions_last_month'] = 'Eyðingar síðasta mánuð';
$string['geo_data_loading'] = 'Hleð landafræðilegum gögnum...';
$string['map_loading'] = 'Hleð gagnvirku korti...';
$string['visiblecourses'] = 'Sýnileg námskeið';
$string['userdetails_student'] = 'Upplýsingar um nemanda: {$a}';
$string['userdetails_teacher'] = 'Upplýsingar um kennara: {$a}';
$string['userdetails_user'] = 'Upplýsingar um notanda: {$a}';
$string['users_overview'] = 'Yfirlit notenda';
$string['totalusers'] = 'Samtals notendur';
$string['activeusers_month'] = 'Virkir notendur (þessi mánuður)';
$string['newusers_month'] = 'Nýir notendur (þessi mánuður)';
$string['onlineusers'] = 'Notendur á netinu';
$string['login_statistics'] = 'Innskráningartölfræði';
$string['user_changes'] = 'Breytingar á notendum';

// User statistics strings
$string['usersaddedtoday'] = 'Notendur bætt við í dag';
$string['usersdeletedtoday'] = 'Notendur eyddir í dag';
$string['userseditedtoday'] = 'Notendur breyttir í dag';
$string['accessestoday'] = 'Aðgangar í dag';

// Chart labels
$string['numberofaccesses'] = '# af aðgöngum';
$string['numberofusers'] = '# af notendum';

// Detalles del curso
$string['coursedetails'] = 'Námskeiðsupplýsingar';
$string['coursesoverview'] = 'Yfirlit yfir námskeið';
$string['coursescreated'] = 'Námskeið búin til ({$a})';
$string['coursesactive'] = 'Virk námskeið ({$a})';
$string['coursesfinished'] = 'Lokið námskeið ({$a})';
$string['averageenrollment'] = 'Meðalfjöldi innritaðra';

// Enlaces y acciones
$string['viewindashboard'] = 'Skoða á stjórnborði';
$string['viewinmoodle'] = 'Skoða á Moodle';
$string['viewcoursedetail'] = 'Skoða námskeiðaskýringar';
$string['viewteacherdetail'] = 'Skoða kennaraupplýsingar';
$string['viewstudentdetail'] = 'Skoða nemandaupplýsingar';
$string['viewuserdetail'] = 'Skoða nánari upplýsingar';
$string['viewenrolledstudents'] = 'Skoða innritaða nemendur';
$string['clickhere'] = 'Smelltu hér til að';
$string['clickherefor'] = 'Smelltu hér til að';
$string['sendemailtoperson'] = 'Senda tölvupóst til einstaklings';
$string['sendemail'] = 'Senda tölvupóst';
$string['managecourse'] = 'Stjórna námskeiði';
$string['backtolist'] = 'Til baka í heildarlista notenda';

// Tablas y listados
$string['courselist'] = 'Listi yfir námskeið ({$a})';
$string['enrolledinacourse'] = 'Innritaður í námskeið: <strong>{$a}</strong>';
$string['platformusers'] = 'Notendur á kerfinu';
$string['courselistwhereisrole'] = 'Listi yfir námskeið þar sem <b>{$a->fullname}</b> er {$a->role}. Samtals: <b>{$a->count}</b>';
$string['teacher'] = 'kennari eða stjórnandi';
$string['student'] = 'nemandi';
$string['intotal'] = 'Samtals';

// Estados de cursos
$string['notstarted'] = 'Ekki byrjað';
$string['finished'] = 'Lokið';
$string['active'] = 'Virkt';
$string['noenddate'] = 'Engin lokadagsetning';
$string['notfinished'] = 'Ekki lokið';

// Encabezados de tablas
$string['id'] = 'ID';
$string['identification'] = 'Auðkenni';
$string['fullname'] = 'Fullt nafn';
$string['shortname'] = 'Stutt nafn';
$string['name'] = 'Nafn';
$string['email'] = 'Tölvupóstur';
$string['city'] = 'Borg';
$string['department'] = 'Deild';
$string['province'] = 'Fylki';
$string['address'] = 'Heimilisfang';
$string['teachers'] = 'Kennari/kennarar';
$string['students_count'] = '# nemenda';
$string['completed_count'] = '# Lokið';
$string['completed_percent'] = '% Lokið';
$string['status'] = 'Staða';
$string['manageinmoodle'] = 'Stjórna í Moodle';
$string['coursestartdate'] = 'Upphaf námskeiðs';
$string['courseenddate'] = 'Lok námskeiðs';
$string['completiondate'] = 'Lokadagsetning';
$string['enrollmentdate'] = 'Innritunardagur';
$string['coursefinished'] = 'Námskeiði lokið';
$string['user'] = 'Notandi';

// Mensajes
$string['noactiveusers'] = 'Engir virkir notendur';
$string['noenrolled'] = '0 innritaðir';
$string['noopensessions'] = 'Engar opnar lotur';
$string['notcompleted'] = 'Ekki lokið';

// Navegación y títulos
$string['navigateyeardata'] = 'Fara yfir gögn síðasta árs';
$string['viewstatisticsof'] = 'Skoða tölfræði fyrir {$a->month} {$a->year}';
$string['back'] = 'Til baka';
$string['viewdetail'] = 'Skoða nánar';
$string['collapse'] = 'Fella saman';
$string['remove'] = 'Fjarlægja';

// Fechas
$string['startdate'] = 'Upphafsdagur';
$string['enddate'] = 'Lokadagur';
$string['enrolldate'] = 'Innritunardagur';
$string['completiondate'] = 'Lokadagsetning';
$string['coursestartdate'] = 'Upphaf námskeiðs';
$string['courseenddate'] = 'Lok námskeiðs';

// Acciones de botones
$string['viewcourse'] = 'Skoða námskeið';
$string['configurecourse'] = 'Stilla námskeið';
$string['viewenrolled'] = 'Skoða innritaða';

// Nuevas cadenas añadidas
$string['coursesmostenrollmentslastyear'] = 'Námskeið með flest innritanir síðasta ár';
$string['completed'] = 'Lokið';
$string['notcompleted'] = 'Ekki lokið';
$string['totalenrolled'] = 'Samtals innritaðir:';
$string['completedpercentage'] = 'Hlutfall lokinna:';
$string['viewcoursedetail'] = 'Skoða námskeiðaskýringar';
$string['gotocourseinplatform'] = 'Fara í námskeið á kerfinu';
$string['uniqueaccessesplatformlastyearbyday'] = 'Einstök aðgöng á kerfið síðasta ár eftir dögum';
$string['accessesofuserlastyearbyday'] = 'Aðgangur <b>{$a}</b> síðasta ár eftir dögum';
$string['top10provincesmostusers'] = 'Topp 10 fylki með flesta notendur';
$string['person'] = 'Aðili';
$string['addresslabel'] = 'Heimilisfang:';
$string['citylabel'] = 'Borg:';
$string['provincelabel'] = 'Fylki:';
$string['phonelabel'] = 'Sími:';
$string['emaillabel'] = 'Tölvupóstur:';
$string['teamslabel'] = 'Teymi:';
$string['talkonteams'] = 'Tala á Teams';
$string['openchatinplatform'] = 'Opna spjall á kerfinu';
$string['viewprofileinplatform'] = 'Skoða prófíl á kerfinu';
$string['provincesmorepercentusers'] = 'Fylki með hærra % af notendum';
$string['categories'] = 'Flokkar';
$string['times'] = 'Tímar';
$string['percenttotalplatformcourses'] = '% af heildarfjölda námskeiða á kerfinu';
$string['gotocategoryinplatform'] = 'Fara í flokk á kerfinu';
$string['coursegeographyandtimes'] = 'Landafræði og tímar námskeiðs';
$string['variouscourseinformation'] = 'Ýmsar upplýsingar um námskeið';
$string['numberstudentsneverentered'] = '# nemenda sem aldrei mættu';
$string['numberstudentprovinces'] = '# fylki nemenda';
$string['numberenrolledteachers'] = '# innritaðra kennara';
$string['numberusedresources'] = '# notaðra auðlinda';
$string['completionstatus'] = 'Staða lokunar';
$string['categoriesandtimes'] = 'Flokkar og tímar';
$string['withoutinstitution'] = 'Án stofnunar';

// Cadenas añadidas para home.php
$string['opensessionsnow'] = 'Opnar lotur núna:';
$string['completionsthismonth'] = 'Lokið námskeið þennan mánuð:';
$string['nocompletionsthismonth'] = 'Engin lokun þennan mánuð 😭';
$string['registrationsthismonth'] = 'Nýskráningar þennan mánuð:';
$string['noregistrationsthismonth'] = 'Engar nýskráningar þennan mánuð 😭';
$string['accessesthismonth'] = 'Aðgangar þennan mánuð:';
$string['noaccessesthismonth'] = 'Engir aðgangar þennan mánuð 😭';
$string['activeuserslasthour'] = 'Virkir notendur síðustu klukkustund:';
$string['noactiveusers'] = 'Engir virkir notendur 😭';
$string['enrollmentsthismonth'] = 'Innritanir þennan mánuð:';
$string['noenrollmentsthismonth'] = 'Engar innskráningar þennan mánuð 😭';
$string['suspensionsthismonth'] = 'Frestanir þennan mánuð:';
$string['nosuspensionsthismonth'] = 'Engar frestanir þennan mánuð 😀';
$string['messagesthismonth'] = 'Skilaboð þennan mánuð:';
$string['nomessagesthismonth'] = 'Engin skilaboð þennan mánuð 😭';
$string['calendar'] = 'Dagatal';

// DataTables strings
$string['copytable'] = 'Afrita töflu';
$string['exportcsv'] = 'Flytja út CSV';
$string['exportexcel'] = 'Flytja út Excel';
$string['exportpdf'] = 'Flytja út PDF';
$string['printtable'] = 'Prenta töflu';
$string['filtercolumns'] = 'Sía dálka';
$string['showingrecords'] = 'Sýni _START_ til _END_ af _TOTAL_ færslum';
$string['previous'] = 'Fyrri';
$string['first'] = 'Fyrsta';
$string['last'] = 'Síðasta';
$string['next'] = 'Næsta';
$string['copy'] = 'Afrita';
$string['hidecolumns'] = 'Fela dálka';
$string['collection'] = 'Safn';
$string['restorevisibility'] = 'Endurheimta sýnileika';
$string['copykeys'] = 'Ýttu á ctrl eða ⌘ + C til að afrita töflugögnin í klippiborðið þitt.<br /><br />Til að hætta við, smelltu á þessi skilaboð eða ýttu á escape.';
$string['copytitle'] = 'Afrita í klippiborð';
$string['csv'] = 'CSV';
$string['excel'] = 'Excel';
$string['showallrows'] = 'Sýna allar raðir';
$string['showrows'] = 'Sýna %d raðir';
$string['pdf'] = 'PDF';
$string['print'] = 'Prenta';
$string['processing'] = 'Vinnur...';
$string['lengthmenu'] = 'Sýna _MENU_ færslur';
$string['zerorecords'] = 'Engar samsvarandi færslur fundust';
$string['emptytable'] = 'Engin gögn tiltæk í töflu';
$string['infoempty'] = 'Sýni 0 til 0 af 0 færslum';
$string['infofiltered'] = '(síað frá _MAX_ samtals færslum)';
$string['search'] = 'Leita:';
$string['loadingrecords'] = 'Hleður...';
$string['loadmessage'] = 'Hleður leitarsvæðum';
$string['showmessage'] = 'Sýna allt';
$string['emptypanes'] = 'Engin leitarsvæði';
$string['title'] = 'Virkir síur - %d';
$string['collapsemessage'] = 'Fella allt saman';
$string['clearmessage'] = 'Hreinsa allt';
$string['searchpanes'] = 'Leitarsvæði';
$string['searchpanesplural'] = 'Leitarsvæði (%d)';
$string['all'] = 'Allt';

// Mánuðir fyrir alþjóðavæðingu DataTables
$string['january'] = 'Janúar';
$string['february'] = 'Febrúar';
$string['march'] = 'Mars';
$string['april'] = 'Apríl';
$string['may'] = 'Maí';
$string['june'] = 'Júní';
$string['july'] = 'Júlí';
$string['august'] = 'Ágúst';
$string['september'] = 'September';
$string['october'] = 'Október';
$string['november'] = 'Nóvember';
$string['december'] = 'Desember';

// Vikudagar fyrir alþjóðavæðingu DataTables
$string['sunday'] = 'Sun';
$string['monday'] = 'Mán';
$string['tuesday'] = 'Þri';
$string['wednesday'] = 'Mið';
$string['thursday'] = 'Fim';
$string['friday'] = 'Fös';
$string['saturday'] = 'Lau';

// Additional missing strings
$string['copyrow'] = 'Afrita 1 röð í klippiborð';
$string['copyrows'] = 'Afrita %d raðir í klippiborð';
$string['averagecompletionindays'] = 'Meðalending lokunar í dögum';
$string['completionindays'] = 'Lokin í dögum';
$string['numbercoursesincategory'] = '# af námskeiðum í flokki';
