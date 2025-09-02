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
$string['selectlanguage'] = 'Veldu tungumál';

// Notendaskýrslur
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
$string['coursesmostenrollmentslastyear'] = 'Námskeið með flestar skráningar síðasta ár';
$string['completed'] = 'Lokið';
$string['notcompleted'] = 'Ekki lokið';
$string['totalenrolled'] = 'Skráðir heildar:';
$string['completedpercentage'] = 'Hlutfall lokinna:';
$string['viewcoursedetail'] = 'Skoða námskeiðaskýringar';
$string['gotocourseinplatform'] = 'Fara í námskeið á vettvangi';
$string['uniqueaccessesplatformlastyearbyday'] = 'Einstök aðgöng á vettvang síðasta ár eftir dögum';
$string['accessesofuserlastyearbyday'] = 'Aðgengi <b>{$a}</b> síðasta ár eftir dögum';
$string['top10provincesmostusers'] = 'Topp 10 fylki með flesta notendur';
$string['person'] = 'Persóna';
$string['addresslabel'] = 'Heimilisfang:';
$string['citylabel'] = 'Borg:';
$string['provincelabel'] = 'Fylki:';
$string['phonelabel'] = 'Sími:';
$string['emaillabel'] = 'Tölvupóstur:';
$string['teamslabel'] = 'Teymi:';
$string['talkonteams'] = 'Tala á Teams';
$string['openchatinplatform'] = 'Opna spjall á vettvangi';
$string['viewprofileinplatform'] = 'Skoða prófíl á vettvangi';
$string['provincesmorepercentusers'] = 'Fylki með meira % af notendum';
$string['categories'] = 'Flokkar';
$string['times'] = 'Sinnum';
$string['percenttotalplatformcourses'] = '% af heildarfjölda námskeiða á vettvangi';
$string['gotocategoryinplatform'] = 'Fara í flokk á vettvangi';
$string['coursegeographyandtimes'] = 'Landafræði námskeiða og tímasetningar';
$string['variouscourseinformation'] = 'Ýmsar upplýsingar um námskeið';
$string['numberstudentsneverentered'] = '# nemenda sem aldrei fóru inn';
$string['numberstudentprovinces'] = '# fylki nemenda';
$string['numberenrolledteachers'] = '# skráðra kennara';
$string['numberusedresources'] = '# notaðra auðlinda';
$string['completionstatus'] = 'Staða námskeiða';
$string['categoriesandtimes'] = 'Categories and times';
$string['withoutinstitution'] = 'Án stofnunar';
