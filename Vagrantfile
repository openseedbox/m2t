VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

  config.vm.hostname = "m2t"
  config.vm.box = "precise64"
  config.vm.box_url = "http://files.vagrantup.com/precise64.box"

  # Create a forwarded port mapping which allows access to a specific port
  # within the machine from a port on the host machine. In the example below,
  # accessing "localhost:8080" will access port 80 on the guest machine.
  config.vm.network :forwarded_port, guest: 80, host: 8081
  config.vm.network :forwarded_port, guest: 81, host: 8082
  config.vm.network :forwarded_port, guest: 9091, host: 9092

  # Create a private network, which allows host-only access to the machine
  # using a specific IP.
  config.vm.network :private_network, ip: "192.168.33.10"

  config.vm.synced_folder "./vagrant", "/vagrant"
  config.vm.synced_folder ".", "/m2t"

  config.vm.provision "ansible" do |ansible|
    ansible.playbook = "./vagrant/playbook.yml"
    ansible.sudo = true
    ansible.verbose = "vv"
  end

end

