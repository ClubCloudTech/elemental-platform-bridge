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
		<div>This shows a list of properties that are being managed by the different participants in their organisations. You can select; properties, buyers, or sellers from this list when setting up a deal in the sandbox.</div>
		<br>
		<div class="tabset">
			<!-- Tab 1 -->
			<input type="radio" name="tabset" id="tab1" aria-controls="Great Estate Agent" checked>
			<!-- <label for="tab1">Great Estate Agent</label> -->
			<!-- Tab 2 -->
			<!-- <input type="radio" name="tabset" id="tab2" aria-controls="Digi Convey">
			<label for="tab2">Digi Convey</label> -->
			<!-- Tab 3 -->
			<!-- <input type="radio" name="tabset" id="tab3" aria-controls="Speedy Solicitors">
			<label for="tab3">Speedy Solicitors </label> -->
			<!-- Tab 4 -->
			<!-- <input type="radio" name="tabset" id="tab4" aria-controls="Kensington">
			<label for="tab4">Kensington</label> -->

			<div class="tab-panels">
				<section id="Great Estate Agent" class="tab-panel">
					<!-- <h3>
						Great Estate Agent
					</h3> -->
					<table class="table">
						<thead>
							<tr>
								<th>Property </th>
								<th>Seller</th>
								<th>Buyer</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td data-type="property">
									<i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">1 Brynhyfryd Crawley Rhosfach SA66 7JT</span>
								</td>
								<td data-type="seller">
									<i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Mr John Smith</span>
								</td>
								<td data-type="buyer">
									<i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Mr Jason Wilson</span>
								</td>
								<tr>
							</tr>
								<td data-type="property">
									<i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">1 Brynhyfryd Crawley Rhosfach SA66 7JT</span>
								</td>
								<td data-type="seller">
									<i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Mrs Jane Smith</span>
								</td>
								<td data-type="buyer">
									<i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Mr Jason Wilson</span>
								</td>
							</tr>
							<tr>
								<td data-type="property">
									<i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">29 Beechwood Avenue Thornaby-on-Tees Richmond TW9 4DD</span>
								</td>
								<td data-type="seller">
									<i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Mrs Sarah Johnson</span>
								</td>
								<td data-type="buyer">
									<i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Mr Louis Harris</span>
								</td>
							</tr>
							<tr>
								<td data-type="property">
									<i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">39 Marlhill Road Eastbourne Blackpool FY3 7TG</span>
								</td>
								<td data-type="seller">
									<i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Mrs Carrie Williams</span>
								</td>
								<td data-type="buyer">
									<i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Mr Timothy Hall</span>
								</td>
							</tr>
							<tr>
								<td data-type="property">
									<i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">Swallows Nest Swallow Nest Dronfield Heathfield TQ12 6RD</span>
								</td>
								<td data-type="seller">
									<i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Mrs Jessica Brown</span>
								</td>
								<td data-type="buyer">
									<i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Mr Lewis Gabriel</span>
								</td>
							</tr>
							<tr>
								<td data-type="property">
									<i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">22 Little Potters Marl Bushey WD23 4QT</span>
								</td>
								<td data-type="seller">
									<i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Mrs Yuni Li</span>
								</td>
								<td data-type="buyer">
									<i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Mr Mateo Gonzalez</span>
								</td>
							</tr>
							<tr>
								<td data-type="property">
									<i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">27 King Street Dewsbury Desborough NN14 2RD</span>
								</td>
								<td data-type="seller">
									<i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Miss Xinning Chen</span>
								</td>
								<td data-type="buyer">
									<i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Mr Tabbi Jansen</span>
								</td>
							</tr>
							<tr>
								<td data-type="property">
									<i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">8 Cwrt Arthur Epping Rhewl LL15 2UJ</span>
								</td>
								<td data-type="seller">
									<i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Miss Melissa Anderson</span>
								</td>
								<td data-type="buyer">
									<i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Mr Katy Vella</span>
								</td>
							</tr>
							<tr>
								<td data-type="property">
									<i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">17 Middle Close Woburn Coulsdon CR5 1BH</span>
								</td>
								<td data-type="seller">
									<i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Miss Rachel Miller</span>
								</td>
								<td data-type="buyer">
									<i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Mrs Jacquie Grishukov</span>
								</td>
							</tr>
							<tr>
								<td data-type="property">
									<i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">3B Bridge Street Eccles Hungerford RG17 0EH</span>
								</td>
								<td data-type="seller">
									<i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Miss Harriet Garcia</span>
								</td>
								<td data-type="buyer">
									<i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Mrs Winfred Roffey</span>
								</td>
							</tr>
							<tr>
								<td data-type="property">
									<i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">1 Ysgoldy Hexham Soar LL47 6UP</span>
								</td>
								<td data-type="seller">
									<i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Mr Kyle Taylor</span>
								</td>
								<td data-type="buyer">
									<i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Mr Ashia Berriman</span>
								</td>
							</tr>
							<tr>
								<td data-type="property">
									<i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">11 The Chase Midhurst Kilburn DE56 0PL</span>
								</td>
								<td data-type="seller">
									<i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Mr Mark Thomas</span>
								</td>
								<td data-type="buyer">
									<i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Mr Doralin Jiri</span>
								</td>
							</tr>
							<tr>
								<td data-type="property">
									<i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">2 Field Lane Cottages Tickhill Thorpe Willoughby YO8 9NL</span>
								</td>
								<td data-type="seller">
									<i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Mr William Moore</span>
								</td>
								<td data-type="buyer">
									<i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Mrs Erhart Feehely</span>
								</td>
							</tr>
							<tr>
								<td data-type="property">
									<i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">Church Street Church Gates Hartland  Old Heathfield TN21 9AH</span>
								</td>
								<td data-type="seller">
									<i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Mrs Janella Strainge</span>
								</td>
								<td data-type="buyer">
									<i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Mr Morgen Dunbleton</span>
								</td>
							</tr>
							<tr>
								<td data-type="property">
									<i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">45 Ranelagh Street  Lytchett Minster and Upton  Liverpool L1 1JR</span>
								</td>
								<td data-type="seller">
									<i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Mr Danette Ogborn</span>
								</td>
								<td data-type="buyer">
									<i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Mrs Darwin Maass</span>
								</td>
							</tr>
							<tr>
								<td data-type="property">
									<i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">The Balk Southlea Henley-in-Arden  Pocklington YO42 2NX</span>
								</td>
								<td data-type="seller">
									<i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Mr Tailor Tollett</span>
								</td>
								<td data-type="buyer">
									<i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Mr Giffer Dolden</span>
								</td>
							</tr>
							<tr>
								<td data-type="property">
									<i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">London Road The Brambles Luton  Halesworth IP19 8DH</span>
								</td>
								<td data-type="seller">
									<i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Mrs Skipton Emmison</span>
								</td>
								<td data-type="buyer">
									<i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Mr Molly Mauchlen</span>
								</td>
							</tr>
							<tr>
								<td data-type="property">
									<i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">4 Blenheim Road Patchway  Wroughton SN4 9HL</span>
								</td>
								<td data-type="seller">
									<i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Mr Matty McCourtie</span>
								</td>
								<td data-type="buyer">
									<i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Mr Emily Hessay</span>
								</td>
							</tr>
							<tr>
								<td data-type="property">
									<i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">23 King Street Oakham  Salford M7 4PU</span>
								</td>
								<td data-type="seller">
									<i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Mrs Wash Goldine</span>
								</td>
								<td data-type="buyer">
									<i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Mr Evie Bartholin</span>
								</td>
							</tr>
							<tr>
								<td data-type="property">
									<i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">67 Beckminster Road Horwich  Wolverhampton WV3 7DY</span>
								</td>
								<td data-type="seller">
									<i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Mr Wanids Joynes</span>
								</td>
								<td data-type="buyer">
									<i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Mr Janella Delgaty</span>
								</td>
							</tr>
							<tr>
								<td data-type="property">
									<i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">12 The Grove Coleshill   London N6 6LB</span>
								</td>
								<td data-type="seller">
									<i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Mr Shurlock Le Marquand</span>
								</td>
								<td data-type="buyer">
									<i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Mrs Carlina Christou</span>
								</td>
							</tr>
							<tr>
								<td data-type="property">
									<i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">47 Glebe Road Redruth  Chalfont St Peter SL9 9NL</span>
								</td>
								<td data-type="seller">
									<i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Mrs Pacorro Madgwick</span>
								</td>
								<td data-type="buyer">
									<i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Mrs Faith Philpin</span>
								</td>
							</tr>
							<tr>
								<td data-type="property">
									<i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">42 Brigade Place Portishead and North Weston  Caterham CR3 5ZU</span>
								</td>
								<td data-type="seller">
									<i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Mrs Vonnie Edgington</span>
								</td>
								<td data-type="buyer">
									<i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Mr Talya Langrick</span>
								</td>
							</tr>
							<tr>
								<td data-type="property">
									<i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">36 Goodwood Close Tynemouth  High Halstow ME3 8SU</span>
								</td>
								<td data-type="seller">
									<i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Mr Eddy Blacker</span>
								</td>
								<td data-type="buyer">
									<i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Mr Chickie Clipson</span>
								</td>
							</tr>
							<tr>
								<td data-type="property">
									<i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">Flat 3 Hillcrest Nook High Road Carlisle CA1 2QU</span>
								</td>
								<td data-type="seller">
									<i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Mr Brendis Lanmeid</span>
								</td>
								<td data-type="buyer">
									<i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Mrs Harrietta Arnaudin</span>
								</td>
							</tr>
						</tbody>
					</table>

				</section>
				<!-- <section id="Digi Convey" class="tab-panel">
					<h3>Digi Convey</h3>
					<table class="table">
						<thead>
							<tr>
								<th>Property </th>
								<th>Seller</th>
								<th>Buyer</th>
							</tr>
						</thead>
						<tbody>

							<tr>
								<td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">85 Duke Street,Guildford ,Highcliffe Drive, Askam-In-Furness ,United Kingdom
										1_Property, LA16 7AD
									</span>
								</td>
								<td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Janella Strainge </span>
								</td>
								<td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Morgen Dunbleton</span>
								</td>

							</tr>
							<tr>
								<td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">Church Street, Church Gates, Hartland , Old Heathfield ,United Kingdom 2_Property,
										TN21 9AH
									</span>
								</td>
								<td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Danette Ogborn </span>
								</td>
								<td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Darwin Maass</span>
								</td>

							</tr>
							<tr>
								<td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">45 Ranelagh Street,Lytchett Minster and Upton , Liverpool ,United Kingdom
										3_Property, L1 1JR
									</span>
								</td>
								<td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Tailor Tollett </span>
								</td>
								<td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Giffer Dolden</span>
								</td>

							</tr>
							<tr>
								<td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">The Balk, Southlea, Henley-in-Arden , Pocklington ,United Kingdom 4_Property, YO42
										2NX
									</span>
								</td>
								<td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Skipton Emmison </span>
								</td>
								<td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Molly Mauchlen</span>
								</td>

							</tr>
							<tr>
								<td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">London Road, The Brambles, Luton , Halesworth ,United Kingdom 5_Property, IP19 8DH
									</span>
								</td>
								<td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Matty McCourtie </span>
								</td>
								<td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Emily Hessay</span>
								</td>

							</tr>
							<tr>
								<td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">4 Blenheim Road,Patchway , Wroughton ,United Kingdom 6_Property, SN4 9HL
									</span>
								</td>
								<td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Wash Goldine </span>
								</td>
								<td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Evie Bartholin</span>
								</td>

							</tr>
							<tr>
								<td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">1A Hogarth Place,Verwood , London ,United Kingdom 7_Property, SW5 9RE
									</span>
								</td>
								<td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Wanids Joynes </span>
								</td>
								<td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Janella Delgaty</span>
								</td>

							</tr>
							<tr>
								<td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">23 King Street,Oakham , Salford ,United Kingdom 8_Property, M7 4PU
									</span>
								</td>
								<td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Shurlock Le Marquand </span>
								</td>
								<td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Carlina Christou</span>
								</td>

							</tr>
							<tr>
								<td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">67 Beckminster Road,Horwich , Wolverhampton ,United Kingdom 9_Property, WV3 7DY
									</span>
								</td>
								<td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Pacorro Madgwick </span>
								</td>
								<td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Faith Philpin</span>
								</td>

							</tr>
							<tr>
								<td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">12 The Grove,Coleshill , London ,United Kingdom 10_Property, N6 6LB
									</span>
								</td>
								<td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Vonnie Edgington </span>
								</td>
								<td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Talya Langrick</span>
								</td>

							</tr>
							<tr>
								<td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">47 Glebe Road,Redruth , Chalfont St Peter ,United Kingdom 11_Property, SL9 9NL
									</span>
								</td>
								<td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Eddy Blacker </span>
								</td>
								<td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Chickie Clipson</span>
								</td>

							</tr>
							<tr>
								<td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">42 Brigade Place,Portishead and North Weston , Caterham ,United Kingdom
										12_Property, CR3 5ZU
									</span>
								</td>
								<td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Brendis Lanmeid </span>
								</td>
								<td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Harrietta Arnaudin</span>
								</td>

							</tr>
						</tbody>
					</table>
				</section> -->
				<!-- <section id="Speedy Solicitors" class="tab-panel">
					<h3>Crown Solicitors </h3>
					<table class="table">
						<thead>
							<tr>
								<th>Property </th>
								<th>Seller</th>
								<th>Buyer</th>
							</tr>
						</thead>
						<tbody>

							<tr>
								<td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">15 Kimbolton Court,Walthamstow , Giffard Park ,United Kingdom 1_Property, MK14
										5PS
									</span>
								</td>
								<td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Ardis Kruschov</span>
								</td>
								<td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Steffen Andrichuk</span>
								</td>


							</tr>
							<tr>
								<td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">3 Oxley Close,Battle , Dudley ,United Kingdom 2_Property, DY2 0EN
									</span>
								</td>
								<td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Alphonse Josskowitz</span>
								</td>
								<td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Hakeem Matic</span>
								</td>

							</tr>
							<tr>
								<td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">1 Romulus Walk,Marlow , Coventry ,United Kingdom 3_Property, CV4 9WG
									</span>
								</td>
								<td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Westleigh Trematick</span>
								</td>
								<td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Natale Levesque</span>
								</td>

							</tr>
							<tr>
								<td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">33 Buxton Gardens,Mansfield , London ,United Kingdom 4_Property, W3 9LE
									</span>
								</td>
								<td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Erna Weblin</span>
								</td>
								<td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Rosette Dagleas</span>
								</td>

							</tr>
							<tr>
								<td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">31 Lintham Drive,Maidstone , Kingswood ,United Kingdom 5_Property, BS15 9GB
									</span>
								</td>
								<td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Yasmin Mayho</span>
								</td>
								<td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Luci Magarrell</span>
								</td>

							</tr>
							<tr>
								<td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">Tylwch, Pen Cae Driw, Oldbury ,United Kingdom 6_Property, SY18 6JL
									</span>
								</td>
								<td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Emmye Foxhall</span>
								</td>
								<td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Dulci Frounks</span>
								</td>

							</tr>
							<tr>
								<td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">12 South Cliff Road,Atherstone , Withernsea ,United Kingdom 7_Property, HU19 2HX
									</span>
								</td>
								<td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Dex Harvard</span>
								</td>
								<td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Zolly Fillon</span>
								</td>

							</tr>
							<tr>
								<td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">Fleetwood Road, Pitfield Farm, Skelton-in-Cleveland , Singleton ,United Kingdom
										8_Property,FY6 8NE
									</span>
								</td>
								<td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Kira Gaitskill</span>
								</td>
								<td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Byrom Peirson</span>
								</td>

							</tr>
							<tr>
								<td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">10 Kirkway,Birchwood , Broadstone ,United Kingdom 9_Property,BH18 8EE
									</span>
								</td>
								<td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Hanan Morsom</span>
								</td>
								<td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Cassey Tomasoni</span>
								</td>

							</tr>
							<tr>
								<td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">1 Godrer Coed,Canterbury , Penpedairheol ,United Kingdom 10_Property, CF82 7TG
									</span>
								</td>
								<td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Jock McCurt</span>
								</td>
								<td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Bethany Trevers</span>
								</td>

							</tr>
							<tr>
								<td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">2 Church Close,Windermere , Stour Row ,United Kingdom 11_Property, SP7 0QE
									</span>
								</td>
								<td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Agathe Demangel</span>
								</td>
								<td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Gillie Chasemore</span>
								</td>

							</tr>
							<tr>
								<td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">Flat 37 Elizabeth House,King's Lynn ,St Giles Mews, Stony Stratford ,United Kingdom
										12_Property, MK11 1HT

									</span>
								</td>
								<td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Reid Chamberlain</span>
								</td>
								<td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Randy Titcom</span>
								</td>

							</tr>

						</tbody>
					</table>
				</section> -->
				<!-- <section id="Kensington" class="tab-panel">
					<h3>Kensington </h3>
					<table class="table">
						<thead>
							<tr>
								<th>Property </th>
								<th>Seller</th>
								<th>Buyer</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">3 Mockford Alley,Frome , Tenterden ,United Kingdom 1_Property, TN30 6AU
									</span>
								</td>
								<td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Agnella Hugonet</span>
								</td>
								<td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Helaina Clarson</span>
								</td>
							</tr>
							<tr>
								<td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">7 The Terrace,Framlingham , Boldon Colliery ,United Kingdom 2_Property, NE35 9AA
									</span>
								</td>
								<td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Dorrie Brion</span>
								</td>
								<td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Dino Gribbon</span>
								</td>
							</tr>
							<tr>
								<td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">22 The Borough,Bridgwater , Montacute ,United Kingdom 3_Property, TA15 6XB
									</span>
								</td>
								<td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Fidelity Anespie</span>
								</td>
								<td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Jeno McKirdy</span>
								</td>
							</tr>
							<tr>
								<td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">Copperhill Street, Frondeg, Wigan , Aberdovey ,United Kingdom 4_Property, LL35 0HT
									</span>
								</td>
								<td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Beverlie Baumer</span>
								</td>
								<td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Laverna Dillinton</span>
								</td>
							</tr>
							<tr>
								<td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">30 Wingfields,Hereford , Downham Market ,United Kingdom 5_Property, PE38 9AR
									</span>
								</td>
								<td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Tudor Churchin</span>
								</td>
								<td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Nikola Gover</span>
								</td>
							</tr>
							<tr>
								<td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">25 Maes Waldo,Sale , Fishguard ,United Kingdom 6_Property, SA65 9ER
									</span>
								</td>
								<td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Jaquenetta Sandbrook</span>
								</td>
								<td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Janaya Griffoen/span>
								</td>
							</tr>
							<tr>
								<td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">1 Heath Hill Road South,Northallerton , Crowthorne ,United Kingdom 7_Property,
										RG45 7BW
									</span>
								</td>
								<td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Sig Digle</span>
								</td>
								<td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Lane Gravells</span>
								</td>
							</tr>
							<tr>
								<td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">1 Bishop Way,South Molton , Bicker ,United Kingdom 8_Property, PE20 3BU
									</span>
								</td>
								<td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Lenna Rhubottom</span>
								</td>
								<td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Jerrilyn Hewson</span>
								</td>
							</tr>
							<tr>
								<td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">2 Saxon Road,Yeovil , Westgate-On-Sea ,United Kingdom 9_Property, CT8 8RS
									</span>
								</td>
								<td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Rianon Capell</span>
								</td>
								<td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Rosy Jannex</span>
								</td>
							</tr>
							<tr>
								<td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">15 Hagley Road West,Ringwood , Birmingham ,United Kingdom 10_Property, B17 8AL
									</span>
								</td>
								<td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Tucky Rickett</span>
								</td>
								<td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Grazia Lamblot</span>
								</td>
							</tr>
							<tr>
								<td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">45 Selby Road,Knares , Leeds ,United Kingdom 11_Property, LS9 0EW
									</span>
								</td>
								<td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Ross Coarser</span>
								</td>
								<td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Kipp Channer</span>
								</td>
							</tr>
							<tr>
								<td data-type="property"><i class="fa fa-clone" aria-hidden="true" data-type="property-copy"></i>
									<span class="tdtext">13 Croft Parc,Wilton , The Lizard ,United Kingdom 12_Property, TR12 7PN</span>
								</td>
								<td data-type="seller"><i class="fa fa-clone" aria-hidden="true" data-type="seller-copy"></i>
									<span class="tdtext">Jabez McAllan</span>
								</td>
								<td data-type="buyer"><i class="fa fa-clone" aria-hidden="true" data-type="buyer-copy"></i>
									<span class="tdtext">Carolann Ayers</span>
								</td>
							</tr>
						</tbody>
					</table>
				</section> -->
			</div>
		</div>
	</div>
	<?php

	return \ob_get_clean();
};
