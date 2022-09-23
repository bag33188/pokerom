// noinspection DuplicatedCode,JSUnresolvedFunction,JSUnresolvedVariable

const mongoURI = // DSN
    "mongodb://brock:3931Sunflower!@localhost:27017/?authSource=admin&authMechanism=SCRAM-SHA-256";

conn = new Mongo("localhost:27017");

db = conn.getDB("admin");
db.createUser({
    user: "brock",
    pwd: "3931Sunflower!", // passwordPrompt()
    roles: [{ role: "dbAdmin", db: "pokerom_files" }],
});

db = db.getSiblingDb("pokerom_files");

db.createCollection("rom.files", {
    validator: {
        $jsonSchema: {
            bsonType: "object",
            required: ["filename", "length", "chunkSize", "uploadDate", "md5"],
            properties: {
                filename: {
                    bsonType: "string",
                    pattern:
                        "^(?:([\\w\\d\\-_]{1,28})(?:\\.)(3ds|xci|nds|gbc|gb|gba))$",
                    minLength: 3,
                    maxLength: 32,
                },
                uploadDate: {
                    bsonType: "date",
                },
                chunkSize: {
                    bsonType: "int",
                    minimum: 261120,
                },
                length: {
                    bsonType: ["int", "long"],
                    minimum: 1044480, // 1020 Kibibytes (base 1024)
                    maximum: 18253611008, // 17 Gibibytes (base 1024)
                    description:
                        "length can either be int32 or int64. ranges are 1044480 (1020 kibibytes) through 18253611008 (17 gibibytes)",
                },
                md5: {
                    bsonType: "string",
                    minLength: 32,
                    maxLength: 32,
                },
                metadata: {
                    bsonType: "object",
                    required: ["contentType", "romType"],
                    properties: {
                        contentType: {
                            bsonType: "string",
                            enum: [
                                "application/octet-stream",
                                "application/x-rom-file",
                            ],
                        },
                        romType: {
                            bsonType: "string",
                            enum: ["GB", "GBC", "GBA", "NDS", "3DS", "XCI"],
                        },
                    },
                },
            },
        },
    },
    validationLevel: "strict",
    validationAction: "error",
});
db.rom.files.createIndex({ filename: 1, uploadDate: 1 });
db.rom.files.createIndex(
    { filename: 1 },
    { unique: true, partialFilterExpression: { filename: { $exists: true } } }
);

db.createCollection("rom.chunks", {
    validator: {
        $jsonSchema: {
            bsonType: "object",
            required: ["files_id", "n", "data"],
            properties: {
                files_id: {
                    bsonType: "objectId",
                },
                data: {
                    bsonType: "binData",
                },
                n: {
                    bsonType: "int",
                },
            },
        },
    },
    validationLevel: "off",
    validationAction: "warn",
});
// 86571 total documents if all proper files are stored in the collection
db.rom.chunks.createIndex({ files_id: 1, n: 1 }, { unique: true });

db.createCollection("rom_files", {
    validator: {
        $jsonSchema: {
            bsonType: "object",
            required: ["filename", "filesize", "filetype"],
            properties: {
                filename: {
                    bsonType: "string",
                    pattern: "^([\\d\\w\\-\\_]+)$",
                    minLength: 1,
                    maxLength: 28,
                },
                filetype: {
                    bsonType: "string",
                    enum: ["gb", "gbc", "gba", "nds", "3ds", "xci"],
                    minLength: 2,
                    maxLength: 3,
                },
                filesize: {
                    bsonType: ["int", "long"],
                    minimum: 0x400, // 1024 bytes
                    maximum: 0x440000000, // 18,253,611,008 bytes
                    description: "Size of file measured in raw Bytes",
                },
            },
        },
    },
    validationLevel: "moderate",
    validationAction: "warn",
});
db.rom_files.createIndex(
    { filename: -1, filetype: -1 },
    {
        unique: true,
        partialFilterExpression: {
            filename: { $exists: true },
            filetype: { $exists: true },
        },
    }
);

// prettier-ignore
let seeds = [{"filename":"0100ABF008968000","filesize":NumberLong("15971909632"),"filetype":"xci"},{"filename":"01008DB008C2C000","filesize":NumberLong("15971909632"),"filetype":"xci"},{"filename":"010018E011D92000","filesize":NumberLong("7985954816"),"filetype":"xci"},{"filename":"0100000011D90000","filesize":NumberLong("4997734912"),"filetype":"xci"},{"filename":"010003F003A34000","filesize":NumberLong("4851431936"),"filetype":"xci"},{"filename":"0100187003A36000","filesize":NumberLong("4468491264"),"filetype":"xci"},{"filename":"00040000001B5000_v00","filesize":NumberLong("4294967295"),"filetype":"3ds"},{"filename":"00040000001B5100_v00","filesize":NumberLong("4294967295"),"filetype":"3ds"},{"filename":"0004000000175E00_v00","filesize":NumberLong("4294967295"),"filetype":"3ds"},{"filename":"0004000000164800_v00","filesize":NumberLong("4294967295"),"filetype":"3ds"},{"filename":"000400000011C400_v00","filesize":NumberLong("2147483648"),"filetype":"3ds"},{"filename":"000400000011C500_v00","filesize":NumberLong("2147483648"),"filetype":"3ds"},{"filename":"0004000000055D00_v00","filesize":NumberLong("2147483648"),"filetype":"3ds"},{"filename":"0004000000055E00_v00","filesize":NumberLong("2147483648"),"filetype":"3ds"},{"filename":"0004000000174600","filesize":NumberLong("2147483648"),"filetype":"3ds"},{"filename":"POKEMON_B2_IREO01","filesize":536870912,"filetype":"nds"},{"filename":"POKEMON_W2_IRDO01","filesize":536870912,"filetype":"nds"},{"filename":"POKEMON_B_IRBO01","filesize":268435456,"filetype":"nds"},{"filename":"POKEMON_W_IRAO01","filesize":268435456,"filetype":"nds"},{"filename":"POKEMON_HG_IPKE01","filesize":134217728,"filetype":"nds"},{"filename":"POKEMON_SS_IPGE01","filesize":134217728,"filetype":"nds"},{"filename":"POKEMON_PL_CPUE01","filesize":134217728,"filetype":"nds"},{"filename":"POKEMON_D_ADAE01","filesize":67108864,"filetype":"nds"},{"filename":"POKEMON_P_APAE","filesize":67108864,"filetype":"nds"},{"filename":"POKEMON_EMERBPEE01","filesize":16777216,"filetype":"gba"},{"filename":"POKEMON_FIREBPRE01","filesize":16777216,"filetype":"gba"},{"filename":"POKEMON_LEAFBPGE01","filesize":16777216,"filetype":"gba"},{"filename":"POKEMON_RUBYAXVE01","filesize":16777216,"filetype":"gba"},{"filename":"POKEMON_SAPPAXPE01","filesize":16777216,"filetype":"gba"},{"filename":"PM_CRYSTAL_BYTE01","filesize":2097152,"filetype":"gbc"},{"filename":"POKEMON_GLDAAUE01","filesize":2097152,"filetype":"gbc"},{"filename":"POKEMON_SLVAAXE01","filesize":2097152,"filetype":"gbc"},{"filename":"POKEMON_YELLOW01","filesize":1048576,"filetype":"gb"},{"filename":"POKEMON_GREEN01","filesize":1048576,"filetype":"gb"},{"filename":"POKEMON_BLUE01","filesize":1048576,"filetype":"gb"},{"filename":"POKEMON_RED01","filesize":1048576,"filetype":"gb"},{"filename":"pokeprism","filesize":2097152,"filetype":"gbc"},{"filename":"pokemon_brown_2014-red_hack","filesize":2097152,"filetype":"gb"},{"filename":"genesis-final-2019-08-23","filesize":16777216,"filetype":"gba"},{"filename":"Pokemon_Ash_Gray_4-5-3","filesize":16777216,"filetype":"gba"},{"filename":"RenegadePlatinum","filesize":104923028,"filetype":"nds"},{"filename":"01001F5010DFA000","filesize":NumberLong("7985954816"),"filetype":"xci"}];

db.rom_files.insertMany(seeds);

// prettier-ignore
let aggregations = [{name:"Calc Total ROMs Size Bytes",pipeline:[{$group:{_id:null,total_length:{$sum:"$length"}}},{$addFields:{total_size:{$toString:{$toLong:"$total_length"}}}},{$project:{_id:0,total_length:1,total_size:{$concat:["$total_size"," ","Bytes"]}}},{$limit:1},]},{name:"Calc Total ROMs Size Gibibytes",pipeline:[{$group:{_id:null,total_length:{$sum:"$length"}}},{$addFields:{total_size:{$toString:{$round:[{$toDouble:{$divide:["$total_length",{$pow:[1024,3]},]}},2,]}}}},{$project:{_id:0,total_length:{$toDecimal:{$divide:["$total_length",{$pow:[1024,3]},]}},total_size:{$concat:["$total_size"," ","Gibibytes"]}}},{$limit:1},]},{name:"Filter GB ROMs",pipeline:[{$sort:{length:-1,filename:1,uploadDate:1}},{$match:{filename:{$regex:"^[\\w\\d\\-_]+\\.gb$",$options:"i"}}},]},{name:"Filter 3DS ROMs",pipeline:[{$sort:{length:-1,filename:1,uploadDate:1}},{$match:{filename:{$regex:"^[\\w\\d\\-_]+\\.3ds$",$options:"i"}}},]},{name:"Filter GBA ROMs",pipeline:[{$sort:{length:-1,filename:1,uploadDate:1}},{$match:{filename:{$regex:"^[\\w\\d\\-_]+\\.gba$",$options:"i"}}},]},{name:"Filter GBC ROMs",pipeline:[{$sort:{length:-1,filename:1,uploadDate:1}},{$match:{filename:{$regex:"^[\\w\\d\\-_]+\\.gbc$",$options:"i"}}},]},{name:"Filter NDS ROMs",pipeline:[{$sort:{length:-1,filename:1,uploadDate:1}},{$match:{filename:{$regex:"^[\\w\\d\\-_]+\\.nds$",$options:"i"}}},]},{name:"Filter XCI ROMs",pipeline:[{$sort:{length:-1,filename:1,uploadDate:1}},{$match:{filename:{$regex:"^[\\w\\d\\-_]+\\.xci$",$options:"i"}}},]},{name:"Proper Rom Files Sort",pipeline:[{$addFields:{field_length:{$strLenCP:"$filename"}}},{$sort:{length:1,field_length:1,uploadDate:-1}},{$unset:"field_length"},]},{name:"Show Rom Sizes (KB)",pipeline:[{$addFields:{rom_size:{$ceil:{$divide:["$length",1024]}}}},{$project:{filename:1,length:1,chunkSize:1,uploadDate:1,md5:1,rom_size:{$concat:[{$toString:{$toLong:"$rom_size"}}," ","KB",]}}},]},{name:"Sort ROM Files By Length (Descending)",pipeline:[{$sort:{length:-1}},]},{name:"Count ROM Files",pipeline:[{$count:"id"},{$addFields:{rom_files_count:{$toInt:"$id"}}},{$project:{id:0}},]},{name:"rom.files Metadata",pipeline:[{$addFields:{romFileType:{$split:["$filename","."]}}},{$project:{romFileType:{$toUpper:{$arrayElemAt:["$romFileType",1]}},filename:1,uploadDate:1,md5:1,chunkSize:1,length:1}},{$addFields:{metadata:{romType:"$romFileType",contentType:"application/octet-stream"}}},{$project:{romFileType:0}},]}];

db.rom.files.aggregate([
    ...aggregations[0].pipeline,
    ...aggregations[3].pipeline,
    ...aggregations[11].pipeline,
]);

// use pokerom_files;
// db.rom_files.reIndex();
// db.rom.files.reIndex();
// db.rom.chunks.reIndex();

/*db.rom.chunks.countDocuments() == 88628*/
