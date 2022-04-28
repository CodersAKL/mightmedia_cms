<?php


function adminPages()
{
	// get('/' . ADMIN_DIR, ADMIN_ROOT . 'pages/dashboard.php', false);
	// doAction('adminRoutes');
	doAction('loadPages');
}


function adminMenu()
{
	$adminMenu['dashboard'] = [
		'url' 	=> '/' . ADMIN_DIR,
		'title' => 'Dashboard',
	];

	return applyFilters('adminMenu', $adminMenu);
}



// old ----


function defaultHead() 
{

	?>
	<base href="<?php echo siteUrl(); ?>" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo getOption('site_name'); ?> - Admin</title>
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="robots" content="index,follow" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<!-- favicon -->
	<link rel="apple-touch-icon" sizes="180x180" href="/core/assets/images/favicon/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/core/assets/images/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/core/assets/images/favicon/favicon-16x16.png">
	<link rel="manifest" href="/core/assets/images/favicon/site.webmanifest">
	<link rel="mask-icon" href="/core/assets/images/favicon/safari-pinned-tab.svg" color="#db7300">
	<meta name="msapplication-TileColor" content="#ff440e">
	<meta name="theme-color" content="#ffffff">
	<?php
		if  (getSession('translation_status') == 1){
			if (! empty(getSession('Translation'))){ echo getSession('Translation'); }
			?>
			<style>
			.mm-translation {
				cursor: default;
				border: 2px dotted #c7c7c7;
    			padding: 0 5px;
			}
			.mm-translation:hover {
				border-color: red;
			}
			/* .mm-translations--btn {
				position: fixed;
				bottom: 0;
				right: 0;
			} */
			</style>
			<script>
				function editLanguageText(frase) {
					var group = frase.getAttribute("data-group");
					var key = frase.getAttribute("data-key");
					var element = document.getElementById(group + '_' + key);
					var person = prompt('OLD text: # ' + element.innerHTML + ' # Enter new text below: ', element.innerHTML);
					updateTranslationInDB(group, key, person,function(event){event.preventDefault()});
				}

				function updateTranslationInDB(group, key, newValue) {
					console.log(group+' '+key+' '+newValue);
					var element = document.getElementById(group + '_' + key);
					var xhttp = new XMLHttpRequest();
					var url = "../content/extensions/translation/updateTranslation.php?group=" + group + "&key=" + key +"&newValue=" + newValue;
					//Send the proper header information along with the request
					xhttp.open('GET', url, true);
					xhttp.send();
					
				}
			</script>
	<?php }
}

// function adminPages() 
// {
// 	global $url, $lang, $conf, $buttons, $timeout, $prisijungimas_prie_mysql;

// 	// todo: check this after page is online

// 	// if($versionData = checkVersion()) {
// 	// 	notifyUpdate($versionData);
// 	// }	

// 	$fileName = (isset($url['a']) && ! empty(getAllAdminPages($url['a'])) ? getAllAdminPages($url['a']) : null);

// 	if (! empty($fileName) && file_exists(ROOT . $fileName) && ! empty(getSession('username')) && getSession('level') == 1 && defined( "OK" ) ) {
// 		if (count($_POST) > 0 && $conf['keshas'] == 1) {
// 			notifyMsg(
// 				[
// 					'type'		=> 'warning',
// 					'message' 	=> getLangText('system', 'cache_info')
// 				]
// 			);
// 		}
		
// 		include_once ROOT . $fileName;

// 	} elseif (isset($url['m'])) {

// 		switch ($url['m']) {
// 			case 1:
// 				$page = 'uncache.php';
// 				break;
// 			case 2:
// 				$page = 'pokalbiai.php';
// 				break;
// 			case 3:
// 				$page = 'antivirus.php';
// 				break;
// 			case 4:
// 				$page = 'search.php';
// 				break;
// 			case 'upgrade':
// 				$page = 'upgrade.php';
// 				break;
// 		}

// 		include_once 'pages/' . $page;
// 	} else {
// 		include_once 'pages/dashboard.php';
// 	}
// }

function getAdminExtensionsMenu($page = null) 
{
	global $adminExtensionsMenu;

	$menu = applyFilters('adminExtensionsMenu', $adminExtensionsMenu);

	return ! empty($page) ? $menu[$page] : $menu;
}

function getAllAdminPages($page = null)
{
	$adminMenu 				= getAdminPages();
	$adminExtensionsMenu 	= getAdminExtensionsMenu();

	$allPages = array_merge($adminMenu, $adminExtensionsMenu);

	if(! empty($page)) {
		return ! empty($allPages[$page]) ? $allPages[$page] : null;
	}
	
	return $allPages;
}

function getAdminPages($page = null) 
{
	global $adminMenu;

	$menu = $adminMenu; //todo: add hooks

	return ! empty($page) ? $menu[$page] : $menu;
}
//todo: optimise it
function getAdminPagesbyId($id = null, $key = null) 
{

	$menu = getAllAdminPages();
	$idArray = [];

	foreach ($menu as $name => $file) {
		$newKey = basename($file, '.php');

		$idArray[$newKey] = [
			'file'	=> $file,
			'name'	=> $name,
		];
	}

	$key = ! empty($key) ? $key : 'name';

	return ! empty($id) ? $idArray[$id][$key] : $menu;
}

function getFeedArray($feedUrl) 
{
     
    $content = file_get_contents($feedUrl);
	$x = simplexml_load_string($content, null, LIBXML_NOCDATA);
	
    return $x->channel;
}

//atvaizduojam blokus
function dragItem($id, $content, $subMenu = null)
{
	return '<li class="dd-item dd3-item" data-id="' . $id . '">
	<div class="dd-handle dd3-handle"></div>
	<div class="dd3-content">
		' . $content . '
	</div>
	' . (! empty($subMenu) ? $subMenu : '') . '
	</li>';
}

//filtering
function tableFilter($formData, $data, $formId = '')
{
	global $lang;

	$newFormData['#'] = '<input type="checkbox" id="visi" name="visi" onclick="checkedAll(\'' . $formId . '\');" class="filled-in"><label for="visi"></label>';

	foreach($formData as $key => $value) {
		$input = '<div class="form-group">';
		$input .= '<div class="form-line">';
		$input .= '<input type="text" name="' . $key . '" value="' . (isset($data[$key]) ? $data[$key] : '') . '" class="form-control">';
		$input .= '</div>';
		$input .= '</div>';

		$newFormData[$value] = $input;
	}

	$newFormData[getLangText('admin', 'action')] = '<button type="submit" class="btn btn-primary waves-effect">' . getLangText('admin', 'filtering') . '</button>';

	return $newFormData;
}

function deleteRedirectSession()
{
	forgotSession('redirect');
}

function buttons($id = null)
{
	global $buttons;

	$buttons = applyFilters('adminButtons', $buttons);

	if(! empty($id)) {
		return isset($buttons[$id]) && ! empty($buttons[$id]) ? $buttons[$id] : null;
	} 
	
	return $buttons;
}

function icons($group, $icon)
{
	global $icons;

	$icons = applyFilters('adminMenuIcons', $icons);
	
	return ! empty($icons[$group][$icon]) ? $icons[$group][$icon] : null;
}

function iconsMenu($icon)
{
	global $icons;

	$iconsMenu = $icons['menu'];
	$iconsMenu = applyFilters('adminMenuIcons', $iconsMenu);
	
	return ! empty($iconsMenu[$icon]) ? $iconsMenu[$icon] : null;
}

