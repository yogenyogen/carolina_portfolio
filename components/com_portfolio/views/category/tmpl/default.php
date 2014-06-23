<?php 
$c = new bll_category(-1);
$cats=$c->findAll();
$LangId = AuxTools::GetCurrentLanguageIDJoomla();
?>
<div class="span12 fluid">
    <?php 
    foreach($cats as $cat)
    {
        $lval=$cat->getLanguageValue($LangId);
        $uri = DS.JText::_('COM_PORTFOLIO_PORTFOLIO_NEEDLE').DS.$lval->alias;
        if(is_file(JPATH_ROOT.DS.$cat->image))
        {
            $imguri = $cat->image;
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