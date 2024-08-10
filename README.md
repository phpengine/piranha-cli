# Piranha CLI, Piranha Cloud Hosting

## About:

The Piranha Cloud Hosting CLI Tool


## Installation

First you'll need to install Git and PHP5. If you don't have either, google them - they're easy to install. To install
piranha cli on your On your Mac, Linux or  Unix Machine silently do the following:

git clone https://github.com/phpengine/piranha-cli.git && sudo php piranha/install-silent

or on Windows, open a terminal with the "Run as Administrator" option...

git clone https://github.com/phpengine/piranha-cli.git && php piranha\install-silent

... that's it, now the piranha command should be available at the command line for you.


## Usage:

So, there are a few simple commands...

First, you can just use

piranha

...This will give you a list of the available modules...

Then you can use

piranha *ModuleName* help

...This will display the help for that module, and tell you a list of available alias for the module command, and the
available actions too.

You'll be able to automate any action from any available module into an autopilot file, or run it from the CLI. I'm
working on a web front end, but you can also use JSON output and the PostInput module to use any module from an API.


## Or some examples

The following URL contains a bunch of tutorials

http://docs.piranha.sh

Go to http://www.piranha.sh for more


## Available Commands:

access - Piranha Access Functions
compute - Piranha Compute Functions
dns - Piranha DNS Functions
database - Piranha Database Functions
objectstorage - Piranha Object Storage Functions
scm - Piranha SCM Functions
slb - Piranha SLB Functions
smp - Piranha SMP Functions
vpc - Piranha VPC Functions

---------------------------------------
Visit www.piranha.sh for more
******************************