<?php
session_start();

if (!isset($_SESSION['CREATED'])) {
    $_SESSION['CREATED'] = time();
} else if (time() - $_SESSION['CREATED'] > 1800) { // session started more than 30 minutes ago
    session_unset();
    session_destroy();
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $loggedIn = true;
    $_SESSION['username'] = $username;
}

if (isset($_POST['loadNumber']) && isset($_POST['startTime'])) {
    $_SESSION['LOAD_NUMBER'] = 'C' . $_POST['loadNumber'];
    $_SESSION['START_TIME'] = $_POST['startTime'];
    $_SESSION['STATUS'] = 'PRE-TRIP';
}

if (isset($_POST['status'])) {
    if ($_POST['status'] == 'READY') {
        $_SESSION['STATUS'] = 'READY';
    } else if ($_POST['status'] == 'LEFT_DANONE') {
        $_SESSION['STATUS'] = 'LEFT_DANONE';
    } else if ($_POST['status'] == 'ARRIVED_AT_DANONE') {
        $_SESSION['STATUS'] = 'ARRIVED_AT_DANONE';
    } else if ($_POST['status'] == 'LEFT_FACILITY') {
        $_SESSION['STATUS'] = 'LEFT_FACILITY';
    } else if ($_POST['status'] == 'ARRIVED_AT_FACILITY') {
        $_SESSION['STATUS'] = 'ARRIVED_AT_FACILITY';
    } else if ($_POST['status'] == 'WAITING') {
        //$_SESSION['STATUS'] = 'WAITING';
        echo '<pre>';
        print_r($_POST);
        echo '</pre>';
        exit();
    } else {
        die('Unhandled status change: ' . $_POST['status']);
    }
}

/*
 * ARRIVED_AT_DANONE possibilities: bobtail, empty, chep pallets, samples, refused load
 */
?>

<!doctype html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="A series of tools and applications relating to Northern Refrigerated.">
    <meta name="author" content="Ron Bodnar">
    <meta name="generator" content="whatisthis">
    <title>NRT / Danone</title>

    <link rel="canonical" href="#">

    <!-- Bootstrap core CSS -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="/assets/css/style-northern.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="/docs/5.0/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
    <link rel="icon" href="/docs/5.0/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="/docs/5.0/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
    <link rel="manifest" href="/docs/5.0/assets/img/favicons/manifest.json">
    <link rel="mask-icon" href="/docs/5.0/assets/img/favicons/safari-pinned-tab.svg" color="#7952b3">
    <link rel="icon" href="/docs/5.0/assets/img/favicons/favicon.ico">

    <meta name="theme-color" content="#7952b3">
</head>

<body id="body-pd">
    <!-- Header and sidebar -->
    <header class="header" id="header">
        <div class="header_toggle">
            <i class="bx bx-menu" id="header-toggle"></i>
        </div>
        <div class="header_img">
            <img src="/assets/img/favicon-2.ico" alt="" />
        </div>
    </header>
    <div class="l-navbar" id="nav-bar">
        <nav class="nav">
            <div>
                <a href="#" class="nav_logo">
                    <i class="bx bx-layer nav_logo-icon"></i>
                    <span class="nav_logo-name">NRT / Danone</span>
                </a>
                <div class="nav_list">
                    <a href="#" class="nav_link active">
                        <i class="bx bx-grid-alt nav_icon"></i>
                        <span class="nav_name">Dashboard</span>
                    </a>
                    <a href="#" class="nav_link">
                        <i class="bx bx-detail nav_icon"></i>
                        <span class="nav_name">Trips</span>
                    </a>
                </div>
            </div>
            <div class="pb-3 text-center">
                <?php if (isset($_SESSION['LOAD_NUMBER'])) {
                ?>
                    <span class="footer"><?php echo $_SESSION['LOAD_NUMBER']; ?></span><br />
                <?php }
                ?>
                <?php if (isset($_SESSION['username'])) {
                ?>
                    <span class="footer">Ronald Bodnar</span>
                <?php } else {
                ?>
                    <p>Not logged in</p>
                <?php }
                ?>
            </div>
        </nav>
    </div>

    <!-- Main content -->
    <div>
        <?php if (isset($_SESSION['username'])) { ?>
            <?php if (!isset($_SESSION['LOAD_NUMBER'])) { ?>
                <form class="form-signin" id="startOfDayForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" novalidate>
                    <label for="loadNumber" class="form-label sr-only">Load Number</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" id="inputGroupPrepend">C</span>
                        <input type="text" class="form-control" id="loadNumber" name="loadNumber" aria-describedby="inputGroupPrepend" required autofocus>
                        <div class="invalid-feedback">
                            Enter a valid load number (numbers only - no prefix)
                        </div>
                    </div>

                    <label for="startTime" class="form-label sr-only mt-3">Start Time</label>
                    <select class="form-control form-select form-select-sm" id="startTime" name="startTime" required>
                        <option selected disabled value="">Select start time...</option>
                        <option value="0200">2:00 AM</option>
                        <option value="0300">3:00 AM</option>
                        <option value="0400">4:00 AM</option>
                        <option disabled class="separator" value=""></option>
                        <option value="1400">2:00 PM</option>
                        <option value="1500">3:00 PM</option>
                        <option value="1600">4:00 PM</option>
                    </select>
                    <div class="invalid-feedback">
                        Please select your start time
                    </div>

                    <button type="submit" class="btn btn btn-secondary btn-block mt-3">Save</button>
                </form>
            <?php } else { ?>
                <?php if ($_SESSION['STATUS'] == 'READY') { ?>
                    <div class="text-center">
                        <p>Once you have received a load and are ready to go, tap the button below.</p>
                        <form id="leaving" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <fieldset>
                                <legend class="pt-0">Were you given a backhaul?</legend>
                                <div class="col d-flex text-end">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios1" value="option1">
                                        <label class="form-check-label" for="gridRadios1">
                                            Yes
                                        </label>
                                    </div>
                                </div>
                                <div class="col d-flex">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios2" value="option2" checked>
                                        <label class="form-check-label" for="gridRadios2">
                                            No
                                        </label>
                                    </div>
                                </div>
                            </fieldset>
                            <button type="button btn-secondary btn-lg p-3" id="leaving">Leaving Facility</button>
                        </form>
                    </div>
                <?php } else if ($_SESSION['STATUS'] == 'PRE-TRIP') { ?>
                    <div class="text-center">
                        <p>Once you have finished your pre-trip and are ready to begin, tap the button below.</p>
                        <code class="small m-3 p-3">This will send a notification to Danone that you are ready to begin working.</code><br /><br />
                        <form id="start" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <button type="button btn-secondary btn-lg p-3">Ready to go</button>
                        </form>
                    </div>
                <?php } else if ($_SESSION['STATUS'] == 'LEFT_FACILITY') { ?>
                    <div class="text-center">
                        <p>When you arrive at your destination, tap the button below to enter shipment information.</p>
                        <code class="small m-3 p-3">Remember to drive safe</code><br /><br />
                        <form id="arrived" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <button type="button btn-secondary btn-lg p-3">Arrived at Facility</button>
                        </form>
                    </div>
                <?php } else if ($_SESSION['STATUS'] == 'ARRIVED_AT_FACILITY') { ?>
                    <form class="form-arrival" id="arrivalForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" novalidate>
                        <input type="hidden" id="status" name="status" value="WAITING">
                        <label for="facilityName" class="form-label sr-only mt-3">Which facility are you at?</label>
                        <select class="form-select form-select-sm" id="facilityName" name="facilityName">
                            <option selected disabled value="">Select Facility...</option>
                            <option value="danone">Danone - COI</option>
                            <option value="accoi">Americold - COI</option>
                            <option value="acont">Americold - Ontario</option>
                            <option value="lineage">Lineage - Riverside</option>
                            <option value="northern">Northern Yard - Ontario</option>
                            <option value="flyers">Flyers</option>
                            <option value="scfuels">SC Fuels</option>
                        </select>
                        <div id="normalArrival" hidden>
                            <label for="orderNumber" class="form-label sr-only pt-3">Customer Order Number</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" id="orderNumber" name="orderNumber" placeholder="4503464432" autofocus>
                                <div class="invalid-feedback">
                                    Enter a valid customer order number
                                </div>
                            </div>

                            <label for="referenceNumber" class="form-label sr-only pt-3">Shipment Reference Number</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" id="referenceNumber" name="referenceNumber" placeholder="5013705532">
                                <div class="invalid-feedback">
                                    Enter a valid shipment reference number
                                </div>
                            </div>

                            <div class="row">
                                <div class="col pt-3">
                                    <label for="pallets" class="form-label sr-only">Pallets</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control" id="pallets" name="pallets" placeholder="24">
                                        <div class="invalid-feedback">
                                            Enter the number of pallets
                                        </div>
                                    </div>
                                </div>

                                <div class="col pt-3">
                                    <label for="weight" class="form-label sr-only">Weight</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control" id="weight" name="weight" placeholder="45,210">
                                        <div class="invalid-feedback">
                                            Enter the load weight
                                        </div>
                                    </div>
                                </div>

                                <div class="col pt-3">
                                    <label for="trailerNumber" class="form-label sr-only">Trailer #</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control" id="trailerNumber" name="trailerNumber" placeholder="50254">
                                        <div class="invalid-feedback">
                                            Enter the trailer number
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">Submit</button>
                        </div>
                        <div id="danoneArrival" hidden>
                            <label for="arrivalStatus" class="form-label sr-only mt-3">What did you arrive with?</label>
                            <select class="form-select form-select-sm" id="arrivalStatus" name="arrivalStatus">
                                <option selected disabled value="">Select...</option>
                                <option value="bobtail">Bobtail</option>
                                <option value="chepPallets">Chep Pallets</option>
                                <option value="emptyTrailer">Empty Trailer</option>
                                <option value="samples">Samples</option>
                                <option value="refusedLoad">Refused Load</option>
                            </select>
                        </div>

                        <div id="northernArrival" hidden>
                            <label for="reason" class="form-label sr-only mt-3">What is the purpose for stopping here?</label>
                            <select class="form-select form-select-sm" id="reason" name="reason">
                                <option selected disabled value="">Select...</option>
                                <option value="break">Break</option>
                                <option value="mechanics">Mechanics</option>
                                <option value="other">Other</option>
                            </select>

                            <div id="other" hidden>
                                <label for="otherReason" class="form-label sr-only pt-3">Specify Reason</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" id="otherReason" name="otherReason" placeholder="Swapping trucks">
                                    <div class="invalid-feedback">
                                        Provide a reason for stopping at the yard
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">Submit</button>
                        </div>

                        <div id="fuelArrival" hidden>
                            <label for="reason" class="form-label sr-only mt-3">Which fuel station are you at?</label>
                            <select class="form-select form-select-sm" id="reason" name="reason">
                                <option selected disabled value="">Select...</option>
                                <option value="scfuelscoi">SC Fuels - COI</option>
                                <option value="scfuelshh">SC Fuels - Hacienda Heights</option>
                                <option value="scfuelsfontana">SC Fuels - Fontana</option>
                                <option value="scfuelschino">SC Fuels - Chino</option>
                                <option value="scfuelsont">SC Fuels - Ontario</option>
                                <option value="flyersont">Flyers - Ontario</option>
                                <option value="flyersriverside">Flyers - Riverside</option>
                            </select>

                            <button type="submit" class="btn btn-primary mt-3">Submit</button>
                        </div>

                        <div id="bobtailArrival" hidden>
                            <button type="submit" class="btn btn-primary mt-3">Submit</button>
                        </div>

                        <div id="emptyArrival" hidden>
                            <label for="trailerNumberEmpty" class="form-label sr-only pt-3">Trailer #</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" id="trailerNumberEmpty" name="trailerNumberEmpty" placeholder="50254">
                                <div class="invalid-feedback">
                                    Enter a trailer number
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">Submit</button>
                        </div>

                        <div id="palletsOrSamples" hidden>
                            <div class="row">
                                <div class="col pt-3">
                                    <label for="palletsSamples" class="form-label sr-only">Pallets</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control" id="palletsSamples" name="palletsSamples" placeholder="406">
                                        <div class="invalid-feedback">
                                            Enter the number of pallets
                                        </div>
                                    </div>
                                </div>

                                <div class="col pt-3">
                                    <label for="trailerNumberSamples" class="form-label sr-only">Trailer #</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control" id="trailerNumberSamples" name="trailerNumberSamples" placeholder="50249">
                                        <div class="invalid-feedback">
                                            Enter the trailer number
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">Submit</button>
                        </div>

                        <div id="refusedLoadArrival" hidden>
                            <label for="orderNumberRefused" class="form-label sr-only pt-3">Customer Order Number</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" id="orderNumberRefused" name="orderNumberRefused" placeholder="4503464432" autofocus>
                                <div class="invalid-feedback">
                                    Enter a valid customer order number
                                </div>
                            </div>

                            <label for="referenceNumberRefused" class="form-label sr-only pt-3">Shipment Reference Number</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" id="referenceNumberRefused" name="referenceNumberRefused" placeholder="5013705532">
                                <div class="invalid-feedback">
                                    Enter a valid shipment reference number
                                </div>
                            </div>

                            <div class="row">
                                <div class="col pt-3">
                                    <label for="palletsRefused" class="form-label sr-only">Pallets</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control" id="palletsRefused" name="palletsRefused" placeholder="24">
                                        <div class="invalid-feedback">
                                            Enter the number of pallets
                                        </div>
                                    </div>
                                </div>

                                <div class="col pt-3">
                                    <label for="weightRefused" class="form-label sr-only">Weight</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control" id="weightRefused" name="weightRefused" placeholder="45,210">
                                        <div class="invalid-feedback">
                                            Enter the weight
                                        </div>
                                    </div>
                                </div>

                                <div class="col pt-3">
                                    <label for="trailerNumberRefused" class="form-label sr-only">Trailer #</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control" id="trailerNumberRefused" name="trailerNumberRefused" placeholder="50254">
                                        <div class="invalid-feedback">
                                            Enter the trailer number
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">Submit</button>
                        </div>
                    </form>
                <?php } else if ($_SESSION['STATUS'] == 'WAITING') { ?>
                    <div class="text-center">
                        <p>When you have finished all necessary yard moves and are either hooked up to a trailer or ready to leave, tap the button below.</p>
                        <form id="leaving" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            Were you given a backhaul?
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                                <label class="form-check-label" for="inlineRadio2">2</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio3" value="option3" disabled>
                                <label class="form-check-label" for="inlineRadio3">3 (disabled)</label>
                            </div>
                            <button type="button btn-secondary btn-lg p-3" id="leaving">Leaving Facility</button>
                        </form>
                    </div>
                <?php } else { ?>
                    <div class="text-center">
                        <p>Please wait... 2</p>
                    </div>
                <?php } ?>
            <?php } ?>
        <?php } else { ?>
            <h1 class="h3 m-3 font-weight-normal text-center">Account Login</h1>

            <form class="form-signin needs-validation" id="loginFormNRT" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
                <label for="username" class="sr-only">Username</label>
                <input type="email" name="username" id="username" class="form-control" placeholder="Username" required autofocus>

                <label for="password" class="sr-only mt-3">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>

                <button type="submit" class="btn btn btn-secondary btn-block mt-3">Log in</button>
            </form>
        <?php } ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/main.js"></script>
</body>

</html>