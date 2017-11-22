# For a custom Box.

Vagrant.configure(2) do |config|

  # Static IP; if not using Landrush.

  _static_ip = '192.168.52.52';

  # Required box configuration.

  config.vm.box = 'ubuntu/xenial64';

  # Configure hostname for this VM.

  config.vm.hostname = 'my.vm'; # Default value.
  # Changed below if the directory containing this file ends with `.my.vm`.

  if !File.dirname(File.expand_path(__FILE__)).scan(/\.my\.vm$/i).empty?
    config.vm.hostname = File.basename(File.dirname(File.expand_path(__FILE__)));
  end; # If in a `.vm` directory, set a matching hostname.

  _vm_hostname_lc_var = config.vm.hostname.downcase.tr('.-', '_');
  _VM_HOSTNAME_UC_VAR = config.vm.hostname.upcase.tr('.-', '_');

  # Configure VirtualBox name, DNS, and resources.

  if Vagrant.has_plugin?('vagrant-disksize')
    config.disksize.size = '20GB'; end;

  config.vm.provider 'virtualbox' do |vb|
    vb.name = 'websharks-ubuntu-xenial-16.04-lts-'+"#{config.vm.hostname}";
    vb.customize ['modifyvm', :id, '--natdnshostresolver1', 'on'];
    vb.customize ['modifyvm', :id, '--natdnsproxy1', 'on'];
    vb.customize ['modifyvm', :id, '--memory', '512'];
    vb.customize ['modifyvm', :id, '--vram', '128'];
    vb.customize ['modifyvm', :id, '--cpus', '1'];
  end; # ↑ Increase resources for a more powerful VM.

  # Enable SSH agent forwarding on the VM.

  config.ssh.forward_agent = true; # Forwards SSH keys.

  # Mount shared/synced directories on the VM.

  config.vm.synced_folder '.', '/vagrant', mount_options: ['defaults'];

  if File.directory?(app_dir = File.expand_path('./app'))
    config.vm.synced_folder app_dir, '/app', mount_options: ['defaults', 'uid=nobody', 'gid=app', 'umask=002'];
  end;
  if File.directory?(app_src_dir = File.expand_path('./app/src'))
    config.vm.synced_folder app_src_dir, '/app/src', mount_options: ['defaults', 'uid=www-data', 'gid=app', 'umask=002'];
  end;

  wp_project_dirs = ( ENV["WP_#{_VM_HOSTNAME_UC_VAR}_PROJECTS_DIR"] || ENV['WP_PROJECTS_DIR'] || File.expand_path('~/projects/wordpress') ).split(/[:;]+/).map(&:strip);
  wp_personal_project_dirs = ( ENV["WP_#{_VM_HOSTNAME_UC_VAR}_PERSONAL_PROJECTS_DIR"] || ENV['WP_PERSONAL_PROJECTS_DIR'] || File.expand_path('~/projects/personal/wordpress') ).split(/[:;]+/).map(&:strip);
  wp_business_project_dirs = ( ENV["WP_#{_VM_HOSTNAME_UC_VAR}_BUSINESS_PROJECTS_DIR"] || ENV['WP_BUSINESS_PROJECTS_DIR'] || File.expand_path('~/projects/business/wordpress') ).split(/[:;]+/).map(&:strip);

  wp_project_dirs.each_with_index do |dir, i|
    if File.directory?(dir)
      config.vm.synced_folder dir, "/wp-projects-#{i}", mount_options: ['defaults', 'ro'];
    end;
  end;
  wp_personal_project_dirs.each_with_index do |dir, i|
    if File.directory?(dir)
      config.vm.synced_folder dir, "/wp-personal-#{i}", mount_options: ['defaults', 'ro'];
    end;
  end;
  wp_business_project_dirs.each_with_index do |dir, i|
    if File.directory?(dir)
      config.vm.synced_folder dir, "/wp-business-#{i}", mount_options: ['defaults', 'ro'];
    end;
  end;

  # Configure DNS using one of two compatible plugins.

  if Vagrant.has_plugin?('landrush')
    config.landrush.enabled = true;
    config.landrush.tld = 'vm';
    config.landrush.upstream '8.8.8.8';
    config.landrush.guest_redirect_dns = false;

  elsif Vagrant.has_plugin?('vagrant-hostsupdater')
    config.vm.network :private_network, ip: _static_ip;
    config.hostsupdater.aliases = [
      'my.'+"#{config.vm.hostname}", 'dev.'+"#{config.vm.hostname}",
      'io.'+"#{config.vm.hostname}", 'api.'+"#{config.vm.hostname}", 'cdn.'+"#{config.vm.hostname}",
      'sub.'+"#{config.vm.hostname}", 'sub1.'+"#{config.vm.hostname}", 'sub2.'+"#{config.vm.hostname}", 'sub3.'+"#{config.vm.hostname}",
      'php54.'+"#{config.vm.hostname}", 'php55.'+"#{config.vm.hostname}", 'php56.'+"#{config.vm.hostname}", 'php70.'+"#{config.vm.hostname}", 'php71.'+"#{config.vm.hostname}",
    ];
  else config.vm.network :private_network, ip: _static_ip; end;

  # Enable caching if the `vagrant-cachier` plugin is installed.

  if Vagrant.has_plugin?('vagrant-cachier')
    config.cache.scope = :box; end;

  # Configure provisioners for this VM.

  config.vm.provision :shell, inline: '/bootstrap/src/vagrant/bootstrap;', run: 'always';
end;
