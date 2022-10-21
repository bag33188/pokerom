// noinspection JSCheckFunctionSignatures,JSUnresolvedFunction

db = db.getSiblingDB("pokerom_files");
db.getCollectionNames().forEach(function (collection) {
    print(`"${collection}" indexes`, db.getCollection(collection).getIndexes());
});
