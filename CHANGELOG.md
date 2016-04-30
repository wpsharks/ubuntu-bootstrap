## v160430

- Changing the default PHP installation directive (`CFG_INSTALL_PHP_VERSION`) to `7.0` (via PPA) instead of `custom`. The PPA is an official package and it can be upgraded easily using `apt-get` whereas `custom` (from source) cannot. The `custom` installer will remain for local testing and for future implementation; e.g., it is useful for beta versions of PHP installed from source.

- Adding new installation directive: `CFG_ENABLE_PHP_OPCACHE`. This defaults to `0` (off) whenever the installer is running on a VM; easier for developers. This directive can be enabled or disabled when the installer runs, or you can alter the `src/php/.ini` file (`opcache.enable = yes|no`) and restart PHP-FPM to change it after installation is complete.

- Adding new installation directive: `CFG_ENABLE_PHP_PHAR_READONLY`. This defaults to `0` (off) whenever the installer is running on a VM. This controls the default `phar.readonly` setting in `src/php/.ini`. For developers using tools that package PHAR files, this must be `0` (off) in order for those tools to work. On a production site, this should be `1` (on) for best security.

- Moving the `cachetool` utility into a setup routine of its own. This is installed a system dependency if at all possible, instead of being optional.

- Bumping minimum requirement for the WSC library to PHP v7.0.4+.

- Adding a master `CHANGELOG.md` file to repo root. That's what you're reading now :-)

- Bug fix. `restart-app-related-services;` See: #6

- Hostname-specific environment variables for WordPress project paths. See: #5
