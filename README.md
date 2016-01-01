## VirtualBox + Vagrant + Landrush; running Ubuntu w/ Nginx (or Apache), MariaDB (MySQL), PHP (choice of custom 7.0+, 7.0, 5.6, 5.5, 5.4), and WordPress

![](http://cdn.websharks-inc.com/jaswsinc/uploads/2015/03/os-x-vagrant-virtualbox.png)

---

### Installation Instructions

#### Step 1: Satisfy Software Requirements

You need to have VirtualBox, Vagrant, and Landrush installed.

```bash
$ brew cask install virtualbox
$ brew cask install vagrant
$ vagrant plugin install landrush
$ vagrant plugin install vagrant-cachier # Suggested (optional).
$ vagrant plugin install vagrant-triggers # Suggested (optional).
```

You need to install the `ubuntu/trusty64` Box.

```bash
$ vagrant box add ubuntu/trusty64
```

#### Step 2: Clone GitHub Repo (Ubuntu LEMP Stack)

```bash
$ mkdir ~/VMs && cd ~/VMs
$ git clone https://github.com/jaswsinc/vagrant-ubuntu-lemp my.vm
```

_Note that `my.vm` becomes your domain name. Change it if you like. Must end with `.vm` please._

#### Step 3: Vagrant Up!

```bash
$ cd ~/VMs/my.vm
$ vagrant up
```

#### Step 4: Install software :-)

```bash
$ vagrant ssh
$ sudo /bootstrap/installer # Presents a configuration dialog.
# Tip: to bypass configuration, add `--use-defaults` argument to the installer.
```

#### Step 5: Confirm it is Working!

- Open <http://my.vm>. You should see a WordPress installation page.
- Open <https://my.vm>. You should get an SSL security warning. Please bypass this self-signed certificate warning and proceed. You should again see the WordPress installation page. SSL is working as expected!

  _The URL <http://my.vm> is not working?_

  _Try flushing your DNS cache. Each time you `vagrant up`, a new IP is generated automatically that is mapped to the `my.vm` hostname. If you are working with multiple VMs, you might need to flush your DNS cache to make sure your system is mapping `my.vm` to the correct IP address. See: <http://jas.xyz/1fmAa4P> for instructions on a Mac._

---

### Additional Steps (All Optional)

#### Step 6: Add Files to: `~/VMs/my.vm/app/src/`

The is the web root. The latest version of WordPress will already be installed. However, you can add any additional application files that you'd like. e.g., phpBB, Drupal, Joomla, whatever you like. It's probably a good idea to put anything new inside a sub-directory of its own; e.g., `~/VMs/my.vm/app/src/phpBB`

#### Step 7: Understanding Environment Variables

This stack comes preconfigured with a MySQL database and environment variables you can use in any PHP config. files.

- `$_SERVER['CFG_MYSQL_DB_HOST']` This is the database host name. Defaults to `127.0.0.1`. Port is `3306` (default port).
- `$_SERVER['CFG_MYSQL_DB_NAME']` This is the database name. Defaults to `admin`.
- `$_SERVER['CFG_MYSQL_DB_USERNAME']` This is the database username. Defaults to `admin`.
- `$_SERVER['CFG_MYSQL_DB_PASSWORD']` This is the database password. Defaults to `admin`.

_**Tip:** For a full list of all global environment variables, see: `setups/env-vars` in the repo._

#### Step 8: Learn to Use the Tools That I've Bundled

A username/password is required to access each of these tools. It is always the same thing.

- Username: `admin` Password: `admin`

Available Tools (Using Any of These is Optional):

- <https://my.vm/tools/pma> PhpMyAdmin
  DB name: `admin`, DB username: `admin`, DB password: `admin`
- <https://my.vm/tools/opcache.php> PHP OPcache extension status dump.
- <https://my.vm/tools/info.php> PHP info (i.e., `phpinfo()`) page.
- <https://my.vm/tools/fpm-status.php> PHP-FPM status page.
- <https://my.vm/tools/status.nginx> NGINX status page.

#### Step 9: Tear it Down and Customize

```bash
$ cd ~/VMs/my.vm
$ vagrant destroy
```

In the project directory you'll find a `/vg-bootstrap` file. This bash script runs as the `root` user during `vagrant up`. Therefore, you can install software and configure anything you like in this script. By default, this script doesn't do much. All of the software installation and system configuration takes place whenever you run `/bootstrap/installer` inside the VM.

##### Customization (Two Choices Available)

1. Customize `/bootstrap/installer` and the associated setup files that it calls upon, which are located in: `/assets/setups/*`. _**Note:** If you go this route, there really is no reason to customize `/vg-bootstrap`. You can leave it as-is._

2. Instead of working with the more complex installer, you can keep things simple and add your customizations to the `/vg-bootstrap` script, which is a very simple starting point. The `/vg-bootstrap` runs whenever you type `vagrant up`, so this is a logical choice for beginners. _**Note:** If you go this route, you can simply choose not to run `/bootstrap/installer`, because all of your customizations will be in the `/vg-bootstrap`; i.e., there will be no reason to run the installer._

##### When you're done with your customizations, type:

```bash
$ vagrant up
```

###### If you decided to use the `/installer` option, also type:

```bash
$ vagrant ssh
$ /bootstrap/installer
```

---

### Domain Name Tips & Tricks

#### Creating a Second VM w/ a Different Domain Name

```bash
$ git clone https://github.com/jaswsinc/vagrant-ubuntu-lemp my-second.vm
$ cd my-second.vm
$ vagrant up
$ vagrant ssh
$ /bootstrap/installer
```

#### Understanding Domain Name Mapping

The URL which leads to your VM is based on the name of the directory that you cloned the repo into; e.g., `my.vm` or `my-second.vm` in the above examples. However, the directory that you clone into MUST end with `.vm` for this to work as expected. If the directory you cloned into doesn't end with `.vm`, the default domain name will be `http://ubuntu.vm`. You can change this hard-coded default by editing `config.vm.hostname` in `Vagrantfile`.

In either case, the domain name is also wildcarded; i.e., `my.vm`, `www.my.vm`, `wordpress.my.vm` all map to the exact same location: `~/VMs/my.vm/app/src/`. This is helpful when testing WordPress Multisite Networks, because you can easily setup a sub-domain network, or even an MU domain mapping plugin.

---

### Testing WordPress Themes/Plugins Easily!

See `/Vagrantfile` where you will find this section already implemented.
_~ See also: `/assets/setups/wordpress`_

```ruby
# Mount WordPress project directory.
if File.directory? File.expand_path('~/projects/wordpress')
  config.vm.synced_folder File.expand_path('~/projects/wordpress'), '/wordpress'
end

# Mount WordPress project directory.
if File.directory? File.expand_path('~/projects/jaswsinc/wordpress')
  config.vm.synced_folder File.expand_path('~/projects/jaswsinc/wordpress'), '/jaswsinc-wordpress'
end

# Mount WordPress project directory.
if File.directory? File.expand_path('~/projects/websharks/wordpress')
  config.vm.synced_folder File.expand_path('~/projects/websharks/wordpress'), '/websharks-wordpress'
end
```

#### ↑ What is happening here w/ these WordPress directories?

The `Vagrantfile` is automatically mounting drives on your VM that are sourced by your local `~/projects` directory (if you have one). Thus, if you have your WordPress themes/plugins in `~/projects/wordpress` (i.e., in your local filesystem), it will be mounted on the VM automatically, as `/wordpress`.

In the `assets/setups/wordpress` file, we iterate `/wordpress` and build symlinks for each of your themes/plugins automatically. This means that when you log into your WordPress Dashboard (<http://my.vm/wp-admin/>), you will have all of your themes/plugins available for testing. If you make edits locally in your favorite editor, they are updated in real-time on the VM. Very cool!

The additional mounts (i.e., `~/projects/jaswsinc/wordpress` and `~/projects/websharks/wordpress`) are simply alternate locations that I use personally. Remove them if you like. See: `Vagrantfile` and `assets/setups/wordpress` to remove in both places. You don't really _need_ to remove them though, because if these locations don't exist on your system they simply will not be mounted. In fact, you might consider leaving them, and just alter the paths to reflect your own personal preference—or for future implementation.

#### The default WordPress mapping looks like this:

- `~/projects/wordpress` on your local system.
  - Is mounted on the VM, as: `/wordpress`
- Then (on the VM) the `/assets/setups/wordpress` script symlinks each theme/plugin into:
  - `/app/src/wp-content/[themes|plugins]` appropriately.

#### What directory structure do I need exactly?

Inside `~/projects/wordpress` you need to have two sub-directories. One for themes and another for plugins.

- `~/projects/wordpress/themes` (put WP themes in this directory; e.g., `my-theme`)
- `~/projects/wordpress/plugins` (put WP plugins here; e.g., `my-plugin`)

Now, whenever you run `/bootstrap/installer` from the VM, your local copy of `~/projects/wordpress/themes/my-theme` becomes `/app/src/wp-content/themes/my-theme` on the VM. Your local copy of `~/projects/wordpress/plugins/my-plugin` becomes `/app/src/wp-content/plugins/my-plugin` on the VM ... and so on... for each theme/plugin sub-directory, and for each of the three possible mounts listed above. This all happens automatically if you followed the instructions correctly.
