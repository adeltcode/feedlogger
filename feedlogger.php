<?php
/*
Plugin Name: FeedLogger
Version: 0.14b
Plugin URI: http://techblog.ecstudio.jp/tech-tips/wordpress/feedlogger.html
Description: Feedの購読者数を記録する
Author: Masaki Yamamoto
Author URI: http://techblog.ecstudio.jp
License: GNU General Public License
*/

add_action('do_feed_rss','feedlogger_write_rss');
add_action('do_feed_rss2','feedlogger_write_rss2');
add_action('do_feed_atom','feedlogger_write_atom');
add_action('do_feed_rdf','feedlogger_write_rdf');
add_action('activity_box_end', 'feedlogger_display');
//add_action('admin_menu', 'feedlogger_add_page');

require(dirname(__FILE__)."/lib/FeedLogger.php");

function feedlogger_write_rss(){
	$fl =& FeedLogger::singleton();
	
	$fl->write("rss");
}

function feedlogger_write_rss2(){
	$fl =& FeedLogger::singleton();
	
	$fl->write("rss2");
}

function feedlogger_write_atom(){
	$fl =& FeedLogger::singleton();
	
	$fl->write("atom");
}

function feedlogger_write_rdf(){
	$fl =& FeedLogger::singleton();
	
	$fl->write("rdf");
}

//function feedlogger_add_page(){
//	add_management_page('FeedLogger','FeedLogger',8, __FILE__, 'feedlogger_options_page');
//}

/**
 * ダッシュボードに表示
 */
function feedlogger_display(){
	$fl =& FeedLogger::singleton();
	?>
	<style type="text/css"><!--
	.flTable {
		border:1px solid #ccc;
		margin:1px;
		background-color:#eee
	}
	.flTableHeader {
		background-color:#C9E0F5;
		border-bottom:1px solid #ccc;
		border-right:1px solid #ccc;
		color:#333;
		margin:1px;
		padding:2px 4px 0px 4px;
		text-align:center
	}
	.flTableRow {
		background-color:#fff;
		color:#333;
		margin:1px
	}
	--></style>
	<h3>FeedLogger</h3>
	<p>購読者合計：<?= $fl->getTotalSubscribers() ?> ユーザー</p>
	<table class="flTable" cellspacing="1" cellpadding="2" border="0">
	 <tr class="flTableHeader">
	  <td>リーダー</td>
	  <td>フィード</td>
	  <td>購読者数</td>
	  <td>最終更新日</td>
	 </tr>
	 <?php if ($sb_dat = $fl->getFeedSummary()) { ?>
	  <?php foreach ($sb_dat as $reader => $feed_dat) { ?>
	  	<?php if ($reader == "_v") { continue; } ?>
	 <tr class="flTableRow">
	 	<td rowspan="<?= count($feed_dat) ?>"><a href="<?= $fl->fetcher_type[$reader]['url'] ?>" target="_blank"><?= $fl->fetcher_type[$reader]['name'] ?></a></td>
	 	<?php
	 	$i = 0;
	 	foreach ($feed_dat as $feed_type => $data){
			if ($i){ ?>
				</tr>
				<tr class="flTableRow">
			<?php } ?>
			<td align="center"><?= $feed_type ?></td>
			<td align="center"><?= $data['num'] ?></td>
			<td><?= date("m/d H:i:s",$data['time']) ?></td>
			<?php $i++;
		} ?>
	 </tr>
	  <?php } ?>
	 <?php }else{ ?>
	 <tr class="flTableRow">
	  <td align="center" colspan="4"> - 購読者数はまだ取得できていません - </td>
	 </tr>
	 <?php } ?>
	</table>
<?php
}