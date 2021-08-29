# vending.machine

Check description and requirements [here](docs/REQUIREMENTS.md)

# How to install the project

1. Run `make start`
2. Enjoy!

You can stop the environment at any time running `make stop`

You can also enter your docker container running `make enter`

You can check the logs running `make logs`

If you are feeling fancy you can execute `make debug` to start both the project and the logs.

# How to run tests

1. Make sure the project is up and running
2. Run `make test`

Notice that this command is executing *phpstan* for code analysis and *phpcs* for code sniffing as well as running the *phpunit* test suite

# How to execute cli commands

Run `make run COMMAND="{COMMAND_NAME} {ARGS}"`

Some examples that you can try:

`make run COMMAND="addcoin 10"`

`make run COMMAND="returncoins"`

`make run COMMAND="checkusertotal"`

Find the whole list of commands [here](/docs/COMMANDS.md)

# Notes:

- As practice, I auto-imposed the restriction of not using mayor frameworks
- I followed a TDD approach, implementing first naive solutions and refactoring in short cycles. Specially at the beginning.
- Code was also designed with DDD best practices in mind
- I implemented some design patters when fitting, always trying to ascribe to SOLID principles. Some of this patters are for instance: state machine, factory, repository, commands, ...
- I made sure to keep most of the logic of the program in the Domain layer, to avoid ending up with an anemic model

# Improvements for the future:

- The persistence layer is a dummy JSON repository, but a DB could be added
- If I where tu use Symfony to improve this program I would improve the command system with a command/query bus and it's corresponding handlers
- Also, I would probably consider adding events to change between states in the vending machine, but this is just a thought
- More end to end tests could be added, but at least the Domain classes, containing most of the logic, are fairly well covered with phpunit tests.
