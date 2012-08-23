set :application, "FTFAttendee"
set :repository,  "git@github.com:fabiofabbrucci/FTFAttendee.git"
set :scm,         :git

set  :keep_releases,  3

set :app_path,    "app"
set :system_path,    "system"

set :serverName, "server.abmundi.com" # The server's hostname
set :domain,     "ftf.abmundi.com"
set :user,       "abmundic"
set :scm_passphrase, ""
ssh_options[:port] = 22123
ssh_options[:forward_agent] = true
set :deploy_to, "/var/www/vhosts/ftf.abmundi.com/httpdocs/"

set :model_manager, "doctrine"

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain                         # This may be the same as your `Web` server
role :db,         domain, :primary => true       # This is where Rails migrations will run

set  :use_sudo,      false

# Update vendors during the deploy
set :update_vendors,  true
set :vendors_mode, "upgrade"

# Set some paths to be shared between versions
set :shared_files,    ["app/config/parameters.ini", "app/config/parameters.yml"]
set :shared_children, [app_path + "/logs", web_path + "/uploads", "vendor", web_path + "/media", app_path + "/spool"]

set :dump_assetic_assets, true
set :assets_install, true

