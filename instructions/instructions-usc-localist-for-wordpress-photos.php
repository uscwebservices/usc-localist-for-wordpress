<?php
/**
 * Settings Instructions: Photos
 *
 * @package	USC Localist For_Wordpress
 * @subpackage USC Localist For_Wordpress/instructions
 * @author	 USC Web Services <webhelp@usc.edu>
 */

?>

<h3 id="templates-photos">Template: Photos</h3>

<p>The data node <code>photo_url</code> will replace the <code>src</code> with the url of the photo.</p>

<p>To set a photo url from the API data, you can add the data attribute <code>data-photo</code> to an <code>img</code> tag and use the mapped dot syntax path to the data.  Use this in conjunction with the <code>data-format</code> to change the image size returned.</p>

<table class="widefat">
	<thead>
		<td><strong>Parameter</strong></td>
		<td><strong>Type</strong></td>
		<td><strong>Options</strong></td>
		<td><strong>Description</strong></td>
	</thead>
	<tbody>
		<tr>
			<td><code>data-photo</code></td>
			<td>string</td>
			<td><code>photo_url</code></td>
			<td>The dot syntax mapping to the photo attribute.</td>
		</tr>
		<tr>
			<td><code>data-format</code></td>
			<td>string</td>
			<td>See list below</td>
			<td>Choose from the available <a href="#templates-photos-photo-format">photo format</a> sizes to set the <code>src</code></td>
		</tr>
	</tbody>
</table>

<h3 id="templates-photos-photo-format">Photo Format</h3>

<p>Using <code>data-format</code> with <code>data-photo</code>, you can set the size of the images to be returned from the following list. <code>huge</code> is the default size returned from the API.</p>

<table class="widefat">
	<thead>
		<td><strong>Value</strong></td>
		<td><strong>Returned Size (px)</strong></td>
	</thead>
	<tr>
		<td><code>tiny</code></td>
		<td>20×20</td>
	</tr>
	<tr>
		<td><code>small</code></td>
		<td>50x50</td>
	</tr>
	<tr>
		<td><code>medium</code></td>
		<td>80×80</td>
	</tr>
	<tr>
		<td><code>big</code></td>
		<td>200×150</td>
	</tr>
	<tr>
		<td><code>big_300</code></td>
		<td>300×225</td>
	</tr>
	<tr>
		<td><code>huge</code></td>
		<td>Uploaded image size</td>
	</tr>
</table>
