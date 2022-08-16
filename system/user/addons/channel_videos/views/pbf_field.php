<?php $layout = strtolower($layout)?>
<?php $json = strtolower($json)?>

<div class="CVField" id="ChannelVideos<?php echo $field_id; ?>" rel="<?php echo $field_id; ?>" data-field_id="<?php echo $field_id; ?>">
    <button class="btn action SearchVideos"><?php echo lang('cv:search'); ?></button>
    <button class="btn action SubmitVideoUrl"><?php echo lang('cv:submit_url'); ?></button>
    <br /><br />

    <div class="cvhidden SVWrapperTR">
        <div colspan="99" class="SVWrapper">
            <div class="cvsearch">
                <?php echo lang('cv:keywords'); ?> <input rel="keywords" type="text" style="width:150px"/>&nbsp;&nbsp;
                <?php echo lang('cv:author') ;?> <input rel="author" value="" type="text" style="width:90px"/>&nbsp;&nbsp;
                <?php echo lang('cv:limit'); ?> <input rel="limit" value="10" type="text" style="width:35px"/>&nbsp;&nbsp;&nbsp;
                <input type="submit" class="btn action searchbutton" value="<?php echo lang('cv:find_videos'); ?>" />
                <span class="searchWarning" style="display:none;"><?php echo lang('cv:search_warning'); ?></span>
            </div>

            <div class="VideosResults">
                <div class="results results-youtube cvhidden">
                    <h2>YouTube <a href="#" class="btn ClearVideoSearch"><?php echo lang('cv:clear_search'); ?></a></h2>
                    <p class="LoadingVideos"><?php echo lang('cv:searching_videos'); ?></p>
                    <div class="inner"></div>
                </div>
                <div class="results results-vimeo cvhidden">
                    <h2>Vimeo <a href="#" class="btn ClearVideoSearch"><?php echo lang('cv:clear_search'); ?></a></h2>
                    <p class="LoadingVideos"><?php echo lang('cv:searching_videos'); ?></p>
                    <div class="inner"></div>
                </div>
            </div>
        </div>
    </div>

    <table class="CVTable" width="100%">
    <thead>
    <?php if ($layout == 'table') :?>
    <tr>
        <?php foreach ($settings['columns'] as $type => $val) :?>
            <?php if ($val == false) {
                continue;
            }?>
            <?php $size = '';
            if ($type == 'image') {
                $size = '120';
            }?>
        <th style="width:<?php echo $size; ?>px"><?php echo $val; ?></th>
        <?php endforeach;?>

        <th style="width:60px"><?php echo lang('cv:actions'); ?></th>
    </tr>
    <?php endif;?>
</thead>
<?php if ($layout == 'table') :?>
    <tbody class="AssignedVideos">
    <?php foreach ($videos as $order => $vid) :?>
        <?php $this->load->view('pbf_single_video', array('vid' => $vid, 'order' => $order)); ?>
    <?php endforeach;?>
    <?php if ($total_videos < 1) :
        ?><tr class="NoVideos"><td colspan="99"><?php echo lang('cv:no_videos'); ?></td></tr><?php
    endif;?>
    </tbody>
<?php else :?>
    <tbody><tr><td class="AssignedVideos TileBased">
    <?php foreach ($videos as $order => $vid) :?>
        <?php $this->load->view('pbf_single_video', array('vid' => $vid, 'order' => $order, 'layout' => $layout)); ?>
    <?php endforeach;?>
    <?php if ($total_videos < 1) :
        ?><p class="NoVideos"><?php echo lang('cv:no_videos'); ?></p><?php
    endif;?>
    </td></tr></tbody>
<?php endif;?>
<tfoot>
    <tr>
        <td <?php if ($settings['video_limit'] == '999999') {
            echo 'style="display:none"';
            }?> colspan="99" class="VideoLimit"><?php echo lang('cv:remain'); ?> <span class="remain"><?php echo $settings['video_limit']; ?></span></td>
    </tr>
</tfoot>
</table>

<script type="text/x-channelvideos" class="jsondata">
<?php echo $json; ?>
</script>
</div>