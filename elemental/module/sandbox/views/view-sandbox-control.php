<?php
/**
 * View for Managing Sandbox
 *
 * @package module/sandbox/views/view-sandbox-control.php
 * @param string $add_account_form - add an account form
 * @param int    $membership_id - membership ID requested to Join.
 */

return function (
	object $current_user
): string {
	ob_start();
	?>

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script> -->
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <style type="text/css">
        .nav-pills-custom .nav-link {
            color: #aaa;
            background: #fff;
            position: relative;
        }

        .nav-pills-custom .nav-link.active {
            color: #45b649;
            background: #fff;
        }

        /* Add indicator arrow for the active tab */

        @media (min-width: 992px) {
            .nav-pills-custom .nav-link::before {
                content: '';
                display: block;
                border-top: 8px solid transparent;
                border-left: 10px solid #fff;
                border-bottom: 8px solid transparent;
                position: absolute;
                top: 50%;
                right: -10px;
                transform: translateY(-50%);
                opacity: 0;
            }
        }

        .nav-pills-custom .nav-link.active::before {
            opacity: 1;
        }
        li{
            font-size:16px;
        }
    </style>



   

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-5-vertical-tabs@2.0.1/dist/b5vtabs.min.css" rel="stylesheet">


    <div class="container-fluid vtabs" style="height:710px;">

        <div class="row">
            <div class="col-md-12">
                <hr />
                <h3>Sandbox</h3>
                <hr />
                <?php
                $file = ABSPATH . WPINC . '/certificates/conveyancer.pfx';
                // echo $file;
                //$data = file_get_contents($file);

                $certPassword = '';
                $cert_info = array();
                // openssl_pkcs12_read($data, $certs, $certPassword);

                if (!$cert_store = file_get_contents($file)) {
                echo "Error: Unable to read the cert file\n";
                // exit;
                }

                if (openssl_pkcs12_read($cert_store, $cert_info, $certPassword)) {
                echo "Certificate loaded\n";
                } else {
                echo "Error: Unable to read the cert store.\n";
                // exit;
                } 
                ?>
            </div>
        </div>

        <div class="row">

            <div class="col-md-3">
                <ul class="nav nav-tabs left-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <div id="lorem-left-tab" class="nav-link tab-clickable active" role="tab" data-bs-toggle="tab" data-bs-target="#lorem-left" aria-controls="lorem-left" aria-selected="true">
                            Conveyance
                        </div>
                    </li>
                    <li class="nav-item" role="presentation">
                        <div id="broker-left-tab" class="nav-link tab-clickable" role="tab" data-bs-toggle="tab" data-bs-target="#broker-left" aria-controls="broker-left" aria-selected="false">
                            Broker
                        </div>
                    </li>
                    <li class="nav-item" role="presentation">

                        <div id="estateagent-left-tab" class="nav-link tab-clickable" role="tab" data-bs-toggle="tab" data-bs-target="#estateagent-left" aria-controls="estateagent-left" aria-selected="false">
                            EStateAgent
                        </div>
                    </li>
                    <li class="nav-item" role="presentation">

                        <div id="lender-left-tab" class="nav-link tab-clickable" role="tab" data-bs-toggle="tab" data-bs-target="#lender-left" aria-controls="lender-left" aria-selected="false">
                            Lender
                        </div>
                    </li>
                </ul>
            </div>


            <div class="col-md-9" style="border: 1px solid #dee2e6;">
                <div class="container-fluid" style="height:600px;">
                    <div id="accordion-left-tabs" class="tab-content accordion">

                        <div id="lorem-left" class="tab-pane fade show active accordion-item" role="tabpanel">
                            <div class="accordion-header" role="tab">
                                <button class="accordion-button collapsed" id="sandbox1" type="button" role="button" data-bs-toggle="collapse" data-bs-target="#lorem-left-article" aria-expanded="true" aria-controls="lorem-left-article">
                                    SandBox 1
                                </button>
                            </div>
                            <article id="lorem-left-article" class="accordion-body accordion-collapse collapse" data-bs-parent="#accordion-left-tabs" aria-labelledby="lorem-left-tab">
                                <h1>Sandbox 1</h1>
                                <section>
                                    <iframe style="width:100%;height:570px;" src="https://sandbox-applet.southbridge.oncoadjute.com/conveyancer?userid=QrUz1BYO9h1uiIXgDd7DPXZaWODmzpCDC74l0M0vmfZc%2Bsdi2GVlNLebxjeJbPijATCBPCLMyb92pGekzAguhJrMpkfcR0keN6dfPsUNivpHbK6zw3u2p6M6xTDUavOc53rSshh9yDmk5PJYitOHZnsNqV%2F8BroQ1LVmwwrshM8bAuhLPGQw2%2BUYs7QKPxIrsKmhl%2F0ouMQQ1nFsZisUode3FycPUgQ43%2B%2FjwJ1a6cBob8m0r4xleyyktkJy2oxiyjCcRJ%2Bd4jAj7zO8H%2F2SYe8LiUbeRAwJMez83VzO2xXWYtEd%2FxrLykWqpNnNHmDk6Ywcu3dMueaBdcYBjI2p0Q%3D%3D">

                                    </iframe>
                                </section>
                            </article>
                        </div>

                        <div id="broker-left" class="tab-pane fade accordion-item" role="tabpanel">
                            <div class="accordion-header" role="tab">
                                <button class="accordion-button collapsed" type="button" role="button" data-bs-toggle="collapse" data-bs-target="#broker-left-article" aria-expanded="false" aria-controls="broker-left-article">

                                </button>
                            </div>

                            <article id="broker-left-article" class="accordion-body accordion-collapse collapse" data-bs-parent="#accordion-left-tabs" aria-labelledby="broker-left-tab">
                                <h1>broker</h1>
                                <section>
                                    <?php

                                    //  var_dump($certs);
                     
                                    echo 'Username: ' . $current_user->user_login;
                                    echo 'User ID: ' . $current_user->ID;
                                    echo 'User Email: ' . $current_user->user_email;
                                    echo 'User First Name: ' . $current_user->user_firstname;
                                    echo 'User Last Name: ' . $current_user->user_lastname;
                                    echo 'User Display Name: ' . $current_user->display_name;
                                    ?>
                                </section>
                            </article>
                        </div>

                        <div id="estateagent-left" class="tab-pane fade accordion-item" role="tabpanel">
                            <div class="accordion-header" role="tab">
                                <button class="accordion-button collapsed" type="button" role="button" data-bs-toggle="collapse" data-bs-target="#estateagent-left-article" aria-expanded="false" aria-controls="estateagent-left-article">

                                    <div class="ellipsis">
                                        EstateAgent
                                    </div>
                                </button>
                            </div>
                            <article id="estateagent-left-article" class="accordion-body accordion-collapse collapse" data-bs-parent="#accordion-left-tabs" aria-labelledby="estateagent-left-tab">
                                <h1>
                                    Agen 2
                                </h1>
                                <section>
                                    <p>
                                        <small>Details</small>
                                    </p>
                                </section>

                            </article>
                        </div>

                        <div id="lender-left" class="tab-pane fade accordion-item" role="tabpanel">
                            <div class="accordion-header" role="tab">
                                <button class="accordion-button collapsed" type="button" role="button" data-bs-toggle="collapse" data-bs-target="#lender-left-article" aria-expanded="false" aria-controls="lender-left-article">

                                    <div class="ellipsis">
                                        lender
                                    </div>
                                </button>
                            </div>
                            <article id="lender-left-article" class="accordion-body accordion-collapse collapse" data-bs-parent="#accordion-left-tabs" aria-labelledby="lender-left-tab">
                                <h1>
                                    Lender 2
                                </h1>
                                <section>
                                    <p>
                                        <small>Details</small>
                                    </p>
                                </section>

                            </article>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            $("#content-wrap").attr('class', 'container-fluid');
            $("#sandbox1").click(function() {
                console.log(` click on e: `);
            });
        </script>
		<?php
		return ob_get_clean();

};
