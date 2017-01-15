<div class="dashBox" style="margin-top:20px;">
	<h3>Categories</h3>
	<? foreach($categories as $category): ?>
		<div>
			<a href="/community/<?=$category['CAT_ID']?>"><?=$category['cat_name']?></a>
			<p><?=$category['cat_desc']?></p>
			<span><?=$category['cat_num_discussions']?></span>
		</div>
	<? endforeach ?>
</div>