<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
$id=0;
if(isset($_POST['id']))
    $id=$_POST['id'];

$lower_limit=0;
if(isset($_REQUEST['limitstart']))
    $lower_limit=$_REQUEST['limitstart'];

$category = new bll_category($id);
$language = new languages(AuxTools::GetCurrentLanguageIDJoomla());
$languages = $language->findAll();

$db= new dbprovider(true);
$id=$db->escape_string($id);

$jspath = AuxTools::getJSPathFromPHPDir(JPATH_COMPONENT_ADMINISTRATOR); 

?>

  <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<div id="j-main-container" class="span10">   
    <div class="btn-toolbar" id="toolbar">
        <div class="btn-wrapper" id="toolbar-new">
                <a class="btn btn-small" href="./index.php?option=com_portfolio&view=category&limitstart=<?php echo $lower_limit; ?>">
                <span class="icon-cancel"></span>
                <?php echo JText::_('COM_PORTFOLIO_CANCEL')?>
                </a>
        </div>
    </div>
    <h3>
        <?php echo JText::_('COM_PORTFOLIO_CATEGORY'). " ".JText::_('COM_PORTFOLIO_DETAILS'); ?> 
    </h3>
    <?php
    $form= form::getInstance();
    $form->setLayout(FormLayouts::FORMS_UL_LAYOUT);
    if(isset($_POST['id']))
    $form->Hidden('id', $category->id);
    $form->Hidden('action', 'edit', '', '');
    foreach($languages as $lang)
    {
        $langval = $category->getLanguageValue($lang->lang_id);
        $form->Label(JText::_('COM_PORTFOLIO_NAME')."($lang->title_native)", 'name_'.$lang->lang_id);
        $form->Text('name_'.$lang->lang_id, $langval->name, '', 'Labels', true);
        $form->Label(JText::_('COM_PORTFOLIO_META_DESCRIPTION')."($lang->title_native)", 'meta_description_'.$lang->lang_id);
        $form->TextArea('meta_description_'.$lang->lang_id, $langval->meta_description, '', 'Labels');
        $form->Label(JText::_('COM_PORTFOLIO_META_TAGS')."($lang->title_native)", 'meta_tags_'.$lang->lang_id);
        $form->TextArea('meta_tags_'.$lang->lang_id, $langval->meta_tags, '', 'Labels');
    }
    $form->Label(JText::_('COM_PORTFOLIO_IMAGE'), 'image');
    $form->JMediaField('image', $category->image);
    $form->Submit(JText::_('COM_PORTFOLIO_SAVE'));
    echo $form->Render('./index.php?option=com_portfolio&view=category&limitstart='.$lower_limit);
    ?>
</div>

