Vagrant.configure(2) do |config|
  config.vm.box = 'gbarbieru/xenial';
  config.vm.provider 'virtualbox' do |vb|
    vb.name = 'websharks-ubuntu-xenial-16.04-lts';
  end;
end;

# --------------------------------------------------------------------
# Based on: <https://atlas.hashicorp.com/gbarbieru/boxes/xenial>
# Additional box preparation documented below.
# --------------------------------------------------------------------

# perl -i -wpe 's/^(\s*)(mesg\s+n\s+.*)$/$1tty -s && mesg n/ui' /root/.profile;

# rm --force --recursive /tmp/*;
# dd if=/dev/zero of=/EMPTY bs=1M &>/dev/null || true;
# rm -f /EMPTY; # ↑ See: <http://jas.xyz/1MGxHCj>

# rm --force /root/.bash_history;
# rm --force /home/vagrant/.bash_history;
# history -c; # Erase current history.

# vm package --output ./websharks-ubuntu-xenial64.box;
# vm box add websharks/ubuntu-xenial64 ./websharks-ubuntu-xenial64.box;
