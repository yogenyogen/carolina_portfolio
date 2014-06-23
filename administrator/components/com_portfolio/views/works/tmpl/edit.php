<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$id=0;
if(isset($_POST['id']))
    $id=$_POST['id'];

$lower_limit=0;
if(isset($_REQUEST['limitstart']))
    $lower_limit=$_REQUEST['limitstart'];

$work = new bll_work($id);
$language = new languages(AuxTools::GetCurrentLanguageIDJoomla());
$languages = $language->findAll();

$db= new dbprovider(true);
$id=$db->escape_string($id);

$jspath = AuxTools::getJSPathFromPHPDir(JPATH_COMPONENT_ADMINISTRATOR); 
$name='#__name';
$ids='#__id';
$dir='stories';

$xmlfile = new SimpleXMLElement('<field name="'.$name.'" type="media" directory="'.$dir.'" />');
$f = new JForm('temp');
$f->load($xmlfile);
$f->setField($xmlfile);
$f->setFieldAttribute($name, 'id', $ids);

$images = $work->get_images();
?>

  <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
  <script type="text/javascript" >

    var nimg = <?php echo count($images); ?>;
    var imgfield =<?php echo json_encode($f->getInput($name)); ?>;
    var imglabel='<label for="Images_#__nimg" id="imalabel_#__nimg" ><?php echo JText::_('COM_PORTFOLIO_IMAGE'); ?> #__nimg</label>';
    var imgtlabel='<label for="ImagesThumb_#__nimg" id="imalabelthumb_#__nimg" ><?php echo JText::_('COM_PORTFOLIO_IMAGE_THUMB'); ?> #__nimg</label>';
    var btndel="<br/><label><?php echo JText::_('COM_PORTFOLIO_MAIN_IMAGE')?>:</label><input type=\"radio\" value=\"#__nimg\" name=\"mainimg\" /><br/><a href=\"#\" onclick=\"return removeimg(#__nimg);\"><?php echo JText::_('COM_PORTFOLIO_REMOVE_IMAGE')?></a>";
    function addimg()
    {
        nimg++;
        var name='Images[]';
        var ni="";
        ni+=nimg;
        var idf = 'image_'+ni;
        var label = imglabel.replace('#__nimg',ni);
        label =label.replace('#__nimg',ni);
        label =label.replace('#__nimg',ni);
        var field = imgfield.replace('___id', idf);
        var btdel =btndel.replace('#__nimg',ni);
        btdel =btdel.replace('#__nimg',ni);
        field = field.replace('___id', idf);
        field = field.replace('___id', idf);
        field = field.replace('___id', idf);
        field = field.replace('___id', idf);
        field = field.replace('___id', idf);
        field = field.replace('#__name', name);
        field = field.replace('#__name', name);
        field = field.replace('#__name', name);
        var label2 =imgtlabel.replace('#__nimg',ni);
        label2 =label2.replace('#__nimg',ni);
        label2 =label2.replace('#__nimg',ni);
        var idf2 = 'imagethumb_'+ni;
        var name2='ImagesThumb[]';
        var field2 = imgfield.replace('___id', idf2);
        field2 = field2.replace('___id', idf2);
        field2 = field2.replace('___id', idf2);
        field2 = field2.replace('___id', idf2);
        field2 = field2.replace('___id', idf2);
        field2 = field2.replace('___id', idf2);
        field2 = field2.replace('#__name', name2);
        field2 = field2.replace('#__name', name2);
        field2 = field2.replace('#__name', name2);
        $('.newimages').append("<div>"+label+field+label2+field2+btdel+"</div>");
        window.addEvent('domready', function() {
			SqueezeBox.initialize({});
			SqueezeBox.assign($$('a.modal'), {
				parse: 'rel'
			});
        });
        return false;

    }

    

    function removeimg(index)
    {
        var label=$('#imalabel_'+index)[0];
        var holder = label.parentNode;
        if(holder.tagName==="LI")
            {
                var sibling = holder.nextSibling;
                var sibling2 = holder.nextSibling.nextSibling;
                var sibling3 = holder.nextSibling.nextSibling.nextSibling;
                var sibling4 = holder.nextSibling.nextSibling.nextSibling.nextSibling;
                sibling.outerHTML="";
                sibling2.outerHTML="";
                sibling3.outerHTML="";
                sibling4.outerHTML="";
            }
        holder.outerHTML="";
        
        nimg--;
        return false;
    }

</script>

<div id="j-main-container" class="span10">   
    <div class="btn-toolbar" id="toolbar">
        <div class="btn-wrapper" id="toolbar-new">
                <a class="btn btn-small" href="./index.php?option=com_portfolio&view=works&limitstart=<?php echo $lower_limit; ?>">
                <span class="icon-cancel"></span>
                <?php echo JText::_('COM_PORTFOLIO_CANCEL')?>
                </a>
        </div>
    </div>
    <h3>
        <?php echo JText::_('COM_PORTFOLIO_WORK'). " ".JText::_('COM_PORTFOLIO_DETAILS'); ?> 
    </h3>
    <?php
    $cat = new bll_category(-1);
    $cats = $cat->findAll();
    $categories=array();
    $selcat = bll_work::get_categories($work->id);
    foreach($cats as $cat)
    {
        $sel = false;
        $name=$cat->getLanguageValue($language->lang_id)->name;
        foreach($selcat as $scat)
        {
            if($scat->id == $cat->id)
                $sel=true;
        }
        if($sel == true)
        {
            $categories['#__'.$cat->id]=$name;
        }
        else
        {
            $categories[$cat->id]=$name;
        }
    }
    $form= form::getInstance();
    $form->setLayout(FormLayouts::FORMS_UL_LAYOUT);
    if(isset($_POST['id']))
    $form->Hidden('id', $work->id);
    $form->Hidden('action', 'edit', '', '');
    foreach($languages as $lang)
    {
        $langval = $work->getLanguageValue($lang->lang_id);
        $form->Label(JText::_('COM_PORTFOLIO_NAME')."($lang->title_native)", 'name_'.$lang->lang_id);
        $form->Text('name_'.$lang->lang_id, $langval->name, '', 'Labels', true);
        $form->Label(JText::_('COM_PORTFOLIO_SPECS')."($lang->title_native)", 'specs_'.$lang->lang_id);
        $form->TextArea('specs_'.$lang->lang_id, $langval->specs, '', 'Labels');
        $form->Label(JText::_('COM_PORTFOLIO_DESCRIPTION')."($lang->title_native)", 'description_'.$lang->lang_id);
        $form->JEditor('description_'.$lang->lang_id, $langval->description, 600, 300, 60, 30);
        
        $form->Label(JText::_('COM_PORTFOLIO_META_DESCRIPTION')."($lang->title_native)", 'meta_description_'.$lang->lang_id);
        $form->TextArea('meta_description_'.$lang->lang_id, $langval->meta_description, '', 'Labels');
        $form->Label(JText::_('COM_PORTFOLIO_META_TAGS')."($lang->title_native)", 'meta_tags_'.$lang->lang_id);
        $form->TextArea('meta_tags_'.$lang->lang_id, $langval->meta_tags, '', 'Labels');
    }
    $form->Label(JText::_('COM_PORTFOLIO_CATEGORIES'), 'categories[]');
    $form->Checkboxes('categories', $categories);
    $index=1;
    foreach($images as $image)
    {
        $form->Label(JText::_('COM_PORTFOLIO_IMAGE')." ".$index, 'images_'.$index, 'imalabel_'.$index);
        $form->JMediaField('Images[]', $image->image, 'stories', 'image_'.$index );
        $form->Label(JText::_('COM_PORTFOLIO_IMAGE_THUMB')." ".$index, 'imagesthumb_'.$index, 'imalabelthumb_'.$index);
        $form->JMediaField('Thumb[]', $image->thumb, 'stories', 'imagesthumb_'.$index );
        $form->HTML("<label>".JText::_('COM_PORTFOLIO_MAIN_IMAGE').":</label><input type=\"radio\" value=\"$index\" name=\"mainimg\" /><br/><a href=\"#\" onclick=\"return removeimg($index);\">".JText::_('COM_PORTFOLIO_REMOVE_IMAGE')."</a>");
        $index++;
    }
    $form->HTML("<div class=\"newimages\"></div><a onclick=\"return addimg()\">".JText::_('COM_PORTFOLIO_ADD_IMAGE')."</a>");
    
    $form->Submit(JText::_('COM_PORTFOLIO_SAVE'));
    echo $form->Render('./index.php?option=com_portfolio&view=works&limitstart='.$lower_limit);
    ?>
</div>

