<?php
	set_time_limit(500);
	$xml = simplexml_load_file("600-immobili.xml", null, LIBXML_NOCDATA);
	$sxe = new SimpleXMLElement($xml->asXML());

	//echo '<pre>' . print_r($sxe->item, true) . '</pre>'; exit;

	for ($i = 600; $i < count($sxe); $i++) {

		if ($sxe->item[$i]->address_gmap != '') {

			$url = 'https://maps.google.com/maps/api/geocode/json?address='.str_replace(' ', '+', trim($sxe->item[$i]->address_gmap)).'&amp;key=AIzaSyA-ygC86dSuea1qHM1Jhcgs5w8HPqJdXcA&amp;region=Romania';

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
			$response = curl_exec($ch);
			curl_close($ch);
			$response_a = json_decode($response);

			echo $url . ' ' . '<pre>' . print_r($response_a->results, true) . '</pre>';

			$latitude = $response_a->results[0]->geometry->location->lat;
			$longitude = $response_a->results[0]->geometry->location->lng;

			$prepAdd = $sxe->item[$i];

			$prepAdd->addChild("latitude", $latitude);
			$prepAdd->addChild("longitude", $longitude);

		}

	}
	$sxe->asXML("all-immobili.xml");
