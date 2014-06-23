<?php
$jinput = JFactory::getApplication()->input;
$c_id=$jinput->get('c_id', 0);
$category = new bll_category($c_id);
$LangId = AuxTools::GetCurrentLanguageIDJoomla();
$total = count(bll_category::get_works_id($c_id));
$limitstart=$jinput->get('limitstart', 0);
$objs = bll_category::get_works($c_id, true, '', $limitstart, NUMBER_ELEMENTS_BY_PAGE);
$do=JFactory::getDocument();
$clval=$category->getLanguageValue($LangId);
$do->setTitle($clval->name);
$do->setDescription($clval->meta_description);
$do->setMetaData('keywords', $clval->meta_tags);
$uri = DS.JText::_('COM_PORTFOLIO_PORTFOLIO_NEEDLE').DS.$clval->alias;
$path=JFactory::getApplication()->getPathway();
$path->addItem($clval->name, './index.php/'.  AuxTools::SEFReady($uri));

?>
<div class="span12 fluid">
    <h3 class="span8 fluid">
        <?php
        if($clval->id > 0)
        {
            echo $clval->name;
        }
        ?>
    </h3>
    <button class="span3 right" onClick="history.go(-1);">
        <span class="icon-32-back"></span>
        <?php echo JText::_('COM_PORTFOLIO_BACK'); ?>
    </button>
</div>
<div class="span12 fluid">
    <?php 
    foreach($objs as $obj)
    {
        $lval=$obj->getLanguageValue($LangId);
        $image = $obj->get_main_image();
        $uri = DS.JText::_('COM_PORTFOLIO_PORTFOLIO_NEEDLE').DS.$lval->alias.'-'.$lval->work_id;
        if($image !== false && is_file(JPATH_ROOT.DS.$image->thumb))
        {
            $imguri = $image->thumb;
        }
        else
        {
            $imguri="./administrator/components/com_portfolio/images/no-image.jpg";
        }
        ?>
        <div class="span3 category-holder">
            <a href="./index.php/<?php echo AuxTools::SEFReady($uri); ?>">
            <img src="<?php echo $imguri; ?>" />
            </a>
            <a href="./index.php/<?php echo AuxTools::SEFReady($uri); ?>">
                <?php 
                echo $lval->name;
                ?>
            </a>
        </div>
        <?php
    }
    ?>
</div>
<div class="span12 pagination">
    <?php 
        echo HtmlGenerator::GeneratePagination
                ('works', null, $total, 
                $limitstart, NUMBER_ELEMENTS_BY_PAGE);
    ?>
</div>