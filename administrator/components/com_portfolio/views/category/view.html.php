<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * View
 */
class PortfolioViewCategory extends JViewLegacy
{
        /**
         * HelloWorlds view display method
         * @return void
         */
        function display($tpl = null) 
        {
 
                // Check for errors.
                if (count($errors = $this->get('Errors'))) 
                {
                        JError::raiseError(500, implode('<br />', $errors));
                        return false;
                }
                $this->actions();
                // Display the template
                parent::display($tpl);
                
                
        }
        
        function actions()
        {
             if(isset($_POST['action']) || isset($_GET['action']))
            {
                if(isset($_GET['action']))
                    $action=$_GET['action'];
                if(isset($_POST['action']))
                    $action=$_POST['action'];
                
                $type_msg='message';
                
                switch ($action)
                {
                    case 'edit':
                        $id=0;
                        
                        $obj=new bll_category(0);
                        $languages = languages::GetLanguages();
                        foreach($languages as $lang)
                        {
                            $lang_suffix="_".$lang->lang_id;
                            $_POST['alias'.$lang_suffix]=AuxTools::CreateAliasFromString($_POST['name'.$lang_suffix]);
                        }
                        if(isset($_POST['id']))
                        {
                            $id=$_POST['id'];
                            $obj=new bll_category($id);
                            $obj=$obj->update();
                            if($obj !== false)
                                $action_msg =JText::_('COM_PORTFOLIO_EDITED');
                            else 
                            {
                                $type_msg='error';
                                $action_msg =JText::_('COM_PORTFOLIO_ERROR_EDITING');
                            }
                        }
                        else
                        { 
                            $obj=$obj->insert();
                            if($obj !== false)
                                $action_msg =JText::_('COM_PORTFOLIO_CREATED');
                            else
                            {
                                $action_msg =JText::_('COM_PORTFOLIO_ERROR_CREATING');
                                $type_msg='error';
                            }
                        
                            $what_msg  =" ". JText::_('COM_PORTFOLIO_CATEGORY'); 
                            JFactory::getApplication()->enqueueMessage(
                                    $action_msg.$what_msg,
                                    $type_msg
                            );
                        }
                    break;

                    case 'delete':
                        if(isset($_GET['id']))
                        {
                            $id=$_GET['id'];
                            $obj=new bll_category($id);
                            
                            if($obj->delete() == true)
                                $action_msg =JText::_('COM_PORTFOLIO_DELETED');
                            else
                            {
                                $type_msg='error';
                                $action_msg =JText::_('COM_PORTFOLIO_ERROR_DELETING');
                            }
                            $what_msg  =" ". JText::_('COM_PORTFOLIO_CATEGORY'); 
                            JFactory::getApplication()->enqueueMessage(
                                    $action_msg.$what_msg,
                                    $type_msg
                            );
                        }
                    break;
                }
            }
        }
}