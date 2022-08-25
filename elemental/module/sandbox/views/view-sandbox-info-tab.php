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

return function (
): string {
	ob_start();
	?>

<div class="">
        <br />
        <h2 style="color: #323064;">Property Transactions by Company</h2>
        <p>This shows a list of properties that are being managed by the different participants in their organisations. You can select; properties, buyers, or sellers from this list when setting up a deal in the sandbox.</p>
        <!-- Tab panes -->
        <div class="tab-content">
            <div id="Agent" class="tab-pane active"><br>
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
                                <span class="tdtext">
                                    Mr John Smith
                                </span>
                            </td>
                            <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
                                <span class="tdtext">
                                    Mr Jason Wilson
                                </span>
                            </td>
                            <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                <span class="tdtext">
                                    1 Brynhyfryd, Crawley, Rhosfach, United Kingdom, SA66 7JT"
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                <span class="tdtext">
                                    Mrs Sarah Johnson
                                </span>
                            </td>
                            <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
                                <span class="tdtext">
                                    Mr Louis Harris
                                </span>
                            </td>
                            <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                <span class="tdtext">
                                    29 Beechwood Avenue, Thornaby-on-Tees , Richmond United Kingdom, TW9 4DD"
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                <span class="tdtext">
                                    Mrs Carrie Williams
                                </span>
                            </td>
                            <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
                                <span class="tdtext">
                                    Mr Timothy Hall
                                </span>
                            </td>
                            <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                <span class="tdtext">
                                    39 Marlhill Road,  Eastbourne ,Blackpool, United Kingdom, FY3 7TG"
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                <span class="tdtext">
                                    Mrs Jessica Brown
                                </span>
                            </td>
                            <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
                                <span class="tdtext">
                                    Mr Lewis Gabriel
                                </span>
                            </td>
                            <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                <span class="tdtext">
                                    Swallows Nest, Swallow Nest, Dronfield, Heathfield United Kingdom, TQ12 6RD"
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                <span class="tdtext">
                                    Mrs Yuni Li
                                </span>
                            </td>
                            <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
                                <span class="tdtext">
                                    Mr Mateo Gonzalez
                                </span>
                            </td>
                            <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                <span class="tdtext">
                                    22 Little Potters, Marl, Bushey United Kingdom, WD23 4QT"
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                <span class="tdtext">
                                    Miss Xinning Chen
                                </span>
                            </td>
                            <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
                                <span class="tdtext">
                                    Mr Tabbi Jansen
                                </span>
                            </td>
                            <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                <span class="tdtext">
                                    27 King Street, Dewsbur, Desborough, United Kingdom, NN14 2RD"
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                <span class="tdtext">
                                    Miss Melissa Anderson
                                </span>
                            </td>
                            <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
                                <span class="tdtext">
                                    Mr Katy Vella
                                </span>
                            </td>
                            <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                <span class="tdtext">
                                    8 Cwrt Arthur, Epping, Rhewl United Kingdom, LL15 2UJ"
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                <span class="tdtext">
                                    Miss Rachel Miller
                                </span>
                            </td>
                            <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
                                <span class="tdtext">
                                    Mrs Jacquie Grishukov
                                </span>
                            </td>
                            <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                <span class="tdtext">
                                    17 Middle Close, Woburn , Coulsdon United Kingdom, CR5 1BH"
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                <span class="tdtext">
                                    Miss Harriet Garcia
                                </span>
                            </td>
                            <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
                                <span class="tdtext">
                                    Mrs Winfred Roffey
                                </span>
                            </td>
                            <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                <span class="tdtext">
                                    3B  Bridge Street, Eccles, Hungerford United Kingdom, RG17 0EH"
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                <span class="tdtext">
                                    Mr Kyle Taylor
                                </span>
                            </td>
                            <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
                                <span class="tdtext">
                                    Mr Ashia Berriman
                                </span>
                            </td>
                            <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                <span class="tdtext">
                                    1 Ysgoldy, Hexham, Soar United Kingdom, LL47 6UP"
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                <span class="tdtext">
                                    Mr Mark Thomas
                                </span>
                            </td>
                            <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
                                <span class="tdtext">
                                    Mr Doralin Jiri
                                </span>
                            </td>
                            <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                <span class="tdtext">
                                    11 The Chase, Midhurst, Kilburn United Kingdom, DE56 0PL"
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                <span class="tdtext">
                                    Mr William Moore
                                </span>
                            </td>
                            <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
                                <span class="tdtext">
                                    Mrs Erhart Feehely
                                </span>
                            </td>
                            <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                <span class="tdtext">
                                    2 Field Lane Cottages, Tickhill, Thorpe Willoughby, United Kingdom, YO8 9NL"
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
                                <span class="tdtext">
                                    Mrs Jane Smith
                                </span>
                            </td>
                            <td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
                                <span class="tdtext">
                                    Mr Jason Wilson
                                </span>
                            </td>
                            <td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
                                <span class="tdtext">
                                    1 Brynhyfryd, Crawley, Rhosfach, United Kingdom, SA66 7JT"
                                </span>
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