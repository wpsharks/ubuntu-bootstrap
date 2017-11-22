The reasons for creating a base Box vary, but the greatest advantage is speed! The WUB installer is extremely flexible, but it's also extremely slow, given the fact that it's an installer, and not simply a VM that is already precompiled for you. By creating a custom base Box, you will reduce the time it takes to spin-up a new VM by about 97%!

## Start w/ a Fresh Copy of the WUB

_**IMPORTANT:** The folder (i.e., host name) that you choose here will become the root domain for all of your custom VMs. I suggest using `my.vm` from this example, but you can change it. So long as it ends with `.vm` please._

```bash
$ cd ~/vms;
$ git clone git@github.com:websharks/ubuntu-bootstrap.git my.vm;
$ cd ~/vms/my.vm;
```

## WUB Packaging Flag (Vagrant Up)

When you bring up a new VM, tell the Ubuntu Bootstrap that you want to enter Packaging Mode. This is done from the command-line on the Host (i.e., from your own personal system) with environment variable `VM_4PKG=1`.

```bash
$ cd ~/vms/my.vm;
$ VM_4PKG=1 vagrant up;
```

## Log In Via SSH

```bash
$ vagrant ssh
```

## Run Bootstrap Installer

```bash
$ sudo /bootstrap/src/installer
```

A dialog will open so you can choose your installation options; e.g., installing your choice of Nginx or Apache, MySQL, firewall, etc, etc. The defaults (in 'Packaging Mode') should work just fine in most cases, but you can customize all you like.

_**Note:** From the installation dialog, one option that you should NOT choose to install, is 'WordPress VM Symlinks'. Symlinks are disabled by default in Packaging Mode. Shared folder symlinks should not become a part of any base image._

_**Note:** You can install any additional software you'd like after the WUB installer is finished; e.g., using `$ apt-get install ...` However, please note that you should **not** do anything on this VM that would leave it in a state that is not suitable for easy reuse later. For instance, don't access the VM from a web browser and install a bunch of WordPress themes/plugins. You want the base Box to be just that, a base to work from later. What most people want from a base image is Nginx/Apache, MySQL, and PHP._

## Log Out of the VM

When you're all done with software installation, log out of the VM.

```bash
$ exit
```

## Package VM as a Base Box

```bash
$ cd ~/vms/my.vm
$ mkdir -p ~/vms/my-boxes;
$ vagrant package --output ~/vms/my-boxes/my.box;
```

🎉 Congratulations! You now have a base Box to work from. The next time you bring up a VM, instead of waiting for the WUB installer to download and install all of the base packages/libraries that it needs, you have an already-compiled base Box that you can spin-up in just a few seconds whenever you like, for as many instances as you need.

## Tell Vagrant About Your Box

```bash
$ cd ~/vms/my.vm
$ vagrant box add [YOU]/[DESCRIPTIVE SLUG] ~/vms/my-boxes/my.box;
# e.g., vagrant box add jaswrks/ubuntu-xenial64-wub-nginx ~/vms/my-boxes/my.box;
```

## Do a Little Housekeeping

```bash
$ cd ~/vms/my.vm
$ vagrant destroy --force;

$ cd ~/vms;
$ rm -rf my.vm;
```

## Using Your Pre-Compiled Custom Box 🍰

### Create Custom VM Directory & `Vagrantfile`

These instructions use an [already-prepared `Vagrantfile` template](https://github.com/websharks/ubuntu-bootstrap/blob/dev/.box.Vagrantfile) (provided by the WUB) that is designed specifically for this purpose; i.e., to be used when spinning up a new VM based on a custom Box.

_**IMPORTANT:** The folder (i.e., host name) that you choose here, must end with the root domain that you built your base Box with. If you used `my.vm`, then any VM you create that is based on that Box needs to be either the root host name itself: `my.vm`, or a sub-domain of your root: `something.my.vm`. In this example I built the box with Nginx, so I'll call it `nginx.my.vm`._

```bash
$ mkdir ~/vms/nginx.my.vm;
$ cd ~/vms/nginx.my.vm;
$ curl https://raw.githubusercontent.com/websharks/ubuntu-bootstrap/dev/.box.Vagrantfile -Lo Vagrantfile;
```

### Customize `Vagrantfile`

```bash
$ vi Vagrantfile;
```

Change this line:

```ruby
config.vm.box = 'ubuntu/xenial64';
```

To the name of your base Box:

```ruby
config.vm.box = '[YOU]/[DESCRIPTIVE SLUG]';
# e.g., config.vm.box = 'jaswrks/ubuntu-xenial64-wub-nginx';
```

_**Tip:** At this point, it would be a good idea to start a new personal GitHub repository with your `Vagrantfile` being the only file for now, that's fine. Just save it somewhere so you can use it again later as a faster starting point. Or you can share your repo with others._

_**For example, if you have [`hub`](https://github.com/github/hub)...**_

```bash
$ cd ~/vms/nginx.my.vm;
$ git init
$ git add --all
$ git commit -m 'Initialzing VM repo.'
$ git create [YOU]/[DESCRIPTIVE SLUG]
# e.g., git create jaswrks/ubuntu-xenial64-wub-nginx
```

### Vagrant Up

```bash
$ cd ~/vms/nginx.my.vm;
$ vagrant up # This should take just a few seconds.
```

### Install WordPress (Latest)

_This assumes that you didn't install WordPress in the base Box already. By default, WordPress is not installed when you build a base Box in 'Packaging Mode', and that's suggested. Instead, `$ wp` (**WP CLI**) can do it without much fuss._

```bash
$ vagrant ssh;
$ cd /app/src;

$ wp core download;

# All of these variables already exist in any WUB installation.
$ wp core config --dbhost="${CFG_MYSQL_DB_HOST}" --dbname="${CFG_MYSQL_DB_NAME}" \
  --dbcharset="${CFG_MYSQL_DB_CHARSET}" --dbcollate="${CFG_MYSQL_DB_COLLATE}" \
  --dbuser="${CFG_MYSQL_DB_USERNAME}" --dbpass="${CFG_MYSQL_DB_PASSWORD}";

$ wp core install --url=https://nginx.my.vm --title=WordPress \
  --admin_user=admin --admin_password=admin --admin_email=admin@nginx.my.vm;

$ sudo chown --recursive www-data /app/src;
$ sudo find /app/src -type d -exec chmod 2775 {} \;
$ sudo find /app/src -type f -exec chmod 664 {} \;
```

_**Tip:** There are other ways to install and configure WordPress; e.g., Puppet, or simply include it in your GitHub repo and mount it on the VM using your `Vagrantfile`. The web-accessible directory on the VM is `/app/src`. So that's where WordPress should go._

_For example, in your custom `Vagrantfile`..._

```ruby
config.vm.synced_folder './wordpress', '/app/src', mount_options: ['defaults', 'uid=www-data', 'gid=www-data', 'umask=002'];
```

_Where `./wordpress` is a directory in your GitHub repo._

### Access Your Custom VM

https://nginx.my.vm

## What's Next? :octocat:

- Learn more about your [`Vagrantfile`](https://www.vagrantup.com/docs/vagrantfile/).

- If you'd like to share your `.box` file with others, create an account at Hashicorp and upload the `~/vms/my-boxes/my.box` file, then 'release' it, giving others a fast track to the same fun you've had. See: https://www.hashicorp.com/ e.g., https://atlas.hashicorp.com/websharksThe reasons for creating a base Box vary, but the greatest advantage is speed! The WUB installer is extremely flexible, but it's also extremely slow, given the fact that it's an installer, and not simply a VM that is already precompiled for you. By creating a custom base Box, you will reduce the time it takes to spin-up a new VM by about 97%!

## Start w/ a Fresh Copy of the WUB

_**IMPORTANT:** The folder (i.e., host name) that you choose here will become the root domain for all of your custom VMs. I suggest using `my.vm` from this example, but you can change it. So long as it ends with `.vm` please._

```bash
$ cd ~/vms;
$ git clone git@github.com:websharks/ubuntu-bootstrap.git my.vm;
$ cd ~/vms/my.vm;
```

## WUB Packaging Flag (Vagrant Up)

When you bring up a new VM, tell the Ubuntu Bootstrap that you want to enter Packaging Mode. This is done from the command-line on the Host (i.e., from your own personal system) with environment variable `VM_4PKG=1`.

```bash
$ cd ~/vms/my.vm;
$ VM_4PKG=1 vagrant up;
```

## Log In Via SSH

```bash
$ vagrant ssh
```

## Run Bootstrap Installer

```bash
$ sudo /bootstrap/src/installer
```

A dialog will open so you can choose your installation options; e.g., installing your choice of Nginx or Apache, MySQL, firewall, etc, etc. The defaults (in 'Packaging Mode') should work just fine in most cases, but you can customize all you like.

_**Note:** From the installation dialog, one option that you should NOT choose to install, is 'WordPress VM Symlinks'. Symlinks are disabled by default in Packaging Mode. Shared folder symlinks should not become a part of any base image._

_**Note:** You can install any additional software you'd like after the WUB installer is finished; e.g., using `$ apt-get install ...` However, please note that you should **not** do anything on this VM that would leave it in a state that is not suitable for easy reuse later. For instance, don't access the VM from a web browser and install a bunch of WordPress themes/plugins. You want the base Box to be just that, a base to work from later. What most people want from a base image is Nginx/Apache, MySQL, and PHP._

## Log Out of the VM

When you're all done with software installation, log out of the VM.

```bash
$ exit
```

## Package VM as a Base Box

```bash
$ cd ~/vms/my.vm
$ mkdir -p ~/vms/my-boxes;
$ vagrant package --output ~/vms/my-boxes/my.box;
```

🎉 Congratulations! You now have a base Box to work from. The next time you bring up a VM, instead of waiting for the WUB installer to download and install all of the base packages/libraries that it needs, you have an already-compiled base Box that you can spin-up in just a few seconds whenever you like, for as many instances as you need.

## Tell Vagrant About Your Box

```bash
$ cd ~/vms/my.vm
$ vagrant box add [YOU]/[DESCRIPTIVE SLUG] ~/vms/my-boxes/my.box;
# e.g., vagrant box add jaswrks/ubuntu-xenial64-wub-nginx ~/vms/my-boxes/my.box;
```

## Do a Little Housekeeping

```bash
$ cd ~/vms/my.vm
$ vagrant destroy --force;

$ cd ~/vms;
$ rm -rf my.vm;
```

## Using Your Pre-Compiled Custom Box 🍰

### Create Custom VM Directory & `Vagrantfile`

These instructions use an [already-prepared `Vagrantfile` template](https://github.com/websharks/ubuntu-bootstrap/blob/dev/.box.Vagrantfile) (provided by the WUB) that is designed specifically for this purpose; i.e., to be used when spinning up a new VM based on a custom Box.

_**IMPORTANT:** The folder (i.e., host name) that you choose here, must end with the root domain that you built your base Box with. If you used `my.vm`, then any VM you create that is based on that Box needs to be either the root host name itself: `my.vm`, or a sub-domain of your root: `something.my.vm`. In this example I built the box with Nginx, so I'll call it `nginx.my.vm`._

```bash
$ mkdir ~/vms/nginx.my.vm;
$ cd ~/vms/nginx.my.vm;
$ curl https://raw.githubusercontent.com/websharks/ubuntu-bootstrap/dev/.box.Vagrantfile -Lo Vagrantfile;
```

### Customize `Vagrantfile`

```bash
$ vi Vagrantfile;
```

Change this line:

```ruby
config.vm.box = 'ubuntu/xenial64';
```

To the name of your base Box:

```ruby
config.vm.box = '[YOU]/[DESCRIPTIVE SLUG]';
# e.g., config.vm.box = 'jaswrks/ubuntu-xenial64-wub-nginx';
```

_**Tip:** At this point, it would be a good idea to start a new personal GitHub repository with your `Vagrantfile` being the only file for now, that's fine. Just save it somewhere so you can use it again later as a faster starting point. Or you can share your repo with others._

_**For example, if you have [`hub`](https://github.com/github/hub)...**_

```bash
$ cd ~/vms/nginx.my.vm;
$ git init
$ git add --all
$ git commit -m 'Initialzing VM repo.'
$ git create [YOU]/[DESCRIPTIVE SLUG]
# e.g., git create jaswrks/ubuntu-xenial64-wub-nginx
```

### Vagrant Up

```bash
$ cd ~/vms/nginx.my.vm;
$ vagrant up # This should take just a few seconds.
```

### Install WordPress (Latest)

_This assumes that you didn't install WordPress in the base Box already. By default, WordPress is not installed when you build a base Box in 'Packaging Mode', and that's suggested. Instead, `$ wp` (**WP CLI**) can do it without much fuss._

```bash
$ vagrant ssh;
$ cd /app/src;

$ wp core download;

# All of these variables already exist in any WUB installation.
$ wp core config --dbhost="${CFG_MYSQL_DB_HOST}" --dbname="${CFG_MYSQL_DB_NAME}" \
  --dbcharset="${CFG_MYSQL_DB_CHARSET}" --dbcollate="${CFG_MYSQL_DB_COLLATE}" \
  --dbuser="${CFG_MYSQL_DB_USERNAME}" --dbpass="${CFG_MYSQL_DB_PASSWORD}";

$ wp core install --url=https://nginx.my.vm --title=WordPress \
  --admin_user=admin --admin_password=admin --admin_email=admin@nginx.my.vm;

$ sudo chown --recursive www-data /app/src;
$ sudo find /app/src -type d -exec chmod 2775 {} \;
$ sudo find /app/src -type f -exec chmod 664 {} \;
```

_**Tip:** There are other ways to install and configure WordPress; e.g., Puppet, or simply include it in your GitHub repo and mount it on the VM using your `Vagrantfile`. The web-accessible directory on the VM is `/app/src`. So that's where WordPress should go._

_For example, in your custom `Vagrantfile`..._

```ruby
config.vm.synced_folder './wordpress', '/app/src', mount_options: ['defaults', 'uid=www-data', 'gid=www-data', 'umask=002'];
```

_Where `./wordpress` is a directory in your GitHub repo._

### Access Your Custom VM

https://nginx.my.vm

## What's Next? :octocat:

- Learn more about your [`Vagrantfile`](https://www.vagrantup.com/docs/vagrantfile/).

- If you'd like to share your `.box` file with others, create an account at Hashicorp and upload the `~/vms/my-boxes/my.box` file, then 'release' it, giving others a fast track to the same fun you've had. See: https://www.hashicorp.com/ e.g., https://atlas.hashicorp.com/websharks
