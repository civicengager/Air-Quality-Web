<?php

namespace AirQuality\Repositories;

/**
 * Fetches device info from a MariaDB database.
 */
class MariaDBDeviceRepository implements IDeviceRepository {
	public static $table_name = "devices";
	public static $column_device_id = "device_id";
	public static $column_device_name = "device_name";
	public static $column_device_type = "device_type";
	public static $column_owner_id = "owner_id";
	public static $column_lat = "device_latitude";
	public static $column_long = "device_longitude";
	public static $column_altitude = "device_altitude";
	
	public static $table_name_type = "device_types";
	public static $column_type_id = "device_type";
	public static $column_type_processor = "processor";
	public static $column_type_connection = "Connection";
	public static $column_type_particle_sensor = "particle_sensor";
	public static $column_type_temp_sensor = "temp_sensor";
	public static $column_type_power = "power";
	public static $column_type_software = "Software";
	public static $column_type_notes = "Other";

	
	
	// ------------------------------------------------------------------------
	
	/**
	 * The database connection.
	 * @var \AirQuality\Database
	 */
	private $database;
	
	/** Function that gets a static variable by it's name. Useful in preparing SQL queries. */
	private $get_static;
	
	function __construct(\AirQuality\Database $in_database) {
		$this->database = $in_database;
		
		$this->get_static = function($name) { return self::$$name; };
	}
	
	
	public function get_all_devices($only_with_location) {
		$s = $this->get_static;
		
		$sql = "SELECT
			{$s("column_device_id")} AS id,
			{$s("column_device_name")} AS name,
			{$s("column_lat")} AS latitude,
			{$s("column_long")} AS longitude,
			{$s("column_altitude")} AS altitude
		FROM {$s("table_name")}";
		
		if($only_with_location)
			$sql .= "\nWHERE
				{$s("column_lat")} IS NOT NULL
				AND {$s("column_long")} IS NOT NULL";
		
		$sql .= ";";
		
		return $this->database->query($sql)->fetchAll();
	}
	
	public function get_device_info_ext($device_id) {
		$s = $this->get_static;
		
		$query_result = $this->database->query(
			"SELECT
				{$s("table_name")}.{$s("column_device_id")} AS id,
				{$s("table_name")}.{$s("column_device_name")} AS name,
				{$s("table_name")}.{$s("column_lat")} AS latitude,
				{$s("table_name")}.{$s("column_long")} AS longitude,
				{$s("table_name")}.{$s("column_altitude")} AS altitude,
				{$s("table_name_type")}.*
			FROM {$s("table_name")}
			JOIN {$s("table_name_type")} ON
				{$s("table_name")}.{$s("column_device_type")} = {$s("table_name_type")}.{$s("column_type_id")}
			WHERE {$s("table_name")}.{$s("column_device_id")} = :device_id;", [
				"device_id" => $device_id
			]
		)->fetch(); // gets the next row from the query
		
		$result = [];
		foreach($query_result as $key => $value) {
			// Hack! Filter out some useless columns
			// We do this so that we can return everything we know about the 
			// device in a manner that means we don't need to alter this code 
			// if additional columns are added later.
			
			if($key == self::$column_type_id)
				continue;
			
			$result[strtolower($key)] = $value;
		}
		return $result;
	}
}
