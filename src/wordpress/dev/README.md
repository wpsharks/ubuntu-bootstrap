## Floating IP

`%%do_floating_ip%%` (%%do_region%%)
`ws-droplet-%%CFG_HOST%%`

This is a dedicated IP address that will survive your server being rebuilt. So it's OK to reference your server by this IP address in code or in configuration preferences. Or, if you prefer, you can use `ws-droplet-%%CFG_HOST%%`, which points to your Floating IP by name.

## SSH Access

Log in via SSH or SFTP. SSH key required.

Host: `ws-droplet-%%CFG_HOST%%` Port: `22`
Username: `%%CFG_WORDPRESS_DEV_USERNAME%%` Password: `%%CFG_WORDPRESS_DEV_PASSWORD%%`

_**Note:** If using FileZilla, you'll need to import your private SSH key. Your public SSH key is already on the server. See: <https://wiki.filezilla-project.org/Howto>_

_**Tip:** When you log in via SFTP you'll arrive at your home directory on the server. To edit WordPress installation files, navigate to `/app/src` (document root)._

### Change SSH Password (Recommended)

Login in via SSH and run these two commands to change your system password to something that's easier to remember. _**Note:** On this server, `sudo` requires your password; so changing your password is helpful if you plan to work from SSH for any length of time._

```bash
$ ssh ws-droplet-%%CFG_HOST%%
$ passwd # Enter old and new password.
```

The same password is used to access web-based tools. So please run `htpasswd` also. _**Note:** This command itself requires `sudo`. You will be asked to enter your new password just to run this command, and then again when the command actually runs._

```bash
$ sudo htpasswd /etc/bootstrap/passwds/.tools %%CFG_WORDPRESS_DEV_USERNAME%% # Enter new password to change.
```

### Becoming Root Using `sudo`

```bash
$ sudo [command] [args]
```

Or you can log in as root by typing `$ sudo -i` and entering your personal password to authenticate.

## WordPress Installation

**Note:** WordPress is already installed and configured, but the web-based installer still needs to be run for the first time in a browser. As a security precaution, the first time you access the web-based installer, **authentication is required by the WordPress Installation Guard**. This prevents someone from finding your site and installing it themselves.

### WordPress Installation Guard Key

Username: `[anything]` (does not matter)
Password: `%%CFG_MAINTENANCE_BYPASS_KEY%%`

- <https://%%CFG_HOST%%>
  - Database name: `%%CFG_MYSQL_DB_NAME%%`
  - Nginx doc root: `/app/src`
  - Choice of `http://` or `https://`
  - In `WP_DEBUG` mode.
  - PHP v%%CFG_INSTALL_PHP_VERSION%%

## WordPress Dev Container Installs

**Note:** WordPress is already installed and configured, but the web-based installers still need to be run for the first time in a browser. As a security precaution, the first time you access the web-based installer, **authentication is required by the WordPress Installation Guard**. This prevents someone from finding your site and installing it themselves.

### WordPress Installation Guard Key

Username: `[anything]` (does not matter)
Password: `%%CFG_MAINTENANCE_BYPASS_KEY%%` (same as above)

- <https://php54-%%CFG_HOST%%>
  - Database name: `%%CFG_MYSQL_DB_NAME%%_php54`
  - Apache doc root: `/app-dev/php5.4/src`
  - Choice of `http://` or `https://`
  - In `WP_DEBUG` mode.

- <https://php55-%%CFG_HOST%%>
  - Database name: `%%CFG_MYSQL_DB_NAME%%_php55`
  - Apache doc root: `/app-dev/php5.5/src`
  - Choice of `http://` or `https://`
  - In `WP_DEBUG` mode.

- <https://php56-%%CFG_HOST%%>
  - Database name: `%%CFG_MYSQL_DB_NAME%%_php56`
  - Apache doc root: `/app-dev/php5.6/src`
  - Choice of `http://` or `https://`
  - In `WP_DEBUG` mode.

- <https://php70-%%CFG_HOST%%>
  - Database name: `%%CFG_MYSQL_DB_NAME%%_php70`
  - Apache doc root: `/app-dev/php7.0/src`
  - Choice of `http://` or `https://`
  - In `WP_DEBUG` mode.

- <https://php71-%%CFG_HOST%%>
  - Database name: `%%CFG_MYSQL_DB_NAME%%_php70`
  - Apache doc root: `/app-dev/php7.1/src`
  - Choice of `http://` or `https://`
  - In `WP_DEBUG` mode.

## Command-Line Utilities

Your server has several utilities already installed. Here are a few that you might find useful.

- `php`
- `composer`
- `mail` (see: man mail)
- `git` (already configured)
- `hub` (already aliased as `git`)
- `wp` (WP-CLI, already configured)
- `psysh` (already configured)
- `docker` (requires `sudo`)
- `casperjs` (build tests)

### WordPress Reinstaller

WordPress is already installed on all of these sites, but if you screw something up or would like to start fresh for any reason, you can run the following command via SSH.

```bash
$ sudo install-wp
```

_**Warning:** This will delete MySQL `db0` and all files in `/app/src`. Then it will recreate everything it deleted and reinstall WordPress so you can start fresh. Before running this command be sure to save anything that you must preserve._

### WordPress Dev Container Reinstaller

Your server is also running Docker. Each of the PHP version-specific installations run inside Docker containers. These containers can also be wiped out easily and rebuilt so that you can start fresh if you'd like.

```bash
$ sudo install-wp-dev 5.4
$ sudo install-wp-dev 5.5
$ sudo install-wp-dev 5.6
$ sudo install-wp-dev 7.0
$ sudo install-wp-dev 7.1
```

**Warning:** This will delete MySQL `db0_phpXX` and all files in `/app-dev/phpX.X/src`. Then it will recreate everything it deleted and reinstall WordPress so you can start fresh. Before running this command be sure to save anything that you must preserve.

#### Logging Into a Docker Dev Container

If you need to access a WordPress version-specific container, you can log in via SSH like this.

```bash
$ sudo docker exec -it app-dev-php5.4 bash
# You will enter a sub-shell for this container.
# You can type `exit` (or Ctrl-C) to leave the sub-shell.
```

_**Note:** These containers don't have much installed other that what's needed to run WordPress. Even things like `vi` are not available here. However, you can install tools using `apt-get`. e.g., `apt-get update && apt-get install vim`_

## Web-Based Tools

### HTTP Authentication

All web-based tools require an initial HTTP authentication.

Username / Password: `[use your SSH credentials]`

### PhpMyAdmin (PMA)

<https://%%CFG_HOST%%/---tools/pma/>

Database Username: `%%CFG_MYSQL_DB_USERNAME%%`
Database Password: `%%CFG_MYSQL_DB_PASSWORD%%`

_**Note:** This provides access to all databases, including those for dev containers._

### PHP Info

https://%%CFG_HOST%%/---tools/info.php
https://php54-%%CFG_HOST%%/---tools/info.php
https://php55-%%CFG_HOST%%/---tools/info.php
https://php56-%%CFG_HOST%%/---tools/info.php
https://php70-%%CFG_HOST%%/---tools/info.php

_**Warning:** PHP info is password protected because your server has environment variables that contain sensitive information. PHP displays them via Superglobals when you run `phpinfo()`. So please be advised that you should only use these PHP info pages that are protected; i.e., don't create your own PHP info pages and make them public, because PHP info pages reveal private details._

### OPCache Dump

https://%%CFG_HOST%%/---tools/opcache.php

_Applies to your main site only. Other PHP version-specific sites are not running OPcache._

### Nginx + PHP Process Manager (FPM)

https://%%CFG_HOST%%/---tools/status.nginx
https://%%CFG_HOST%%/---tools/fpm-status.php

_Applies to your main site only. Other PHP version-specific sites run Apache._

## OpenVPN (Tunnelblick or Viscosity)

A Virtual Private Network (VPN). Learn more: http://jas.xyz/2cKxP3Q

The most important thing you need to know about a VPN is that it secures your computer's Internet connection to guarantee that all of the data you're sending and receiving is encrypted and secured from prying eyes.

Your server is running an instance of OpenVPN. It has been configured in such a way that you can connect securely and browse the web with a permanent static IP address. You simply need a VPN client software application to use this. I suggest [Tunnelblick](https://tunnelblick.net/), [Pritunl](https://client.pritunl.com/), or [Viscosity](https://www.sparklabs.com/viscosity/).

```bash
$ brew cask install tunnelblick
# Or: brew cask install pritunl
# Or: brew cask install viscosity
```

- Open Tunnelblick, Pritunl, or Viscosity and import the `.ovpn` file attached to this document. If you're using Tunnelblick you can just double-click to open the `.ovpn` file. If you're using Pritunl or Viscosity, open the application and choose to import the `.ovpn` connection file.
- Connect to the VPN by choosing 'connect' in either application.
- When asked for a username/password, use your SSH credentials.
- Confirm that your IP address has changed to the floating ip `%%do_floating_ip%%`.
  - Try: <https://www.google.com/search?q=my+ip>

### VPN Bandwidth Limitations

You can use up to 1TB of data each month. In other words, there is no restriction. If you somehow use more than 1TB of data (not likely), what will happen is unknown. At this time, DigitalOcean does not charge for bandwidth overage. They will probably just throttle your connection or contact Jason.

### VPN Public DNS by Google

When you're connected to the VPN, all of your local DNS resolution occurs through Google's public DNS servers. These will likely offer you better speeds, better consistency, and improved security — compared to those provided by your ISP. You can learn more about Google's public DNS servers here: <https://developers.google.com/speed/public-dns/>

### VPN Logging Considerations

There are two log files on the server related to OpenVPN. One is `/var/log/openvpn/error.log` and the other is `/var/log/openvpn/status.log`. You may also find OpenVPN-related data in `/var/log/syslog`.

As far as I can tell, none of these monitor your browsing habits, they just log initial VPN connections, errors, and other protocol exchanges at verbosity level `3` (normal). Having said that, if you are concerned about security, please feel free to review `/bootstrap/src/openvpn/.conf`. That's the OpenVPN server configuration file.

### Sharing Your VPN w/ Others

Your VPN allows up to 100 simultaneous connections. You can share your VPN with others if you'd like.

- Send them a copy of your `.ovpn` file.
- Create an account for them on the server (see **Giving Others System Access** below). Instead of making them an admin, use `create-user` and `setup-user` in the second example that is shown.
- Provide them with the instructions above so they can make a connection.
- They, like you, will also browse the web as `%%do_floating_ip%%`.

_**Note:** While the server does allow up to 100 clients at a time, the firewall is configured to allow, at most, 6 new connections every 30 seconds. So you can have up to 100 people connected at the same time, but there is a throttle on new incoming connection attempts._

## XDebug Remote Host IP

### Main Site: `%%CFG_HOST%%`

XDebug is already installed and configured. However, since this is a live site that is connected to the Internet, XDebug needs you to give it a specific remote IP address that it should connect to.

- Open: `/bootstrap/src/php/.ini` and find the following.

  ```text
  ;xdebug.remote_host= MY.IP.ADDRESS
  ```

  _Uncomment and change this to your personal IP address._

- Restart the PHP process manager.

  ```bash
  $ sudo service php7.1-fpm restart
  ```

### Dev Containers: e.g., `php5.4-%%CFG_HOST%%`

XDebug is already installed and configured. However, since this is a live site that is connected to the Internet, XDebug needs you to give it a specific remote IP address that it should connect to.

- Open: `/app-dev/php5.4/src/.htaccess` and find the following.

  ```text
  # php_value xdebug.remote_host MY.IP.ADDRESS
  ```

  _Uncomment and change this to your personal IP address._

## Open Ports

Port `22` is open, but requires an SSH key and the firewall limits you to a maximum of 6 connection attempts every 30 seconds. In addition, Fail2Ban is guarding this port and will automatically ban abusers.

Port `1194` is open for VPN connections that have the required SSL certificates, as provided by the attached `.ovpn` file that you got with this document.

`80` and `443` are open, but can only be accessed by CloudFlare. All of your web server sits behind the CloudFlare firewall. So while you can access your sites in a browser, what you're actually doing is accessing those sites through CloudFlare — a security feature.

### Opening New Ports

```bash
$ sudo ufw allow 3306/tcp # i.e., [port]/[proto]
$ sudo service ufw restart # Restart service.
# Opens MySQL port to external connections also.
```

### Throttle Access to An Open Port (Recommended)

```bash
$ sudo ufw limit 3306/tcp # i.e., [port]/[proto]
$ sudo service ufw restart # Restart service.
# Opens MySQL port to external connections also.
```

_i.e., A maximum of 6 connections every 30 seconds when you do it this way._

## Outgoing Mail via PHP `mail()` or `wp_mail()`

This server is running a very simple installation of Postfix that has no ability to receive email from the Internet, but it does have the ability to send outgoing email via PHP to any outside location. This works well when running basic tests in a semi-live environment. However, there are no SPF records, no DKIM, etc. Therefore, mail sent by this server will likely end up in your spam folder. If you intend to send email in any official way you should install the WP Mail SMTP plugin and use a dedicated SMTP server of your choosing; e.g., GMail SMTP or Amazon SES, etc.

_**Tip:** You can also send mail from the command-line using `mail`. See: `$ man mail` for instructions._

## Advanced MySQL Database Access

You don't need to open port 3306 for MySQL to work from inside the server itself via `localhost` (aka: `127.0.0.1`). For internal connections, here are the important details.

Host name: `%%CFG_MYSQL_DB_HOST%%` port: `%%CFG_MYSQL_DB_PORT%%` (default)
Username: `%%CFG_MYSQL_DB_USERNAME%%` Password: `%%CFG_MYSQL_DB_PASSWORD%%`
Default/primary database name: `%%CFG_MYSQL_DB_NAME%%`

_**See also:** `/etc/environment` for global environment variables with this data._

### External MySQL Connections

External connections (if necessary) require that you open port `3306` to the outside world. See: **Opening New Ports** above. For external connections, a different username/password is required as an extra security precaution. Here are the important details.

Host name: `ws-droplet-%%CFG_HOST%%` port `%%CFG_MYSQL_DB_PORT%%` (default)
Username: `%%CFG_MYSQL_X_DB_USERNAME%%` Password: `%%CFG_MYSQL_X_DB_PASSWORD%%`
Default/primary database name: `%%CFG_MYSQL_DB_NAME%%`

## Giving Others System Access

### Adding a new administrator (unrestricted access).

e.g., If you're sharing with another team member you trust, use the following commands. This creates their account, sets up their home directory, assigns them to all admin groups, grants them SSH access, and makes it possible for them to run `sudo` commands on the server.

```bash
$ sudo create-admin --user=someone --pass=xxxxxxxxxxxxxxx
$ sudo setup-admin --user=someone --name='Someone Special' --email=someone@example.com --ssh-keys=/tmp/path/to/authorized_keys;

# TIP: you can omit --ssh-keys=... if the username is:
#   `jaswrks`, `raamdev`, `kristineds`, `renzms`
#   SSH keys for these users exist on the server already.
```

### Adding a new user w/ restricted access.

e.g., If you just want to give them an account and adjust permissions yourself, use the following commands. This creates their account, sets up their home directory and SSH key. By default, SSH access is not allowed, and no other permissions are granted either.

```bash
$ sudo create-user --user=someone --pass=xxxxxxxxxxxxxxx
$ sudo setup-user --user=someone --name='Someone Special' --email=someone@example.com --ssh-keys=/tmp/path/to/authorized_keys;

# TIP: you can omit --ssh-keys=... if the username is:
#   `jaswrks`, `raamdev`, `kristineds`, `renzms`
#   SSH keys for these users exist on the server already.
```

You can give them SSH access by adding them to the `ssh-access` group. If they also need to edit app-related files on the server, add them to groups `app` and `www-data`. If you want to make them an admin, also add them groups `adm` and `admin`. You can give them `sudo` access by adding them to the `sudo` group.

```bash
$ sudo usermod --append --groups=ssh-access someone
# i.e., sudo usermod --append --groups=[group] [username]
```

## Installing New Software

```bash
$ sudo apt-get update # Update package repos.
$ sudo apt-get install [package]
```

### Searching for Packages

```bash
$ sudo apt-get update # Update package repos.
$ sudo apt-cache search [query]
```

_**Suggested reading:** <http://jas.xyz/2c9cDrj>_

## Global Environment Variables

```bash
$ sudo cat /etc/environment # And review.
```

## No Data Backups (Be Advised)

There are no data backups in dev land. These test sites and the entire server can easily be rebuilt by Jason, using an existing image, but any data that you store on this server will be lost in that scenario. _**Tip:** If it's important, don't store it on this server._

## Snapshots (Easy Restoration)

If you install something you shouldn't have, uninstall something that you needed, or otherwise find yourself in a pickle, let Jason or Raam know. A snapshot of this server was taken when it was first built. Jason and/or Raam can restore you to that original state in 5 minutes by logging into DigitalOcean and restoring your Droplet to that original snapshot.

_**Tip:** If the problem is related specifically to WordPress itself (or there's a problem with your MySQL database), you can use the `install-wp` family of commands to restore both WordPress and the database yourself. See the section above: **WordPress Reinstaller** and **WordPress Dev Container Reinstaller**_

## Server Management/Maintenance & Ownership

This server is owned by WebSharks, Inc. Jason manages this server for you, and he will let you know ahead of time if there are updates planned. Whenever updates do occur, there will likely be a loss of data, simply because Jason doesn't have enough time to manage servers and deal with the recovery and/or migration of your test sites also.

Here's what to expect when updates need to occur, as determined by Jason.

- You will be notified by Jason at least 14 days in advance.
- You will have that time to save/export anything that you need to keep.
- The server will be upgraded and then delivered to you in a new default state.
- You will receive a new `.ovpn` file with newly generated client SSL certificates.
- Your Floating IP (`%%do_floating_ip%%`) will remain; i.e., you will have the same IP as before.
- You will receive another document like this one with the latest up-to-date information.
