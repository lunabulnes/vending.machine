# vending.machine

Check description and requirements [here](docs/REQUIREMENTS.md)

# How to install the project

1. Run `make start`
2. Enjoy!

You can stop the environment at any time running `make stop`
You can also enter your docker container running `make enter`
You can check the logs running `make logs`

If you are feeling fancy you can execute `make dev` to start both the project and the logs.

# How to run tests

1. Make sure the project is up and running
2. Run `make tests`

# How to execute cli commands

Run `make run COMMAND="{COMMAND_NAME} {ARGS}"`

Some examples that you can try:

`make run COMMAND="addcoin 10"`
`make run COMMAND="returncoins"`
`make run COMMAND="checkusertotal"`

Find the whole list of commands [here](src/Infrastructure/Cli/CommandFactory.php)

