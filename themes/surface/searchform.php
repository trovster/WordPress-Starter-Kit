<form role="search" method="get" class="search" action="<?php echo home_url( '/' ); ?>" >
	<fieldset>
		<legend>&nbsp;</legend>
		<div class="text search required">
			<label for="search-search">Search</label>
			<input type="search" value="<?php echo get_search_query(true); ?>" name="s" id="search-search" required="required" placeholder="Search…" />
		</div>
		<div class="submit button search">
			<input type="submit" value="Search" />
		</div>
	</fieldset>
</form>