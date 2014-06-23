<?php

$LangId = AuxTools::GetCurrentLanguageIDJoomla();
$lang = new languages($LangId);
$ob = new bll_work(0);
$nelementsbypage =NUMBER_ELEMENTS_BY_PAGE;
$total = count($ob->findAll(null,null,true, $ob->getPrimaryKeyField()));
$lower_limit=0;

if(isset($_REQUEST['limitstart']))
    $lower_limit=$_REQUEST['limitstart'];

$objs= $ob->findAll(null,null,true, $ob->getPrimaryKeyField(), $lower_limit, $nelementsbypage);
?>

<div id="j-main-container" class="span10"> 
    <div class="btn-toolbar" id="toolbar">
        <div class="btn-wrapper" id="toolbar-new">

                <a class="btn btn-small btn-success" href="./index.php?option=com_portfolio&view=works&layout=edit">

                        <?php echo JText::_('COM_PORTFOLIO_NEW')?>

                    <span class="icon-new icon-white"></span>

                </a>

        </div>
        <div class="btn-wrapper" id="toolbar-cancel">
            <a class="btn btn-small" href="./index.php?option=com_portfolio">
                <span class="icon-cancel"></span>
                <?php echo JText::_('COM_PORTFOLIO_CANCEL')?>
            </a>
        </div>
    </div>
    <h3>
        <?php echo JText::_('COM_PORTFOLIO_WORK')." ". JText::_('COM_PORTFOLIO_MANAGER'); ?>
    </h3>
    <table class="table table-striped">
        <tr>
              <th><?php echo JText::_('COM_PORTFOLIO_ID')?></th>
              <th><?php echo JText::_('COM_PORTFOLIO_IMAGE')?></th>
              <th><?php echo JText::_('COM_PORTFOLIO_NAME')."($lang->title_native)"?></th>
              <th><?php echo JText::_('COM_PORTFOLIO_ALIAS')."($lang->title_native)"?></th>
              <th><?php echo JText::_('COM_PORTFOLIO_CATEGORY')?></th>
              <th><?php echo JText::_('COM_PORTFOLIO_ACTIONS')?></th>
        </tr>
        <?php
foreach($objs as $obj)
{
?>

    <tr>

        <td> <?php echo $obj->id; ?></td>
        <td> <?php
                if($obj->get_main_image()!== false)
                {
                    ?>
                    <img width="90" src="../<?php echo $obj->get_main_image()->thumb; ?>" /> 
                    <?php
                }
                else
                {
                    ?>
                    <img width="90" src="../<?php echo "/administrator/components/com_portfolio/images/no-image.jpg"; ?>" /> 
                    <?php 
                }
                ?>
        </td>
        <td> <?php echo $obj->getLanguageValue($LangId)->name; ?></td>
        <td> <?php echo $obj->getLanguageValue($LangId)->alias; ?></td>
        <td>
            <ul>
            <?php
            
            foreach(bll_work::get_categories($obj->id) as $cat)
            {
                echo "<li>".$cat->getLanguageValue($LangId)->name."</li>";
            }
            ?>
            </ul>
        </td>
        <td>

            <form method="POST" action="./index.php?option=com_portfolio&view=works&layout=edit">
                 <input type="hidden"  name="id" value="<?php echo $obj->id; ?>"/>
                 <input type="hidden" name="limitstart" value="<?php echo $lower_limit; ?>" />
                 <button><?php echo JText::_('COM_PORTFOLIO_EDIT')?></button>
            </form>
            <a class="button" href="./index.php?option=com_portfolio&view=works&action=delete&id=<?php echo $obj->id; ?>">
            <?php echo JText::_('COM_PORTFOLIO_DELETE')?>
            </a>
        </td>    
    </tr>
<?php
}
?>

      </table>
    <?php
    echo HtmlGenerator::GeneratePagination($ob->getObjectName(), './index.php?option=com_portfolio&view=work', $total, $lower_limit, $nelementsbypage);
    ?>
</div>