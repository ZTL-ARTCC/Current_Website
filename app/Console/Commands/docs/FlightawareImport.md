## Command Usage Documentation: `Realops:LoadFromFlightaware`

First, you will need a FlightAware AeroAPI API key. Get a free key [here](https://www.flightaware.com/aeroapi/signup/personal).

Then, perform a dry run by running `Realops:LoadFromFlightaware` with the following **required environment variables:**

- `FLIGHTAWARE_START_DATE` set to the ISO8601 of when the event should start
- `FLIGHTAWARE_END_DATE` set to the ISO8601 of when the event should end
- `FLIGHTAWARE_MAX_FLIGHTS` set to the target flight count for the event

> [!IMPORTANT]  
> There are four important notes about this script:
> 1. Times will not be hit perfectly. The start date and end date are used as outer bounds. Flights will not be pulled before the start date, or after the end date. However, flights may not be pulled all the way up to the end date. The task will attempt to even out flights across the entire period by "chunking" it into smaller sections, determined by the event length and max flight count.
> 2. `FLIGHTAWARE_END_DATE` is the end bound for when *flights will be pulled*, not necessarily the end of the event. I recommend setting this to ~2 hours prior to the event to give pulled arrivals a chance to get to ATL.
> 3. **Times are push times, including for arrivals.** The time pulled by FlightAware is the **push time** of the aircraft, *even if it is an arrival.* Even if an arrival does not actually arrive at the airport by the end date, **it will still be pulled!** Keep this in mind when planning the event and using this script, and set the end date to some amount prior to the actual end of the event (I recommend ~1-2 hours, so there are still some departures). This is a limitaiton of the FlightAware API and we just need to compensate for it with our event planning.
> 4. The flight target will not be hit. The flight target is an absolute upper bound, and the script will never pull more than it, but it is very unlikely it will hit the flight target exactly. It will be up to 40 flights below that number.

Once you run the dry-run, you will see output like below.

![image](https://github.com/ZTL-ARTCC/Current_Website/assets/36366790/d2e687c0-df20-4a35-bcea-bde7845ad167)

> [!IMPORTANT]
> **Verify these three things:**
> 1. The target event duration (in seconds) is correct (item #1 on the image)
> 2. The actual event duration (in seconds) is within acceptable bounds (item #2 on the image)
> 3. The actual flight count is within acceptable bounds (item #3 on the image)

**If all of these are correct, proceed with the actual import.**


> [!WARNING]  
> The non-dryrun will contact flightaware, and use up API credits. Be careful, here be dragons (that like taking wallets)!

Set the same environment as before, but add the following additional variables:
- `FLIGHTAWARE_API_KEY` set to your AeroAPI key
- `FLIGHTAWARE_DRYRUN` set to **false**.


> [!WARNING]  
> The import task will take a very long time, depending on how many flights you wish to import. This is normal. **Do not stop the import process midway through.** Already downloaded flights will still be saved, but your API credits will still be used up and running the task again will not pick up where it left off.
