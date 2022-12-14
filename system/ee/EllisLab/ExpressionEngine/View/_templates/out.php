<!doctype html>
<html>
	<head>
		<title><?=$cp_page_title?> | ExpressionEngine</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" lang="en-us" dir="ltr">
		<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"  name="viewport">
		<meta name="referrer" content="no-referrer">
		<meta name="robots" content="noindex, nofollow">
		<?=ee()->view->head_link('css/out.min.css')?>
	</head>
	<body>
		<section class="flex-wrap">
			<section class="wrap">

				<?=$child_view?>

				<?php if ( ! isset($branded) OR $branded !== FALSE):?>
					<section class="bar">
						<p class="left"><a href="https://expressionengine.com/" rel="external"><b>ExpressionEngine</b></a></p>
						<p style="float: right;">&copy;<?=date('Y')?> <a href="https://packettide.com/" rel="external">Packet Tide</a>, LLC</p>
					</section>
				<?php endif; ?>
			</section>
		</section>
	</body>
</html>
