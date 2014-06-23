<?php

$LangId = AuxTools::GetCurrentLanguageIDJoomla();
$lang = new languages($LangId);
$ob = new bll_category(0);
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

                <a class="btn btn-small btn-success" href="./index.php?option=com_portfolio&view=category&layout=edit">

                        <?php echo JText::_('COM_PORTFOLIO_NEW')?>

                    <span class="icon-new icon-white"></span>

                </a>

        </div>
        <div class="btn-wrapper" id="toolbar-back">
            <a class="btn btn-small" href="./index.php?option=com_portfolio">
                <span class="icon-cancel"></span>
                <?php echo JText::_('COM_PORTFOLIO_CANCEL')?>
            </a>
        </div>
    </div>
    <h3>
        <?php echo JText::_('COM_PORTFOLIO_CATEGORY')." ". JText::_('COM_PORTFOLIO_MANAGER'); ?>
    </h3>
    <table class="table table-striped">
        <tr>
              <th><?php echo JText::_('COM_PORTFOLIO_ID')?></th>
              <th><?php echo JText::_('COM_PORTFOLIO_NAME')."($lang->title_native)"?></th>
              <th><?php echo JText::_('COM_PORTFOLIO_ALIAS')."($lang->title_native)"?></th>
              <th><?php echo JText::_('COM_PORTFOLIO_ACTIONS')?></th>
        </tr>
        <?php
foreach($objs as $obj)
{
?>

    <tr>

        <td> <?php echo $obj->id; ?></td>
        <td> <?php echo $obj->getLanguageValue($LangId)->name; ?></td>
        <td> <?php echo $obj->getLanguageValue($LangId)->alias; ?></td>
        <td>

            <form method="POST" action="./index.php?option=com_portfolio&view=category&layout=edit">
                 <input type="hidden"  name="id" value="<?php echo $obj->id; ?>"/>
                 <input type="hidden" name="limitstart" value="<?php echo $lower_limit; ?>" />
                 <button><?php echo JText::_('COM_PORTFOLIO_EDIT')?></button>
            </form>
            <a class="button" href="./index.php?option=com_portfolio&view=category&action=delete&id=<?php echo $obj->id; ?>">
            <?php echo JText::_('COM_PORTFOLIO_DELETE')?>
            </a>
        </td>    
    </tr>
<?php
}
?>

      </table>
    <?php
    echo HtmlGenerator::GeneratePagination($ob->getObjectName(), './index.php?option=com_portfolio&view=category', $total, $lower_limit, $nelementsbypage);
    ?>
</div>