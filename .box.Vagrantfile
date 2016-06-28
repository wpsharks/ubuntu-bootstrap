# For a custom Box.

Vagrant.configure(2) do |config|

  # Static IP; if not using Landrush.

  _static_ip = '192.168.52.52';

  # Required box configuration.

  config.vm.box = 'websharks/ubuntu-xenial64';

  # Configure hostname for this VM.

  config.vm.hostname = 'ubuntu.my.vm'; # Default value.
  # Changed below if the directory containing this file ends with `.my.vm`.

  if !File.dirname(File.expand_path(__FILE__)).scan(/\.my\.vm$/i).empty?
    config.vm.hostname = File.basename(File.dirname(File.expand_path(__FILE__)));
  end; # If in a `.vm` directory, set a matching hostname.

  _vm_hostname_lc_var = config.vm.hostname.downcase.tr('.-', '_');
  _VM_HOSTNAME_UC_VAR = config.vm.hostname.upcase.tr('.-', '_');

  # Configure VirtualBox name, DNS, and resources.

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

  if File.directory?(wp_projects_dir = ENV["WP_#{_VM_HOSTNAME_UC_VAR}_PROJECTS_DIR"] || ENV['WP_PROJECTS_DIR'] || File.expand_path('~/projects/wordpress'))
    config.vm.synced_folder wp_projects_dir, '/wordpress', mount_options: ['defaults', 'ro'];
  end;
  if File.directory?(wp_personal_projects_dir = ENV["WP_#{_VM_HOSTNAME_UC_VAR}_PERSONAL_PROJECTS_DIR"] || ENV['WP_PERSONAL_PROJECTS_DIR'] || File.expand_path('~/projects/personal/wordpress'))
    config.vm.synced_folder wp_personal_projects_dir, '/wp-personal', mount_options: ['defaults', 'ro'];
  end;
  if File.directory?(wp_business_projects_dir = ENV["WP_#{_VM_HOSTNAME_UC_VAR}_BUSINESS_PROJECTS_DIR"] || ENV['WP_BUSINESS_PROJECTS_DIR'] || File.expand_path('~/projects/business/wordpress'))
    config.vm.synced_folder wp_business_projects_dir, '/wp-business', mount_options: ['defaults', 'ro'];
  end;

  # Configure DNS using one of two compatible plugins.

  if Vagrant.has_plugin?('landrush')
    config.landrush.enabled = true;
    config.landrush.tld = 'vm';
    config.landrush.upstream '8.8.8.8';
    config.landrush.guest_redirect_dns = false;

  elsif Vagrant.has_plugin?('vagrant-hostsupdater')
    config.vm.network :private_network, ip: _static_ip; # Static IP is far less flexible.
    config.hostsupdater.aliases = ['sub.'+"#{config.vm.hostname}", 'sub1.'+"#{config.vm.hostname}", 'sub2.'+"#{config.vm.hostname}", 'sub3.'+"#{config.vm.hostname}"]

  else config.vm.network :private_network, ip: _static_ip; end;
end;
