# ConnectedHumber-Air-Quality-Interface

> The web interface and JSON api for the ConnectedHumber Air Quality Monitoring Project.

This project contains the web interface for the ConnectedHumber air Quality Monitoring system. It is composed of 2 parts:

 - A PHP-based JSON API server (entry point: api.php)
 - A Javascript client application that runs in the browser

The client-side browser application is powered by [Leaflet](https://leafletjs.com/).

## System Requirements
In order to run this program, you'll need the following:

 - Git
 - Bash (if on Windows, try [Git Bash](https://gitforwindows.org/)) - the build script is written in Bash
 - [composer](https://getcomposer.org/) - For the server-side packages
 - [Node.JS](https://nodejs.org/)
 - [npm](https://npmjs.org/) - comes with Node.JS - used for building the client-side code

## Getting Started
The client-side code requires building. Currently, no pre-built versions are available (though these can be provided upon request), so this must be done from source. A build script is available, however, which automates the process - as explained below.

### Building From Source
The build script ensures that everything it does will not go outside the current directory (i.e. all dependencies are installed locally).

To build from source, start off by running the `setup` and `setup-dev` build commands like this:

```bash
./build setup setup-dev
```

This will initialise any git submodules and install both the server-side and client-side dependencies. Once done, all you need to do is build the client-side code:

```bash
./build client
```

For development purposes, the `client-watch` command is available.

### Configuration
Some configuration must be done before the application is ready for use. The first time `api.php` is called from a browser, it will create a new blank configuration file at `data/settings.toml`, if it doesn't already exist. See the `settings.default.toml` file in this repository for a list of configurable settings, but do **not** edit `settings.default.toml`! Instead, enter your configuration details into `data/settings.toml`, which overrides `settings.default.toml`. In particular, you'll probably want to change the settings under the `[database]` header - but ensure you give the entire file a careful read.

## Notes
 - Readings are taken every 6 minutes as standard.

### Contributing
Contributions are welcome - feel free to [open an issue](https://github.com/ConnectedHumber/Air-Quality-Web/issues/new) or (even better) a [pull request](https://github.com/ConnectedHumber/Air-Quality-Web/compare).

The [issue tracker](https://github.com/ConnectedHumber/Air-Quality-Web/issues) is the place where all the tasks relating to the project are kept.

## License
This project is licensed under the _Mozilla Public License 2.0_. The full text of this license can be found in the [LICENSE](https://github.com/ConnectedHumber/Air-Quality-Web/blob/master/LICENSE) file of this repository, along with a helpful summary of what you can and can't do provided by GitHub.
