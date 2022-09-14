/**
 * @name password-generator
 * @description NodeJS Password Hash Generator Using BcryptJS
 */

const colors = require("colors");
const readline = require("readline");
const yargs = require("yargs");
const bcrypt = require("bcryptjs");

colors.setTheme({
    silly: "rainbow",
    input: "grey",
    verbose: "cyan",
    prompt: "grey",
    info: "green",
    data: "grey",
    help: "cyan",
    warn: "yellow",
    debug: "blue",
    error: "red",
});

let argv = yargs(process.argv.slice(2))
    .option("salt", {
        alias: "s",
        type: "number",
        description: "custom salt value",
        demandOption: false,
    })
    .help()
    .alias("help", "h").argv;

// create readline interface
const rlInterface = readline.createInterface({
    input: process.stdin,
    output: process.stdout,
});

let saltVal = 10;

// allow option for custom salt value
if (argv.salt) saltVal = argv.salt;

if (process.env.NODE_ENV !== "production") {
    console.log("Salt value: ", colors.debug(saltVal));
    console.log("Argv Salt value: ", colors.warn(argv.salt));
}

rlInterface.question("Enter password: ", async (pw) => {
    try {
        await bcrypt.genSalt(saltVal, (err, salt) => {
            if (err) throw err;
            bcrypt.hash(pw, salt, (err, hash) => {
                if (err) throw err;
                console.log(`Hashed password: ${colors.info(hash)}`);
            });
        });
    } catch (e) {
        console.log(colors.error(e));
    } finally {
        rlInterface.close();
    }
});
