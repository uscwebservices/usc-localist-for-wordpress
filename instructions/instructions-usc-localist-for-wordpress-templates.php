<?php
/**
 * Settings Instructions: Templates
 *
 * @package	Usc<em>Localist</em>For_Wordpress
 * @subpackage Usc<em>Localist</em>For_Wordpress/instructions
 * @author	 USC Web Services <webhelp@usc.edu>
 */
?>
<h3 id="templates">Templates</h3>

<p>You can add a custom template in the <a href="edit.php?post_type=event-template">Events Templates</a> section.</p>

<p><a class="button button-primary" href="edit.php?post_type=event-template">Edit Custom Events Templates</a></p>

<p>Add the type of template you want to use with the <code>slug</code> from the Custom Template Type in the shortcode as <code>template_multiple</code> for events lists or <code>template_single</code> for event details page.</p>

<p>Sample:</p>

<pre>
[localist-calendar template_multiple="multiple-template-slug" template_single="single-template-slug"]
</pre>

<p>See the Template sections below for specific output formats:</p>

<ul>
	<li><a href="?page=usc-localist-for-wordpress-admin&tab=data">Data</a></li>
	<li><a href="?page=usc-localist-for-wordpress-admin&tab=links">Links</a></li>
	<li><a href="?page=usc-localist-for-wordpress-admin&tab=dates">Dates</a></li>
	<li><a href="?page=usc-localist-for-wordpress-admin&tab=photos">Photos</a></li>
</ul>

<h4 id="templates-samples">Template Samples</h4>

<p>Below are the default templates used for the plugin.</p>

<h5 id="templates-samples-multiple">Multiple Events Template</h5>

<pre>
<?php
echo esc_html__(
	'
	<html>
	<article class="event-item">
		<h1 class="event-title"><a href="" data-link="detail" data-field="title"></a></h1>
		<div class="event-dates" data-date-type="datetime-start-end" data-format-date="l, F jS, Y" data-separator="<br>"></div>
		<address class="event-location" data-field="location_name"></address>
		<img src="" data-photo="photo_url" data-format="medium" />
	</article>
	</html>
	'
);

?>
</pre>

<h5 id="templates-samples-single">Single Event Template</h5>

<pre>
<?php
echo esc_html__(
	'
	<html>
	<article class="event single">
		<h1 class="event-title" data-field="title"></h1>
		<img class="event-image" src="" data-photo="photo_url" data-format="big" />
		<div class="event-dates" data-date-type="datetime-start-end" data-format-date="l, F jS, Y" data-separator="<br>"></div>
		<div class="event-location">
			<a class="event-map" href="" data-link="map" data-field="location_name"></a>
			<span class="event-location" data-field="geo.city"></span>
		</div>
		<div data-field="description"></div>
	</article>
	</html>
	'
);
?>
</pre>
