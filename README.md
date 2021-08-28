# vending.machine

Check description and requirements [here](docs/REQUIREMENTS.md)

# How to install the project

1. Run `make start`
2. Enjoy!

You can stop the environment at any time running `make stop`
You can also enter your docker container running `make enter`

# How to run tests

1. Make sure the project is up and running
2. Run `make tests`

# How to execute cli commands

Run `make run COMMAND="{COMMAND_NAME} {ARGS}"`

Some examples that you can try:

`make run COMMAND="addcoin 10"`

`make run COMMAND="returncoins"`

`make run COMMAND="checkusertotal"`

