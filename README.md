<img src="https://media.githubusercontent.com/media/websharks/ubuntu-bootstrap/dev/assets/cover.png" width="300" align="right" />

## WebSharks Ubuntu Bootstrap

VirtualBox + Vagrant + Landrush, running Ubuntu 16.04 LTS (Xenial Xersus) with your choice of Nginx or Apache + MariaDB (MySQL), PHP (choice of PHP 7.1, PHP 7.0, PHP 5.6, PHP 5.5), and WordPress.

## Installation Instructions

### 1.) Satisfy Software Requirements

You need VirtualBox, Vagrant, and a DNS plugin.  
_The following commands will do the trick for you._

```bash
$ brew cask install virtualbox vagrant;
```

Tweak VirtualBox by running this line please. See [details](https://github.com/mitchellh/vagrant/issues/3083) to learn more.

```bash
$ VBoxManage dhcpserver remove --netname HostInterfaceNetworking-vboxnet0;
```

Install a DNS plugin. See [`vagrant-hostsupdater` vs. LandRush](https://github.com/websharks/ubuntu-bootstrap#vagrant-hostsupdater-vs-landrush) comparison.

```bash
$ vagrant plugin install vagrant-hostsupdater; # Easiest (recommended).
# $ vagrant plugin install landrush; # More difficult, but greater flexibility.
```

Optionally install the triggers plugin. See [plugin details](https://github.com/emyl/vagrant-triggers).

```bash
$ vagrant plugin install vagrant-triggers;
```

Optionally install a caching plugin. See [plugin details](http://fgrehm.viewdocs.io/vagrant-cachier).

```bash
$ vagrant plugin install vagrant-cachier;
```

### 2.) Clone WebSharks Ubuntu Bootstrap

```bash
$ mkdir ~/vms && cd ~/vms;
$ git clone https://github.com/websharks/ubuntu-bootstrap my.vm;
```

_Note the sub-directory name (`my.vm`) becomes your domain name._  
_Change it if you like. Must end with `.vm` please._

### 3.) Vagrant Up

```bash
$ cd ~/vms/my.vm;
$ vagrant up;
```

### 4.) Install Software

```bash
$ vagrant ssh;
$ sudo /bootstrap/src/installer --CFG_USE_WIZARD=0;
```

### 5.) Confirm Working

Open <https://my.vm>. Upon first visit, you'll run into an SSL security warning. You can avoid that warning altogether later using the details below. For now, please bypass this self-signed certificate warning and proceed. You should then see the WordPress installation page!

**Q:** What if <https://my.vm> doesn't work for me?  
**A:** Try [flushing your local DNS cache](https://jas.xyz/2p7Q9wr).

## Additional Steps (All Optional)

### Install Root CA

If you'd like to always see a green SSL status for your local test sites (i.e., avoid `https://` warnings on anything ending with `.vm`), you can download and install [this root CA certificate file](https://github.com/websharks/ubuntu-bootstrap/blob/master/src/ssl/vm-ca-crt.pem) and set your trust settings to "Always Trust" for this certificate.

To clarify, any SSL certificates created by the Ubuntu Bootstrap will use that root CA certificate. Trusting the root CA (it's fake, and only for the Ubuntu Bootstrap project) will green-light all of your local `.vm` domains when accessing them over `https://`.

On a Mac, you can simply drag n' drop the certificate file onto your Keychain.app. Then open the settings for the certificate and choose "Always Trust" at the top.

_**Note:** Prior to macOS Sierra, there was a nasty bug in Keychain.app that would lock your system when attempting to 'Always Trust'. If you're running an older OS X release try [this command-line alternative given by @raamdev](https://github.com/websharks/ubuntu-bootstrap/issues/11#issuecomment-224332504)._

### Add Files to Web Directory

Doc root: `~/vms/my.vm/src/app/src/`

The latest version of WordPress will already be installed. However, you can add any additional application files that you'd like. e.g., phpBB, Drupal, Joomla, whatever you like. It's probably a good idea to put anything new inside a sub-directory; e.g., `~/vms/my.vm/src/app/src/phpBB`

### Understanding Environment Variables

- `$_SERVER['CFG_MYSQL_DB_HOST']` Database host.
- `$_SERVER['CFG_MYSQL_DB_NAME']` Database name.
- `$_SERVER['CFG_MYSQL_DB_USERNAME']` Database username.
- `$_SERVER['CFG_MYSQL_DB_PASSWORD']` Database password.

_**Tip:** For a full list of all global environment variables, see: `src/setups/env-vars` in the repo. Or, from the command-line on your VM type: `$ cat /etc/environment` (shows you the values too)._

### Access Web-Based Tools

A username/password is required for access.
username: `admin`, password: `admin`

- <https://my.vm/---tools/pma/> PhpMyAdmin  
  DB name: `db0`, DB user: `admin`, DB pass: `admin`
- <https://my.vm/---tools/opcache.php> OPcache dump.
- <https://my.vm/---tools/info.php> `phpinfo()` output page.
- <https://my.vm/---tools/fpm-status.php> PHP-FPM status page.
- <https://my.vm/---tools/status.nginx> NGINX status page (default web server).
- <https://my.vm/---tools/apache-status> Apache status page (if installed).
- <https://my.vm/---tools/apache-info> Apache info page (if installed).
- <http://my.vm:8025> MailHog web interface for test emails.

### Tear it Down and Customize

```bash
$ cd ~/vms/my.vm;
$ vagrant destroy;
```

In the project directory you'll find a `/src/vagrant/bootstrap-custom` file. This bash script runs as the `root` user during `vagrant up`. Therefore, you can install software and configure anything you like in this script. By default, this script does nothing. All of the software installation and system configuration takes place whenever you run `/bootstrap/src/installer` inside the VM.

#### Customization (Two Choices Available)

1. Customize `/src/installer` and the associated setup files that it calls upon, which are located in: `/src/setups/*`. _**Note:** If you go this route, there really is no reason to customize the `/src/vagrant/bootstrap-custom` file. You can leave it as-is._

2. Or, instead of working with the more complex installer, you can keep things simple and add your customizations to the `/src/vagrant/bootstrap-custom` script, which is a very simple starting point. The `/src/vagrant/bootstrap-custom` runs whenever you type `vagrant up`, so this is a logical choice for beginners. _**Note:** If you go this route, you can simply choose not to run `/bootstrap/src/installer`, because all of your customizations will be in the `/src/vagrant/bootstrap-custom` file; i.e., there will be no reason to run the installer._

#### When you're done with your customizations, type:

```bash
$ vagrant up;
```

##### If you decided to use the `/bootstrap/src/installer` option, also type:

```bash
$ vagrant ssh;
$ sudo /bootstrap/src/installer; # Presents a configuration dialog.
# Tip: to bypass configuration add the `--CFG_USE_WIZARD=0` argument to the installer.
```

## Domain Name Tips & Tricks

### Creating a Second VM w/ a Different Domain Name

```bash
$ git clone https://github.com/jaswrks/vagrant-ubuntu-lemp my-second.vm;

$ cd my-second.vm;
$ vagrant up && vagrant ssh;

$ sudo /bootstrap/src/installer; # Presents a configuration dialog.
# Tip: to bypass configuration add the `--CFG_USE_WIZARD=0` argument to the installer.
```

### Understanding Domain Name Mapping

The URL which leads to your VM is based on the name of the directory that you cloned the repo into; e.g., `my.vm` or `my-second.vm` in the above examples. However, the directory that you clone into MUST end with `.vm` for this to work as expected. If the directory you cloned into doesn't end with `.vm`, the default domain name will be `http://ubuntu.vm`. You can change this hard-coded default by editing `config.vm.hostname` in `Vagrantfile`.

In either case, the domain name is also wildcarded; i.e., `my.vm`, `www.my.vm`, `wordpress.my.vm` all map to the exact same location: `~/vms/my.vm/app/src/`. This is helpful when testing WordPress Multisite Networks, because you can easily setup a sub-domain network, or even an MU domain mapping plugin.

## Testing WordPress Themes/Plugins Easily!

See `/Vagrantfile` where you will find this section already implemented.
_~ See also: `src/wordpress/install-symlinks`_

```ruby
# Mount WordPress projects directory.
if File.directory?(wp_projects_dir = ENV["WP_#{_VM_HOSTNAME_UC_VAR}_PROJECTS_DIR"] || ENV['WP_PROJECTS_DIR'] || File.expand_path('~/projects/wordpress'))
  config.vm.synced_folder wp_projects_dir, '/wp-projects', mount_options: ['defaults', 'ro'];
end;

# Mount WordPress personal projects directory.
if File.directory?(wp_personal_projects_dir = ENV["WP_#{_VM_HOSTNAME_UC_VAR}_PERSONAL_PROJECTS_DIR"] || ENV['WP_PERSONAL_PROJECTS_DIR'] || File.expand_path('~/projects/personal/wordpress'))
  config.vm.synced_folder wp_personal_projects_dir, '/wp-personal', mount_options: ['defaults', 'ro'];
end;

# Mount WordPress business projects directory.
if File.directory?(wp_business_projects_dir = ENV["WP_#{_VM_HOSTNAME_UC_VAR}_BUSINESS_PROJECTS_DIR"] || ENV['WP_BUSINESS_PROJECTS_DIR'] || File.expand_path('~/projects/business/wordpress'))
  config.vm.synced_folder wp_business_projects_dir, '/wp-business', mount_options: ['defaults', 'ro'];
end;
```

### ↑ What is happening here w/ these WordPress directories?

The `Vagrantfile` is automatically mounting drives on your VM that are sourced by your local `~/projects` directory (if you have one). Thus, if you have your WordPress themes/plugins in `~/projects/wordpress` (i.e., in your local filesystem), it will be mounted on the VM automatically as `/wp-projects`.

In the `src/wordpress/install-symlinks` file, we iterate `/wp-projects` and build symlinks for each of your themes/plugins automatically. This means that when you log into your WordPress Dashboard (<https://my.vm/wp-admin/>), you will have all of your themes/plugins available for testing. If you make edits locally in your favorite editor, they are updated in real-time on the VM. Very cool!

The additional mounts (i.e., `~/projects/personal/wordpress` and `~/projects/business/wordpress`) are simply alternate locations that I use personally. Remove them if you like. See: `Vagrantfile` and `src/wordpress/install-symlinks` to remove in both places. You don't really _need_ to remove them though, because if these locations don't exist on your system they simply will not be mounted. In fact, you might consider leaving them, and just alter the paths to reflect your own personal preference—or for future implementation.

### The default WordPress mapping looks like this:

- `~/projects/wordpress` on your local system.
  - Is mounted on the VM as: `/wp-projects`
- Then (on the VM) the `src/wordpress/install-symlinks` script symlinks each theme/plugin into:
  - `/app/src/wp-content/[themes|plugins]` appropriately.

### What directory structure do I need exactly?

Inside `~/projects/wordpress` you need to have two sub-directories. One for themes and another for plugins.

- `~/projects/wordpress/themes` (put WP themes in this directory; e.g., `my-theme`)
- `~/projects/wordpress/plugins` (put WP plugins here; e.g., `my-plugin`)

Now, whenever you run `/bootstrap/src/installer` from the VM, your local copy of `~/projects/wordpress/themes/my-theme` becomes `/app/src/wp-content/themes/my-theme` on the VM. Your local copy of `~/projects/wordpress/plugins/my-plugin` becomes `/app/src/wp-content/plugins/my-plugin` on the VM ... and so on... for each theme/plugin sub-directory, and for each of the three possible mounts listed above. This all happens automatically if you followed the instructions correctly.

### Can I override the default source directories for WordPress?

Yes. Looking over the code snippet above, you can see that there are three environment variables that you can set in your `~/.profile` that, if found, will override the default locations automatically. Here's a quick example showing how you might customize these in your own `~/.profile`

```bash
export WP_PROJECTS_DIR=~/my-projects/wordpress;
export WP_PERSONAL_PROJECTS_DIR=~/my-personal-projects/wordpress;
export WP_BUSINESS_PROJECTS_DIR=~/my-business-projects/wordpress;
```

It is also possible to define hostname-specific environment variables. Take note of `_MY_VM` in the examples below. This correlates with `my.vm` (the hostname you are locking these to), and then converted to all uppercase with dots and hyphens now as `_` underscores instead.

```bash
export WP_MY_VM_PROJECTS_DIR=~/my-projects/wordpress;
export WP_MY_VM_PERSONAL_PROJECTS_DIR=~/my-personal-projects/wordpress;
export WP_MY_VM_BUSINESS_PROJECTS_DIR=~/my-business-projects/wordpress;
```

## Bootstrap Command-Line Arguments

The following CLI arguments can be passed to `/bootstrap/src/installer`, just in case you'd like to avoid the configuration wizard entirely. These are all optional; i.e., if you don't provide these arguments you will be prompted to configure the bootstrap using a command-line dialog interface whenever the installer runs.

_**Tip:** You can learn more about how these work and what the defaults are by looking over the [src/setups/config](src/setups/config) file carefully and perhaps searching for their use in other files found in `src/setups/*`._

---

- `--CFG_4CI=0|1` Building as a base image for a CI server?
- `--CFG_4PKG=0|1` Building as a base image that will be packaged?

---

- `--CFG_USE_WIZARD=0|1` Set as `0` to bypass the wizard.

---

- `--CFG_HOST=my.cool.vm` Host name.
- `--CFG_ROOT_HOST=cool.vm` Root host name.

---

- `--CFG_SLUG=my-cool-vm` Slug w/ dashes, no underscores.
- `--CFG_VAR=my_cool_vm` Slug w/ underscores, no dashes.

---

- `--CFG_ADMIN_GENDER=[male|female]` Admin gender.

---

- `--CFG_ADMIN_USERNAME=admin` Admin username.
- `--CFG_ADMIN_PASSWORD=admin` Admin password.

---

- `--CFG_ADMIN_NAME='Admin Istrator'` Display name.
- `--CFG_ADMIN_EMAIL='admin@my.cool.vm'` Admin email address.
- `--CFG_ADMIN_PUBLIC_EMAIL='webmaster@my.cool.vm'` Public email address.

---

- `--CFG_ADMIN_PREFERRED_SHELL=/bin/zsh` Or `/bin/bash`.
- `--CFG_ADMIN_STATIC_IP_ADDRESS=[ip]` e.g., `123.456.789.0`
- `--CFG_ADMIN_AUTHORIZED_SSH_KEYS=[file]` e.g., `/authorized_keys`

---

- `--CFG_AWS_TRANSFER_ACCESS_KEY_ID=xxxxxxx...` AWS access key ID.
- `--CFG_AWS_TRANSFER_SECRET_ACCESS_KEY=xxxxxxx...` AWS secret access key.

---

- `--CFG_INSTALL_SWAP=0|1` Install swap space?

---

- `--CFG_INSTALL_WS_CA_FILES=0|1` Use WebSharks certificate authority?

---

- `--CFG_INSTALL_POSTFIX=0|1` Install Postfix?
- `--CFG_INSTALL_MAILHOG=0|1` Install MailHog instead of Postfix?

---

- `--CFG_INSTALL_OPENVPN=0|1` Install OpenVPN server?

---

- `--CFG_INSTALL_DOCKER=0|1` Install Docker?

---

- `--CFG_INSTALL_MYSQL=0|1` Install MySQL?

---

- `--CFG_MYSQL_DB_HOST=127.0.0.1` MySQL DB host name.
- `--CFG_MYSQL_DB_PORT=3306` MySQL DB port number.

---

- `--CFG_MYSQL_SSL_KEY=[file]` MySQL SSL key file.
- `--CFG_MYSQL_SSL_CRT=[file]` MySQL SSL crt file.
- `--CFG_MYSQL_SSL_CA=[file]` MySQL SSL ca file.
- `--CFG_MYSQL_SSL_CIPHER=[cipher]` MySQL SSL cipher.

---

- `--CFG_MYSQL_SSL_ENABLE=0|1` MySQL over SSL?

---

- `--CFG_MYSQL_DB_CHARSET=utf8mb4` MySQL DB charset.
- `--CFG_MYSQL_DB_COLLATE=utf8mb4_unicode_ci` MySQL DB collation.

---

- `--CFG_MYSQL_DB_NAME=db0` MySQL DB name.

---

- `--CFG_MYSQL_DB_USERNAME=client` MySQL DB username.
- `--CFG_MYSQL_DB_PASSWORD=[password]` Default is auto-generated.

---

- `--CFG_MYSQL_X_DB_USERNAME=x_client` MySQL external DB username.
- `--CFG_MYSQL_X_DB_PASSWORD=[password]` Default is auto-generated.

---

- `--CFG_MYSQL_X_REQUIRES_SSL=0|1` External connections require SSL?

---

- `--CFG_INSTALL_MEMCACHE=0|1` Install Memcached?
- `--CFG_INSTALL_RAMDISK=0|1` Install a RAM disk partition?

---

- `--CFG_INSTALL_PHP_CLI=0|1` Install PHP command-line interpreter?
- `--CFG_INSTALL_PHP_FPM=0|1` Install PHP-FPM process manager for Apache/Nginx?
- `--CFG_INSTALL_PHP_VERSION=[7.1|7.0|5.6|5.5]` Which PHP version to install?

---

- `--CFG_ENABLE_PHP_OPCACHE=0|1` Enable the PHP OPcache extension?
- `--CFG_INSTALL_PHP_XDEBUG=0|1` Install PHP XDebug extension?
- `--CFG_ENABLE_PHP_PHAR_READONLY=0|1` Force PHAR readonly mode?
- `--CFG_ENABLE_PHP_ASSERTIONS=0|1` Enable PHP assertions?

---

- `--CFG_INSTALL_COMPOSER=0|1` Install Composer?

---

- `--CFG_INSTALL_PMA=0|1` Install PhpMyAdmin?
- `--CFG_PMA_BLOWFISH_KEY=[key]` Blowfish key for PMA.

---

- `--CFG_INSTALL_PSYSH=0|1` Install Psysh?
- `--CFG_INSTALL_PHPCS=0|1` Install PHP Code Sniffer?
- `--CFG_INSTALL_PHING=0|1` Install Phing?
- `--CFG_INSTALL_PHPUNIT=0|1` Install PHPUnit?
- `--CFG_INSTALL_SAMI=0|1` Install Sami codex generator?
- `--CFG_INSTALL_APIGEN=0|1` Install APIGen codex generator?
- `--CFG_INSTALL_CACHETOOL=0|1` Install PHP-FPM cachetool?
- `--CFG_INSTALL_WP_CLI=0|1` Install WP CLI tool?
- `--CFG_INSTALL_WP_I18N_TOOLS=0|1` Install WP i18n tools?
- `--CFG_INSTALL_WEBSHARKS_CORE=0|1` Install WS Core?

---

- `--CFG_INSTALL_NGINX=0|1` Install Nginx?
- `--CFG_INSTALL_APACHE=0|1` Install Apache?

---

- `--CFG_WEB_SERVER_SSL_ONLY=0|1` Serve `https://` requests only?
- `--CFG_MAINTENANCE_BYPASS_KEY=[key]` Bypass key in maintenance mode.

---

- `--CFG_INSTALL_CASPERJS=0|1` Install CasperJS?

---

- `--CFG_INSTALL_APP_REPO=0|1` Install a Git repo?

---

- `--CFG_INSTALL_WORDPRESS=0|1` Install WordPress?
- `--CFG_INSTALL_WORDPRESS_VM_SYMLINKS=0|1` Install symlinks?

---

- `--CFG_INSTALL_WORDPRESS_DEV_CONTAINERS=0|1` Install WP dev containers?

---

- `--CFG_WORDPRESS_DEV_GENDER=[male|female]` WP developer gender.

---

- `--CFG_WORDPRESS_DEV_USERNAME=[username]` WP developer username.
- `--CFG_WORDPRESS_DEV_PASSWORD=[password]` Default is auto-generated.

---

- `--CFG_WORDPRESS_DEV_NAME=[name]` WP developer name.
- `--CFG_WORDPRESS_DEV_EMAIL=[email]` WP developer email address.

---

- `--CFG_WORDPRESS_DEV_PREFERRED_SHELL=/bin/zsh` Or `/bin/bash`.
- `--CFG_WORDPRESS_DEV_STATIC_IP_ADDRESS=[ip]` e.g., `123.456.789.0`
- `--CFG_WORDPRESS_DEV_AUTHORIZED_SSH_KEYS=[file]` e.g., `/authorized_keys`

---

- `--CFG_INSTALL_DISCOURSE=0|1` Install Discourse?

---

- `--CFG_DISCOURSE_SMTP_HOST=email-smtp.us-east-1.amazonaws.com` SMTP host name.
- `--CFG_DISCOURSE_SMTP_PORT=587` SMTP port number.

---

- `--CFG_DISCOURSE_SMTP_AUTH_TYPE=login` SMTP authentication type.
- `--CFG_DISCOURSE_SMTP_USERNAME=[username]` SMTP username.
- `--CFG_DISCOURSE_SMTP_PASSWORD=[password]` SMTP password.

---

- `--CFG_INSTALL_FIREWALL=0|1` Install firewall?

---

- `--CFG_FIREWALL_ALLOWS_ADMIN_ONLY_VIA_22=0|1` Allow admin only?
  - Requires `--CFG_ADMIN_STATIC_IP_ADDRESS` ; for production use only.
- `--CFG_FIREWALL_ALLOWS_MYSQL_VIA_3306=0|1` Allow external connections to MySQL?
- `--CFG_FIREWALL_ALLOWS_MYSQL_INSIDE_VPN=0|1` Allow network-based connections?
- `--CFG_FIREWALL_ALLOWS_CF_ONLY_VIA_80_443=0|1` Allow only CloudFlare?

---

- `--CFG_INSTALL_FAIL2BAN=0|1` Install Fail2Ban?

---

- `--CFG_INSTALL_UNATTENDED_UPGRADES=0|1` Configure unattended upgrades?

---

- `--CFG_CONFIG_FILE=/app/.config.json` This is for any purpose you like. The value that you set here becomes a global environment variable that your application can consume. If set, it should be a configuration file path on the VM. The file does not even need to exist when configured, it's simply an environment variable that you can use elsewhere in your application later.

## `vagrant-hostsupdater` vs Landrush

The `vagrant-hostsupdater` plugin is the easiest way to get started. It's quite simple in that it merely updates the `/etc/hosts` file in macOS. This avoids any confusion. You can just `$ sudo vi /etc/hosts` and take a quick peek at what's been done after you `vagrant up` for the first time.

However, unlike the more powerful Landrush plugin, `vagrant-hostsupdater` doesn't automatically assign a new IP for each VM instance that you bring up. Instead, the IP is established by the `Vagrantfile`, and in the WebSharks Ubuntu Bootstrap (if you run the `vagrant-hostsupdater` plugin), your VM's IP address will always be the default hard-coded: `192.168.42.42`

You may eventually want to run multiple VMs at the same time; i.e., you'll need multiple IP addresses. To accomplish this with the `vagrant-hostsupdater` plugin you'll need to edit the [`Vagrantfile`](Vagrantfile) manually. Bump the IP from `192.168.42.42` (default), to `192.168.42.43`, `192.168.42.44`, etc. — for each of your additional VM instances.

The Landrush plugin is slightly heavier, but it's also more flexible. It spins up a small DNS server and redirects DNS traffic, automatically registering/unregistering IP addresses for VMs as they come up and go down. With Landrush there is no need to edit the `Vagrantfile` manually. You can run several VMs all at the same time, all on different IPs, and without needing to edit the `Vagrantfile`.

## Bootstrapping Base Images for Custom Vagrant Boxes

Please see: [Packaging a Custom Box](https://github.com/websharks/ubuntu-bootstrap/wiki/Packaging-a-Custom-Box) for full instructions.
