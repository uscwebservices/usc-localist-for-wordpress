<?php 

$html = '
<!-- 1 -->
<div class="event-dates" data-date-type="date">
	<time class="event-date-start" datetime="2020-03-08T10:45:00-08:00">03/08/2020</time>
	<time class="event-date-start" datetime="2020-03-22T10:45:00-07:00">03/22/2020</time>
	<time class="event-date-start" datetime="2020-03-29T10:45:00-07:00">03/29/2020</time>
	<time class="event-date-start" datetime="2020-04-05T10:45:00-07:00">04/05/2020</time>
</div>

<!-- 3 -->
<div class="event-dates" data-date-type="time" date-instance="start">
	<time class="event-time-start">11:45 am</time>
	<time class="event-time-start">10:45 am</time>
	<time class="event-time-start">10:45 am</time>
	<time class="event-time-start">10:45 am</time>
</div>

<!-- 4 -->
<div class="event-dates" data-date-type="time" date-instance="end">
	<time class="event-time-start">11:45 am</time>
	<time class="event-time-start">10:45 am</time>
	<time class="event-time-start">10:45 am</time>
	<time class="event-time-start">10:45 am</time>
</div>

<!-- 5 -->
<div class="event-dates" data-date-type="datetime-start-end" data-format-date="l, F jS, Y" data-separator="<br>">
	<time class="event-datetime-start-end" datetime="2020-03-08T10:45:00-08:00">
		<span class="event-date-start">Sunday, March 8th, 2020</span>
		<span class="event-separator-datetime"> at </span>
		<span class="event-time-start">11:45 am</span>
		<span class="event-sepatrator-time"> to </span>
		<span class="event-time-end">1:45 pm</span>
	</time>
	<br>
	<time class="event-datetime-start-end" datetime="2020-03-22T10:45:00-07:00">
		<span class="event-date-start">Sunday, March 22nd, 2020</span>
		<span class="event-separator-datetime"> at </span>
		<span class="event-time-start">10:45 am</span>
		<span class="event-sepatrator-time"> to </span>
		<span class="event-time-end">12:45 pm</span>
	</time>
	<br>
	<time class="event-datetime-start-end" datetime="2020-03-29T10:45:00-07:00">
		<span class="event-date-start">Sunday, March 29th, 2020</span>
		<span class="event-separator-datetime"> at </span>
		<span class="event-time-start">10:45 am</span>
		<span class="event-sepatrator-time"> to </span>
		<span class="event-time-end">12:45 pm</span>
	</time>
	<br>
	<time class="event-datetime-start-end" datetime="2020-04-05T10:45:00-07:00">
		<span class="event-date-start">Sunday, April 5th, 2020</span>
		<span class="event-separator-datetime"> at </span>
		<span class="event-time-start">10:45 am</span>
		<span class="event-sepatrator-time"> to </span>
		<span class="event-time-end">12:45 pm</span>
	</time>
</div>

<!-- 6 -->
<div class="event-dates" data-date-type="datetime-start-end" data-format-date="l, F jS, Y" data-separator="<br>" data-sepatrator-date-time=" from " data-sepatrator-time=" - ">
	<time class="event-datetime-start-end" datetime="2020-03-08T10:45:00-08:00">
		<span class="event-date-start">Sunday, March 8th, 2020</span>
		<span class="event-separator-datetime"> at </span>
		<span class="event-time-start">11:45 am</span>
		<span class="event-sepatrator-time"> to </span>
		<span class="event-time-end">1:45 pm</span>
	</time>
	<br>
	<time class="event-datetime-start-end" datetime="2020-03-22T10:45:00-07:00">
		<span class="event-date-start">Sunday, March 22nd, 2020</span>
		<span class="event-separator-datetime"> at </span>
		<span class="event-time-start">10:45 am</span>
		<span class="event-sepatrator-time"> to </span>
		<span class="event-time-end">12:45 pm</span>
	</time>
	<br>
	<time class="event-datetime-start-end" datetime="2020-03-29T10:45:00-07:00">
		<span class="event-date-start">Sunday, March 29th, 2020</span>
		<span class="event-separator-datetime"> at </span>
		<span class="event-time-start">10:45 am</span>
		<span class="event-sepatrator-time"> to </span>
		<span class="event-time-end">12:45 pm</span>
	</time>
	<br>
	<time class="event-datetime-start-end" datetime="2020-04-05T10:45:00-07:00">
		<span class="event-date-start">Sunday, April 5th, 2020</span>
		<span class="event-separator-datetime"> at </span>
		<span class="event-time-start">10:45 am</span>
		<span class="event-sepatrator-time"> to </span>
		<span class="event-time-end">12:45 pm</span>
	</time>
</div>
'; // end $html

echo htmlentities( $html );

?>