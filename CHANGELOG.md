## v160610

- Updating MIME types for compressed archives.
- Retain ability to alter `zend.assertions` at runtime.
- Nginx + WooCommerce uploads folder now automaticall guarded by Nginx/Apache.
- Removing `xmlrpc.php` from the list of forbidden locations in the Nginx config file. This is needed by JetPack for WordPress.

## v160501

- Now automatically disabling HSTS whenever the installer runs on a VM. Since browsers like Chrome will lock-onto `https://` only with this enabled, it was impossible to access any development tools over the `http://` protocol; e.g., MailHog. If you install this latest version of the Ubuntu Bootstrap on a `.vm` domain that you have used in the past, **be sure to follow these instructions**. See: <http://classically.me/blogs/how-clear-hsts-settings-major-browsers>. For instance, remove the `my.vm` or any other `.vm` domains from your HSTS cache. In Google Chrome you can do this from: `chrome://net-internals/#hsts`

- Adding [MailHog](https://github.com/mailhog/MailHog) installation directive: `CFG_INSTALL_MAILHOG`. For VMs, this is now on by default. Instead of installing Postfix, MailHog is now installed as a recommended default. With MailHog running, all outgoing mail sent via PHP scripts (regardless of destination) will instead be sent to a test mailbox that can be accessed in the sandbox UI here: <http://my.vm:8025>. **Note:** If you are unable to access that location, see the note above about clearing HSTS first. The <http://my.vm:8025> URL is available only over `http://`.

- Updating [README.md](README.md) after recent changes to the Ubuntu Bootstrap. Documented the new hostname-specific environment variables for WordPress theme/plugin mounts. Added a new optional step to the installation instructions that documents the installation of our root CA in order to avoid SSL certificate warnings.

## v160430

- Changing the default PHP installation directive (`CFG_INSTALL_PHP_VERSION`) to `7.0` (via PPA) instead of `custom`. The PPA is an official package and it can be upgraded easily using `apt-get` whereas `custom` (from source) cannot. The `custom` installer will remain for local testing and for future implementation; e.g., it is useful for beta versions of PHP installed from source.

- Adding new installation directive: `CFG_ENABLE_PHP_OPCACHE`. This defaults to `0` (off) whenever the installer is running on a VM; easier for developers. This directive can be enabled or disabled when the installer runs, or you can alter the `src/php/.ini` file (`opcache.enable = yes|no`) and restart PHP-FPM to change it after installation is complete.

- Adding new installation directive: `CFG_ENABLE_PHP_PHAR_READONLY`. This defaults to `0` (off) whenever the installer is running on a VM. This controls the default `phar.readonly` setting in `src/php/.ini`. For developers using tools that package PHAR files, this must be `0` (off) in order for those tools to work. On a production site, this should be `1` (on) for best security.

- Moving the `cachetool` utility into a setup routine of its own. This is installed a system dependency if at all possible, instead of being optional.

- Bumping minimum requirement for the WSC library to PHP v7.0.4+.

- Adding a master `CHANGELOG.md` file to repo root. That's what you're reading now :-)

- Bug fix. `restart-app-related-services;` See: #6

- Hostname-specific environment variables for WordPress project paths. See: #5
