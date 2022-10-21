// noinspection JSCheckFunctionSignatures,JSUnresolvedFunction

db = db.getSiblingDB("pokerom_files");
db.getCollectionNames().forEach(function (collection) {
    let indexes = db.getCollection(collection).getIndexes();
    print("Indexes on " + collection + ":");
    printjson(indexes);
});

