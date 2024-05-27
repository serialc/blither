<div class="container">
    <div class="row align-items-md-stretch">
        <div id="main_content" class="col-md-6">
            <div class="h-100 p-4 text-bg-dark rounded-3">

<?php
if (file_exists(SITE_PATH . 'introduction.md')) {
    echo $parsedown->text(file_get_contents(SITE_PATH . 'introduction.md'));
} else {
    echo '<h4>Customize ' . SITE_PATH . 'introduction.md</h4>';
}
?>
            </div>
        </div>

        <div id='event_date' class="col-md-6">
            <div class="h-100 p-4 bg-body-tertiary border rounded-3">
                <h2>Next event</h2>
                <p class="fs-4 text-warning">

<?php
echo $next_event['pretty_date'] . '<br>' . $next_event['stime'] . ' - ' . $next_event['etime'] . '</p>';
?>

                <h3>Location</h3>

<?php
// show the location based on $conf
// - rizemeet_location key must exists in $conf
// - rizemeet_location must not be empty
// - location file must exist
if ( isset($conf['rizemeet_location']) and
        strcmp($conf['rizemeet_location'], '') !== 0 and
        file_exists(EVENT_ROOMS_FOLDER . $conf['rizemeet_location']) ) {

    $room = json_decode(file_get_contents(EVENT_ROOMS_FOLDER . $conf['rizemeet_location']), true);
    echo $parsedown->text($room['description']);
} else {
    echo 'No location set yet';
}
?>

                <h2 class="mt-5">Next meeting topic</h2>
                <div>

<?php
// show the suggested next meeting topics
if ( $conf['rizemeet_meeting_topic'] ) {
    echo $parsedown->text($conf['rizemeet_meeting_topic']);
} else {
    echo 'Free form discussion';
}
?>
                </div>
            </div>
        </div>
    </div>
</div>
