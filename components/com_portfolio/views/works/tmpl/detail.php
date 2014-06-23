<?php

$jinput = JFactory::getApplication()->input;
$w_id=$jinput->get('wid', 0);
$work = new bll_work($w_id);
$LangId = AuxTools::GetCurrentLanguageIDJoomla();
$lval=$work->getLanguageValue($LangId);
$do=JFactory::getDocument();
$do->setTitle($lval->name);
$do->setDescription($lval->meta_description);
$do->setMetaData('keywords', $lval->meta_tags);
$uri = DS.JText::_('COM_PORTFOLIO_PORTFOLIO_NEEDLE').DS.$lval->alias;
$path=JFactory::getApplication()->getPathway();
$path->addItem($lval->name, './index.php/'.  AuxTools::SEFReady($uri));
$images = $work->get_images();

?>
<div class="span12 fluid">
    <div class="span12 fluid">
        <h3 class="span8 fluid">
            <?php
                echo $lval->name;
            ?>
        </h3>
        <button class="span3 right" onClick="history.go(-1);">
            <span class="icon-32-back"></span>
            <?php echo JText::_('COM_PORTFOLIO_BACK'); ?>
        </button>
    </div>
    <div class="span5 fluid">
     <?php 
     $imghtml="";
     $thumbhtml="";
     $index=0;
     $rdisabled="";
     foreach($images as $image)
     {
        $root = JPATH_ROOT.DS;
        if(is_file($root.$image->image) && is_file($root.$image->thumb) )
        {
            $imghtml.='<div id="image-'.$index.'" class="image_holder span12">
                <img src="'.$image->image.'" class="image span12" /> 
            </div>';
            $index++;
        }
     }
     $hide=false;
     if($index <= 1)
     {
         $hide=true;
     }
     ?>
        <style>
            .icon_w
            {
                width:30px;
                height:20px;
            }
            .icon_w:disabled
            {
                cursor: default;
            }
            #images-container
            {
                display:block;
                position:relative;
            }
            #left
            {
                left:0px;
                position:absolute;
            }
            #right
            {
                right:0px;
                position:absolute;
            }
        </style>
        <div id="images-container" class="span12">
            <?php if($hide != true): ?>
                <button id="left" onclick="return left();" class="icon_w left icon-leftarrow" disabled="disabled"></button>
            <?php endif; ?>
                
            <?php echo $imghtml; ?>
            <?php 
                if($index==0)
                {
                    echo '<div id="image-'.$index.'" class="image_holder span12">
                        <img src="./administrator/components/com_portfolio/images/no-image.jpg" class="image span12" /> 
                    </div>';
                }
            ?>
            <?php if($hide != true): ?>
                <button id="right" onclick="return right();" class="icon_w right icon-rightarrow"></button>
            <?php endif; ?>
        </div>
    </div>
    <div class="span6 fluid">
        <?php if($lval->specs != ""): ?>
            <h3>
                <?php echo JText::_('COM_PORTFOLIO_SPECS'); ?>
            </h3>
            <div class="specs">
                <?php echo $lval->specs; ?>
            </div>
        <?php endif; ?>
        <?php if($lval->description != ""): ?>
            <h3>
                <?php echo JText::_('COM_PORTFOLIO_DESCRIPTION'); ?>
            </h3>
            <div class="description">
                <?php echo $lval->description; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script type="text/javascript">
    
    var cant = <?php echo $index; ?>;
    var active=0;
    
    jQuery(document).ready(function(){
       for(var i=0; i < cant; i++)
       {
           var i_id = "#image-"+i;
           if(i > 0)
           {
               jQuery(i_id).css("display", "none");
           }
       }
    });
    
    function hide_images()
    {
        for(var i=0; i < cant; i++)
        {
            var i_id = "#image-"+i;
            jQuery(i_id).css("display", "none");
        }
    }
    
    function left()
    {
        if(active > 0)
        {
            hide_images();
            active--;
            var i_id = "#image-"+active;
            jQuery(i_id).css("display", "");
            if(active <= 0)
            {
                jQuery("#left")[0].disabled=true;
            }
            else
            {
                jQuery("#left")[0].disabled=false;
            }
            jQuery("#right")[0].disabled=false;
        }
    }
    
    function right()
    {
        if(active < cant)
        {
            hide_images();
            active++;
            var i_id = "#image-"+active;
            jQuery(i_id).css("display", "");
            if(active >= (cant-1))
                jQuery("#right")[0].disabled=true;
            else
                jQuery("#right")[0].disabled=false;
            
            jQuery("#left")[0].disabled=false;
        }
    }
</script>