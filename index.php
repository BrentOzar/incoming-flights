<?php

require( "env.php" );

class FlightAware {

	private $api_url = "flightxml.flightaware.com";

	public function __construct( $user, $key ) {
		$this->api_url = "http://$user:$key@" . $this->api_url;
	}

	public function createApiUrl( $endpoint ) {
		return $this->api_url . $endpoint;
	}

	public function get( $limit = 15 ) {

		$endpoint = "/json/FlightXML3/AirportBoards?airport_code=SAN&type=enroute&howMany=$limit";

		set_error_handler(
			create_function(
				'$severity, $message, $file, $line',
				'throw new ErrorException($message, $severity, $severity, $file, $line);'
			)
		);

		$result;
		try {
			$url = $this->createApiUrl( $endpoint );
			$data = file_get_contents( $url );
			$result = [
				"success" => TRUE,
				"data" => json_decode( $data ),
			];
		} catch ( Exception $error ) {
			$result = [
				"error" => $error->getMessage(),
			];
		}

		restore_error_handler();

		return $result;
	}
}

$flightaware = new FlightAware( FLIGHTAWARE_API_USER, FLIGHTAWARE_API_KEY );

?>

<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<title>Incoming Flights</title>
	<link rel="stylesheet" type="text/css" href="flapper.css" integrity="sha384-e+o4G5Lf7My/ANtIN2anXbiK6z84BZ4x9e4uYINqMJY59ov8pLXKvw53BiywnYlY" crossorigin="anonymous">
	<style>

		.flight {
			margin: 20px;
		}

		.error {
			font-weight: bold;
			font-size: 2rem;
		}

		.flapper {
			display: inline-block;
			margin-right: 35px;
		}

		.a,
		.b {
			display: inline-block;
		},
		body {
			background-color: #000000;
		}

	</style>
	<meta http-equiv="refresh" content="600">
</head>
<body style="background-color:#000000">

	<div id="flights">
		<div class="col a"></div>
		<div class="col b"></div>
	</div>

	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT" crossorigin="anonymous"></script>
	<script src="jquery.transform-0.9.3.min.js" integrity="sha384-RsG7DLaROGayTExLelOG8drfmFO0tFrDL+0Z5XpNu2vCaZkmvCH1LSoPh7NnYaEx" crossorigin="anonymous"></script>
	<script src="jquery.flapper.js" integrity="sha384-be79bmdVRG3Jgczr9zr02D/lbWUffODFCgMesNC4zD7l/llSSDA1Pjv58GK4fMBY" crossorigin="anonymous"></script>
	<script>

		const digit_size = "L"; // Change this to resize flapper ( XS, S, M, L, XL, XXL )
		const parent_el = $( "#flights" );
		const flight_data = <?php echo json_encode( $flightaware->get( 3 ) ); ?>;

		if ( flight_data.error ) {
			let message = "Error Retrieving Flights";
			if ( flight_data.error )
				message += "<br>" + flight_data.error;
			parent_el.html( '<div class="error">'+ message +'</div>' );
		}
		else {
			flights();
		}

		function flights() {
			
			flight_data.data.AirportBoardsResult.enroute.flights.forEach( ( flight, index ) => {

				parent_el.find( ".a" ).append( '<div class="flight" data-flight="'+ index +'"></div>' );
				parent_el.find( ".b" ).append( '<div class="flight" data-flight="'+ index +'"></div>' );

				let flight_container_a = parent_el.find( '.a div[data-flight="'+ index +'"]' );
					flight_container_a.append( '<input class="'+ digit_size +' ident"><br>' );
					flight_container_a.append( '<input class="'+ digit_size +' origin_code">' );
					flight_container_a.append( '<input class="'+ digit_size +' destination_code">' );

				let flight_container_b = parent_el.find( '.b div[data-flight="'+ index +'"]' );
					flight_container_b.append( '<input class="'+ digit_size +' aircrafttype"><br>' );
					flight_container_b.append( '<input class="'+ digit_size +' estimated_arrival_time">' );

				let ident = flight.ident || "???????";
				let aircrafttype = flight.aircrafttype || "????";
				let origin_code = ( flight.origin && flight.origin.code ) ? flight.origin.code.substr( 1 ) : "???";
				let destination_code = ( flight.destination && flight.destination.code ) ? flight.destination.code.substr( 1 ) : "???";
				let estimated_arrival_time = ( flight.estimated_arrival_time && flight.estimated_arrival_time.time ) ? flight.estimated_arrival_time.time : "???????";
				
				// column a

				let ident_el = flight_container_a.find( "input.ident" );
				console.log( "ident_el", ident_el );
				ident_el
					.flapper({ width: ident.length })
					.val( ident ).change();

				let origin_code_el = flight_container_a.find( "input.origin_code" );
				console.log( "origin_code_el", origin_code_el );
				origin_code_el
					.flapper({ width: origin_code.length })
					.val( origin_code ).change();

				let destination_code_el = flight_container_a.find( "input.destination_code" );
				console.log( "destination_code_el", destination_code_el );
				destination_code_el
					.wrap( '<div style="float: right"></div>' )
					.flapper({ width: destination_code.length })
					.val( destination_code ).change();

				// column b

				let aircrafttype_el = flight_container_b.find( "input.aircrafttype" );
				console.log( "aircrafttype_el", aircrafttype_el );
				aircrafttype_el
					.flapper({ width: aircrafttype.length })
					.val( aircrafttype ).change();

				let estimated_arrival_time_el = flight_container_b.find( "input.estimated_arrival_time" );
				console.log( "estimated_arrival_time_el", estimated_arrival_time_el );
				estimated_arrival_time_el
					.flapper({ width: estimated_arrival_time.length })
					.val( estimated_arrival_time ).change();

			});
		}

	</script>

</body>
</html>