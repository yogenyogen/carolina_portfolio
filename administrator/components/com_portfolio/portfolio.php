<?php// No direct access to this filedefined('_JEXEC') or die('Restricted access');// import joomla controller libraryjimport('joomla.application.component.controller');require_once JPATH_ROOT.'/libs/defines.php';require_once BASE_DIR.LIBS.INCLUDES;oDirectory::loadClassesFromDirectory(JPATH_COMPONENT_ADMINISTRATOR.DS.MODELS.DS.DATA);oDirectory::loadClassesFromDirectory(JPATH_COMPONENT_ADMINISTRATOR.DS.MODELS.DS.LOGIC);// Get an instance of the controller prefixed by Fittizen$controller = JControllerLegacy::getInstance('Portfolio');$lang = JFactory::getLanguage();$extension = 'com_portfolio';$language_tag = AuxTools::GetCurrentLanguageJoomla();$reload = true;$lang->load($extension, JPATH_COMPONENT_ADMINISTRATOR, $language_tag, $reload);JToolbarHelper::title(JText::_('Portfolio Manager'), 'Portfolio');$menu_elements = array(        JText::_('COM_PORTFOLIO_START')=>        './index.php?option=com_portfolio',        JText::_('COM_PORTFOLIO_CATEGORY')=>        './index.php?option=com_portfolio&view=category',        JText::_('COM_PORTFOLIO_WORKS')=>        './index.php?option=com_portfolio&view=works',);$element = "";if(isset($_GET['view'])){   $element = $_GET['view'];}JHtml::stylesheet(JPATH_COMPONENT_ADMINISTRATOR.DS.STYLES.STYLE);HtmlGenerator::GenerateJoomlaSideBarMenu($menu_elements, $element);// Get the task$jinput = JFactory::getApplication()->input;$task = $jinput->get('task', "", 'STR' );// Perform the Request task$controller->execute($task);// Redirect if set by the controller$controller->redirect();AuxTools::DatabaseDebugging();