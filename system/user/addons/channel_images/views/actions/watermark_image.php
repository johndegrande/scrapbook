<table cellspacing="0" cellpadding="0" border="0" class="ChannelImagesTable CITable" style="white-space: normal; text-align: justify;">
	<thead>
		<tr>
			<th colspan="2">
				<h4>
					<?php echo lang('ci:watermark:position_settings')?>
				</h4>
			</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?php echo lang('ci:watermark:padding')?> </td>
			<td>
				<?php echo form_input($action_field_name.'[padding]', $padding, 'style="border:1px solid #ccc; width:80%;"')?>
				<small><?php echo lang('ci:watermark:padding:exp')?></small>
			</td>
		</tr>
		<tr>
			<td><?php echo lang('ci:watermark:horalign')?> </td>
			<td>
				<?php echo form_input($action_field_name.'[horizontal_alignment]', $horizontal_alignment, 'style="border:1px solid #ccc; width:80%;"')?>
				<small><?php echo lang('ci:watermark:horalign:exp')?></small>
			</td>
		</tr>
		<tr>
			<td><?php echo lang('ci:watermark:vrtalign')?> </td>
                        <td >
				<?php echo form_input($action_field_name.'[vertical_alignment]', $vertical_alignment, 'style="border:1px solid #ccc; width:80%;"')?>
				<small><?php echo lang('ci:watermark:vrtalign:exp')?></small>
			</td>
		</tr>
		<tr>
			<td><?php echo lang('ci:watermark:horoffset')?> </td>
              
                        <td >
				<?php echo form_input($action_field_name.'[horizontal_offset]', $horizontal_offset, 'style="border:1px solid #ccc;"')?>
                           
                            <small><?php echo lang('ci:watermark:horoffset:exp')?></small>
                         
			</td>
                    
		</tr>
		<tr>
			<td><?php echo lang('ci:watermark:vrtoffset')?> </td>
			<td>
				<?php echo form_input($action_field_name.'[vertical_offset]', $vertical_offset, 'style="border:1px solid #ccc; width:80%;"')?>
				<small><?php echo lang('ci:watermark:vrtoffset:exp')?></small>
			</td>
		</tr>
	</tbody>
</table>

<table cellspacing="0" cellpadding="0" border="0" class="ChannelImagesTable">
	<thead>
		<tr>
			<th colspan="2">
				<h4>
					<?php echo lang('ci:watermark:overlay_pref')?>
				</h4>
			</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?php echo lang('ci:watermark:overlay_path')?> </td>
			<td>
				<?php echo form_input($action_field_name.'[overlay_path]', $overlay_path, 'style="border:1px solid #ccc; width:80%;"')?>
				<small><?php echo lang('ci:watermark:overlay_path:exp')?></small>
			</td>
		</tr>
		<tr>
			<td><?php echo lang('ci:watermark:opacity')?> </td>
			<td>
				<?php echo form_input($action_field_name.'[opacity]', $opacity, 'style="border:1px solid #ccc; width:80%;"')?>
				<small><?php echo lang('ci:watermark:opacity:exp')?></small>
			</td>
		</tr>
		<tr>
			<td><?php echo lang('ci:watermark:x_trans')?> </td>
			<td>
				<?php echo form_input($action_field_name.'[x_transp]', $x_transp, 'style="border:1px solid #ccc; width:80%;"')?>
				<small><?php echo lang('ci:watermark:x_trans:exp')?></small>
			</td>
		</tr>
		<tr>
			<td><?php echo lang('ci:watermark:y_trans')?> </td>
			<td>
				<?php echo form_input($action_field_name.'[y_transp]', $y_transp, 'style="border:1px solid #ccc; width:80%;"')?>
				<small><?php echo lang('ci:watermark:y_trans:exp')?></small>
			</td>
		</tr>
	</tbody>
</table>