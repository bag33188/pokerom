// noinspection JSUnresolvedFunction,JSUnresolvedVariable,ES6ConvertVarToLetConst,JSCheckFunctionSignatures,JSUnusedGlobalSymbols

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
db.rom.files.createIndex(
    { filename: "text", "metadata.romType": "text" },
    { sparse: true }
);

db.createCollection("rom.chunks", {
    validator: {
        $jsonSchema: {
            bsonType: "object",
            required: ["files_id", "n", "data"],
            properties: {
                files_id: {
                    bsonType: "objectId",
                    description: "References rom.files._id",
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

var aggregationsObj = {
    "Calc Total ROMs Size Bytes": [
        { $group: { _id: null, total_length: { $sum: "$length" } } },
        {
            $addFields: {
                total_size: { $toString: { $toLong: "$total_length" } },
            },
        },
        {
            $project: {
                _id: 0,
                total_length: 1,
                total_size: { $concat: ["$total_size", " ", "Bytes"] },
            },
        },
        { $limit: 1 },
    ],
    "Calc Total ROMs Size Gibibytes": [
        { $group: { _id: null, total_length: { $sum: "$length" } } },
        {
            $addFields: {
                total_size: {
                    $toString: {
                        $round: [
                            {
                                $toDouble: {
                                    $divide: [
                                        "$total_length",
                                        { $pow: [1024, 3] },
                                    ],
                                },
                            },
                            2,
                        ],
                    },
                },
            },
        },
        {
            $project: {
                _id: 0,
                total_length: {
                    $toDecimal: {
                        $divide: ["$total_length", { $pow: [1024, 3] }],
                    },
                },
                total_size: { $concat: ["$total_size", " ", "Gibibytes"] },
            },
        },
        { $limit: 1 },
    ],
    "Filter GB ROMs": [
        { $sort: { length: -1, filename: 1, uploadDate: 1 } },
        {
            $match: {
                filename: { $regex: "^[\\w\\d\\-_]+\\.gb$", $options: "i" },
            },
        },
    ],
    "Filter 3DS ROMs": [
        { $sort: { length: -1, filename: 1, uploadDate: 1 } },
        {
            $match: {
                filename: { $regex: "^[\\w\\d\\-_]+\\.3ds$", $options: "i" },
            },
        },
    ],
    "Filter GBA ROMs": [
        { $sort: { length: -1, filename: 1, uploadDate: 1 } },
        {
            $match: {
                filename: { $regex: "^[\\w\\d\\-_]+\\.gba$", $options: "i" },
            },
        },
    ],
    "Filter GBC ROMs": [
        { $sort: { length: -1, filename: 1, uploadDate: 1 } },
        {
            $match: {
                filename: { $regex: "^[\\w\\d\\-_]+\\.gbc$", $options: "i" },
            },
        },
    ],
    "Filter NDS ROMs": [
        { $sort: { length: -1, filename: 1, uploadDate: 1 } },
        {
            $match: {
                filename: { $regex: "^[\\w\\d\\-_]+\\.nds$", $options: "i" },
            },
        },
    ],
    "Filter XCI ROMs": [
        { $sort: { length: -1, filename: 1, uploadDate: 1 } },
        {
            $match: {
                filename: { $regex: "^[\\w\\d\\-_]+\\.xci$", $options: "i" },
            },
        },
    ],
    "Proper Rom Files Sort": [
        { $addFields: { field_length: { $strLenCP: "$filename" } } },
        { $sort: { length: 1, field_length: 1, uploadDate: -1 } },
        { $unset: "field_length" },
    ],
    "Show Rom Sizes (KB)": [
        { $addFields: { rom_size: { $ceil: { $divide: ["$length", 1024] } } } },
        {
            $project: {
                filename: 1,
                length: 1,
                chunkSize: 1,
                uploadDate: 1,
                md5: 1,
                rom_size: {
                    $concat: [
                        { $toString: { $toLong: "$rom_size" } },
                        " ",
                        "KB",
                    ],
                },
            },
        },
    ],
    "Sort ROM Files By Length (Descending)": [{ $sort: { length: -1 } }],
    "Count ROM Files": [
        { $count: "id" },
        { $addFields: { rom_files_count: { $toInt: "$id" } } },
        { $project: { id: 0 } },
    ],
    "rom.files Metadata": [
        { $addFields: { romFileType: { $split: ["$filename", "."] } } },
        {
            $project: {
                romFileType: {
                    $toUpper: { $arrayElemAt: ["$romFileType", 1] },
                },
                filename: 1,
                uploadDate: 1,
                md5: 1,
                chunkSize: 1,
                length: 1,
            },
        },
        {
            $addFields: {
                metadata: {
                    romType: "$romFileType",
                    contentType: "application/octet-stream",
                },
            },
        },
        { $project: { romFileType: 0 } },
    ],
};

/*
db.rom.files.reIndex();
db.rom.chunks.reIndex();

db.rom.files.aggregate(aggregationsObj["Proper Rom Files Sort"]);
db.rom.files.aggregate(aggregationsObj["Calc Total ROMs Size Gibibytes"]);
*/
