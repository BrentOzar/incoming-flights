# incoming-flights
Display an airport's incoming flights with the FlightAware v3 API and Flapper split-flap display simulator.

![Incoming Flights Example](/demo/incoming-flights-487.gif?raw=true "Incoming Flights in Action")

That's just an animated gif - <a href="https://ozar.me/incomingflights/">here is the actual animation.</a> Note that the flights aren't up to date - it's just a static example.

Requires a <a href="https://flightaware.com/commercial/flightxml/v3/pricing.rvt">FlightAware v3 API account.</a> As of 2018-09, that's up to 500 queries per month free (one update every ~90 minutes), or an update every 18 minutes for $13/mo, or every 2 minutes for $50/mo.

Built with <a href="https://github.com/jayKayEss/Flapper">Flapper</a> for the gorgeous split-flap display simulator. Built by <a href="https://www.upwork.com/o/profiles/users/_~011526af5db8ffa2d8">David R. on Upwork</a> - highly recommended for web projects.

## Setup

<a href="https://flightaware.com/account/join/">Register for a FlightAware account</a>, then <a href="https://flightaware.com/commercial/flightxml/v3/pricing.rvt">sign up for v3 API access.</a> You can use the free tier, but you'll be capped at 500 responses per month. If you choose a paid tier, configure your account so that it doesn't automatically upgrade to the higher tier - otherwise, when someone finds your web page, they might just leave it open and rack up a bill on your card.

Copy the env.php.sample file to env.php and edit it. FLIGHTAWARE API USER is your FlightAware username, and FLIGHTAWARE API KEY is the API key you were assigned during API registration. Keep those secret since someone else could rack up a heck of a bill with your account.

To set your airport, edit the index.php file. Look for this line:

    $endpoint = "/json/FlightXML3/AirportBoards?airport_code=SAN&type=enroute&howMany=$limit";

And change the SAN airport code to your own airport code.

Upload the files to your web server. You probably don't want them in a publicly accessible place since any visitor can exhaust your API request limit.

Leave the web page open, and it'll automatically refresh itself every 10 minutes with the 3 closest flights to your airport.

## Issues? Requests?

Incoming-flights works good enough for me, so before you file an issue or complaint, be ready to make the necessary code changes yourself. I'm sharing it here for folks to play with, but unfortunately I'm not able to do support. I welcome code contributions though.


## License

Incoming-flights is licensed under the <a href="https://github.com/BrentOzar/incoming-flights/blob/master/LICENSE">MIT License</a>, as is <a href="https://github.com/jayKayEss/Flapper">Flapper.</a>