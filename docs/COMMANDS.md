# Vending machine CLI commands

Commands can be executed outside the docker container using the Makefile option `make run COMMAND="{COMMAND_NAME} {ARGS}"` or inside the container just executing `php ./src/RunCli.php ${COMMAND}` in the project directory.

Commands are not case sensitive, but arguments are.

This is the complete list of commands available in the program:

- `addcoin [COIN_VALUE] : void`

- `getusertotal : float`

- `buy [PRODUCT_NAME] : [Product, ?coins]`

- `getcatalog : Catalog`

- `startmaintenance : void`

- `stopmaintenance : void}`

- `addstock [PRODUCT_NAME] [PRICE] [QUANTITY]`

- `addchange [COIN_VALUE] [QUANTITY]`
