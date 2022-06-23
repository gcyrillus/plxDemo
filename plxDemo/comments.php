
<form action="comments.php<?php echo !empty($_GET['a'])?'?a='.$_GET['a']:'' ?>" method="post" id="form_comments">

	<div class="inline-form action-bar">
		<?php echo $h2 ?>
		<ul class="menu">
			<?php echo implode($breadcrumbs); ?>
		</ul>
		<?php echo $selector ?>
		<?php echo plxToken::getTokenPostMethod() ?>
		<input type="submit" name="btn_ok" value="<?php echo L_OK ?>" onclick="return confirmAction(this.form, 'id_selection', 'delete', 'idCom[]', '<?php echo L_CONFIRM_DELETE ?>')" />
	</div>

	<?php if(isset($h3)) echo $h3 ?>

	<div class="scrollable-table">
		<table id="comments-table" class="full-width">
			<thead>
				<tr>
					<th class="checkbox"><input type="checkbox" onclick="checkAll(this.form, 'idCom[]')" /></th>
					<th class="datetime"><?= L_COMMENTS_LIST_DATE ?></th>
<?php
			$all = ($_SESSION['selCom'] == 'all');
			if($all) {
?>
					<th class="status"><?= L_COMMENT_STATUS_FIELD ?></th>
<?php
			}
?>
					<th class="message"><?= L_COMMENTS_LIST_MESSAGE ?></th>
					<th class="author"><?= L_COMMENTS_LIST_AUTHOR ?> <?= L_COMMENT_EMAIL_FIELD ?></th>
					<th class="site"><?= L_COMMENT_SITE_FIELD ?></th>
					<th class="action"><?= L_COMMENTS_LIST_ACTION ?></th>
				</tr>
			</thead>
			<tbody>

<?php
			# On va récupérer les commentaires
			$plxAdmin->getPage();
			$start = $plxAdmin->aConf['bypage_admin_coms']*($plxAdmin->page-1);
			$coms = $plxAdmin->getCommentaires($comSelMotif,'rsort',$start,$plxAdmin->aConf['bypage_admin_coms'],'all');
			if($coms) {
				while($plxAdmin->plxRecord_coms->loop()) { # On boucle
					$artId = $plxAdmin->plxRecord_coms->f('article');
					$status = $plxAdmin->plxRecord_coms->f('status');
					$id = $status.$artId.'.'.$plxAdmin->plxRecord_coms->f('numero');
					$query = 'c=' . $id;
					if(isset($_GET['a'])) {
						$query .= '&a=' . $_GET['a'];
					}
					# On génère notre ligne
?>
				<tr class="top type-<?= $plxAdmin->plxRecord_coms->f('type') ?>">
					<td><input type="checkbox" name="idCom[]" value="<?= $id ?>" /></td>
					<td class="datetime"><?= plxDate::formatDate($plxAdmin->plxRecord_coms->f('date')) ?></td>
<?php
				if($all) {
?>
					<td class="status"><?= empty($status) ? L_COMMENT_ONLINE : L_COMMENT_OFFLINE ?></td>
<?php
				}
?>
					<td class="wrap"><?= nl2br($plxAdmin->plxRecord_coms->f('content')) ?></td>
					<td class="author"><?php
					$author = "&#9919;";
					$mail = 'mail';
					if(!empty($mail)) {
					?>&#9919;<?php
					} else {
						echo $author;
					}
?></td>
					<td class="site"><?php
					$site = '&#9919;	';
					if(!empty($site)) {
					?>&#9919;<?php
					} else {
						echo '&nbsp;';
					}
?></td>
					<td class="action">
						<a href="#" title="&#9919;"><?= L_COMMENT_ANSWER ?></a>
						<a href="#" title="&#9919;"><?= L_COMMENT_EDIT ?></a>
						<a href="article.php?a=<?= $artId ?>" title="<?= L_COMMENT_ARTICLE_LINKED_TITLE ?>"><?= L_COMMENT_ARTICLE_LINKED ?></a>
					</td>
				</tr>
<?php
				}
			} else { # Pas de commentaires
?>
				<tr>
					<td colspan="5" class="center"><?= L_NO_COMMENT ?></td>
				</tr>
<?php
			}
			?>
			</tbody>
		</table>
	</div>

</form>

<p id="pagination">
<?php
	# Hook Plugins
	eval($plxAdmin->plxPlugins->callHook('AdminCommentsPagination'));
	# Affichage de la pagination
	if($coms) { # Si on a des articles (hors page)
		# Calcul des pages
		$last_page = ceil($nbComPagination/$plxAdmin->aConf['bypage_admin_coms']);
		$stop = $plxAdmin->page + 2;
		if($stop<5) $stop=5;
		if($stop>$last_page) $stop=$last_page;
		$start = $stop - 4;
		if($start<1) $start=1;
		# Génération des URLs
		$sel = '&amp;sel='.$_SESSION['selCom'].(!empty($_GET['a'])?'&amp;a='.$_GET['a']:'');
		$p_url = 'comments.php?page='.($plxAdmin->page-1).$sel;
		$n_url = 'comments.php?page='.($plxAdmin->page+1).$sel;
		$l_url = 'comments.php?page='.$last_page.$sel;
		$f_url = 'comments.php?page=1'.$sel;
		# Affichage des liens de pagination
		printf('<span class="p_page">'.L_PAGINATION.'</span>', '<input style="text-align:right;width:35px" onchange="window.location.href=\'comments.php?page=\'+this.value+\''.$sel.'\'" value="'.$plxAdmin->page.'" />', $last_page);
		$s = $plxAdmin->page>2 ? '<a href="'.$f_url.'" title="'.L_PAGINATION_FIRST_TITLE.'">&laquo;</a>' : '&laquo;';
		echo '<span class="p_first">'.$s.'</span>';
		$s = $plxAdmin->page>1 ? '<a href="'.$p_url.'" title="'.L_PAGINATION_PREVIOUS_TITLE.'">&lsaquo;</a>' : '&lsaquo;';
		echo '<span class="p_prev">'.$s.'</span>';
		for($i=$start;$i<=$stop;$i++) {
			$s = $i==$plxAdmin->page ? $i : '<a href="'.('comments.php?page='.$i.$sel).'" title="'.$i.'">'.$i.'</a>';
			echo '<span class="p_current">'.$s.'</span>';
		}
		$s = $plxAdmin->page<$last_page ? '<a href="'.$n_url.'" title="'.L_PAGINATION_NEXT_TITLE.'">&rsaquo;</a>' : '&rsaquo;';
		echo '<span class="p_next">'.$s.'</span>';
		$s = $plxAdmin->page<($last_page-1) ? '<a href="'.$l_url.'" title="'.L_PAGINATION_LAST_TITLE.'">&raquo;</a>' : '&raquo;';
		echo '<span class="p_last">'.$s.'</span>';
	}
?>
</p>

<?php if(!empty($plxAdmin->aConf['clef'])) : ?>

<ul class="unstyled-list">
	<li><?php echo L_COMMENTS_PRIVATE_FEEDS ?> :</li>
	<?php $urlp_hl = $plxAdmin->racine.'feed.php?admin'.$plxAdmin->aConf['clef'].'/commentaires/hors-ligne'; ?>
	<li><a href="<?php echo $urlp_hl ?>" title="<?php echo L_COMMENT_OFFLINE_FEEDS_TITLE ?>"><?php echo L_COMMENT_OFFLINE_FEEDS ?></a></li>
	<?php $urlp_el = $plxAdmin->racine.'feed.php?admin'.$plxAdmin->aConf['clef'].'/commentaires/en-ligne'; ?>
	<li><a href="<?php echo $urlp_el ?>" title="<?php echo L_COMMENT_ONLINE_FEEDS_TITLE ?>"><?php echo L_COMMENT_ONLINE_FEEDS ?></a></li>
</ul>

<?php endif; ?>

<?php
# Hook Plugins
eval($plxAdmin->plxPlugins->callHook('AdminCommentsFoot'));


# Hook Plugins
eval($plxAdmin->plxPlugins->callHook('AdminCommentFoot'));
# On inclut le footer
include PLX_ROOT.'core/admin/foot.php';
exit;
?>