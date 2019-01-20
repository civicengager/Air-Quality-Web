<?php

namespace AirQuality\Actions;

use \SBRL\TomlConfig;
use \AirQuality\Repositories\IMeasurementTypeRepository;
use \AirQuality\ApiResponseSender;

use \AirQuality\PerfFormatter;

class ListReadingTypes implements IAction {
	/** @var TomlConfig */
	private $settings;
	/** @var IMeasurementTypeRepository */
	private $types_repo;
	
	/** @var ApiResponseSender */
	private $sender;
	
	public function __construct(
		TomlConfig $in_settings,
		IMeasurementTypeRepository $in_types_repo,
		ApiResponseSender $in_sender) {
		$this->settings = $in_settings;
		$this->types_repo = $in_types_repo;
		$this->sender = $in_sender;
	}
	
	public function handle() : bool {
		global $start_time;
		
		$start_handle = microtime(true);
		
		// 1: Parse & validate parameters
		
		// 1: Pull data from database
		$data = $this->types_repo->get_all_types();
		
		// 1.5: Validate data from database
		if(empty($data)) {
			http_response_code(404);
			header("content-type: text/plain");
			header("x-time-taken: " . PerfFormatter::format_perf_data($start_time, $start_handle, null));
			echo("Error: No types are currently present in the system.");
			return false;
		}
		
		// 3: Serialise data
		$start_encode = microtime(true);
		$response = json_encode($data);
		
		// 4: Send response
		
		// Don't a cache control header, because new types might get added at any time
		// TODO: Investigate adding a short-term (~10mins?) cache-control header here
		
		header("content-length: " . strlen($response));
		header("content-type: application/json");
		header("x-time-taken: " . PerfFormatter::format_perf_data($start_time, $start_handle, $start_encode));
		echo($response);
		return true;
	}
}