Vagrant.configure(2) do |config|
  config.vm.box = 'ubuntu/trusty64';

  # Configure the hostname for this VM.
  config.vm.hostname = 'ubuntu.vm'; # Default value.
  if !File.dirname(File.expand_path(__FILE__)).scan(/\.vm$/i).empty?
    config.vm.hostname = File.basename(File.dirname(File.expand_path(__FILE__)));
  end;
  # Configure vars for the VM hostname (lower & upper).
  _vm_hostname_lc_var = config.vm.hostname.downcase.tr('.-', '_');
  _VM_HOSTNAME_UC_VAR = config.vm.hostname.upcase.tr('.-', '_');

  # Mount `/vagrant/src/app/src` in a specific way. See `src/setups/mkdirs` for details.
  config.vm.synced_folder './src/app/src', '/vagrant/src/app/src', mount_options: ['defaults', 'uid=www-data', 'gid=www-data', 'umask=002'];

  # Mount WordPress projects directory.
  if File.directory?(wp_projects_dir = ENV["WP_#{_VM_HOSTNAME_UC_VAR}_PROJECTS_DIR"] || ENV['WP_PROJECTS_DIR'] || File.expand_path('~/projects/wordpress'))
    config.vm.synced_folder wp_projects_dir, '/wordpress', mount_options: ['defaults', 'ro'];
  end;

  # Mount WordPress personal projects directory.
  if File.directory?(wp_personal_projects_dir = ENV["WP_#{_VM_HOSTNAME_UC_VAR}_PERSONAL_PROJECTS_DIR"] || ENV['WP_PERSONAL_PROJECTS_DIR'] || File.expand_path('~/projects/personal/wordpress'))
    config.vm.synced_folder wp_personal_projects_dir, '/wp-personal', mount_options: ['defaults', 'ro'];
  end;

  # Mount WordPress business projects directory.
  if File.directory?(wp_business_projects_dir = ENV["WP_#{_VM_HOSTNAME_UC_VAR}_BUSINESS_PROJECTS_DIR"] || ENV['WP_BUSINESS_PROJECTS_DIR'] || File.expand_path('~/projects/business/wordpress'))
    config.vm.synced_folder wp_business_projects_dir, '/wp-business', mount_options: ['defaults', 'ro'];
  end;

  # Allow SSH keys that the user has to be used on the VM w/o copying them to the VM.
  config.ssh.forward_agent = true; # Does not work in scripts; shell only.

  # Configure DNS automatically? Exclude when building a base image.
  if Vagrant.has_plugin?('landrush') && ENV['VM_4CI'] != '1' && ENV['VM_4PKG'] != '1'
    config.landrush.enabled = true; # Enable landrush plugin.
    config.landrush.tld = 'vm'; # Set landrush TLD for this VM.
    config.landrush.upstream '8.8.8.8'; # Google public DNS.
  end;

  # Configure box-specific caching.
  if Vagrant.has_plugin?('vagrant-cachier')
    config.cache.scope = :box;
    config.cache.enable :apt;
  end;

  # Configure resource allocations.
  config.vm.provider 'virtualbox' do |vb|
    vb.customize ['modifyvm', :id, '--memory', '512'];
    vb.customize ['modifyvm', :id, '--vram', '128'];
    vb.customize ['modifyvm', :id, '--cpus', '1'];
  end;

  # When building a VM that is going to be packaged-up.
  if ENV['VM_4CI'] == '1' # Avoids problems when packaging a box.
    config.vm.provision :shell, inline: 'touch /etc/vm-4ci.cfg;';
  end;

  # When building a VM that is going to be packaged-up.
  if ENV['VM_4CI'] == '1' || ENV['VM_4PKG'] == '1' # Packaging.
    config.vm.provision :shell, inline: 'touch /etc/vm-4pkg.cfg;';
    config.ssh.insert_key = false; # Exclude unique SSH key.
  end;

  # Run script as part of the provisioning process.
  config.vm.provision :shell, path: 'src/vagrant/bootstrap', run: 'always';

  # Run script as part of the provisioning process.
  if Vagrant.has_plugin?('vagrant-triggers')
    config.trigger.after [:up, :resume], :append_to_path => File.dirname(File.expand_path(__FILE__)) do
      run 'src/vagrant/bootstrap-me';
    end;
  end;
end;
