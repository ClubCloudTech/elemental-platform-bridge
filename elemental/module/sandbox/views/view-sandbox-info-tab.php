<?php

/**
 * Sandbox Info Tab
 *
 * @package module/sandbox/views/view-sandbox-info-tab.php
 */

/**
 * Render the Sandbox Info Tab
 *
 * @return string
 */

return function (): string {
    ob_start();
?>

    <div class="main_tabDiv" style="margin-top:2%;width:100%;">
        <h2 style="color: #323064;">Property Transactions by Company</h2>
        <p>This shows a list of properties that are being managed by the different participants in their organisations. You can select; properties, buyers, or sellers from this list when setting up a deal in the sandbox.</p>
        <br>

        <div class="nav nav-tabs">
            <span class="nav-item active">
                <a class="nav-link " data-toggle="tab" href="#Agent">Great Estate Agent</a> |
            </span>
            <span class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#Conveyancer1">Digi Convey</a> |
            </span>
            <span class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#Conveyancer2">Speedy Solicitors </a> |
            </span>

            <span class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#Lender">Kensington</a>
            </span>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content" style="margin-top:2%;width:100%;">
                <div id="Agent" class="container tab-pane active"><br>
                    <h3>
                        Great Estate Agent
                    </h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Seller </th>
                                <th>Buyer</th>
                                <th>Property</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mr John Smith </span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
                                    <span class="tdtext">Mrs Sarah Johnson</span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">1 Brynhyfryd, Crawley, Rhosfach, United Kingdom, SA66 7JT</span>
                                </td>
                            </tr>

                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mrs Carrie Williams</span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
                                    <span class="tdtext">Mrs Jessica Brown</span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">29 Beechwood Avenue, Thornaby-on-Tees, Richmond United Kingdom, TW9 4DD</span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mrs Yuni Li</span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
                                    <span class="tdtext">Miss Xinning Chen</span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">39 Marlhill Road, Eastbourne, Blackpool, United Kingdom, FY3 7TG </span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Miss Melissa Anderson</span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
                                    <span class="tdtext">Miss Rachel Miller</span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">Swallows Nest, Swallow Nest, Dronfield, Heathfield United Kingdom, TQ12 6RD</span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Miss Harriet Garcia</span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
                                    <span class="tdtext">Mr Kyle Taylor 22</span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext"> Little Potters, Marl, Bushey United Kingdom, WD23 4QT</span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mr Mark Thomas</span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
                                    <span class="tdtext">Mr William Moore</span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">27 King Street, Dewsbur, Desborough, United Kingdom, NN14 2RD</span>
                                </td>
                            </tr>

                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mr Jason Wilson</span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
                                    <span class="tdtext"> Mr Louis Harris</span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext"> 8 Curt Arthur, Epping, Rhewl United Kingdom, LL15 2UJ
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mr Timothy Hall</span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
                                    <span class="tdtext">Mr Lewis Gabriel</span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">17 Middle Close, Woburn, Coulsdon United Kingdom, CR5 1BH</span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mr Mateo Gonzalez </span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
                                    <span class="tdtext">Mr Tabbi Jansen 3B</span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">Bridge Street, Eccles, Hungerford United Kingdom, RG17 0EH</span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mr Katy Vella </span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
                                    <span class="tdtext">Mrs Jacquie Grichuk</span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">1 Ysgoldy, Hexham, Soar United Kingdom, LL47 6UP</span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mrs Winfred Roffey </span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
                                    <span class="tdtext">Mr Ashia Berriman</span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">11 The Chase, Midhurst, Kilburn United Kingdom, DE56 0PL</span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mr Doral in Jiri </span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
                                    <span class="tdtext">Mrs Erhart Feehely</span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">2 Field Lane Cottages, Tickhill, Thorpe Willoughby, United Kingdom, YO8 9NL</span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mr John Smith  </span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
                                    <span class="tdtext">Mrs Sarah Johnson</span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">1 Brynhyfryd, Crawley, Rhosfach, United Kingdom, SA66 7JT</span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Miss Melissa Anderson </span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
                                    <span class="tdtext">Miss Rachel Miller</span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">Swallows Nest, Swallow Nest, Dronfield, Heathfield United Kingdom, TQ12 6RD buy</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>


                <div id="Conveyancer1" class="container tab-pane fade"><br>
                    <h3>Digi Convey</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Seller </th>
                                <th>Buyer</th>
                                <th>Property</th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mrs Janella Strainge </span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mr Morgen Dunbleton</span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">85 Duke Street, , Guildford , , Highcliffe Drive, Askam-In-Furness ,United Kingdom 1_Property LA16 7AD
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mr Danette Ogborn </span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mr Darwin Maass</span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext"> Church Street, , NULL, Church Gates, Hartland , Old Heathfield ,United Kingdom 2_Property TN21 9AH
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mr Tailor Tollett </span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mr Giffer Dolden</span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">45 Ranelagh Street, , NULL, , Lytchett Minster and Upton , Liverpool ,United Kingdom 3_Property L1 1JR
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mrs Skipton Emmison </span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mr Molly Mauchlen</span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext"> The Balk, , NULL, Southlea, Henley-in-Arden , Pocklington ,United Kingdom 4_Property YO42 2NX
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mr Matty McCourtie </span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mr Emily Hessay</span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext"> London Road, , NULL, The Brambles, Luton , Halesworth ,United Kingdom 5_Property IP19 8DH
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mrs Wash Goldine </span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mr Evie Bartholin</span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">4 Blenheim Road, , NULL, , Patchway , Wroughton ,United Kingdom 6_Property SN4 9HL
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mr Wanids Joynes </span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mr Janella Delgaty</span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">1A Hogarth Place, , NULL, , Verwood , London ,United Kingdom 7_Property SW5 9RE
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mr Shurlock Le Marquand </span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mr Carlina Christou</span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">23 King Street, , NULL, , Oakham , Salford ,United Kingdom 8_Property M7 4PU
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mrs Pacorro Madgwick </span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mr Faith Philpin</span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">67 Beckminster Road, , NULL, , Horwich , Wolverhampton ,United Kingdom 9_Property WV3 7DY
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mrs Vonnie Edgington </span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mr Talya Langrick</span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">12 The Grove, , NULL, , Coleshill , London ,United Kingdom 10_Property N6 6LB
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mr Eddy Blacker </span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mr Chickie Clipson</span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">47 Glebe Road, , NULL, , Redruth , Chalfont St Peter ,United Kingdom 11_Property SL9 9NL
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mr Brendis Lanmeid </span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mr Harrietta Arnaudin</span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">42 Brigade Place, , NULL, , Portishead and North Weston , Caterham ,United Kingdom 12_Property CR3 5ZU
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>


                <div id="Conveyancer2" class="container tab-pane fade"><br>
                    <h3>Crown Solicitors </h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Seller </th>
                                <th>Buyer</th>
                                <th>Property</th>
                            </tr>
                        </thead>
                        <tbody>




                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mrs Ardis Kruschov</span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mr Steffen Andrichuk/span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">15 Kimbolton Court, , , NULL, , Walthamstow , Giffard Park ,United Kingdom 1_Property MK14 5PS
                                    </span>
                                </td>

                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext"> Alphonse Josskowitz</span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mr Hakeem Matic/span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">3 Oxley Close, , , NULL, , Battle , Dudley ,United Kingdom 2_Property DY2 0EN
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext"> Westleigh Trematick</span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mr Natale Levesque/span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">1 Romulus Walk, , , NULL, , Marlow , Coventry ,United Kingdom 3_Property CV4 9WG
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mrs Erna Weblin</span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mrs Rosette Dagleas/span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">33 Buxton Gardens, , , NULL, , Mansfield , London ,United Kingdom 4_Property W3 9LE
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mrs Yasmin Mayho</span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mrs Luci Magarrell/span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">31 Lintham Drive, , , NULL, , Maidstone , Kingswood ,United Kingdom 5_Property BS15 9GB
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mrs Emmye Foxhall</span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mrs Dulci Frounks/span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext"> Tylwch, , NULL, Pen Cae Driw, Oldbury , ,United Kingdom 6_Property SY18 6JL
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext"> Dex Harvard</span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mr Zolly Fillon/span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">12 South Cliff Road, , NULL, , Atherstone , Withernsea ,United Kingdom 7_Property HU19 2HX
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mrs Kira Gaitskill</span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mr Byrom Peirson/span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext"> Fleetwood Road, , NULL, Pitfield Farm, Skelton-in-Cleveland , Singleton ,United Kingdom 8_Property FY6 8NE
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext"> Hanan Morsom</span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mrs Cassey Tomasoni/span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">10 Kirkway, , NULL, , Birchwood , Broadstone ,United Kingdom 9_Property BH18 8EE
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext"> Jock McCurt</span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mrs Bethany Trevers/span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">1 Godrer Coed, , NULL, , Canterbury , Penpedairheol ,United Kingdom 10_Property CF82 7TG
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mrs Agathe Demangel</span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mrs Gillie Chasemore/span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">2 Church Close, , NULL, , Windermere , Stour Row ,United Kingdom 11_Property SP7 0QE
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext"> Reid Chamberlain</span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mrs Randy Titcom/span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">Flat 37 Elizabeth House, , King's Lynn , , St Giles Mews, Stony Stratford ,United Kingdom 12_Property MK11 1HT

                                    </span>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <div id="Lender" class="container tab-pane fade"><br>
                    <h3>Kensington </h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Seller </th>
                                <th>Buyer</th>
                                <th>Property</th>
                            </tr>
                        </thead>
                        <tbody>





                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mrs Agnella Hugonet</span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mrs Helaina Clarson/span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">3 Mockford Alley, , NULL, , Frome , Tenterden ,United Kingdom 1_Property TN30 6AU
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mrs Dorrie Brion</span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mr Dino Gribbon/span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">7 The Terrace, , NULL, , Framlingham , Boldon Colliery ,United Kingdom 2_Property NE35 9AA
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mrs Fidelity Anespie</span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mr Jeno McKirdy/span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">22 The Borough, , NULL, , Bridgwater , Montacute ,United Kingdom 3_Property TA15 6XB
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mrs Beverlie Baumer</span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mrs Laverna Dillinton/span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext"> Copperhill Street, , NULL, Frondeg, Wigan , Aberdovey ,United Kingdom 4_Property LL35 0HT
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext"> Tudor Churchin</span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mr Nikola Gover/span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">30 Wingfields, , NULL, , Hereford , Downham Market ,United Kingdom 5_Property PE38 9AR
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mrs Jaquenetta Sandbrook</span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mrs Janaya Griffoen/span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">25 Maes Waldo, , NULL, , Sale , Fishguard ,United Kingdom 6_Property SA65 9ER
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext"> Sig Digle</span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mrs Lane Gravells/span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">1 Heath Hill Road South, , NULL, , Northallerton , Crowthorne ,United Kingdom 7_Property RG45 7BW
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mrs Lenna Rhubottom</span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mrs Jerrilyn Hewson/span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">1 Bishop Way, , NULL, , South Molton , Bicker ,United Kingdom 8_Property PE20 3BU
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mrs Rianon Capell</span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mrs Rosy Jannex/span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">2 Saxon Road, , NULL, , Yeovil , Westgate-On-Sea ,United Kingdom 9_Property CT8 8RS
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext"> Tucky Rickett</span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mrs Grazia Lamblot/span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">15 Hagley Road West, , NULL, , Ringwood , Birmingham ,United Kingdom 10_Property B17 8AL
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext"> Ross Coarser</span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mr Kipp Channer/span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">45 Selby Road, , NULL, , Knares , Leeds ,United Kingdom 11_Property LS9 0EW
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext"> Jabez McAllan</span>
                                </td>
                                <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                    <span class="tdtext">Mrs Carolann Ayers/span>
                                </td>
                                <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                    <span class="tdtext">13 Croft Parc, , NULL, , Wilton , The Lizard ,United Kingdom 12_Property TR12 7PN</span>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>



            </div>


        </div>

        </body>
        <script>
            let seller = document.querySelectorAll('i[data-type="seller-copy"]')
            let buyer = document.querySelectorAll('i[data-type="buyer-copy"]')
            let property = document.querySelectorAll('i[data-type="property-copy"]')
            seller.forEach(function(button) {

                button.addEventListener('click', function() {
                    let seller = this.parentNode.parentNode
                        .querySelector('td[data-type="seller"]')
                        .innerText;
                    navigator.clipboard.writeText(seller);
                    console.log(`${seller} copied.`);
                });
            });
            buyer.forEach(function(button) {
                button.addEventListener('click', function() {
                    let buyer = this.parentNode.parentNode
                        .querySelector('td[data-type="buyer"]')
                        .innerText;

                    navigator.clipboard.writeText(buyer);
                    console.log(`${buyer} copied.`);
                });
            });
            property.forEach(function(button) {

                button.addEventListener('click', function() {
                    let property = this.parentNode.parentNode
                        .querySelector('td[data-type="property"]')
                        .innerText;

                    let tmp = document.createElement('textarea');
                    navigator.clipboard.writeText(property);
                    console.log(`${property} copied.`);
                });
            });
        </script>

    <?php

    return \ob_get_clean();
};
