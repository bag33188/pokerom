// noinspection JSUnresolvedFunction,JSUnresolvedVariable

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
db.rom.chunks.createIndex({ files_id: 1, n: 1 }, { unique: true });

// prettier-ignore
let aggregations = [{name:"Calc Total ROMs Size Bytes",pipeline:[{$group:{_id:null,total_length:{$sum:"$length"}}},{$addFields:{total_size:{$toString:{$toLong:"$total_length"}}}},{$project:{_id:0,total_length:1,total_size:{$concat:["$total_size"," ","Bytes"]}}},{$limit:1},]},{name:"Calc Total ROMs Size Gibibytes",pipeline:[{$group:{_id:null,total_length:{$sum:"$length"}}},{$addFields:{total_size:{$toString:{$round:[{$toDouble:{$divide:["$total_length",{$pow:[1024,3]},]}},2,]}}}},{$project:{_id:0,total_length:{$toDecimal:{$divide:["$total_length",{$pow:[1024,3]},]}},total_size:{$concat:["$total_size"," ","Gibibytes"]}}},{$limit:1},]},{name:"Filter GB ROMs",pipeline:[{$sort:{length:-1,filename:1,uploadDate:1}},{$match:{filename:{$regex:"^[\\w\\d\\-_]+\\.gb$",$options:"i"}}},]},{name:"Filter 3DS ROMs",pipeline:[{$sort:{length:-1,filename:1,uploadDate:1}},{$match:{filename:{$regex:"^[\\w\\d\\-_]+\\.3ds$",$options:"i"}}},]},{name:"Filter GBA ROMs",pipeline:[{$sort:{length:-1,filename:1,uploadDate:1}},{$match:{filename:{$regex:"^[\\w\\d\\-_]+\\.gba$",$options:"i"}}},]},{name:"Filter GBC ROMs",pipeline:[{$sort:{length:-1,filename:1,uploadDate:1}},{$match:{filename:{$regex:"^[\\w\\d\\-_]+\\.gbc$",$options:"i"}}},]},{name:"Filter NDS ROMs",pipeline:[{$sort:{length:-1,filename:1,uploadDate:1}},{$match:{filename:{$regex:"^[\\w\\d\\-_]+\\.nds$",$options:"i"}}},]},{name:"Filter XCI ROMs",pipeline:[{$sort:{length:-1,filename:1,uploadDate:1}},{$match:{filename:{$regex:"^[\\w\\d\\-_]+\\.xci$",$options:"i"}}},]},{name:"Proper Rom Files Sort",pipeline:[{$addFields:{field_length:{$strLenCP:"$filename"}}},{$sort:{length:1,field_length:1,uploadDate:-1}},{$unset:"field_length"},]},{name:"Show Rom Sizes (KB)",pipeline:[{$addFields:{rom_size:{$ceil:{$divide:["$length",1024]}}}},{$project:{filename:1,length:1,chunkSize:1,uploadDate:1,md5:1,rom_size:{$concat:[{$toString:{$toLong:"$rom_size"}}," ","KB",]}}},]},{name:"Sort ROM Files By Length (Descending)",pipeline:[{$sort:{length:-1}},]},{name:"Count ROM Files",pipeline:[{$count:"id"},{$addFields:{rom_files_count:{$toInt:"$id"}}},{$project:{id:0}},]},{name:"rom.files Metadata",pipeline:[{$addFields:{romFileType:{$split:["$filename","."]}}},{$project:{romFileType:{$toUpper:{$arrayElemAt:["$romFileType",1]}},filename:1,uploadDate:1,md5:1,chunkSize:1,length:1}},{$addFields:{metadata:{romType:"$romFileType",contentType:"application/octet-stream"}}},{$project:{romFileType:0}},]}];

db.rom.files.aggregate([
    ...aggregations[0].pipeline,
    ...aggregations[3].pipeline,
    ...aggregations[11].pipeline,
]);

/*
db.rom.files.reIndex();
db.rom.chunks.reIndex();
*/

db.createCollection("emulators", {
    validator: {
        $jsonSchema: {
            bsonType: "object",
            required: ["name", "rom_types", "rom_files", "consoles"],
            properties: {
                name: {
                    bsonType: "string",
                    maxLength: 20,
                    minLength: 3,
                },
                rom_types: {
                    bsonType: "array",
                    uniqueItems: true,
                    items: {
                        bsonType: "string",
                        enum: ["GB", "GBC", "GBA", "NDS", "3DS", "XCI"],
                        minLength: 2,
                        maxLength: 3,
                    },
                },
                download_url: {
                    bsonType: "string",
                },
                rom_files: {
                    bsonType: "array",
                    uniqueItems: true,
                    items: {
                        bsonType: "objectId",
                        minItems: 0,
                        maxItems: 20,
                    },
                },
                consoles: {
                    bsonType: "array",
                    uniqueItems: true,
                    items: {
                        bsonType: "string",
                    },
                },
            },
        },
    },
});

let b = [
    {
        _id: ObjectId("63306b23a48e8bd8f7547b88"),
        name: "Visual Boy Advanced",
        download_url:
            "https://www.emulator-zone.com/doc.php/gba/vboyadvance.html",
        rom_files: [
            ObjectId("6305c9634bc3e0c5dc0d7adc"),
            ObjectId("6305c2924bc3e0c5dc0c46f3"),
            ObjectId("6305c29a4bc3e0c5dc0c46f7"),
            ObjectId("6305c27b4bc3e0c5dc0c46ef"),
            ObjectId("6305c2a04bc3e0c5dc0c46fa"),
            ObjectId("6305c2c94bc3e0c5dc0c4705"),
            ObjectId("6305c2ab4bc3e0c5dc0c46fd"),
            ObjectId("6305c2b04bc3e0c5dc0c4701"),
            ObjectId("6305c95b4bc3e0c5dc0d7ad8"),
            ObjectId("6305c2e64bc3e0c5dc0c472d"),
            ObjectId("6305c2ec4bc3e0c5dc0c473f"),
            ObjectId("6305c2f14bc3e0c5dc0c4751"),
            ObjectId("6305c2cf4bc3e0c5dc0c4709"),
            ObjectId("6305c2e14bc3e0c5dc0c471b"),
            ObjectId("6305c96e4bc3e0c5dc0d7af2"),
            ObjectId("6305c9684bc3e0c5dc0d7ae0"),
        ],
        rom_types: ["GB", "GBC", "GBA"],
        consoles: [
            "Nintendo Gameboy",
            "Nintendo Gameboy Color",
            "Nintendo Gameboy Advanced",
        ],
    },
    {
        _id: ObjectId("63306ec5a48e8bd8f7547b8a"),
        name: "DeSmuME",
        download_url: "https://desmume.org/",
        rom_files: [
            ObjectId("6305c3544bc3e0c5dc0c4b73"),
            ObjectId("6305c35f4bc3e0c5dc0c4d77"),
            ObjectId("6305c3414bc3e0c5dc0c496d"),
            ObjectId("6305c34d4bc3e0c5dc0c4a70"),
            ObjectId("6305c32a4bc3e0c5dc0c4869"),
            ObjectId("6305c3204bc3e0c5dc0c47e7"),
            ObjectId("6305c3374bc3e0c5dc0c48eb"),
            ObjectId("6305c9744bc3e0c5dc0d7b04"),
            ObjectId("6305c2f94bc3e0c5dc0c4763"),
            ObjectId("6305c3024bc3e0c5dc0c47a5"),
        ],
        rom_types: ["NDS"],
        consoles: ["Nintendo DS", "Nintendo DSi", "Nintendo DSi XL"],
    },
    {
        _id: ObjectId("63306f39a48e8bd8f7547b8b"),
        name: "Citra",
        download_url: "https://citra-emu.org/",
        rom_files: [
            ObjectId("6305c3e54bc3e0c5dc0c6fa3"),
            ObjectId("6305c4124bc3e0c5dc0c7fb5"),
            ObjectId("6305c45f4bc3e0c5dc0c8fc7"),
            ObjectId("6305c4c24bc3e0c5dc0c9fd9"),
            ObjectId("6305c36e4bc3e0c5dc0c4f7b"),
            ObjectId("6305c3974bc3e0c5dc0c5785"),
            ObjectId("6305c3b34bc3e0c5dc0c5f8f"),
            ObjectId("6305c3cc4bc3e0c5dc0c6799"),
            ObjectId("6307ce21b12790f2b2083cb8"),
        ],
        rom_types: ["3DS"],
        consoles: ["Nintendo 3ds", "Nintendo 3ds XL", "New Nintendo 3ds XL"],
    },
    {
        _id: ObjectId("63306fc3dc26832c185fc57b"),
        name: "Yuzu",
        download_url: "https://yuzu-emu.org/",
        rom_files: [
            ObjectId("6305c6184bc3e0c5dc0cebac"),
            ObjectId("6305c5344bc3e0c5dc0cafec"),
            ObjectId("6305c7694bc3e0c5dc0d3a1b"),
            ObjectId("6305c9a94bc3e0c5dc0d7b6a"),
            ObjectId("6305c6fa4bc3e0c5dc0d2769"),
            ObjectId("6305c81e4bc3e0c5dc0d57fa"),
            ObjectId("6305c8a14bc3e0c5dc0d6a20"),
        ],
        rom_types: ["XCI"],
        consoles: ["Nintendo Switch"],
    },
];
