<?php

defined('_JEXEC') or die;

if (!defined('DS'))
  define('DS', DIRECTORY_SEPARATOR);

$admin_root=JPATH_ROOT.'/administrator/components/com_portfolio/';

require_once JPATH_ROOT.DS.'libs/defines.php';
require_once JPATH_ROOT.DS.LIBS.INCLUDES;
oDirectory::loadClassesFromDirectory($admin_root.MODELS.DATA);
oDirectory::loadClassesFromDirectory($admin_root.MODELS.LOGIC);

$lang = JFactory::getLanguage();
$extension = 'com_portfolio';
$language_tag = AuxTools::GetCurrentLanguageJoomla();
$reload = true;
$lang->load($extension, $admin_root, $language_tag, $reload);

function portfolioBuildRoute(&$query) {
  
  return array();
}

function PortfolioParseRoute($segments) 
{
    $vars = array();
    $definer = $segments[count($segments)-1];
    //preparing for search
    $definer=(str_replace(':', '-', $definer));
    $arr = explode('-', $definer);
    $view_layout=$arr[count($arr)-1];
    $arr = explode('.', $view_layout);
    $view_layout=$arr[0];
    $set_layout="";
    if(is_numeric($view_layout))//business detail
    {
        $set_layout="work_detail";
    }
    else if(!is_numeric($view_layout))//category detail
    {
        $set_layout="category_detail";
    }
    
    switch($set_layout)
    {
        case "category_detail":
            $vars['option'] = "com_portfolio";
            $vars['view'] = "works";
            $vars['layout'] = "default";
            $arr = explode('.', $definer);
            $needle=$arr[0];
            $cat = new portfolio_category_lang(0);
            $cat=$cat->find(array(array('alias','=')), array(array($needle,null)));
            $id=0;
            if( $cat->category_id > 0)
                $id=$cat->category_id;
            $vars['c_id']=$id;
        break;
        case "work_detail":
            $id=$view_layout;
            $vars['option'] = "com_portfolio";
            $vars['view'] = "works";
            $vars['layout'] = "detail";
            $vars['wid']=$id;
        break;
        case "default":
            //default view
            $vars['option'] = "com_portfolio";
            $vars['view'] = "category";
            $vars['layout'] = "default";
        break;
    }
    
    
    return $vars;
}