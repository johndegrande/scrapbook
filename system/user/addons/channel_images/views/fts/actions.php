<div class="ChannelImagesField cfix">
<div class="CIActions cfix" id="CIActions">
<a href="#" class="AddActionGroup"><img src="<?php echo $themeUrl; ?>img/add.png" /><?php echo lang('ci:add_action_group')?></a>
<div class="default_actions">
<?php $io=0;foreach($actions as $action_name => &$actionobj): echo $io."<br>";?>
	<div class="<?php echo $actionobj->info['name']; ?>">
		<?php echo base64_encode('

		<tr>
			<td></td>
			<td>'.$actions[$action_name]->info['title'].'</td>
			<td><!--<div style="white-space: normal;">-->
			'.$actions[$action_name]->display_settings().'
			<input type="hidden" class="action_step" name="channel_images[action_groups][][actions]['.$action_name.'][step]" value="">
		<!-- </div>--></td>
		<td><a href="#" class="MoveAction">&nbsp;</a><a href="#" class="DelAction">&nbsp;</a></td>
		</tr>

		');?>
	</div>
<?php endforeach;?>
</div>
<script id="ChannelImagesActionGroup" type="text/x-jquery-tmpl">
    <div style="white-space: normal;">
<table cellspacing="0" cellpadding="0" border="0" class="ChannelImagesTable ActionGroup">
	<thead>
		<tr class="group_name">
			<th colspan="4">
				<h4>{{{group_name}}}</h4> <small><?php echo lang('ci:click2edit') ?></small>
				<input type="hidden" class="gname" name="channel_images[action_groups][][group_name]" value="{{{group_name}}}">
				<span class="imageprev">
					<input type="checkbox" name="channel_images[action_groups][][wysiwyg]" value="yes" class="wysiwyg" {{#wysiwyg}}checked{{/wysiwyg}}> <?php echo lang('ci:wysiwyg')?> &nbsp;&nbsp;
					<input type="checkbox" name="channel_images[action_groups][][editable]" value="yes" class="editable" {{#editable}}checked{{/editable}}> <?php echo lang('ci:editable')?> &nbsp;&nbsp;
					<input type="radio" name="channel_images[small_preview]" value="" class="small_preview" {{#small_preview}}checked{{/small_preview}}> <?php echo lang('ci:small_prev')?> &nbsp;&nbsp;
					<input type="radio" name="channel_images[big_preview]" value="" class="big_preview" {{#big_preview}}checked{{/big_preview}} > <?php echo lang('ci:big_prev')?>
					<a href="#" class="DelActionGroup">&nbsp;</a>
				</span>
			</th>
		</tr>
		<tr class="action_cols">
			<th style="width:50px"><?php echo lang('ci:step')?></th>
			<th style="width:150px"><?php echo lang('ci:action')?></th>
			<th><?php echo lang('ci:settings')?></th>
			<th style="width:40px"></th>
		</tr>
	</thead>
	<tbody>
	{{#actions}}
		<tr>
			<td>1</td>
			<td>{{{action_name}}}</td>
			<td>
				<a href="#" class="SettingsToggler" rel="<?php echo lang('ci:show_settings')?>"><?php echo lang('ci:hide_settings')?></a>
				<div class="actionsettings">
				{{{action_settings}}}
				</div>
				<input type="hidden" class="action_step" name="channel_images[action_groups][][actions][{{{action}}}][step]" value="">
			</td>
			<td><a href="#" class="MoveAction">&nbsp;</a><a href="#" class="DelAction">&nbsp;</a></td>
		</tr>
	{{/actions}}
	{{^actions}}
		<tr class="NoActions"><td colspan="4"><?php echo lang('ci:no_actions')?></td></tr>
	{{/actions}}
	</tbody>
	<tfoot>
		<tr>
			<td colspan="3">
				<select>
				<option value=""><?php echo lang('ci:add_action')?></option>
				<?php foreach($actions as $action_name => &$actionobj):?>
					<option value="<?php echo $actionobj->info['name'];?>"><?php echo $actionobj->info['title']?></option>
				<?php endforeach;?>
				</select>
			</td>
			<td>

			</td>
		</tr>
	</tfoot>
	</table>

</script>
</div>
    </div>