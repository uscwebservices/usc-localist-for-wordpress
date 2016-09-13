<?php
/**
 * Settings Instructions: Data
 *
 * @package	Usc<em>Localist</em>For_Wordpress
 * @subpackage Usc<em>Localist</em>For_Wordpress/instructions
 * @author	 USC Web Services <webhelp@usc.edu>
 */

?>

<h3 id="templates-data-fields">Template: Data Fields</h3>

<p>To use the data from the API, you can add the data attribute <code>data-field</code> to any HTML element and use the mapped dot syntax path to the data.  The <code>data-field</code> will start at the individual <code>event</code> level and fill the content area of the selected tag.</p>

<table class="widefat">
	<thead>
		<td><strong>Parameter</strong></td>
		<td><strong>Type</strong></td>
		<td><strong>Options</strong></td>
		<td><strong>Description</strong></td>
	</thead>
	<tbody>
		<tr>
			<td><code>data-field</code></td>
			<td>string</td>
			<td></td>
			<td>The dot syntax mapping to the API for the desired value.</td>
		</tr>
	</tbody>
</table>

<h5 id="templates-data-field-sample-data">Sample Data</h5>

<pre>
event: {
title: "Reshaping Tradition: Contemporary Ceramics from East Asia",
geo: {
	street: "46 North Los Robles Avenue",
	city: "Pasadena",
	state: "CA",
	country: "US",
	zip: "91101"
}
}
</pre>

<h5 id="templates-data-field-template-code">Template Code</h5>

	<pre>&lt;address data-field=&quot;geo.city&quot;&gt;&lt;/address&gt;</pre>

<h5 id="templates-data-field-template-output">Template Output</h5>

	<pre>&lt;address data-field=&quot;geo.city&quot;&gt;Pasadena&lt;/address&gt;</pre>
