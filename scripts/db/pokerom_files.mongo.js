// noinspection JSUnresolvedFunction,JSUnresolvedVariable,JSCheckFunctionSignatures,JSUnusedGlobalSymbols

conn = new Mongo("localhost:27017");

db = conn.getDB("admin");

db.createUser({
    user: "brock",
    pwd: "3931Sunflower!" /* passwordPrompt() */,
    roles: [{ role: "dbAdmin", db: "pokerom_files" }],
});

db = db.getSiblingDb("pokerom_files");

db.createCollection("rom.files", {
    validator: {
        $jsonSchema: {
            bsonType: "object",
            required: [
                "filename",
                "length",
                "chunkSize",
                "uploadDate",
                "md5",
                "metadata",
            ],
            description:
                "Represents a distinct file in GridFS as a single document.",
            properties: {
                _id: {
                    bsonType: "objectId",
                    description: "The unique identifier for this document.",
                },
                filename: {
                    bsonType: "string",
                    pattern:
                        "^(?:([\\w\\d\\-_]{1,30})(?:\\.)(3ds|xci|nds|gbc|gb|gba))$",
                    minLength: 3,
                    maxLength: 34,
                    description:
                        "Human-readable name for the stored file. String/filename format must pertain to the pattern as defined above.",
                },
                uploadDate: {
                    bsonType: "date",
                    description:
                        "The date-time the document was first stored in GridFS.",
                },
                chunkSize: {
                    bsonType: "int",
                    minimum: 261120,
                    description: "The size of each chunk in bytes.",
                },
                length: {
                    bsonType: ["int", "long"],
                    minimum: 1044480,
                    maximum: 18253611008,
                    description:
                        "The size of the document in bytes. (1020 KiB - 17 GiB).",
                },
                md5: {
                    bsonType: "string",
                    minLength: 32,
                    maxLength: 32,
                    description:
                        "[Deprecated]: md5 hash. Will be removed in the future.",
                },
                metadata: {
                    bsonType: "object",
                    required: ["contentType", "romType"],
                    description: "Additional information about the file.",
                    properties: {
                        contentType: {
                            bsonType: "string",
                            enum: [
                                "application/octet-stream",
                                "application/x-rom-file",
                            ],
                            description:
                                "Content type to be specified in an HTTP-Header when uploading/downloading from the GridFS Store.",
                        },
                        romType: {
                            bsonType: "string",
                            enum: ["GB", "GBC", "GBA", "NDS", "3DS", "XCI"],
                            description:
                                "The specific ROM type of the ROM file that is stored.",
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
            description:
                "Represents a distinct chunk of a file document in GridFS.",
            properties: {
                _id: {
                    bsonType: "objectId",
                    description: "The unique ObjectId of the chunk.",
                },
                files_id: {
                    bsonType: "objectId",
                    description:
                        'The _id of the "parent" document, as specified in the files collection. Reference: `rom.files._id`',
                },
                data: {
                    bsonType: "binData",
                    description: "The chunk's payload as a BSON Binary type.",
                },
                n: {
                    bsonType: "int",
                    description:
                        "The sequence number of the chunk. Starts at 0.",
                },
            },
        },
    },
    validationLevel: "off",
    validationAction: "warn",
});
db.rom.chunks.createIndex({ files_id: 1, n: 1 }, { unique: true });

let aggregationsObj = {
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
