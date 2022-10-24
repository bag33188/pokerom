const fs = require("fs");
const path = require("path");
const axios = require("axios").default;
const { green, yellow, blue, red } = require("colors");

console.log("Generating seeds...\n".blue);

const __basedir__ = __dirname.replace(
    /([\x2F\x5C]scripts)([\x2F\x5C]helpers)/i,
    ""
);

const [dumpDir, seedFilePath] = [
    path.resolve(__basedir__, "data", "dump"),
    path.join(path.join(__basedir__, "data"), "seeds.json"),
];

const apiUrl = "http://pokerom.test/api";

const gamesDataPath = path.join(dumpDir, "games.json");
const romsDataPath = path.join(dumpDir, "roms.json");
const romFilesDataPath = path.join(dumpDir, "rom_files.json");

async function generateResourceData() {
    try {
        console.log("Fetching data from API...".yellow);

        let { data: games } = await axios.get(`${apiUrl}/seeds/games`);
        let { data: roms } = await axios.get(`${apiUrl}/seeds/roms`);
        let { data: romFiles } = await axios.get(`${apiUrl}/seeds/rom-files`);
        console.log("Data fetched successfully!\n".green);

        console.log("Writing data to files...".yellow);

        fs.writeFileSync(gamesDataPath, JSON.stringify(games));

        fs.writeFileSync(romsDataPath, JSON.stringify(roms));

        fs.writeFileSync(romFilesDataPath, JSON.stringify(romFiles));
        console.log("Data written successfully!\n".green);
    } catch (e) {
        throw e;
    }
}

generateResourceData()
    .then(() => {
        const seedData = {
            romsData: require(romsDataPath),
            gamesData: require(gamesDataPath),
            romFilesData: require(romFilesDataPath),
        };

        let { romsData, gamesData, romFilesData } = seedData;

        // console.log(`'roms' length: ${seedData.romsData.length}\n\n`.blue);
        // console.log(`'games' length: ${seedData.gamesData.length}`.blue);
        // console.log(`'rom_files' length: ${seedData.romFilesData.length}`.blue);

        console.log("Mapping resource data...".yellow);

        gamesData = gamesData.map((gameData) => {
            // delete gameData["id"];
            delete gameData["created_at"];
            delete gameData["updated_at"];
            delete gameData["slug"];
            // delete gameData["rom_id"];
            gameData["region"] = gameData["region"].toLowerCase();
            gameData["game_type"] = gameData["game_type"].toLowerCase();
            gameData["game_name"] = gameData["game_name"].replace(
                /^Pok\xE9mon/i,
                "Pokemon"
            );
            gameData["date_released"] = gameData["date_released"].replace(
                /T[0-2][0-4]:[0-5]\d:[0-5]\d\.\d{6}Z$/i,
                ""
            );
            return gameData;
        });

        romsData = romsData.map((romData) => {
            // delete romData["id"];
            delete romData["created_at"];
            delete romData["updated_at"];
            // delete romData["game_id"];
            // delete romData["file_id"];
            // delete romData["has_game"];
            // delete romData["has_file"];
            romData["rom_type"] = romData["rom_type"].toLowerCase();
            return romData;
        });

        romFilesData = romFilesData.map((romFileData) => {
            // delete romFileData["_id"];
            delete romFileData["uploadDate"];
            // delete romFileData["md5"];
            return romFileData;
        });
        console.log("Resource data mapped successfully!\n".green);

        console.log("Writing resource data to file...".yellow);

        fs.writeFileSync(
            seedFilePath,
            JSON.stringify({
                roms: romsData,
                games: gamesData,
                rom_files: romFilesData,
            })
        );

        console.log("Resource data written successfully!\n".green);

        console.log("Seeds generated!".blue);
    })
    .catch((err) => console.error(err));
