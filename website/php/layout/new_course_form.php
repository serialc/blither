<form action="" method="post">
    <h1>New Course</h1>
        <div class="row">
        <div class="col-md-6 mb-3">
            <div class="input-group mb-3">
                <label for="cname" class="input-group-text">Name</label>
                <input type="text" class="form-control" autofocus="autofocus" id="cname" name="cname" maxlength="128" aria-describedby="cname" value="
<?php 
if ( isset($_POST['cname']) ) {
    echo $_POST['cname'];
}
?>
">

            </div>

            <div class="input-group">
                <label for="ldesc" class="input-group-text">Location</label>
                <input type="text" class="form-control" autofocus="autofocus" id="ldesc" name="ldesc" maxlength="128" aria-describedby="ldesc" value="
<?php 
if ( isset($_POST['ldesc']) ) {
    echo $_POST['ldesc'];
}
?>
">

            </div>
            <div class="form-text mb-3">
                Provide city name or lat/lng.
            </div>

            <div class="input-group mb-3">
                <label for="ccode" class="input-group-text">Country</label>
                <select class="form-select" id="ccode" name="ccode">
                <option 
<?php 
if ( isset($_POST['ccode']) ) {
    echo (strcmp($_POST['ccode'], "") === 0 ? "selected" : "" );
}
?>
></option>

<?php

$cc = explode("\n", file_get_contents('rsrcs/iso3166-1_alpha-2.csv'));
$header = true;
foreach ($cc as $concode) {
    if ($header) {
        $header = false;
        continue;
    }

    $concode = explode(",", $concode);
    if ( count($concode) !== 2 ) {
        continue;
    }

    // select the country if previously selected
    if (isset($_POST['ccode']) and strcmp($_POST['ccode'], $concode[1]) === 0) {
        print('<option value="' . $concode[1] . '" selected>' . $concode[0] . '</option>');
    } else {
        print('<option value="' . $concode[1] . '">' . $concode[0] . '</option>');
    }
}

?>
                </select>
            </div>

            <div class="input-group mb-3">
                <span for="cdescription" class="input-group-text">Describe</span>
                <textarea id="cdescription" name="cdescription" class="form-control" aria-label="With textarea">
<?php
if ( isset($_POST['cdescription']) ) {
    echo $_POST['cdescription'];
}
?>
</textarea>
            </div>


            <div class="input-group mb-3">
                <label for="basketsnum" class="input-group-text">Number of baskets</label>
                <input type="number" class="form-control" autofocus="autofocus" id="basketsnum" name="basketsnum" maxlength="128" aria-describedby="basketsnum" value="
<?php 
if ( isset($_POST['basketsnum']) ) {
    echo $_POST['basketsnum'];
}
?>
">
            </div>

            <button class="btn btn-success" type="submit">Submit</button>

        </div>
</div>
</form>
