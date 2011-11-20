<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

$page = &$this->get('Pagination');
?>


<form id="adminForm" name="adminForm" method="post">
<?php if($this->items):?>
	<table class="adminlist" cellspacing=0>
		<?php foreach($this->items[0] as $col):?>
		<col>
		<?php endforeach;?>
		<tr>
			 <th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
			 </th>		
		<?php foreach($this->items[0] as $key=>$col):?>
			 <th><?php echo JText::_($key);?></th>
		<?php endforeach;?>
			<th></th>
			<th></th>
		</tr>
		
		<?php foreach($this->items as $key=>$item):?>
			<tr <?php echo $key%2==1?"class=\"odd\"":'';?>>
				 <td class="center">
					<?php echo JHtml::_('grid.id', $key, $item-><primary_id>); ?>
				 </td>			
				<?php foreach($item as $td):?>
					<td><?php echo $td; ?></td>
				<?php endforeach;?>
				<td><a href="index.php?option=<component>&view=<view>&task=remove&cid[]=<?php echo $item-><primary_id>?>">Del</a></td>
				<td><a href="index.php?option=<component>&view=<view>&layout=form&cid[]=<?php echo $item-><primary_id>?>">Edit</a></td>
			</tr>
		<?php endforeach;?>
	</table>
<?php endif; ?>	
	<!--удалить для админки-->
	<input type="hidden" name="limitstart" value="<?php echo JRequest::getInt('limitstart',0);?>"/>
	<input type="hidden" name="limit" value="<?php echo JRequest::getInt('limit',20);?>"/>
	<!--конец-->
	<input type="hidden" name="view" value="<?php echo JRequest::getVar('view');?>"/>
	<input type="hidden" name="task" value="<?php echo JRequest::getInt('task');?>"/>
	<input type="hidden" name="boxchecked" value="0">
	<?php echo $page->getListFooter(); ?>
</form>
