"use strict";

import SmartSettings from 'smartsettings';
import NanoModal from 'nanomodal';

import Config from './Config.mjs';
import GetFromUrl from './Helpers/GetFromUrl.mjs';

class UI {
	constructor(in_config, in_map_manager) {
		this.config = in_config;
		this.map_manager = in_map_manager;
		
		this.ui_panel = new SmartSettings("Settings");
		// this.ui_panel.watch((event) => console.log(event));
	}
	
	async setup() {
		this.reading_types = JSON.parse(
			await GetFromUrl(`${this.config.api_root}?action=list-reading-types`)
		);
		
		this.ui_panel.loadConfig([
			{
				type: "range",
				name: "Heatmap Blob Radius",
				items: [
					0.001,		// min
					0.05,	// max
					Config.heatmap.blob_radius,		// initial value
					0.001	// step
				],
				callback: ((event) => {
					this.map_manager.heatmap.overlay_config.radius = parseFloat(event.target.value);
				}).bind(this)
			},
			{
				type: "select",
				name: "Reading Type",
				items: this.reading_types.map((type) => type.friendly_text),
				callback: ((event) => {
					let new_type = this.reading_types.find((type) => type.friendly_text == event.target.value).id;
					
					this.map_manager.heatmap.update_reading_type(new_type);
				}).bind(this)
			},
			{
				type: "button",
				name: `${Config.version}, built ${Config.build_date.toDateString()}`,
				callback: (async (_event) => {
					NanoModal(
						await GetFromUrl(`${Config.api_root}?action=changelog`)
					).show();
				})
			}
		]);
		this.ui_panel.setIndex("Reading Type", this.reading_types.findIndex((type) => type.id == "PM25"));
	}
}

export default UI;
