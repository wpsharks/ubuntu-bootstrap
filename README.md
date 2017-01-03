<img src="https://media.githubusercontent.com/media/websharks/ubuntu-bootstrap/dev/assets/cover.png" width="300" align="right" />

## VirtualBox + Vagrant + Landrush; running Ubuntu 16.04 LTS (Xenial Xersus) w/ Nginx (or Apache), MariaDB (MySQL), PHP (choice of PHP 7.1, PHP 7.0, PHP 5.6, PHP 5.5), and WordPress

### Installation Instructions

#### Step 1: Satisfy Software Requirements

You need VirtualBox, Vagrant, and a DNS plugin.

```bash
$ brew cask install virtualbox;
$ brew cask install vagrant;

# Also tweak VirtualBox by running this line please:
# See: <https://github.com/mitchellh/vagrant/issues/3083> for details.
$ VBoxManage dhcpserver remove --netname HostInterfaceNetworking-vboxnet0;

# ---------------------------------------------

# You need only one of these. Please choose:
$ vagrant plugin install vagrant-hostsupdater; # Easiest (recommended).
$ vagrant plugin install landrush; # More difficult, but greater flexibility.

# ---------------------------------------------

$ vagrant plugin install vagrant-triggers; # Optional (recommended).
# This allows for special event handling. Helpful, but not required at this time.
```

_**Note:** If you don't install Landrush and instead you go with the simpler DNS plugin `vagrant-hostsupdater` (or you choose not to install a DNS plugin at all), it will mean that your VM will have a static IP address of: `192.168.42.42`_

_This also means that you can only run a single VM at one time, because the static IP is the same for each VM. If you go this route and also want to run multiple VM instances at the same time you will need to change the IP address in the [`Vagrantfile`](Vagrantfile) for each additional VM that you bring up._

_You can just edit the [`Vagrantfile`](Vagrantfile) and bump the IP from `192.168.42.42` (default),  to `192.168.42.43`, `192.168.42.44`, etc (for each of your additional VMS)._

#### Step 2: Clone GitHub Repo (Ubuntu Bootstrap)

```bash
$ mkdir ~/vms && cd ~/vms;
$ git clone https://github.com/websharks/ubuntu-bootstrap my.vm;
```

_Note that `my.vm` becomes your domain name. Change it if you like. Must end with `.vm` please._

#### Step 3: Vagrant Up!

```bash
$ cd ~/vms/my.vm;
$ vagrant up;
```

#### Step 4: Install software :-)

```bash
$ vagrant ssh;
$ sudo /bootstrap/src/installer; # Presents a configuration dialog.
# Tip: To bypass the dialog use `--CFG_USE_WIZARD=0` argument to installer.
```

#### Step 5: Confirm it is Working!

- Open <https://my.vm>. You should get an SSL security warning. Please bypass this self-signed certificate warning and proceed. You should then see the WordPress installation page. SSL is working as expected!

  _The URL <https://my.vm> is not working?_

  _Try flushing your DNS cache. Each time you `vagrant up`, a new IP is generated automatically that is mapped to the `my.vm` hostname. If you are working with multiple VMs, you might need to flush your DNS cache to make sure your system is mapping `my.vm` to the correct IP address. See: <http://jas.xyz/1fmAa4P> for instructions on a Mac._

---

### Additional Steps (All Optional)

#### Step 6: Install Root CA

If you'd like to always see a green SSL status for your local test sites (i.e., avoid `https://` warnings on anything ending with `.vm`), you can download and install [this root CA certificate file](https://github.com/websharks/ubuntu-bootstrap/blob/master/src/ssl/vm-ca-crt.pem) and set your trust settings to "Always Trust" for this certificate.

Any SSL certificates created by the Ubuntu Bootstrap will use that root CA certificate. Trusting the root CA (it's fake, and only for the Ubuntu Bootstrap project), will green-light all of your local `.vm` domains when accessing them over `https://`. On a Mac, you can simply download, then drag n' drop the certificate file onto your Keychain.app. Open up the settings for that certification in Keychain.app and choose "Always Trust" at the top. Done! :-)

**↑ UPDATE (WARNING):** If you're on a Mac, there is a nasty bug in the Keychain application that can lock your system when attempting to 'Always Trust'. Until that bug is fixed in the Mac OS, please see the command-line alternatives demonstrated [here](https://github.com/websharks/ubuntu-bootstrap/issues/11#issuecomment-224305268) by @jaswsinc and @raamdev. I suggest using [the example given by @raamdev](https://github.com/websharks/ubuntu-bootstrap/issues/11#issuecomment-224332504).

#### Step 7: Add Files to: `~/vms/my.vm/src/app/src/`

The is the web root. The latest version of WordPress will already be installed. However, you can add any additional application files that you'd like. e.g., phpBB, Drupal, Joomla, whatever you like. It's probably a good idea to put anything new inside a sub-directory of its own; e.g., `~/vms/my.vm/src/app/src/phpBB`

#### Step 8: Understanding Environment Variables

This stack comes preconfigured with a MySQL database and environment variables you can use in any PHP config. files.

- `$_SERVER['CFG_MYSQL_DB_HOST']` This is the database host name. Defaults to `127.0.0.1`. Port is `3306` (default port).
- `$_SERVER['CFG_MYSQL_DB_NAME']` This is the database name. Defaults to `admin`.
- `$_SERVER['CFG_MYSQL_DB_USERNAME']` This is the database username. Defaults to `admin`.
- `$_SERVER['CFG_MYSQL_DB_PASSWORD']` This is the database password. Defaults to `admin`.

_**Tip:** For a full list of all global environment variables, see: `src/setups/env-vars` in the repo. Or, from the command-line on your VM type: `$ cat /etc/environment` (shows you the values too)._

#### Step 9: Learn to Use the Tools That I've Bundled

A username/password is required to access each of these tools. It is always the same thing.

- Username: `admin` Password: `admin`

Available Tools (Using Any of These is Optional):

- <https://my.vm/---tools/pma/> PhpMyAdmin  
  DB name: `db0`, DB username: `admin`, DB password: `admin`
- <https://my.vm/---tools/opcache.php> PHP OPcache extension status dump.
- <https://my.vm/---tools/info.php> `phpinfo()` output page.
- <https://my.vm/---tools/fpm-status.php> PHP-FPM status page.
- <https://my.vm/---tools/status.nginx> NGINX status page (if Nginx was installed).
- <https://my.vm/---tools/apache-status> Apache status page (if Apache was installed).
- <https://my.vm/---tools/apache-info> Apache page (if Apache was installed).
- <http://my.vm:8025> MailHog web interface for reviewing test emails on a VM.

#### Step 10: Tear it Down and Customize

```bash
$ cd ~/vms/my.vm;
$ vagrant destroy;
```

In the project directory you'll find a `/src/vagrant/bootstrap-custom` file. This bash script runs as the `root` user during `vagrant up`. Therefore, you can install software and configure anything you like in this script. By default, this script does nothing. All of the software installation and system configuration takes place whenever you run `/bootstrap/src/installer` inside the VM.

##### Customization (Two Choices Available)

1. Customize `/src/installer` and the associated setup files that it calls upon, which are located in: `/src/setups/*`. _**Note:** If you go this route, there really is no reason to customize the `/src/vagrant/bootstrap-custom` file. You can leave it as-is._

2. Or, instead of working with the more complex installer, you can keep things simple and add your customizations to the `/src/vagrant/bootstrap-custom` script, which is a very simple starting point. The `/src/vagrant/bootstrap-custom` runs whenever you type `vagrant up`, so this is a logical choice for beginners. _**Note:** If you go this route, you can simply choose not to run `/bootstrap/src/installer`, because all of your customizations will be in the `/src/vagrant/bootstrap-custom` file; i.e., there will be no reason to run the installer._

##### When you're done with your customizations, type:

```bash
$ vagrant up;
```

###### If you decided to use the `/bootstrap/src/installer` option, also type:

```bash
$ vagrant ssh;
$ sudo /bootstrap/src/installer; # Presents a configuration dialog.
# Tip: to bypass configuration add the `--CFG_USE_WIZARD=0` argument to the installer.
```

---

### Domain Name Tips & Tricks

#### Creating a Second VM w/ a Different Domain Name

```bash
$ git clone https://github.com/jaswsinc/vagrant-ubuntu-lemp my-second.vm;
$ cd my-second.vm;
$ vagrant up;
$ vagrant ssh;
$ sudo /bootstrap/src/installer; # Presents a configuration dialog.
# Tip: to bypass configuration add the `--CFG_USE_WIZARD=0` argument to the installer.
```

#### Understanding Domain Name Mapping

The URL which leads to your VM is based on the name of the directory that you cloned the repo into; e.g., `my.vm` or `my-second.vm` in the above examples. However, the directory that you clone into MUST end with `.vm` for this to work as expected. If the directory you cloned into doesn't end with `.vm`, the default domain name will be `http://ubuntu.vm`. You can change this hard-coded default by editing `config.vm.hostname` in `Vagrantfile`.

In either case, the domain name is also wildcarded; i.e., `my.vm`, `www.my.vm`, `wordpress.my.vm` all map to the exact same location: `~/vms/my.vm/app/src/`. This is helpful when testing WordPress Multisite Networks, because you can easily setup a sub-domain network, or even an MU domain mapping plugin.

---

### Testing WordPress Themes/Plugins Easily!

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

#### ↑ What is happening here w/ these WordPress directories?

The `Vagrantfile` is automatically mounting drives on your VM that are sourced by your local `~/projects` directory (if you have one). Thus, if you have your WordPress themes/plugins in `~/projects/wordpress` (i.e., in your local filesystem), it will be mounted on the VM automatically as `/wp-projects`.

In the `src/wordpress/install-symlinks` file, we iterate `/wp-projects` and build symlinks for each of your themes/plugins automatically. This means that when you log into your WordPress Dashboard (<https://my.vm/wp-admin/>), you will have all of your themes/plugins available for testing. If you make edits locally in your favorite editor, they are updated in real-time on the VM. Very cool!

The additional mounts (i.e., `~/projects/personal/wordpress` and `~/projects/business/wordpress`) are simply alternate locations that I use personally. Remove them if you like. See: `Vagrantfile` and `src/wordpress/install-symlinks` to remove in both places. You don't really _need_ to remove them though, because if these locations don't exist on your system they simply will not be mounted. In fact, you might consider leaving them, and just alter the paths to reflect your own personal preference—or for future implementation.

#### The default WordPress mapping looks like this:

- `~/projects/wordpress` on your local system.
  - Is mounted on the VM as: `/wp-projects`
- Then (on the VM) the `src/wordpress/install-symlinks` script symlinks each theme/plugin into:
  - `/app/src/wp-content/[themes|plugins]` appropriately.

#### What directory structure do I need exactly?

Inside `~/projects/wordpress` you need to have two sub-directories. One for themes and another for plugins.

- `~/projects/wordpress/themes` (put WP themes in this directory; e.g., `my-theme`)
- `~/projects/wordpress/plugins` (put WP plugins here; e.g., `my-plugin`)

Now, whenever you run `/bootstrap/src/installer` from the VM, your local copy of `~/projects/wordpress/themes/my-theme` becomes `/app/src/wp-content/themes/my-theme` on the VM. Your local copy of `~/projects/wordpress/plugins/my-plugin` becomes `/app/src/wp-content/plugins/my-plugin` on the VM ... and so on... for each theme/plugin sub-directory, and for each of the three possible mounts listed above. This all happens automatically if you followed the instructions correctly.

#### Can I override the default source directories for WordPress?

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

---

### Bootstrap Command-Line Arguments

The following CLI arguments can be passed to `/bootstrap/src/installer`, just in case you'd like to avoid the configuration wizard entirely. These are all optional; i.e., if you don't provide these arguments you will be prompted to configure the bootstrap using a command-line dialog interface whenever the installer runs.

_**Tip:** You can learn more about how these work and what the defaults are by looking over the [src/setups/config](src/setups/config) file carefully and perhaps searching for their use in other files found in `src/setups/*`._

- `--CFG_USE_WIZARD=0|1` `0` to bypass the wizard.

---

- `--CFG_4CI=0|1` Building this as a base image for a CI server?
- `--CFG_4PKG=0|1` Building this as a base image that will be packaged up?

---

- `--CFG_HOST=my.cool.vm` Host name. Normally this is just `my.vm`.
- `--CFG_ROOT_HOST=cool.vm` Root host name. Normally this is the same as `CFG_HOST`.
- `--CFG_OTHER_HOSTS=[comma-delimited]` e.g., `a.vm,b.vm,c.vm` Other hosts to resolve locally. These are only resolved inside the VM; i.e., these additional host names are added to `/etc/hosts` inside the VM so the VM will be capable of connecting to them. Mainly useful when building a base image for a CI server that is entirely self-contained.

---

- `--CFG_SLUG=my-cool-vm` Slug w/ dashes, no underscores.
- `--CFG_VAR=my_cool_vm` Slug w/ underscores, no dashes.

---

- `--CFG_ADMIN_GENDER=[male|female]` Admin gender.

---

- `--CFG_ADMIN_USERNAME=admin` Administrative username.
- `--CFG_ADMIN_PASSWORD=admin` Administrative password.

---

- `--CFG_ADMIN_NAME='Admin Istrator'` Display name.
- `--CFG_ADMIN_EMAIL='admin@my.cool.vm'` Admin email address.
- `--CFG_ADMIN_PUBLIC_EMAIL='hostnamster@my.cool.vm'` Public email address.

---

- `--CFG_ADMIN_PREFERRED_SHELL=/bin/zsh` Or `/bin/bash`.
- `--CFG_ADMIN_STATIC_IP_ADDRESS=[ip]` e.g., `123.456.789.0`
- `--CFG_ADMIN_AUTHORIZED_SSH_KEYS=[file]` e.g., `/authorized_keys`

---

- `--CFG_TOOLS_USERNAME=admin` Administrative username.
- `--CFG_TOOLS_PASSWORD=admin` Administrative password.

---

- `--CFG_TOOLS_PMA_BLOWFISH_KEY=[key]` Secret key. Default is auto-generated.
- `--CFG_MAINTENANCE_BYPASS_KEY=[key]` Secret key. Default is auto-generated.

---

- `--CFG_MYSQL_DB_HOST=127.0.0.1` MySQL DB host name.
- `--CFG_MYSQL_DB_PORT=3306` MySQL DB port number.

---

- `--CFG_MYSQL_SSL_KEY=[file]` MySQL SSL key file.
- `--CFG_MYSQL_SSL_CRT=[file]` MySQL SSL crt file.
- `--CFG_MYSQL_SSL_CA=[file]` MySQL SSL ca file.
- `--CFG_MYSQL_SSL_CIPHER=[cipher]` MySQL SSL cipher.

---

- `--CFG_MYSQL_SSL_ENABLE=0|1` MySQL DB supports SSL connections?

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

- `--CFG_MYSQL_X_REQUIRES_SSL=0|1` External connection require SSL?

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

- `--CFG_INSTALL_NGINX=0|1` Install Nginx?
- `--CFG_INSTALL_APACHE=0|1` Install Apache?

---

- `--CFG_WEB_SERVER_SSL_ONLY=0|1` Require https:// ?

---

- `--CFG_INSTALL_MYSQL=0|1` Install MySQL?

---

- `--CFG_INSTALL_MEMCACHE=0|1` Install Memcached?
- `--CFG_INSTALL_RAMDISK=0|1` Install a RAM disk partition?

---

- `--CFG_INSTALL_PHP_CLI=0|1` Install PHP command-line interpreter?
- `--CFG_INSTALL_PHP_FPM=0|1` Install PHP-FPM process manager for Apache/Nginx?
- `--CFG_INSTALL_PHP_VERSION=[7.1|7.0|5.6|5.5]` Which PHP version to install?
- `--CFG_ENABLE_PHP_OPCACHE=0|1` Enable the PHP OPcache extension?
- `--CFG_INSTALL_PHP_XDEBUG=0|1` Install PHP XDebug extension?
- `--CFG_ENABLE_PHP_PHAR_READONLY=0|1` Force PHAR readonly mode?
- `--CFG_ENABLE_PHP_ASSERTIONS=0|1` Enable PHP assertions?

---

- `--CFG_INSTALL_PSYSH=0|1` Install Psysh?
- `--CFG_INSTALL_PHPCS=0|1` Install PHP Code Sniffer?
- `--CFG_INSTALL_PHING=0|1` Install Phing?
- `--CFG_INSTALL_PHPUNIT=0|1` Install PHPUnit?
- `--CFG_INSTALL_SAMI=0|1` Install Sami codex generator?
- `--CFG_INSTALL_APIGEN=0|1` Install APIGen codex generator?
- `--CFG_INSTALL_CASPERJS=0|1` Install CasperJS?
- `--CFG_INSTALL_COMPOSER=0|1` Install Composer?
- `--CFG_INSTALL_WP_I18N_TOOLS=0|1` Install WP i18n tools?

---

- `--CFG_INSTALL_APP_REPO=0|1` Install a Git repo for the `/app/src` directory?

---

- `--CFG_INSTALL_DISCOURSE=0|1` Install Discourse?

---

- `--CFG_DISCOURSE_SMTP_HOST=email-smtp.us-east-1.amazonaws.com` SMTP host name.
- `--CFG_DISCOURSE_SMTP_PORT=587` SMTP port number.

---

- `--CFG_DISCOURSE_SMTP_AUTH_TYPE=login` SMTP authentication type.

---

- `--CFG_DISCOURSE_SMTP_USERNAME=[username]` SMTP username.
- `--CFG_DISCOURSE_SMTP_PASSWORD=[password]` SMTP password.

---

- `--CFG_INSTALL_WP_CLI=0|1` Install WP CLI tool?

---

- `--CFG_INSTALL_WORDPRESS=0|1` Install WordPress?
- `--CFG_INSTALL_WORDPRESS_VM_SYMLINKS=0|1` Install symlinks?

---

- `--CFG_INSTALL_WORDPRESS_DEV_CONTAINERS=0|1` Install WordPress dev containers?
  - This installs multiple versions of PHP (running WP in Docker containers) for additional testing.

---

- `--CFG_WORDPRESS_DEV_USERNAME=[username]` Install for another user?
- `--CFG_WORDPRESS_DEV_PASSWORD=[password]` Default is auto-generated.

---

- `--CFG_WORDPRESS_DEV_GENDER=[male|female]` The other user's gender.

---

- `--CFG_WORDPRESS_DEV_NAME=[name]` The other user's name.
- `--CFG_WORDPRESS_DEV_EMAIL=[email]` The other user's email address.

---

- `--CFG_WORDPRESS_DEV_PREFERRED_SHELL=/bin/zsh` Or `/bin/bash`.
- `--CFG_WORDPRESS_DEV_AUTHORIZED_SSH_KEYS=[file]` e.g., `/authorized_keys`

---

- `--CFG_INSTALL_FIREWALL=0|1` Install firewall?
- `--CFG_INSTALL_FAIL2BAN=0|1` Install Fail2Ban?

---

- `--CFG_FIREWALL_ALLOWS_ADMIN_ONLY_VIA_22=0|1` Allow admin only?
  - Requires `--CFG_ADMIN_STATIC_IP_ADDRESS` ; for production use only.
- `--CFG_FIREWALL_ALLOWS_MYSQL_VIA_3306=0|1` Allow external connections to MySQL?
- `--CFG_FIREWALL_ALLOWS_MYSQL_INSIDE_VPN=0|1` Allow network-based connections?
- `--CFG_FIREWALL_ALLOWS_CF_ONLY_VIA_80_443=0|1` Allow only CloudFlare?

---

- `--CFG_INSTALL_UNATTENDED_UPGRADES=0|1` Configure unattended upgrades?

---

- `--CFG_CONFIG_FILE=/app/.config.json` This is unrelated to the installer. It's for any purpose you like. e.g., To help you configure a web-based application that will run on this VM. The value that you set here becomes a global environment variable that your application can consume in any way you like. It is expected to be a config file path on the VM.

---

#### Bootstrapping Base Images for Custom Vagrant Boxes

Please see: [Packaging a Custom Box](https://github.com/websharks/ubuntu-bootstrap/wiki/Packaging-a-Custom-Box) for full instructions.
