// noinspection JSUnresolvedFunction,JSUnusedLocalSymbols

/**
 * @name password-generator
 * @description NodeJS Password Hash Generator Using BcryptJS
 */

const { blue, yellow, red, green } = require("colors");
const readline = require("readline");
const yargs = require("yargs");
const bcrypt = require("bcryptjs");

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
    console.log("Salt value: ", saltVal.toString().blue);
    console.log("Argv Salt value: ", (argv.salt || 10).toString().yellow);
}

rlInterface.question("Enter password: ", async (pw) => {
    try {
        await bcrypt.genSalt(saltVal, (err, salt) => {
            if (err) throw err;
            bcrypt.hash(pw, salt, (err, hash) => {
                if (err) throw err;
                console.log(`Hashed password: ${hash.green}`);
            });
        });
    } catch (e) {
        console.log(e.valueOf().red);
    } finally {
        rlInterface.close();
    }
});
