Vagrant.configure(2) do |config|
  config.vm.box = 'ubuntu/trusty64';

  # Configure the hostname for this VM.
  config.vm.hostname = 'ubuntu.vm'; # Default value.
  if !File.dirname(File.expand_path(__FILE__)).scan(/\.vm$/i).empty?
    config.vm.hostname = File.basename(File.dirname(File.expand_path(__FILE__)));
  end;

  # Mount `/vagrant` as `/bootstrap`.
  config.vm.synced_folder '.', '/bootstrap';
  config.vm.synced_folder '.', '/vagrant', disabled: true;

  # Mount WordPress projects directory.
  if File.directory?(wp_projects_dir = ENV['WP_PROJECTS_DIR'] || File.expand_path('~/projects/wordpress'))
    config.vm.synced_folder wp_projects_dir, '/wordpress', mount_options: ['ro'];
  end;

  # Mount WordPress personal projects directory.
  if File.directory?(wp_personal_projects_dir = ENV['WP_PERSONAL_PROJECTS_DIR'] || File.expand_path('~/projects/personal/wordpress'))
    config.vm.synced_folder wp_personal_projects_dir, '/wp-personal', mount_options: ['ro'];
  end;

  # Mount WordPress business projects directory.
  if File.directory?(wp_business_projects_dir = ENV['WP_BUSINESS_PROJECTS_DIR'] || File.expand_path('~/projects/business/wordpress'))
    config.vm.synced_folder wp_business_projects_dir, '/wp-business', mount_options: ['ro'];
  end;

  # Allow SSH keys that the user has to be used on the VM w/o copying them to the VM.
  config.ssh.forward_agent = true;

  # Configure DNS automatically?
  if Vagrant.has_plugin?('landrush')
    config.landrush.enabled = true; # Enable landrush plugin.
    config.landrush.tld = 'vm'; # Set landrush TLD for this VM.
    config.landrush.upstream '8.8.8.8'; # Google public DNS.
  end;

  # Configure box-specific caching.
  if Vagrant.has_plugin?('vagrant-cachier')
    config.cache.scope = :box;
  end;

  # Configure resource allocations.
  config.vm.provider 'virtualbox' do |vb|
    vb.customize ['modifyvm', :id, '--memory', '512'];
    vb.customize ['modifyvm', :id, '--vram', '128'];
    vb.customize ['modifyvm', :id, '--cpus', '1'];
  end;

  # Run script(s) as part of the provisioning process.
  config.vm.provision :shell, path: 'vg-bootstrap', run: 'always';
  if Vagrant.has_plugin?('vagrant-triggers')
    config.trigger.after [:up, :resume], :append_to_path => File.dirname(File.expand_path(__FILE__)) do
      run 'vg-bootstrap-me';
    end;
  end;
end;
