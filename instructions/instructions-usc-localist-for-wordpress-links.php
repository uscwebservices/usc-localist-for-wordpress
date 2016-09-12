<?php
/**
 * Settings Instructions: Links
 *
 * @package	Usc<em>Localist</em>For_Wordpress
 * @subpackage Usc<em>Localist</em>For_Wordpress/instructions
 * @author	 USC Web Services <webhelp@usc.edu>
 */
?>

<h3 id="templates-links-event-details-page">Event Details Page</h3>

<p>This plugin uses the WordPress <a href="/wp-admin/customize.php">Customizer</a> to set global calendar settings for the Events Details Page, which can be overriden in the shortcode.</p>

<h4 id="templates-links-event-details-page-separate">Separate Event Details Page</h4>

<p>In the Customizer under the section <strong>Localist Calendar Options</strong>, choose a page from the <strong>Event Details Page</strong> dropdown where the events link to an event details page.</p>

<p>On the page that was selected, you must use the shortcode: </p>

	<code>[localist-calendar get="event"]</code>

<h4 id="templates-links-event-details-page-inline">Inline Event Details Page</h4>

<p>To display events and the event detail with one shortcode, you will leave the <strong>Event Details Page</strong> drowpdown blank and add the attribute <code>is_events_page="true"</code> to the shortcode.</p>

	<p><code>[localist-calendar get="events" is_events_page="true"]</code></p>

<h4 id="templates-links-event-details-page-localist">Localist Event Details Page</h4>

<p>If you leave the <strong>Event Details Page</strong> drop down blank and omit <code>is_events_page="true"</code> from the shortcode, the event links will go to the event detail page on the <a href="https://calendar.usc.edu">USC Calendar</a>.</p>

<h3 id="template-links-types">Template Links</h3>

<p>To set a link from the API data, you can add the data attribute <code>data-link</code> to an <code>a</code> tag and use the mapped dot syntax path to the data which will set the <code>src</code> attribute of the tag.</p>

<p>You can use this in conjunction with the <code>data-field</code> to set the text of the link.</p>

<table class="widefat">
	<thead>
		<td><strong>Parameter</strong></td>
		<td><strong>Type</strong></td>
		<td><strong>Options</strong></td>
		<td><strong>Description</strong></td>
	</thead>
	<tbody>
		<tr>
			<td rowspan="3"><code>data-link</code></td>
			<td rowspan="3">string</td>
			<td>string</td>
			<td>The dot syntax mapping to the url. (<a href="#note-link">see note<sup>1</sup> below</a>)</td>
		</tr>
		<tr>
			<td><code>map</code></td>
			<td>Automatically sets link to <code>location_name</code>. (<a href="#note-link-map">see note<sup>2</sup> below</a>)</td>
		</tr>
		<tr>
			<td><code>detail</code></td>
			<td>Automatically sets link to <a href="#templates-links-event-details-page">event detail</a> page.</td>
		</tr>
	</tbody>
</table>

<p id="note-link"><strong><sup>1</sup>Note:</strong> If linking to a <code>string</code> mapped node and there is no link returned, the anchor <code>&lt;a&gt;</code> tag will be changed to a <code>&lt;span&gt;</code> tag and the <code>href</code> attribute removed.</p>

<p id="note-link-map"><strong><sup>2</sup>Note:</strong> The <code>data-link="map"</code> function will set the link to the three letter code at the end of the location name. Leavey Library (LVL) will link to the UPC map for <em>LVL</em>.  Any three letter codes for HSC will link to the HSC map.  If there is no three letter code, the link will go to the UPC maps with a query parameter of the <code>location_name</code>.  The link will fall back to the following nodes for information:</p>

<ol>
	<li>USC Maps: 3 letter code in (parenthesis) from <code>location_name</code></li>
	<li>Google Maps: <code>geo.street</code> + <code>geo.city</code> + <code>geo.state</code></li>
	<li>Google Maps: <code>geo.latitude</code> + <code>geo.longitude</code></li>
	<li>USC Maps (UPC with query): <code>location_name</code></li>
	<li>Google Maps: <code>address</code></li>
</ol>

<h5 id="templates-links-sample-data">Sample Data</h5>

<pre>
event: {
title: "USC Tommy Trojan",
localist_url: "http://calendar.usc.edu/event/usc_tommy_trojan",
location_name: "Student Union (STU)",
ticket_url: "http://eventbrite.com/",
venue_url: ""
}
</pre>

<h5 id="templates-links-template-code">Template Code</h5>

<pre>
<?php
echo esc_html__(
	'
	<a href="" data-link="localist_url" data-field="title"></a>
	<a class="event-map" href="" data-link="map" data-field="location_name"></a>
	<a class="ticket" href="" data-link="ticket_url">Ticket</a>
	<a class="ticket" href="" data-link="venue_url" data-field="location_name"></a>
	'
);

?>
</pre>

<h5 id="templates-links-template-output">Template Output</h5>

<pre>
<?php
echo esc_html__(
	'
	<a href="http://calendar.usc.edu/event/usc_tommy_trojan" data-link="localist_url" data-field="title">USC Tommy Trojan</a>
	<a class="event-map" href="http://web-app.usc.edu/maps/?b=STU" data-link="map" data-field="location_name">Student Union (STU)</a>
	<a class="ticket" href="http://eventbrite.com/" data-link="ticket_url">Ticket</a>
	<span class="ticket" data-link="venue_url" data-field="location_name">Student Union (STU)</span>
	'
);
?>
</pre>
