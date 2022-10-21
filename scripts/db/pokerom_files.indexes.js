// noinspection JSCheckFunctionSignatures,JSUnresolvedFunction

db = db.getSiblingDB("pokerom_files");

db.getCollectionNames().forEach(function (collection) {
    let indexes = db.getCollection(collection).getIndexes();
    print(`Indexes for "${collection}" collection: `, indexes);
    printjson(indexes);
});
