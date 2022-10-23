const fs = require("fs");
const path = require("path");
const axios = require("axios").default;

console.log("Generating seeds...\n");

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
    console.log("Fetching data from API...\n");

    let { data: games } = await axios.get(`${apiUrl}/seeds/games`);
    let { data: roms } = await axios.get(`${apiUrl}/seeds/roms`);
    let { data: romFiles } = await axios.get(`${apiUrl}/seeds/rom-files`);

    console.log("Writing data to files...\n");

    fs.writeFileSync(gamesDataPath, JSON.stringify(games));

    fs.writeFileSync(romsDataPath, JSON.stringify(roms));

    fs.writeFileSync(romFilesDataPath, JSON.stringify(romFiles));
}

generateResourceData()
    .then(() => {
        const seedData = {
            romsData: require(romsDataPath),
            gamesData: require(gamesDataPath),
            romFilesData: require(romFilesDataPath),
        };

        let { romsData, gamesData, romFilesData } = seedData;

        console.log("Mapping resource data...\n");

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

        console.log("Writing resource data to file...\n");

        fs.writeFileSync(
            seedFilePath,
            JSON.stringify({
                roms: romsData,
                games: gamesData,
                rom_files: romFilesData,
            })
        );

        console.log("Seeds generated!");
    })
    .catch((err) => console.error(err));
