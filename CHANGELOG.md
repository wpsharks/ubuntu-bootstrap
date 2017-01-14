## v170114.11523

- Nginx adjustments that allow for larger PHP request headers sent by WP plugins; e.g., Jetpack.

## v170111.14775

- Bug fix. Incorrectly missing `php-fpm` is resource limit adjustments after recent changes. Fixed in this release.

## v170109.33193

- Enhancing Nginx + PHP configuration. Now allowing virtual `.php` files.

## v170109.18307

- Enhancing unattended upgrades configuration.
- Automatically reboot after unattended upgrades when/if necessary.
- Automatically purge unused dependencies after unattended upgrades; e.g., automatically remove unused kernels from the past to avoid these building up over time and resulting in 'no space left on device' as a result — which has been a problem on Ubuntu boxes for years now.

## v170108.5188

- Adding a default umask for SFTP interactions.

## v170104.56167

- Making PHP versions easier to maintain.
- Updating PHPCS and WP i18n Tools.

## v170103.75837

- Adding support for PHP v7.1 and making this the new default PHP version.
- Adding security checks for Comet Cache directories when running on Nginx.
- Bug fix. XDebug via PECL now requires PHP 5.5+. Not possible on PHP 5.4.x any longer.
- Adding `phpX-dev` package before attempting to install XDebug.
- Adding `WP_NGINX_CONFIG = done` to satisfy Comet Cache.

## v160914.56388

- Typos corrected in WP-dev README file.
- Bug fix. Initial WordPress Installation Guard was not backing down once WP was installed.

## v160914.42855

- New Bash function: `is-htpasswd-valid`
- New config param: `CFG_WORDPRESS_DEV_USERNAME`
- New config param: `CFG_WORDPRESS_DEV_PASSWORD`
- New config param: `CFG_WORDPRESS_DEV_NAME`
- New config param: `CFG_WORDPRESS_DEV_EMAIL`
- New config param: `CFG_WORDPRESS_DEV_PREFERRED_SHELL`
- New config param: `CFG_WORDPRESS_DEV_AUTHORIZED_SSH_KEYS`
- New WordPress Installation Guard for added developer security.
- Enhancing force-SSL handlers. Now considering `CF-Visitor` scheme for compatibility with CloudFlare Flexible SSL mode. Props @ [Ruby](http://jas.xyz/2cMrjtc).
- Updating the GitHub `hub` tool to v2.2.8.

## v160913.53816

- Enhancing CloudFlare IP whitelisting routine.
- Adding update routines for CloudFlare IP whitelisting.
- Reorganized and refactored bootstrap updater for production servers.
- Reorganized file and directory structure for WordPress dev containers.
- The `install-wp` utility now deletes and recreates the primary database.
- WordPress dev containers are now referenced with `phpXX-` or `phpXX.` (either/or).
- Improving `auto_prepend_file` in PHP configuration for better compatibility w/ CloudFlare and Nginx reverse proxy.

## v160913.32760

- Adding contributor SSH keys.
- New Bash function: `create-admin()`
- New binary in `$PATH`: `create-admin`
- New Bash function: `setup-admin()`
- New binary in `$PATH`: `setup-admin`
- New binary in `$PATH`: `install-wp`
- New binary in `$PATH`: `install-wp-dev`
- New binary in `$PATH`: `install-wp-symlinks`
- Bug fix. Trying to use `app` group before it exists.
- Enhancing `is-dev()` function; better detection now.

## v160913.22912

- New WordPress Docker containers that run multiple versions of PHP on sub-domains.
- New config parameter: `CFG_INSTALL_WORDPRESS_DEV_CONTAINERS`
- New config parameter: `CFG_FIREWALL_ALLOWS_MYSQL_VIA_3306`
- New config parameter: `CFG_INSTALL_SAMI`
- New config parameter: `CFG_MYSQL_SSL_KEY`
- New config parameter: `CFG_MYSQL_SSL_CRT`
- New config parameter: `CFG_MYSQL_SSL_CA`
- New config parameter: `CFG_MYSQL_SSL_CIPHER`
- New config parameter: `CFG_MYSQL_X_DB_USERNAME`
- New config parameter: `CFG_MYSQL_X_DB_PASSWORD`
- New config parameter: `CFG_MYSQL_X_REQUIRES_SSL`
- New Bash function: `is-dev()`
- New Bash function: `docker-bridge-ip()`
- SSH security hardening.

## v160911.84308

- Adding OpenVPN firewall rules for DigitalOcean Droplets that use a floating IP. This makes it possible for the OpenVPN to take advantage of the floating IP and use it instead of the local public IP that comes with each Droplet. The effect is that when you're connected to the VPN, you appear to have the floating (static) IP. Props to [Programster](http://blog.programster.org/openvpn-digitalocean-ip-alias/).

## v160911.40710

- Adding OpenVPN server as an installation option.
- Adding Psysh (PHP interactive debugger) as a new installation option.
- Adding new options: `CFG_ADMIN_STATIC_IP_ADDRESS`, `CFG_FIREWALL_ALLOWS_ADMIN_ONLY_VIA_22`.
- Bug fix. Initial upgrade sometimes requires an attended installation. Now using `Dpkg::Options::=--force-confnew` to avoid this.
- Bug fix. Firewall rules now declare `proto tcp` in all appropriate cases, even when using `to any` declarations.
- Adding a new/improved default `secure_path` when using `sudo` to make `sudo` more intuitive.

## v160821.17673

- Bug fix. Some MySQL config options were not overwriting upstream defaults.
- Bug fix. Do not require SSL for external connections to a MySQL server running on a VM.

## v160821.9818

- Improving mounts in `Vagrantfile` by now mounting both `/app` and `/app/src` as their own directories instead of these being symlinks as seen in previous releases. This makes it easier to deal with path mapping in various applications and it reduces confusion by making the paths to these important locations shorter/simpler.

- Moved WordPress installation scripts into `src/wordpress/install` and `src/wordpress/install-symlinks` — making them more portable. This way they can also be called from outside of the bootstrap installer when necessary.

- Moved VM `/wordpress` mount (for symlinks) to `/wp-projects` instead. This should not have any impact on existing WordPress symlink customizations via `~/.profile` though, as it only impacts the location of the mount on the VM — not in your local file system. The reason for this change was to make room for WordPress itself, which may eventually live under `/wordpress` (on the VM) at some point in a future release of the WebSharks Ubuntu Bootstrap. The README for this repo has been updated to reflect this new location.

- Improving the default WP-CLI configuration file template. It now defines `--user`, which is set to the current user's username, instead of always the default `admin` value as before. This means that when you use WP-CLI with the default configuration file template created by the WUB, you're logged into WordPress as the user you SSH into the VM with. However, there are exceptions. If you log into the VM as user `vagrant` (e.g., `$ vagrant ssh`) or as `root` (e.g., `$ sudo ...`), a default user of `admin` still applies as before. The reason for this change is that it now makes it possible to create a user account on the VM, SSH into the VM as that user, and use WP-CLI as that matching user in WordPress, by default. This works so long as the user you're logged into the VM with is not `vagrant` or `root`. Not necessarily recommended, but now possible :-)

- Bug fix. PHP error log directory was not writable under some scenarios. Fixed in this release.

## v160820.46840

- Adding support for XDebug in PHP.

## v160628.57417

- Enhancing 'Packaging Mode' for custom Boxes built on top of the WUB.
- **New Wiki Article:** https://github.com/websharks/ubuntu-bootstrap/wiki/Packaging-a-Custom-Box

## v160628.31873

- Enhancing Nginx config files with respect to WordPress.
- Adding [instructions for WP multisite on Nginx](https://github.com/websharks/ubuntu-bootstrap/wiki/WordPress-Multisite-on-Nginx).

## v160628.24514

- Updating to latest `phings` dev branch.
- Integrating with `phings` so that `CFG_VERSION` is bumped automatically.

## v160628.22003

- Branch rename; i.e., `000000-dev` became `dev`.
- Drop dependencies from local bootstrap. See: #14
- Adding `phings` submodule. Project now built with `websharks/phings`.
- Remove `WP_CACHE` from default `wp-config.php` template file. See: #16
- Copy `.wp-config.php` into place instead of creating a symlink. See: #15
- New configuration option: `CFG_WEB_SERVER_SSL_ONLY` defaults to being off when the WUB is used together with Vagrant. When this is off it allows for a VM to be accessed over `http://` or `https://` and HSTS is completely disabled also. Props at @raamdev ~ See: https://github.com/websharks/ubuntu-bootstrap/issues/11

## 160622

- Adding `proxies.conf` to Nginx configuration in support of proxy configurations and proxy caching.
- Adding `proxy-xar-locations.conf` in support of `/software/` proxies via CDN.

## 160619

- Adding support for `vagrant-hostsupdater` as an alternative to Landrush for Vagrant. See [this README section](https://github.com/websharks/ubuntu-bootstrap#step-1-satisfy-software-requirements) for details.
- Adding support for no DNS plugin at all, which results in a static IP that you can add to `/etc/hosts` if you don't want to run Landrush. See [this README section](https://github.com/websharks/ubuntu-bootstrap#step-1-satisfy-software-requirements) for details.
- Adding a welcome message for new users that instructs them to run `vagrant ssh` and `sudo /bootstrap/src/installer` after they do a `vagrant up`.
- Improving the `Vagrantfile`; i.e., making it a little more dynamic, and more compatible with a variety of DNS plugins for Vagrant.
- Moving internal `/etc/bootstrap/.installed` flag to `/bootstrap/.installed-version` so the `Vagrantfile` can read this also.
- Adding `systemctl enable phpX.X-fpm` calls to make sure PHP-FPM always restarts automatically on reboot in Ubuntu XX.
- Enhancing package-related wrappers that deal with directory symlinks (internally).
- Adding `gnupg2` utility; called as `gpg2` from command-line.
- Updating `sharefile` utility. Now using `gpg2`.
- Enhancing `/bootstrap/src/transfer/export-data`. Excluding `mysql` database with permissions. Not necessary to export this DB.

## v160616

This release includes a LOT of changes. Please read carefully before upgrading. Having said that, the installation and bootstrap installer workflow remain the same as they were. So the way in which you _use_ the WUBS hasn't changed, but reviewing the details below is still important.

### Highlights

- **Ubuntu XX:** Upgraded from Trusty to 16.04 LTS ([Xenial Xersus](https://wiki.ubuntu.com/XenialXerus/ReleaseNotes)). See [what's new in Xenial](https://www.digitalocean.com/community/tutorials/what-s-new-in-ubuntu-16-04).
- **Nginx/Apache** config files have both been completely restructured for easier maintenance and greater flexibility. Apache config files are now broken down by context (e.g., main, http, server) making them easier to maintain alongside Nginx.
- **Systemd:** Upstart scripts converted to Systemd for forward compatibility and [easier maintenance](https://wiki.ubuntu.com/SystemdForUpstartUsers).
- **Apache + HTTP/2:** The Apache web server is now installed with experimental HTTP/2 support enabled by default. Latest Apache installed via PPA—from the same author behind PHP packages. Thank you Ondřej Surý.
- **OpenSSL:** Upgrading to latest OpenSSL for ALPN extension compatibility in Google Chrome with respect to HTTP/2 support being enabled in both Nginx & Apache now.

_Additional detailed changes listed below..._

### **IMPORTANT:** New System Requirements!

- Requires the _very_ latest version of VirtualBox:

  ```bash
  $ brew cask install virtualbox # upgrades you to latest.
  ```

- Requires the _very_ latest version of Vagrant:

  ```bash
  $ brew cask install vagrant # upgrades you to latest.
  ```

- **New Vagrant Box:** (Xenial Box by WebSharks):

  ```bash
  $ vagrant box add websharks/ubuntu-xenial64 # by @jaswsinc
  ```

- Latest Vagrant update requires a reinstall of all plugins following these instructions:

  _It's important that you reinstall all plugins as the latest version of Vagrant may choke otherwise. I encountered errors when I tried skipping this. Don't skip this :-)_

  ```bash
  $ rm -rf ~/.vagrant.d/gems # Out w/ the old!
  $ rm ~/.vagrant.d/plugins.json # Same here.

  $ vagrant plugin install landrush # In with the new.
  $ vagrant plugin install vagrant-triggers # optional (recommended).
  $ vagrant plugin install vagrant-cachier # optional (no longer used by the WUBS).
  ```

_With those dependencies satisfied, you shouldn't have any trouble with the latest WUBS._

### Nginx Changes

- **Nginx:** Upgraded to latest release of Nginx for Ubuntu XX, built with OpenSSL that supports the ALPN extension for Google Chrome compatibility. See: <https://news.ycombinator.com/item?id=11541017>
- **Nginx:** Reorganizing Nginx configuration files for easier maintenance and greater flexibility. _This is a **significant** change in the structure of the Nginx configuration files. See: `/src/nginx` if you'd like to review._
- **Nginx:** Exposing system-wide environment variables to the Nginx config file.
- **Nginx:** Removing static file extension checks before PHP fallback to `index.php`. This allows for **all** virtual requests not satisfied by Nginx to be handled by PHP now (e.g., WordPress, Drupal).

### Apache Changes

- **Apache:** Upgrading from Apache v2.4.x to 2.4.x.
- **Apache:** Adding `http2` module to Apache configuration.
- **Apache:** Adding `libtool` and `apache2-dev` to Apache install routines.
- **Apache:** Integrating with the `http2` module for Apache. HTTP/2 now the default.
- **Apache:** Reorganizing Apache configuration files for easier maintenance and greater flexibility. _This is a **significant** change in the structure of the Apache configuration files. See: `/src/apache` if you'd like to review._
- **Apache:** Adding `mod_cloudflare` to Apache configuration in support of `CFG_FIREWALL_ALLOWS_CF_ONLY_VIA_80_443`.
- **Apache:** Adding automatic CORs headers to Apache configuration.
- **Apache:** Adding `CustomLog` directive and conditional logging via `!dontlog`.
- **Apache:** Adding automatic `content-encoding` headers for `svgz`, `gz`, and `tgz` MIME types.
- **Apache:** Removing explicit `vary: accept-encoding` header in conjuction with `mod_deflate` because DEFLATE already handles this.
- **Apache:** Achieving PFS (perfect forward secrecy) in Apache via `DHParameters` now that it's possible in the latest Apache release.
- **Apache:** Updating to the latest [modern SSL ciphers](https://mozilla.github.io/server-side-tls/ssl-config-generator/) recommended by Mozilla.
- **Apache:** Removing support for broken TLSv1 in `https://` server.
- **Apache:** Adding MIME type: `application/x-php-source` with extensions `x-php`, `phps`.
- **Apache:** Adding new `LogFormat` variations for future implementation.
- **Apache:** Adding built-in security check for `wp-content/uploads/woocommerce-uploads`.
- **Apache:** Adding automatic `x-content-type-options nosniff` header.
- **Apache:** Adding support for several new MIME types to bring the Apache configuration up-to-date with the Nginx configuration.
- **Apache:** Ditching `mod_fastcgi` in favor of `mod_proxy`, `mod_proxy_fcgi` and unix sockets for easier FastCGI configuration.
- **Apache:** Automatically disabling `access.log` for `robots.txt` and `favicon.ico`.
- ... and other changes that bring Apache up-to-date with our Nginx configuration.

### Changes Common to Both Nginx/Apache

- **Nginx/Apache:** Changing MIME type `application/x-javascript` to `application/javascript`.
- **Nginx/Apache:** Disabling SSL stapling since all certificates generated by the WUBS are self-signed. If you use the WUBS on a live site (perfectly OK), but be sure to use CloudFlare or another proxy instead of depending on real SSL certificates.

### PHP Changes

- **PHP:** Removing support for `custom` and/or `custom-src` PHP installation from source. Choices are now limited to: `7.0`, `5.6`, or `5.5` via one PPA instead of three.
- **PHP:** `ln --symbolic /usr/sbin/php-fpm7.0 /usr/sbin/php7.0-fpm` so that all PHP versions match-up in terms of their file/directory structure.
- **PHP:** Restructuring PHP-FPM configuration files, including FastCGI params, which are now 100% configured by the WUBS for greater flexibility and less dependence on upstream maintainers.
- **PHP:** OPcache now enabled by default on VMs also. However, on a VM the `opcache.revalidate_freq` is now forced to `0` so that stat checks occur on every request; i.e., to avoid confusion when working in a development environment where file changes are expected to apply on the next request—not several seconds later!

### WebSharks Core Changes

- **WebSharks Core:** Moving `/usr/local/src/wsc` to `/usr/local/src/websharks-core`.

### MailHog Changes

- **MailHog:** Converted Upstart script to Systemd for Ubuntu Xenial compatibility.

### Firewall Changes

- **Firewall:** SSH port 22 is now wide open for VMs instead of being limited to 6 connections every 30 seconds. It came to my attention that Vagrant itself is known for making repeated connections on a `vagrant up` following a `vagrant halt`. This was, at times, triggering the firewall limitation, which was really only intended as a way to experiment with what is otherwise applied to a live production server only. Props at @raamdev for feedback that led to this discovery.

  _Here is the relevant excerpt to help illustrate this change:_

  ```bash
  if is-vm; then
    ufw allow 22/tcp; # No limitations.
  else ufw limit 22/tcp; fi; # SSH, SFTP, Git, etc.
  # limit = max of 6 connections every 30 seconds.
  # See: `/lib/ufw/user.rules` for details.
  ```

### Vagrant Plugin Changes

- **Vagrant:** Dropping support for `vagrant-cachier` plugin as it has compatibility issues with Ubuntu Xenial and is generally problematic. The benefits gained from using this were nice, but given the dynamic nature of the WUBS installer it tends to get in the way more than anything else.

### Bash Library & Zsh Shell Changes

- **Bash/Zsh:** Updating `trim()`. Now accepts a second argument with specific chars to trim.
- **Bash/Zsh:** Updating `sharefile()`. Now accepts `symmetric` keyword in second argument to indicate that you want to share a file using a symmetric key instead of a personal GPG key. This allows a server to share files via transfer.sh with other servers; e.g., to export data from one server to another one as discussed in the next changelog item.
- **Bash/Zsh:** Two new utilities: `/bootstrap/src/transfer/export-data` and `import-data`. Running the `export-data` utility will export `/app`, `/repos`, and a dump of all MySQL databases, and then GZIP the data and upload it via transfer.sh, giving you back a URL leading to the `.gz` file. From another server you can run `import-data [URL to .gz file]` in order to import and restore this data on another server instance easily. Permissions, directory structure, and ownership are all preserved during export/import.

  _**Note:** These commands must be run interactively. Each command asks for a symmetric key that can be used to encrypt the data for safe transfer. For instance, running `export-data` will ask you to enter a secret key and confirm it. Running `import-data [URL to .gz file]` will ask you for the password used when the export was performed; i.e., the password you entered when running `export-data`. The underlying encryption is performed with GPG using AES256 encryption with a symmetric key you supply._

  _**Note:** Files stored temporarily by transfer.sh expire after 14 days. Max size: 10GB._
- **Bash/Zsh:** Removing support for installer option: `--CFG_INSTALL_MYSQL_DB_IMPORT_FILE`, in favor of the new `export-data` and `import-data` utilities.

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
