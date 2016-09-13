<?php
/**
 * Settings Instructions: Dates
 *
 * @package	Usc<em>Localist</em>For_Wordpress
 * @subpackage Usc<em>Localist</em>For_Wordpress/instructions
 * @author	 USC Web Services <webhelp@usc.edu>
 */

?>

<h3 id="dates-range">Dates Range</h3>

<p>This plugin uses the WordPress <a href="/wp-admin/customize.php">Customizer</a> to set global calendar settings for the Dates Range, which can be overriden in the shortcode.</p>

<p>In the Customizer under the section <strong>Localist Calendar Options</strong>, choose an option from the <strong>Dates Range</strong> radio buttons.</p>

<table class="widefat">
	<thead>
		<td><strong>Option</strong></td>
		<td><strong>Output</strong></td>
	</thead>
	<tr>
		<td><code>no</code></td>
		<td>1/1/2016</td>
	</tr>
	<tr>
		<td><code>yes</code></td>
		<td>1/1/2016 - 3/1/2016</td>
	</tr>
</table>

<p><strong>Note:</strong> Date ranges will display on multiple event lists only.  Single event details iterate through the dates.  If set to <code>no</code>, the multiple display will show the single date of the current instance returned.</p>

<h3 id="templates-date-options">Template: Date Options</h3>

<p>To set a date or time setting from the API data, you can add the data attribute <code>data-date-type</code> to any HTML tag.  This will automatically map the data to the <code>first_date</code>, <code>last_date</code>, or event instance(s) depending on the options chosen.</p>

<table class="widefat">
	<thead>
		<td><strong>Parameter</strong></td>
		<td><strong>Type</strong></td>
		<td><strong>Options</strong></td>
		<td><strong>Default</strong></td>
		<td><strong>Description</strong></td>
	</thead>
	<tbody>
		<tr>
			<td rowspan="3"><code>data-date-type</code></td>
			<td rowspan="3">string</td>
			<td>
				<code>date</code>
			</td>
			<td></td>
			<td>Returns the date of the selection. Use with <code>data-format-date</code>.</td>
		</tr>
		<tr>
			<td>
				<code>time</code>
			</td>
			<td></td>
			<td>Returns the time of the selection. Use with <code>data-format-time</code>.</td>
		</tr>
		<tr>
			<td>
				<code>datetime-start-end</code>
			</td>
			<td></td>
			<td>Returns the date and time of the selection. Use with <code>data-format-date</code> and <code>data-format-time</code>.</td>
		</tr>
		<tr>
			<td rowspan="2"><code>data-date-instance</code></td>
			<td rowspan="2">string</td>
			<td><code>start</code></td>
			<td><code>start</code></td>
			<td>Use the <code>start</code> of the event instance for the date/time output.</td>
		</tr>
		<tr>
			<td><code>end</code></td>
			<td></td>
			<td>Use the <code>end</code> of the event instance for the date/time output.</td>
		</tr>
		<tr>
			<td><code>data-format-time</code></td>
			<td>string</td>
			<td></td>
			<td><code>g:i a</code></td>
			<td>Set the time output format using <a href="http://php.net/manual/function.date.php">PHP Date</a>.</td>
		</tr>
		<tr>
			<td><code>data-format-date</code></td>
			<td>string</td>
			<td></td>
			<td><code>m/d/Y</code></td>
			<td>Set the date output format using <a href="http://php.net/manual/function.date.php">PHP Date</a>.</td>
		</tr>
		<tr>
			<td><code>data-separator-range</code></td>
			<td>string</td>
			<td></td>
			<td><code> - </code></td>
			<td>Set the separator for multiple events using the <a href="#customizer-event-dates-range">range option</a>.</td>
		</tr>
		<tr>
			<td><code>data-separator-date-time</code></td>
			<td>string</td>
			<td></td>
			<td><code> at </code></td>
			<td>Set the separator for instances using <code>data-date-instance="datetime-start-end"</code> between the date and time output for event instances with only a start time.</td>
		</tr>
		<tr>
			<td><code>data-separator-date-time-multiple</code></td>
			<td>string</td>
			<td></td>
			<td><code> from </code></td>
			<td>Set the separator for instances using <code>data-date-instance="datetime-start-end"</code> between the date and time output for event instances with a start and end time.</td>
		</tr>
		<tr>
			<td><code>data-separator-time</code></td>
			<td>string</td>
			<td></td>
			<td><code> to </code></td>
			<td>Set the separator for instances using <code>data-date-instance="datetime-start-end"</code> between the start time and end time output.</td>
		</tr>
		<tr>
			<td><code>data-separator</code></td>
			<td>string</td>
			<td></td>
			<td></td>
			<td>Used between date/time <code>&lt;time&gt;</code>instances for single events.</td>
		</tr>
	</tbody>
</table>

<h5 id="templates-dates-sample-data">Sample Data</h5>

<pre>
event: {
first_date: "2020-02-02",
last_date: "2020-04-05",
event_instances: [
	{
		event_instance: {
			start: "2020-03-08T10:45:00-08:00",
		}
	},
	{
		event_instance: {
			start: "2020-03-22T10:45:00-07:00",
		}
	},
	{
		event_instance: {
			start: "2020-03-29T10:45:00-07:00",
			end: "2020-03-29T12:45:00-07:00"
		}
	},
	{
		event_instance: {
			start: "2020-04-05T10:45:00-07:00",
			end: "2020-04-05T12:45:00-07:00"
		}
	}
]
}
</pre>

<h5 id="templates-dates-template-code">Template Code</h5>
<pre>
<?php
echo esc_html__(
	'
	<!-- 1 -->
	<div class="event-dates" data-date-type="date"></div>

	<!-- 2 -->
	<div class="event-dates" data-date-type="time" date-instance="start"></div>

	<!-- 3 -->
	<div class="event-dates" data-date-type="time" date-instance="end">

	<!-- 4 -->
	<div class="event-dates" data-date-type="datetime-start-end" data-format-date="l, F jS, Y" data-separator="<br>"></div>

	<!-- 5 -->
	<div class="event-dates" data-date-type="datetime-start-end" data-format-date="l, F jS, Y" data-separator="<br>" data-separator-date-time=" beginning at " data-separator-date-time-multiple=" starting at " data-separator-time=" until "></div>
	'
);
?>
</pre>

<h5 id="templates-dates-template-output">Template Output</h5>

<pre>
<?php
echo esc_html__(
	'
	<!-- 1 -->
	<div class="event-dates" data-date-type="date">
	<time class="event-date-start" datetime="2020-03-08T10:45:00-08:00">03/08/2020</time>
	<time class="event-date-start" datetime="2020-03-22T10:45:00-07:00">03/22/2020</time>
	<time class="event-date-start" datetime="2020-03-29T10:45:00-07:00">03/29/2020</time>
	<time class="event-date-start" datetime="2020-04-05T10:45:00-07:00">04/05/2020</time>
	</div>

	<!-- 2 -->
	<div class="event-dates" data-date-type="time" date-instance="start">
	<time class="event-time-start">11:45 am</time>
	<time class="event-time-start">10:45 am</time>
	<time class="event-time-start">10:45 am</time>
	<time class="event-time-start">10:45 am</time>
	</div>

	<!-- 3 -->
	<div class="event-dates" data-date-type="time" date-instance="end">
	<time class="event-time-start">11:45 am</time>
	<time class="event-time-start">10:45 am</time>
	<time class="event-time-start">10:45 am</time>
	<time class="event-time-start">10:45 am</time>
	</div>

	<!-- 4 -->
	<div class="event-dates" data-date-type="datetime-start-end" data-format-date="l, F jS, Y" data-separator="<br>">
	<time class="event-datetime-start-end" datetime="2020-03-08T10:45:00-08:00">
		<span class="event-date-start">Sunday, March 8th, 2020</span>
		<span class="event-separator-datetime"> at </span>
		<span class="event-time-start">11:45 am</span>
	</time>
	<br>
	<time class="event-datetime-start-end" datetime="2020-03-22T10:45:00-07:00">
		<span class="event-date-start">Sunday, March 22nd, 2020</span>
		<span class="event-separator-datetime"> at </span>
		<span class="event-time-start">10:45 am</span>
	</time>
	<br>
	<time class="event-datetime-start-end" datetime="2020-03-29T10:45:00-07:00">
		<span class="event-date-start">Sunday, March 29th, 2020</span>
		<span class="event-separator-datetime"> from </span>
		<span class="event-time-start">10:45 am</span>
		<span class="event-separator-time"> to </span>
		<span class="event-time-end">12:45 pm</span>
	</time>
	<br>
	<time class="event-datetime-start-end" datetime="2020-04-05T10:45:00-07:00">
		<span class="event-date-start">Sunday, April 5th, 2020</span>
		<span class="event-separator-datetime"> from </span>
		<span class="event-time-start">10:45 am</span>
		<span class="event-separator-time"> to </span>
		<span class="event-time-end">12:45 pm</span>
	</time>
	</div>

	<!-- 5 -->
	<div class="event-dates" data-date-type="datetime-start-end" data-format-date="l, F jS, Y" data-separator="<br>" data-separator-date-time=" beginning at " data-separator-date-time-multiple=" starting at " data-separator-time=" until ">
	<time class="event-datetime-start-end" datetime="2020-03-08T10:45:00-08:00">
		<span class="event-date-start">Sunday, March 8th, 2020</span>
		<span class="event-separator-datetime"> beginning at </span>
		<span class="event-time-start">11:45 am</span>
	</time>
	<br>
	<time class="event-datetime-start-end" datetime="2020-03-22T10:45:00-07:00">
		<span class="event-date-start">Sunday, March 22nd, 2020</span>
		<span class="event-separator-datetime"> beginning at </span>
		<span class="event-time-start">10:45 am</span>
	</time>
	<br>
	<time class="event-datetime-start-end" datetime="2020-03-29T10:45:00-07:00">
		<span class="event-date-start">Sunday, March 29th, 2020</span>
		<span class="event-separator-datetime"> starting at </span>
		<span class="event-time-start">10:45 am</span>
		<span class="event-separator-time"> until </span>
		<span class="event-time-end">12:45 pm</span>
	</time>
	<br>
	<time class="event-datetime-start-end" datetime="2020-04-05T10:45:00-07:00">
		<span class="event-date-start">Sunday, April 5th, 2020</span>
		<span class="event-separator-datetime"> starting at </span>
		<span class="event-time-start">10:45 am</span>
		<span class="event-separator-time"> until </span>
		<span class="event-time-end">12:45 pm</span>
	</time>
	</div>
	')
?>
</pre>
